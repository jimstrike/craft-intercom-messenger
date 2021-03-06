<?php
/**
 * Intercom Messenger plugin for Craft CMS 3.x
 *
 * Intercom.com: the Business Messenger you and your customers will love.
 * Sure, it does live chat. But there’s also bots, apps, product tours, and more
 * like email, messages, and a help center that help you build relationships with your customers.
 * For more information visit: https://www.intercom.com/
 *
 * @link      https://github.com/jimstrike
 * @copyright Copyright (c) Dhimiter Karalliu
 */

namespace jimstrike\intercommessenger\controllers;

use Craft;

use yii\web\Response;

use jimstrike\intercommessenger\Plugin;

/**
 * Class SettingsController
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class SettingsController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * The actions must be in 'kebab-case'
     * @var bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = false;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
    }

    /**
     * Index action
     *
     * @return Response
     */
    public function actionIndex(): Response
    {
        $sites = Craft::$app->getSites();
        
        $primarySite = $sites->getPrimarySite();
        $siteHandle = Craft::$app->getRequest()->getQueryParam('site') ?? $primarySite->handle;
        $currentSite = $sites->getSiteByHandle($siteHandle) ?? $primarySite;

        return $this->renderTemplate(Plugin::$plugin->handle . '/_settings/index', [
            'plugin' => $this->plugin(),
            'currentSite' => $currentSite,
        ]);
    }

    /**
     * Save action
     *
     * @return Response|null
     */
    public function actionSave()
    {
        $this->requirePostRequest();

        $params = Craft::$app->getRequest()->getBodyParams();
        
        $siteId = $params['siteId'];
        $data = $params['settings'];

        $settings = Plugin::getInstance()->getSettings();

        if (empty($data['appId'])) {
            $data['enabled'] = false;
        }

        foreach ($data as $field => $value) {
            $settings->$field = $settings->makeValue($field, $value, $siteId);
        }

        $pluginSettingsSaved = Craft::$app->getPlugins()->savePluginSettings(Plugin::getInstance(), $settings->toArray());

        Craft::$app->getSession()->setNotice(Plugin::t('global.settings.saved'));

        return $this->redirectToPostedUrl();
    }

    /**
     * User hash check
     *
     * @return Response
     */
    public function actionUserHashCheck(): Response
    {
        $siteId = Craft::$app->getRequest()->getQueryParam('siteId') 
            ?? Craft::$app->getSites()->getPrimarySite()->id;

        $data = Craft::$app->getRequest()->getQueryParam('data') 
            ?? '';

        $secret = Plugin::getInstance()->getSettings()->getIdentitySecret($siteId);
        $hash = Plugin::$plugin->messenger->userHash($data, $secret);

        return $this->asJson([
            $hash => "is the user hash for `{$data}`.",
        ]);
    }
}
