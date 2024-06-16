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
use jimstrike\intercommessenger\Plugin;
use jimstrike\intercommessenger\helpers\PluginHelper;

trait SettingsTrait
{
    /**
     * Set array value for property
     * 
     * @param string $field
     * @param mixed $value
     * @param int $siteId default
     * 
     * @return array
     * $this->appId = [
     *     ['siteId' => 'value'],
     *     ['siteId' => 'value']
     * ]
     */
    public function makeValue(string $field, $value, int $siteId = 1): array
    {
        // Sanitize value
        $value = $this->_sanitizeValue($field, $value, $siteId);

        $base = \is_array($this->$field) ? $this->$field : [];
        
        $replace = [($siteId) => (\is_string($value) ? \trim($value) : ($value ?? ''))];

        $a = \array_replace($base, $replace) ?? (array)$this->$field;
        
        \ksort($a);

        return $a;
    }

    // Misc helpers
    // =========================================================================

    /**
     * Check is enabled by sections
     * 
     * @param int|null $siteId default
     * @return bool
     */
    public function isEnabledBySections(int $siteId = null): bool
    {
        $sections = \array_keys(\array_filter((array)$this->getSections($siteId)));

        if (!$sections) {
            return true;
        }

        $request = Craft::$app->getRequest();

        $uri = \implode('/', (array)$request->getSegments()) ?: '__home__';
        $entry = \craft\elements\Entry::find()->uri($uri)->one();

        if ($entry instanceof \craft\elements\Entry) {
            if (\in_array(($entry->sectionId ?? null), $sections)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check is enabled by URL paths
     * 
     * @param int|null $siteId default
     * @return bool
     */
    public function isEnabledByUrlPaths(int $siteId = null): bool
    {
        $urlPaths = $this->getUrlPaths($siteId);
        
        if (!$urlPaths) {
            return true;
        }

        $hasActiveUrlPaths = $this->_hasActiveUrlPaths($siteId, $urlPaths);

        if (!$hasActiveUrlPaths) {
            return true;
        }

        $route = '/' . trim(Craft::$app->getRequest()->getFullPath(), '/');

        foreach ($urlPaths as $urlPath) {
            $path = $urlPath[0] ?? '/';
            $active = (bool)$urlPath[1] ?? false;

            if ($active && $path == $route) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check is enabled by multiple fields
     * 
     * @param int|null $siteId default
     * @return bool
     */
    public function isEnabled(int $siteId = null): bool
    {
        $hasActiveUrlPaths = $this->_hasActiveUrlPaths($siteId);

        if ($hasActiveUrlPaths) {
            return $this->getEnabled($siteId) 
                && $this->isEnabledByUrlPaths($siteId)
            ;
        }

        $isEnabledBySections = $this->isEnabledBySections($siteId);

        return $this->getEnabled($siteId)
            && $this->isEnabledBySections($siteId)
        ;
    }

    /**
     * User field map
     * 
     * @return array
     */
    public function setupLoggedInUserFieldMap(): array
    {
        return [
            'name' => Plugin::t('settings.setup_logged_in_user.name.label'),
            'email' => Plugin::t('settings.setup_logged_in_user.email.label'),
            'dateCreated' => Plugin::t('settings.setup_logged_in_user.date_created.label'),
            'userId' => Plugin::t('settings.setup_logged_in_user.user_id.label'),
            'userHash' => Plugin::t('settings.setup_logged_in_user.user_hash.label'),
        ];
    }

    /**
     * Get alignment options
     * 
     * @return array
     */
    public function alignmentOptions(): array
    {
        return [
            [
                'value' => 'right',
                'label' => Plugin::t('settings.alignment.option.label.right'),
            ],
            [
                'value' => 'left',
                'label' => Plugin::t('settings.alignment.option.label.left'),
            ]
        ];
    }

    /**
     * Get API regional location options
     * 
     * @return array
     */
    public function apiRegionalLocationOptions(): array
    {
        $a = [];

        $regions = Plugin::$plugin->messenger->getApiBaseRegions();

        foreach ($regions as $key => $region) {
            $a[] = [
                'value' => $key,
                'label' => Plugin::t($region['name']),
            ];
        }

        return $a;
    }

    /**
     * Get default color theme
     * 
     * @return string 
     */
    public function getDefaultColorTheme(): string
    {
        return Plugin::$plugin->messenger->getDefaultColorTheme();
    }

    // Private methods
    // =========================================================================

    /**
     * Check if any active URL paths
     * 
     * @param int|null $siteId default
     * @param array|null $urlPaths default
     * @return bool
     */
    private function _hasActiveUrlPaths(int $siteId = null, array $urlPaths = null): bool
    {
        if (!$urlPaths) {
            $urlPaths = $this->getUrlPaths($siteId);
        }

        if (!$urlPaths) {
            return false;
        }
        
        foreach ($urlPaths as $urlPath) {
            $path = $urlPath[0] ?? '/';
            $active = (bool)$urlPath[1] ?? false;

            if ($path && $active) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sanitize value
     * 
     * @param string $field
     * @param mixed $value
     * @param int $siteId
     * @return mixed
     */
    private function _sanitizeValue(string $field, $value, int $siteId = 1)
    {
        // URL paths
        if ($field == 'urlPaths') {
            if (!empty($value) && is_array($value)) {
                $value = call_user_func(function() use ($value) {
                    foreach ($value as $key => $row) {
                        if (!isset($row[0])) {
                            continue;
                        }
        
                        if (empty($row[0])) {
                            unset($value[$key]);
                            continue;
                        }
        
                        $parsed = parse_url($row[0]);
                        $path = $parsed['path'] ?? '/';
                        $path = '/' . trim(trim($path), '/');
                        
                        $value[$key][0] = $path;
                    }

                    $col1 = array_column($value, 0);
                    $col2 = array_column($value, 1);

                    $col1 = array_unique($col1);

                    $a = []; 
                    
                    foreach ($col1 as $key => $col) {
                        $a[$key][0] = $col;
                        $a[$key][1] = $col2[$key];
                    }
                    
                    return $a;
                });
            }
        }

        // Identity secret
        if ($field == 'identitySecret') {
            $value = call_user_func(function() use ($value, $siteId) {
                if (empty($value)) {
                    return '';
                }

                $mask = PluginHelper::mask($value);

                if ($value == $mask) {
                    $value = $this->getIdentitySecret($siteId);
                }

                return $value;
            });
        }

        // Horizontal / Vertical padding
        if ($field == 'horizontalPadding' || $field == 'verticalPadding') {
            $value = (int)$value;
        }

        // Hide default launcher
        if ($field == 'hideDefaultLauncher') {
            if (!$this->getEnableCustomLauncher($siteId)) {
                $value = false;
            }

            if (!$this->getEnableCustomLauncher($siteId) && !$this->getShowDefaultLauncherScrollBottomPageOnly($siteId)) {
                $value = false;
            }

            if ($this->getShowDefaultLauncherScrollBottomPageOnly($siteId)) {
                $value = true;
            }
        }

        // --

        return $value;
    }

    /**
     * Get setting
     * 
     * @param string $setting
     * @param int|null $siteId default
     * @return mixed
     */
    private function _getSetting(string $setting, int $siteId = null)
    {
        if (empty($siteId)) {
            $siteId = Craft::$app->getSites()->getCurrentSite()->id ?? null;
        }

        $configs = Craft::$app->getConfig()->getConfigFromFile(Plugin::$plugin->handle);
        
        if (isset($configs[$siteId][$setting])) {
            return $configs[$siteId][$setting];
        }

        if (isset($configs[$setting])) {
            return $configs[$setting];
        }

        return $this->$setting[$siteId] ?? '';
    }
}
