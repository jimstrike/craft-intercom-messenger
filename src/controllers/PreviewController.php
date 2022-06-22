<?php
/**
 * Intercom Messenger plugin for Craft CMS 4.x
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
 * Class PreviewController
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class PreviewController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * The actions must be in 'kebab-case'
     * @var bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected array|int|bool $allowAnonymous = false;

    // Public Methods
    // =========================================================================

    public function init(): void
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

        $script = Plugin::$plugin->messenger->script($currentSite->id);
        
        return $this->renderTemplate(Plugin::$plugin->handle . '/_preview/index', [
            'plugin' => $this->plugin(),
            'script' => $script,
            'currentSite' => $currentSite
        ]);
    }
}
