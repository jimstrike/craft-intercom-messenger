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

use jimstrike\intercommessenger\Plugin;
use jimstrike\intercommessenger\helpers\PluginHelper;

/**
 * Class Settings
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class Settings extends Model
{
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
        if (Craft::$app->getEdition() != Craft::Pro) {
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
        if (Craft::$app->getEdition() != Craft::Pro) {
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
        if (Craft::$app->getEdition() !== Craft::Pro) {
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

    // Helper set methods
    // =========================================================================

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
                return true; break;
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
                return true; break;
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