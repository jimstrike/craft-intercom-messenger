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

namespace jimstrike\intercommessenger\variables;

use Craft;
use jimstrike\intercommessenger\Plugin;
use jimstrike\intercommessenger\models\Settings;

/**
 * Class Variable
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class Variable
{
    // Public Methods
    // =========================================================================

    /**
     * @return string 
     */
    public function getName(): string
    {
        $name = Plugin::$plugin->name;

        return $name;
    }

    /**
     * Settings
     * 
     * @return Settings model
     */
    public function settings(): Settings
    {
        return Plugin::$plugin->getSettings();
    }

    /**
     * Asset Published Url
     * 
     * Twig usage:
     * {{ craft['intercom-messenger'].asset('path/to/asset') }}
     * 
     * @param string $resourcePath
     * 
     * @return string 
     */
    public function asset(string $resourcePath): string
    {
        return Plugin::asset($resourcePath);
    }

    /**
     * Composer
     * 
     * @return string 
     */
    public function composer(): array
    {
        return Plugin::composer();
    }

    /**
     * Messenger chat script
     * 
     * Twig usage:
     * {{ craft['intercom-messenger'].script()|raw }}
     * {{ craft['intercom-messenger'].script(currentSite.id)|raw }}
     * 
     * @param int|null $siteId default
     * @param bool $wrap default
     * 
     * @return string
     */
    public function script(int $siteId = null, bool $wrap = false): string
    {
        return Plugin::$plugin->messenger->scriptExtend($siteId, $wrap);
    }

    /**
     * Custom launcher selector
     * 
     * @return string 
     */
    public function customLauncherSelector(): string
    {
        return Plugin::$plugin->messenger->customLauncherSelector();
    }
}
