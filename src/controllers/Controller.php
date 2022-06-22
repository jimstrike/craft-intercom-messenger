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

use jimstrike\intercommessenger\Plugin;

/**
 * Class Controller
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
abstract class Controller extends \craft\web\Controller
{
    /**
     * Plugin properties and methods to be accessible in templates 
     * if you don't want to send the Plugin::$plugin object.
     *
     * @return array
     */
    protected function plugin(): array
    {
        return [
            'name' => Plugin::$plugin->name,
            'handle' => Plugin::$plugin->handle,
            'settings' => Plugin::$plugin->getSettings(),
            'help' => Plugin::$plugin::help(),
            'version' => Plugin::$plugin->version,
            'composer' => Plugin::$plugin->composer(),
        ];
    }
}
