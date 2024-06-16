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

trait MessengerTrait
{
    /**
     * @var array $apiBaseRegions 
     */
    private $apiBaseRegions = [
        'default' => ['name' => 'region.name.default', 'url' => 'https://api-iam.intercom.io'],
        'us' => ['name' => 'region.name.us', 'url' => 'https://api-iam.intercom.io'],
        'eu' => ['name' => 'region.name.eu', 'url' => 'https://api-iam.eu.intercom.io'],
        'au' => ['name' => 'region.name.au', 'url' => 'https://api-iam.au.intercom.io'],
    ];
    
    /**
     * API base regions
     * 
     * @return array 
     */
    public function getApiBaseRegions(): array
    {
        return $this->apiBaseRegions;
    }

    /**
     * API base regions keys
     * 
     * @return array 
     */
    public function getApiBaseRegionsKeys(): array
    {
        return array_keys($this->apiBaseRegions);
    }

    /**
     * API default base region key
     * 
     * @return array 
     */
    public function getApiDefaultBaseRegionKey(): string
    {
        $keys = array_keys($this->apiBaseRegions);

        return isset($this->apiBaseRegions['default']) ? 'default' : $keys[0];
    }

    /**
     * Get API base region
     * 
     * @param string $region
     * 
     * @return array 
     */
    public function getApiBaseRegion(string $region = 'default'): array
    {
        if (!\in_array($region, $this->getApiBaseRegionsKeys())) {
            $region = $this->getApiDefaultBaseRegionKey();
        }

        return $this->apiBaseRegions[$region] ?? $this->apiBaseRegions['default'];
    }

    /**
     * Get API base URL
     * 
     * @param string $region
     * 
     * @return string 
     */
    public function getApiBaseUrl(string $region = 'default'): string
    {
        $api = $this->getApiBaseRegion($region);

        return $api['url'] ?? $this->apiBaseRegions[$this->getApiDefaultBaseRegionKey()]['url'];
    }
}
