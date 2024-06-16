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

namespace jimstrike\intercommessenger\twigextensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use Craft;
use craft\helpers\StringHelper;

use jimstrike\intercommessenger\Plugin;
use jimstrike\intercommessenger\helpers\PluginHelper;

/**
 * Class TwigExtension
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class TwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     * 
     * @return string
     */
    public function getName(): string
    {
        return Plugin::$plugin->name;
    }

    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function getFilters(): array
    {
        $prefix = $this->prefix();
        
        return [   
            // |tt         
            new TwigFilter('tt', [Plugin::class, 't']),

            // |intercom_messenger_t
            new TwigFilter($prefix . '_t', [Plugin::class, 't']),
            
            // |intercom_messenger_mask
            new TwigFilter($prefix . '_mask', [PluginHelper::class, 'mask']),
        ];
    }

    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function getFunctions(): array
    {
        $prefix = $this->prefix();

        return [
            // intercom_messenger_asset(resourcePath)
            new TwigFunction($prefix . '_asset', [Plugin::class, 'asset']),
            
            // intercom_messenger_script(siteId, false)
            new TwigFunction($prefix . '_script', [Plugin::$plugin->messenger, 'scriptExtend']),

            // intercom_messenger_custom_launcher_selector()
            new TwigFunction($prefix . '_custom_launcher_selector', [Plugin::$plugin->messenger, 'customLauncherSelector']),
        ];
    }

    // Private Methods
    // =========================================================================

    /**
     * Prefix filter and function name with plugin handle 'intercom_messenger'
     * 
     * @return string 
     */
    private function prefix(): string
    {
        return StringHelper::toSnakeCase(Plugin::$plugin->handle);
    }
}
