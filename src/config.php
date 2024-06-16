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

/**
 * Intercom Messenger config.php
 *
 * This file exists only as a template for the "Intercom Messenger" settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to '<craft_project>/config' as 'intercom-messenger.php'
 * and make your changes there to override default settings.
 *
 * Once copied to '<craft_project>/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

use craft\helpers\App;

 // Same settings on all sites
return [
    /**
     * App ID 
     * 
     * Workspace ID for your Intercom account. 
     * @link https://www.intercom.com/help/en/articles/3539-where-can-i-find-my-workspace-id-app-id)
     * 
     * @var string
     */ 
    'appId' => '',

    /**
     * Enable "Intercom Messenger"?
     * 
     * Determines if the plugin should be enabled on your site. 
     * You can preview the plugin before enabling it.
     * 
     * @var bool
     */ 
    'enabled' => false,

    /**
     * Set up for logged-in users?
     * 
     * When you set up your Messenger for logged-in users, you know who your users 
     * are when they chat with you or use your product. This helps you support your customers 
     * and answer questions via live chat. Choose which user fields you want to send.
     * 
     * NOTE: In order to enforce identity verification for logged-in users you must turn on 
     * "User hash" together with "User ID" and/or "Email". Enable identity verification.
     * @link https://www.intercom.com/help/en/articles/183-enable-identity-verification-for-web-and-mobile
     * 
     * @var array|null
     */
    'setupLoggedInUser' => [
        //* @var bool */
        'name' => false,
        //* @var bool */
        'email' => false,
        //* @var bool */
        'dateCreated' => false,
        //* @var bool */
        'userId' => false,
        //* @var bool */
        'userHash' => false,
    ],

    /**
     * Identity verification secret (on web)
     * 
     * Identity verification prevents third parties from impersonating your logged-in users and seeing their conversations. 
     * We strongly recommend that all Intercom customers enforce identity verification. Used in compination with 
     * "User hash", "User ID" and/or "Email" from "Set up for logged-in users" setting above. 
     * @link https://www.intercom.com/help/en/articles/183-enable-identity-verification-for-web-and-mobile
     * 
     * !IMPORTANT:
     * Please DO NOT insert your "Identity Verification Secret" here. 
     * Save it as an environment variable in your ".env" file instead.
     * 
     * @var string 
     */
    'identitySecret' => App::env('INTERCOM_IDENTITY_VERIFICATION_SECRET'),

    /**
     * User groups for logged-in users
     * 
     * Set up for logged-in users only if the user belongs to the selected user groups below or all groups if none selected.
     * 
     * @var array|null 
     */
    'setupUserGroups' => [
        //* @var bool */
        (10) => true,  // user group with ID 10 (show)
        //* @var bool */
        (11) => false, // user group ID 11 (don't show)
    ],

    /**
     * Sections
     * 
     * Determines whether the Messenger must be displayed only on selected sections or all sections if none selected.
     * 
     * @var array|null 
     */
    'sections' => [
        //* @var bool */
        (1) => true,  // section with ID 1 (show)
        //* @var bool */
        (2) => false, // section with ID 2 (don't show)
    ],

    /**
     * URL paths
     * 
     * Determines whether the Messenger must be displayed only on selected URL paths. 
     * It will override "Sections" setting above if there's at least one active URL path below.
     * 
     * @var array|null
     */
    'urlPaths' => [
        (0) => [
            //* @var string */
            0 => '/url/path/here',
            //* @var bool */
            1 => true
        ],
        (1) => [
            0 => '/another/url/path/here',
            1 => false
        ]
    ],

    /**
     * Alignment
     * 
     * Determines which bottom corner you want the Messenger to sit on. 
     * By default it will sit in the bottom right corner of your product or site.
     * 
     * Possible values are: 'right' and 'left'.
     * 
     * @var string
     */
    'alignment' => 'right',

    /**
     * Horizontal padding
     * 
     * You can set your horizontal padding to 20 or higher. 
     * The Messenger will revert to the default horizontal padding setting (20) on mobile.
     * 
     * @var int|string
     */
    'horizontalPadding' => 20,

    /**
     * Vertical padding
     * 
     * You can set your vertical padding to 20 or higher. 
     * The Messenger will revert to the default vertical padding setting (20) on mobile.
     * 
     * @var int|string
     */
    'verticalPadding' => 20,

    /**
     * Action color
     * 
     * Used in button links and more to highlight and emphasise. Default color is: #8f00b3
     * 
     * @var string
     */
    'actionColor' => '#8f00b3',

    /**
     * Background color
     * 
     * Used behind your team profile and other attributes. Default color is: #8f00b3
     * 
     * @var string
     */
    'backgroundColor' => '#8f00b3',

    /**
     * Use your own theme color?
     * 
     * Allows you to overwrite Intercom's color settings and set your own "Action color" and "Background color" above.
     * 
     * @var bool
     */
    'useOwnThemeColor' => false,

    /**
     * Show standard launcher only when a user scrolls to the bottom of the page?
     * 
     * You'll need to have "Hide default launcher?" setting turned on so that 
     * the launcher is hidden when the page loads initially.
     * 
     * NOTE: If your pages are not scrollable then keep this feature turned off.
     * 
     * @var bool
     */
    'showDefaultLauncherScrollBottomPageOnly' => false,

    /**
     * Enable custom launcher?
     * 
     * Before enabling this feature add the attribute `ns-intercom-messenger-custom-launcher` 
     * to the HTML element that you want the Messenger to open when clicked. 
     * Add as many custom launchers to your website as you wish.
     * 
     * EXAMPLE: <a href="mailto:help@your-app.com" ns-intercom-messenger-custom-launcher>Support</a>
     * 
     * @var bool
     */
    'enableCustomLauncher' => false,

    /**
     * Hide default launcher?
     * 
     * You can choose to disable the standard launcher so that only your custom launcher appears. 
     * Before hiding the default launcher make sure you have set up a custom launcher in your HTML code.
     * 
     * NOTE: It works only if the custom launcher is enabled or the standard launcher 
     * is shown only when the user scrolls to the bottom of the page.
     * 
     * @var bool
     */
    'hideDefaultLauncher' => false,

    /**
     * API regional location
     * 
     * If you are using a data center hosted in one of the regional locations listed below, you will need to choose the associated API base. 
     * Select the default value if in doubt or preview and test before enabling the plugin.
     * 
     * Available options are: 'default', 'us', 'eu', 'au'. 
     * 
     * A null or empty value will trigger 'default' option.
     * 
     * @var string
     */ 
    'apiRegionalLocation' => 'default'
];

//** Multi-site settings */
/* return [
    // Site with ID: 1
    (1) => [
        'enabled' => true,
        'setupLoggedInUser' => [
            'name' => false,
            'email' => true,
            'dateCreated' => false,
            'userId' => false,
            'userHash' => true,
        ],
        'urlPaths' => [
            (0) => [
                0 => '/url/path/here',
                1 => true
            ],
            (1) => [
                0 => '/another/url/path/here',
                1 => false
            ]
        ],
        // ...
    ],

    // Site with ID: 2
    (2) => [
        'enabled' => false,
    ],
]; */