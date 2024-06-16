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
 * Translation: en 
 */
return [

    // PLUGIN
    // =========================================================================

    'plugin.name' => 'Intercom Messenger',
    'plugin.plugin_name' => 'Intercom Messenger plugin',
    'plugin.slogan' => 'Intercom.com: the Business Messenger you and your customers will love.',

    // GLOBAL
    // =========================================================================
    
    'global.settings' => 'Settings',
    'global.settings.saved' => 'Settings saved.',
    'global.save' => 'Save',
    'global.please_wait' => 'Please wait',
    'global.documentation' => 'Documentation',
    'global.docs' => 'Docs',
    'global.preview' => 'Preview',
    'global.note' => 'Note',
    'global.notice' => 'Notice',
    'global.learn_more' => 'Learn more',
    'global.help_center' => 'Help center',
    'global.help' => 'Help',
    'global.version' => 'Version',
    'global.readme' => 'README',
    'global.readme.github' => 'README on GitHub',
    'global.trial' => 'Trial',
    'global.subscription' => 'Subscription',
    'global.position' => 'Position',
    'global.launcher' => 'Launcher',
    'global.custom_launcher' => 'Custom launcher',
    'global.example' => 'Example',
    'global.examples' => 'Examples',
    'global.support' => 'Support',
    'global.craftpro.footnote' => 'You need Craft PRO in order to use this feature.',

    // SUBNAV
    // =========================================================================

    'subnav.getting_started.heading' => 'Documentation',
    'subnav.settings.heading' => 'Site settings',

    'subnav.getting_started.label' => 'Getting started',
    'subnav.settings.label' => 'Settings',

    // SETTINGS
    // =========================================================================

    // Settings label
    'settings.app_id.label' => 'App ID',
    'settings.enabled.label' => 'Enable "{plugin}"',
    'settings.setup_logged_in_user.label' => 'Set up for logged-in users',
    'settings.setup_logged_in_user.name.label' => 'Name',
    'settings.setup_logged_in_user.email.label' => 'Email',
    'settings.setup_logged_in_user.date_created.label' => 'Date created',
    'settings.setup_logged_in_user.user_id.label' => 'User ID',
    'settings.setup_logged_in_user.user_hash.label' => 'User hash',
    'settings.identity_secret.label' => 'Identity verification secret (on web)',
    'settings.setup_user_groups.label' => 'User groups for logged-in users',
    'settings.sections.label' => 'Sections',
    'settings.url_paths.label' => 'URL paths',
    'settings.url_paths.cols.url.label' => 'URL',
    'settings.url_paths.cols.active.label' => 'Active',
    'settings.alignment.label' => 'Alignment',
    'settings.horizontal_padding.label' => 'Horizontal padding',
    'settings.vertical_padding.label' => 'Vertical padding',
    'settings.action_color.label' => 'Action color',
    'settings.background_color.label' => 'Background color',
    'settings.use_own_theme_color.label' => 'Use your own color theme',
    'settings.show_default_launcher_scroll_bottom_page_only.label' => 'Show standard launcher only when a user scrolls to the bottom of the page',
    'settings.enable_custom_launcher.label' => 'Enable custom launcher',
    'settings.hide_default_launcher.label' => 'Hide default launcher',
    'settings.api_regional_location.label' => 'API regional location',

    // Settings instructions
    'settings.app_id.instructions' => 'Workspace ID for your Intercom account',
    'settings.enabled.instructions' => 'Determines if the plugin should be enabled on your site. You can preview the plugin before enabling it.',
    'settings.setup_logged_in_user.instructions' => 'When you set up your Messenger for logged-in users, you know who your users are when they chat with you or use your product. This helps you support your customers and answer questions via live chat. Choose which user fields you want to send.',
    'settings.identity_secret.instructions' => 'Identity verification prevents third parties from impersonating your logged-in users and seeing their conversations. We strongly recommend that all Intercom customers enforce identity verification. Used in compination with "{user_hash}", "{user_id}" and/or "{email}" from "{setup_logged_in_user}" setting above. {enable_identity_verification}',
    'settings.setup_user_groups.instructions' => 'Set up for logged-in users only if the user belongs to the selected user groups below or all groups if none selected.',
    'settings.sections.instructions' => 'Determines whether the Messenger must be displayed only on selected sections or all sections if none selected.',
    'settings.url_paths.instructions' => 'Determines whether the Messenger must be displayed only on selected URL paths. It will override "{sections}" setting above if there\'s at least one active URL path below.',
    'settings.url_paths.cols.url.info' => 'Without base URL e.g.:<br> <code>/contact<code>',
    'settings.url_paths.cols.active.info' => 'Enable check against URL path.',
    'settings.alignment.instructions' => 'Determines which bottom corner you want the Messenger to sit on. By default it will sit in the bottom right corner of your product or site.',
    'settings.alignment.option.label.left' => 'Left',
    'settings.alignment.option.label.right' => 'Right',
    'settings.horizontal_padding.instructions' => 'You can set your horizontal padding to 20 or higher. The Messenger will revert to the default horizontal padding setting (20) on mobile.',
    'settings.vertical_padding.instructions' => 'You can set your vertical padding to 20 or higher. The Messenger will revert to the default vertical padding setting (20) on mobile.',
    'settings.action_color.instructions' => 'Used in button links and more to highlight and emphasise. Default color is: {default_color}',
    'settings.background_color.instructions' => 'Used behind your team profile and other attributes. Default color is: {default_color}',
    'settings.use_own_theme_color.instructions' => 'Allows you to overwrite Intercom\'s color settings and set your own "{action_color}" and "{background_color}" above.',
    'settings.show_default_launcher_scroll_bottom_page_only.instructions' => 'You\'ll need to have "{hide_default_launcher}" setting turned on so that the launcher is hidden when the page loads initially.',
    'settings.enable_custom_launcher.instructions' => 'Before enabling this feature add the tag attribute {custom_launcher_selector} to the HTML element that you want the Messenger to open when clicked. Add as many custom launchers to your website as you wish.',
    'settings.hide_default_launcher.instructions' => 'You can choose to disable the standard launcher so that only your custom launcher appears. Before hiding the default launcher make sure you have set up a custom launcher in your HTML code.',
    'settings.api_regional_location.instructions' => 'If you are using a data center hosted in one of the regional locations listed below, you will need to choose the associated API base. Select the default value if in doubt or preview and test before enabling the plugin.',

    // Settings placeholder
    'settings.identity_secret.placeholder' => 'Paste Intercom identity verification secret here...',

    // Settings footnotes
    'settings.app_id.footnote' => 'To find your workspace ID sign in to your account and navigate to your workspace. Then check the URL in the address bar. It will look something like this: {logged_in_url_pattern}. The part of the URL where it says "{workspace_id}" will be your app or workspace ID.',
    'settings.setup_logged_in_user.footnote' => 'In order to enforce identity verification for logged-in users you must turn on "{user_hash}" together with "{user_id}" and/or "{email}". Admin users are excluded from this setup. When previewing in control panel or plugin\'s admin section, a fake logged-in user is generated. {enable_identity_verification}',
    'settings.identity_secret.footnote' => 'Verify your {user_id} hash or your {email} hash with Intercom\'s hash calculator. If they match then you are good to go, otherwise update identity verification secret above with the correct one.',
    'settings.show_default_launcher_scroll_bottom_page_only.footnote' => 'If your pages are not scrollable then keep this feature turned off.',
    'settings.enable_custom_launcher.footnote' => 'Check out the examples on how to create a custom launcher for usage in templates. {documentation_url}',
    'settings.hide_default_launcher.footnote' => 'It works only if the custom launcher is enabled or the standard launcher is shown only when the user scrolls to the bottom of the page.',

    // Settings errors
    'settings.app_id.errors.blank' => 'App ID cannot be blank.',
    'settings.identity_secret.errors.blank' => 'Identity verification secret cannot be blank.',

    // Settings warnings
    'settings.config.warning' => 'This is being overridden by the `{setting}` setting in the `{file}` file.',

    // PREVIEW
    // =========================================================================

    'preview.with.site' => 'Preview with site',
    'preview.invalid.app.id' => 'Invalid App ID',

    // PAGE
    // =========================================================================

    'page.getting_started.title' => 'Getting started',
    'page.settings.title' => 'Settings',
    'page.settings.footnote' => 'You will need an Intercom {trial} or {subscription} in order to use this plugin. Or you can create a free {developer_account} to build and test Intercom Messenger in development environment before signing up for a subcription. For more information visit {intercom_homepage}',

    // TEXT
    // =========================================================================

    // EXCEPTION
    // =========================================================================

    'exception.forbidden.access' => 'You are not allowed to access this page.',
    'exception.forbidden.action' => 'You are not allowed to perform this action.',

    // PERMISSIONS
    // =========================================================================

    'permissions.embed' => 'Embed and allow usage',

    // INTERCOM
    // =========================================================================

    'intercom' => 'Intercom',
    'intercom.com' => 'Intercom.com',
    'intercom.messenger' => 'Intercom Messenger',
    'intercom.homepage' => 'Homepage',
    'intercom.website' => 'Website',
    'intercom.developer_account' => 'Developer account',
    'intercom.developer_signups' => 'Developer signup',
    'intercom.developers' => 'Developers',

    // help
    'intercom.help' => 'Intercom Help',
    'intercom.help.center' => 'Intercom Help Center',
    'intercom.help.messenger' => 'About Messenger',
    'intercom.help.customize_basics' => 'Customize basics',
    'intercom.help.find_app_id' => 'Find your app ID',
    'intercom.help.trusted_domains' => 'List trusted domains',
    'intercom.enforce_identity' => 'Enforce identity',
    'intercom.help.identity_verification' => 'Identity verification',
    'intercom.help.enable_identity_verification' => 'Enable identity verification',
    'intercom.help.enforce_identity_verification' => 'Enforce identity verification',
    'intercom.help.disable_standard_launcher' => 'Disable standard launcher',

    // REGIONS
    // =========================================================================

    'region.name.default' => 'Default',
    'region.name.us' => 'US',
    'region.name.eu' => 'Europe',
    'region.name.au' => 'Australia',
];
