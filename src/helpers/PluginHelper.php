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

namespace jimstrike\intercommessenger\helpers;

/**
 * Class Sanitize
 * 
 * @author  Dhimiter Karalliu
 * @package Intercom Messenger
 * @since   1.0.0
 */
class PluginHelper {

    /**
     * Mask string
     * 
     * @param string $str
     * @param string $char default
     * 
     * @return string
     */
    public static function mask(string $str, string $char = '*'): string
    {
        if (empty($str)) {
            return '';
        }

        $timesToRepeat = 6;
        $strlen = strlen($str);

        $prefix = function($times) use ($char) {
            return "{$char}hidden" . str_repeat($char, $times);
        };

        if ($strlen < 4) {
            return $prefix($timesToRepeat);
        }

        $length = $strlen > 6 ? 3 : 1;
        $timesToRepeat = $strlen > 6 ? round($timesToRepeat / 2) : $timesToRepeat - 1;
        $suffix = substr($str, -$length);

        return $prefix($timesToRepeat) . $suffix;
    }

    /**
     * Minify string
     * 
     * @param string $str
     * 
     * @return string
     */
    public static function minify($str): string
    {
        $str = str_replace(["\r", "\n"], " ", $str);
        $str = preg_replace(["/\s+\n/", "/\n\s+/", "/ +/"], ["\n", "\n ", " "], $str);

        return $str;
    }

    /**
     * Sanitize a hex color value
     * 
     * @param string $color
     * @param bool $hash default
     * 
     * @return string
     */
    public static function color(string $color, bool $hash = true): string
    {
        $color = \str_replace('#', '', trim($color));
    
        // If the string is 6 characters long then use it in pairs.
        if (3 == \strlen($color)) {
            $color = \substr($color, 0, 1) . \substr($color, 0, 1) . \substr($color, 1, 1) . \substr($color, 1, 1) . \substr($color, 2, 1) . \substr($color, 2, 1);
        }
    
        $substr = [];
        
        for ($i = 0; $i <= 5; $i++) {
            $default = 0 == $i ? 'F' : $substr[$i-1];
            $substr[$i] = \substr($color, $i, 1);
            $substr[$i] = false === $substr[$i] || !\ctype_xdigit($substr[$i]) ? $default : $substr[$i];
        }

        $hex = \implode('', $substr);
    
        return !$hash ? $hex : '#' . $hex;
    }
}