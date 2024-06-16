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

namespace jimstrike\intercommessenger\models;

use Craft;
use craft\base\Model;
use craft\enums\CmsEdition;

use jimstrike\intercommessenger\Plugin;

/**
 * Class Settings
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class Settings extends Model
{
    use SettingsTrait;

    // Public
    public $appId;
    public $enabled;
    public $setupLoggedInUser;
    public $identitySecret;
    public $setupUserGroups;
    public $sections;
    public $urlPaths;
    public $alignment;
    public $horizontalPadding;
    public $verticalPadding;
    public $showDefaultLauncherScrollBottomPageOnly;
    public $enableCustomLauncher;
    public $hideDefaultLauncher;
    public $apiRegionalLocation;

    // Getters and Setters
    // =========================================================================

    /**
     * Get app ID
     * 
     * @param int|null $siteId default
     * @return string
     */
    public function getAppId(int $siteId = null): string
    {
        $setting = $this->_getSetting('appId', $siteId);
        
        return $setting ?: '';
    }

    /**
     * Get enbabled
     * 
     * @param int|null $siteId default
     * @return bool
     */
    public function getEnabled(int $siteId = null): bool
    {
        $setting = $this->_getSetting('enabled', $siteId);
        
        return (bool)$setting ?: false;
    }

    /**
     * Get setup for logged in user
     * 
     * @param int|null $siteId default
     * @return array
     */
    public function getSetupLoggedInUser(int $siteId = null): array
    {
        if (Craft::$app->getEdition() != CmsEdition::Pro) {
            return [];
        }

        $setting = $this->_getSetting('setupLoggedInUser', $siteId);
        
        return $setting ?: [];
    }

    /**
     * Get identity secret
     * 
     * @param int|null $siteId default
     * @return string
     */
    public function getIdentitySecret(int $siteId = null): string
    {
        if (Craft::$app->getEdition() != CmsEdition::Pro) {
            return '';
        }

        $setting = $this->_getSetting('identitySecret', $siteId);
        
        return $setting ?: '';
    }

    /**
     * Get setup user groups
     * 
     * @param int|null $siteId default
     * @return array
     */
    public function getSetupUserGroups(int $siteId = null): array
    {
        if (Craft::$app->getEdition() !== CmsEdition::Pro) {
            return [];
        }

        $setting = $this->_getSetting('setupUserGroups', $siteId);
        $userGroups = $setting ?: [];
        
        return $userGroups;
    }

    /**
     * Get sections
     * 
     * @param int|null $siteId default
     * @return array
     */
    public function getSections(int $siteId = null): array
    {
        $setting = $this->_getSetting('sections', $siteId);
        $sections = $setting ?: [];
        
        return $sections;
    }

    /**
     * Get URL paths
     * 
     * @param int|null $siteId default
     * @return array
     */
    public function getUrlPaths(int $siteId = null): array
    {
        $setting = $this->_getSetting('urlPaths', $siteId);
        $urlPaths = $setting ?: [];
        
        return $urlPaths;
    }

    /**
     * Get alignment
     * 
     * @param int|null $siteId default
     * @return string
     */
    public function getAlignment(int $siteId = null): string
    {
        $setting = $this->_getSetting('alignment', $siteId);

        if (!in_array($setting, ['left', 'right'])) {
            return 'right';
        }
        
        return $setting ?: 'right';
    }

    /**
     * Get horizontal padding
     * 
     * @param int|null $siteId default
     * @return int
     */
    public function getHorizontalPadding(int $siteId = null): int
    {
        $setting = (int)$this->_getSetting('horizontalPadding', $siteId);

        if ($setting < 20) {
            return 20;
        }
        
        return $setting ?: 20;
    }

    /**
     * Get vertical padding
     * 
     * @param int|null $siteId default
     * @return int
     */
    public function getVerticalPadding(int $siteId = null): int
    {
        $setting = (int)$this->_getSetting('verticalPadding', $siteId);

        if ($setting < 20) {
            return 20;
        }
        
        return $setting ?: 20;
    }

    /**
     * Get show default launcher scroll bottom page only
     * 
     * @param int|null $siteId default
     * @return bool
     */
    public function getShowDefaultLauncherScrollBottomPageOnly(int $siteId = null): bool
    {
        $setting = $this->_getSetting('showDefaultLauncherScrollBottomPageOnly', $siteId);
        
        return (bool)$setting ?: false;
    }

    /**
     * Get enable custom launcher
     * 
     * @param int|null $siteId default
     * @return bool
     */
    public function getEnableCustomLauncher(int $siteId = null): bool
    {
        $setting = $this->_getSetting('enableCustomLauncher', $siteId);
        
        return (bool)$setting ?: false;
    }

    /**
     * Get hide default launcher
     * 
     * @param int|null $siteId default
     * @return bool
     */
    public function getHideDefaultLauncher(int $siteId = null): bool
    {
        $setting = $this->_getSetting('hideDefaultLauncher', $siteId);
        
        return (bool)$setting ?: false;
    }

    /**
     * Get API reginal location
     * 
     * @param int|null $siteId default
     * @return string
     */
    public function getApiRegionalLocation(int $siteId = null): string
    {
        $setting = $this->_getSetting('apiRegionalLocation', $siteId);

        if (!in_array($setting, Plugin::$plugin->messenger->getApiBaseRegionsKeys())) {
            $setting = Plugin::$plugin->messenger->getApiDefaultBaseRegionKey();
        }
        
        return $setting;
    }
}
