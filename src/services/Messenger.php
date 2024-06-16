<?php
/**
 * Intercom Messenger plugin for Craft CMS 4.x|5.x
 *
 * Intercom.com: the Business Messenger you and your customers will love.
 * Sure, it does live chat. But there’s also bots, apps, product tours, and more
 * like email, messages, and a help center that help you build relationships with your customers.
 * For more information visit: https://www.intercom.com/
 *
 * @link      https://github.com/jimstrike
 * @copyright Copyright (c) Dhimiter Karalliu
 */

namespace jimstrike\intercommessenger\services;

use Craft;
use craft\base\Component;
use craft\enums\CmsEdition;

use jimstrike\intercommessenger\Plugin;
use jimstrike\intercommessenger\models\Settings;
use jimstrike\intercommessenger\elements\FakeUser;

/**
 * Class Messenger
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class Messenger extends Component
{
    /**
     * Custom launcher selector
     * 
     * @return string
     */
    public function customLauncherSelector(): string
    {
        return 'ns-' . Plugin::$plugin->handle . '-custom-launcher';
    }

    /**
     * Messenger chat script
     *  
     * @param int|null $siteId default
     * @param bool $wrap default
     * 
     * @return string
     */
    public function script(int $siteId = null, bool $wrap = false): string
    {
        $intercomSettings = $this->_intercomSettings($siteId);
        
        $comment = Plugin::comment();

        if (!$intercomSettings || empty(($intercomSettings['app_id'] ?? null))) {
            $error = Plugin::t('settings.app_id.errors.blank');
            return "/** {$comment}: {$error} */ ";
        }

        $s = trim($this->_script());

        $s = str_replace('{YOUR_OBJECT}', json_encode($intercomSettings), $s);
        $s = str_replace('{YOUR_APP_ID}', $intercomSettings['app_id'], $s);
        $s = str_replace('{COMMENT}', $comment, $s);

        if (Plugin::$plugin->getSettings()->getShowDefaultLauncherScrollBottomPageOnly($siteId)) {
            $s .= trim($this->_onscroll());
        }

        return $wrap ? "<script>{$s}</script>" : $s;
    }

    /**
     * Messenger chat script for direct use in template
     * 
     * @param int|null $siteId default
     * @param bool $wrap default
     * 
     * @return string
     */
    public function scriptExtend(int $siteId = null, bool $wrap = false): string
    {
        if (!Plugin::$plugin->getSettings()->isEnabled($siteId)) {
            return $this->script($siteId, $wrap);
        }
        
        $comment = Plugin::comment();

        return "/** {$comment} already initiated */ ";
    }

    /**
     * User hash
     *  
     * @param string $data of logged-in user (user ID or email)
     * @param string $key from identity verification secret
     * 
     * @return string
     */
    public function userHash(string $data, string $key): string
    {
        return hash_hmac('sha256', $data, $key);
    }

    // Private Methods
    // =========================================================================

    /**
     * Messenger settings
     *  
     * @param int|null $siteId default
     * 
     * @return string
     */
    private function _intercomSettings(int $siteId = null): array
    {
        $settings = Plugin::$plugin->getSettings();

        if (empty($settings->getAppId($siteId))) {
            return [];
        }

        $a = [
            'app_id' => $settings->getAppId($siteId),
            'alignment' => $settings->getAlignment($siteId),
            'horizontal_padding' => $settings->getHorizontalPadding($siteId), 
            'vertical_padding' => $settings->getVerticalPadding($siteId),
            'hide_default_launcher' => $settings->getHideDefaultLauncher($siteId),
        ];

        if ($settings->getEnableCustomLauncher($siteId)) {
            $a = array_merge($a, [
                'custom_launcher_selector' => '[' . $this->customLauncherSelector() . ']',
            ]);
        }

        if (Craft::$app->getEdition() !== CmsEdition::Pro) {
            return $a;
        }

        $user = $this->_user($settings, $siteId);

        if ($user) {
            $a = array_merge($a, $user);
        }

        return $a;
    }

    /**
     * Logged in user
     *  
     * @param Settings $settings
     * @param int|null $siteId default
     * 
     * @return string
     */
    private function _user(Settings $settings, int $siteId = null): array
    {
        $a = [];

        if (Craft::$app->getEdition() !== CmsEdition::Pro) {
            return $a;
        }

        $userSession = Craft::$app->getUser();

        if ($userSession->getIsGuest()) {
            return $a;
        }

        $request = Craft::$app->getRequest();

        if ($userSession->getIsAdmin()) {
            if (!$request->getIsCpRequest()) {
                return $a;
            }
        }

        $setupLoggedInUser = \array_filter((array)$settings->getSetupLoggedInUser($siteId));

        if (!$setupLoggedInUser) {
            return $a;
        }

        if ($request->getIsCpRequest() || Craft::$app->controller->module->id == Plugin::$plugin->handle) {
            $user = new FakeUser();
            // @todo: Add user groups to fake user for testing in preview?
            // $user->setGroups([]); 
        } else {
            $user = $userSession->getIdentity();
        }

        if (!$user) {
            return $a;
        }

        $userBelongsToSetupUserGroups = $this->_userBelongsToSetupUserGroups($siteId, $settings, $user);

        if (!$userBelongsToSetupUserGroups) {
            return $a;
        }

        $name = $setupLoggedInUser['name'] ?? null;
        if ($name) {
            $a['name'] = $user->name;
        }

        $email = $setupLoggedInUser['email'] ?? null;
        if ($email) {
            $a['email'] = $user->email;
        }

        if (($setupLoggedInUser['dateCreated'] ?? null)) {
            $a['created_at'] = $user->dateCreated->getTimestamp();
        }

        $userId = $setupLoggedInUser['userId'] ?? null;
        if ($userId) {
            $a['user_id'] = $user->id;
        }

        $userHash = $setupLoggedInUser['userHash'] ?? null;
        if ($userHash && ($userId || $email)) {
            if ($settings->getIdentitySecret($siteId)) {
                $identifier = !$userId ? $user->email : (string)$user->id;
                $secret = $settings->getIdentitySecret($siteId);

                $a['user_hash'] = $this->userHash($identifier, $secret);
            }
        }

        return $a;
    }

    /**
     * Check if user belongs to selected user groups
     * 
     * @param int|null $siteId default
     * @param Settings $settings default
     * @param \craft\elements\User $user default
     * 
     * @return bool
     */
    private function _userBelongsToSetupUserGroups(int $siteId = null, Settings $settings = null, \craft\elements\User $user = null): bool
    {
        if (Craft::$app->getEdition() !== CmsEdition::Pro) {
            return false;
        }

        if (!$settings) {
            $settings = Plugin::$plugin->getSettings();
        }

        $setupUserGroups = $settings->getSetupUserGroups($siteId);
        $setupUserGroups = array_filter($setupUserGroups);

        if (!$setupUserGroups) {
            return true; // allow if none selected
        }
        
        if (!$user) {
            $user = Craft::$app->getUser()->getIdentity();
        }
        
        foreach ($setupUserGroups as $groupId => $value) {
            if (empty($value)) {
                continue;
            }

            if ($user->isInGroup($groupId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Intercom js script
     * 
     * @return string
     */
    private function _script(): string
    {
        return "/** {COMMENT} */ "
            . "window.intercomSettings = {YOUR_OBJECT};"
            . "(function(){var w=window;var ic=w.Intercom;if(typeof ic==='function'){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/{YOUR_APP_ID}';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
        ";
    }

    /**
     * Scroll event listener
     * 
     * @return string
     */
    private function _onscroll(): string
    {
        return "var intercomMessengerPlugin={windowHeight:window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight,documentHeight:document.documentElement.scrollHeight};window.addEventListener('scroll',function(){intercomMessengerPlugin.windowScrollTop=window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop||0;if(intercomMessengerPlugin.windowScrollTop+intercomMessengerPlugin.windowHeight>intercomMessengerPlugin.documentHeight-240){Intercom('update',{'hide_default_launcher':false})}else{Intercom('update',{'hide_default_launcher':true})}},false);";
    }
}
