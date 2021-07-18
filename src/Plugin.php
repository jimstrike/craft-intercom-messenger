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

namespace jimstrike\intercommessenger;

use Craft;
use craft\services\Plugins;
use craft\events\RegisterUrlRulesEvent;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\View;
use craft\web\twig\variables\CraftVariable;
use craft\helpers\UrlHelper;

use yii\base\Event;

use jimstrike\intercommessenger\models\Settings;
use jimstrike\intercommessenger\services\Messenger;
use jimstrike\intercommessenger\twigextensions\TwigExtension;
use jimstrike\intercommessenger\variables\Variable;

/**
 * Class Plugin
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class Plugin extends \craft\base\Plugin
{
    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Plugin::$plugin
     *
     * @var Plugin
     */
    public static $plugin;

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @inheritdoc
     * @var string
     */
    public $schemaVersion = '1.0.3';

    /**
     * @inheritdoc
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @inheritdoc
     * @var bool
     */
    public $hasCpSection = true;

    /**
     * @var string
     */
    const ASSETS_NS_PREFIX = '@jimstrike/intercommessenger/assets/dist';

    /**
     * @inheritdoc
     * 
     * @return void
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        // Disable cp section if no admin changes allowed
        if (!Craft::$app->getConfig()->getGeneral()->allowAdminChanges) {
            $this->hasCpSection = false;
        }

        // Register components and other stuff
        $this->_registerComponents();
        $this->_registerTwigExtensions();
        $this->_registerVariables();
        $this->_registerCpRoutes();
        $this->_registerBeginBody();

        // Logging - We're loaded
        Craft::info(self::t('{name} plugin loaded', ['name' => $this->name]), __METHOD__);
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): array
    {
        $item = parent::getCpNavItem();

        $item['label'] = self::t('plugin.name');

        return $item;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsResponse()
    {
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl($this->handle . '/settings'));
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     * 
     * @return Settings|null
     */
    protected function createSettingsModel()
    {
        $settings = new Settings();
        
        return $settings;
    }

    // Static helpers
	// =========================================================================

    /**
     * Plugin's t() method
     * Plugin::t('message to be translated')
     * 
     * @var string $message
     * @var array $params
     * 
     * @return string
     */
	public static function t(string $message, array $params = [], string $language = null): string
    {
        return Craft::t(self::$plugin->handle, $message, $params, $language);
    }

    /**
     * Plugin's comment
     * 
     * @return string
     */
    public static function comment(): string
    {
        return self::$plugin->name .' ('. self::$plugin->handle .') plugin';
    }

    /**
     * Assets ns prefix
     * 
     * @return string
     */
    public static function assetsNsPrefix(): string
    {
        return rtrim(self::ASSETS_NS_PREFIX, '/');
    }

    /**
     * Asset base url
     * 
     * @return string
     */
    public static function assetsBaseUrl(): string
    {
        $nsPrefix = self::assetsNsPrefix();

        return Craft::$app->assetManager->getPublishedUrl($nsPrefix, false);
    }

    /**
     * Asset
     * 
     * @return string
     */
    public static function asset(string $resourcePath): string 
    {
        return self::assetsBaseUrl() . '/' . ltrim($resourcePath, '/');
    }

    /**
     * Composer
     * 
     * @return array
     */
    public static function composer(): array
    {
        try {
            $composer = __DIR__ . '/../composer.json';
            $json = file_get_contents($composer);

            return json_decode($json, true);
        } 
        catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Help
     * 
     * @return array
     */
    public static function help(): array
    {
        try {
            return include __DIR__ . '/etc/help.php';
        } 
        catch (\Exception $e) {
            return [];
        }
    }

    // Private methods
    // =========================================================================

    /**
     * Register components
     * 
     * @return void
     */
    private function _registerComponents(): void
    {
        $this->setComponents([
            'messenger' => Messenger::class,
        ]);
    }

    /**
     * Register twig extensions
     * 
     * @return void
     */
    private function _registerTwigExtensions(): void
    {
        Craft::$app->view->registerTwigExtension(new TwigExtension());
    }

    /**
     * Register variables
     * 
     * @return void
     */
    private function _registerVariables(): void
    {
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set($this->handle, Variable::class);
        });
    }

    /**
     * Register CP routes
     * 
     * @return void
     */
    private function _registerCpRoutes(): void
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $routes = [
                '__home__' => 'settings/index',
                'settings' => 'settings/index',
                'settings/user-hash-check' => 'settings/user-hash-check',
                'preview' => 'preview/index',
            ];

            foreach ($routes as $route => $action) {
                $route = !$route || $route == '__home__' ? $this->handle : $this->handle . '/' . $route;
                $route = trim($route, '/');
                $event->rules[$route] = trim($this->handle . '/' . $action, '/');
            }
        });
    }

    /**
     * Register begin body
     * 
     * @return void
     */
    private function _registerBeginBody(): void
    {
        Event::on(View::class, View::EVENT_BEGIN_BODY, function() {
            $request = Craft::$app->getRequest();
            
            if ($request->getIsSiteRequest() && !$request->getIsConsoleRequest()) {
                $currentSite = Craft::$app->getSites()->getCurrentSite();

                if ($this->getSettings()->isEnabled($currentSite->id)) {
                    $script = $this->messenger->script($currentSite->id, false);

                    if ($script) {
                        $view = Craft::$app->getView(); 
                        $view->registerJs($script, View::POS_END);
                    }
                }
            }
        });
    }

    /**
     * Register end body
     * 
     * @return void
     */
    private function _registerEndBody(): void
    {
        /*Event::on(View::class, View::EVENT_END_BODY, function() {
            // @todo
        });*/
    }

    /**
     * Register before install
     * 
     * @return void
     */
    private function _registerBeforeInstall(): void
    {
        /*Event::on(Plugins::class, Plugins::EVENT_BEFORE_INSTALL_PLUGIN, function(PluginEvent $event) {
            if ($event->plugin === $this) {
                // We were just installed
            }
        });*/
    }

    /**
     * Register after install
     * 
     * @return void
     */
    private function _registerAfterInstall(): void
    {
        /*Event::on(Plugins::class, Plugins::EVENT_AFTER_INSTALL_PLUGIN, function(PluginEvent $event) {
            if ($event->plugin === $this) {
                // We were just installed
            }
        });*/
    }
}