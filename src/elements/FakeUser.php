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

namespace jimstrike\intercommessenger\elements;

use craft\elements\User;

/**
 * Class FakeUser
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class FakeUser extends User
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->_setDefaultProperties();
    }

    /**
     * Set property
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return FakeUser
     */
    public function setProperty(string $name, $value): ?self
    {
        if (property_exists(__CLASS__, $name)) {
            $this->$name = $value;
        }

        return $this;
    }

    /**
     * Set default properties
     * 
     * @return void
     */
    private function _setDefaultProperties(): void
    {
        $properties = [
            'id' => 0,
            'firstName' => 'Fake',
            'lastName' => 'User',
            'email' => 'fake@localhost',
            'dateCreated' => new \DateTime('2021-01-01 00:00:00'),
        ];

        foreach ($properties as $name => $value) {
            if (property_exists(__CLASS__, $name)) {
                $this->$name = $value;
            }
        }
    }
}
