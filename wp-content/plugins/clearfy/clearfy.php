<?php
/**
 * Plugin Name: Webcraftic Clearfy – WordPress optimization plugin
 * Plugin URI: https://clearfy.pro
 * Description: Disables unused Wordpress features, improves performance and increases SEO rankings, using Clearfy, which makes WordPress very easy.
 * Author: Creative Motion <info@cm-wp.com>
 * Version: 1.9.1
 * Text Domain: clearfy
 * Domain Path: /languages/
 * Author URI: https://cm-wp.com
 * Framework Version: FACTORY_447_VERSION
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

/**
 * -----------------------------------------------------------------------------
 * CHECK REQUIREMENTS
 * Check compatibility with php and wp version of the user's site. As well as checking
 * compatibility with other plugins from Webcraftic.
 * -----------------------------------------------------------------------------
 */

require_once(dirname(__FILE__) . '/libs/factory/core/includes/class-factory-requirements.php');

$plugin_info = array(
	'prefix' => 'wbcr_clearfy_',
	'plugin_name' => 'wbcr_clearfy',
	'plugin_title' => __('Clearfy', 'clearfy'),

	// PLUGIN SUPPORT
	'support_details' => array(
		'url' => 'http://clearfy.pro',
		'pages_map' => array(
			'features' => 'premium-features',  // {site}/premium-features
			'pricing' => 'pricing',           // {site}/prices
			'support' => 'support',           // {site}/support
			'docs' => 'docs'               // {site}/docs
		)
	),
	//todo: for compatibility with Robin image optimizer
	'freemius_plugin_id' => '2315',
	'freemius_public_key' => 'pk_70e226af07d37d2b9a69720e0952c',

	'has_updates' => true,
	'updates_settings' => [
		'repository' => 'github',
		'alternate_updates_mode' => true,
		'github_username' => 'Creative-Motion-Development', // обязательно, имя пользователя репозитория в Github
		'github_authorize_token' => '', // необязательно, только если репозиторий приватный
		'github_repository' => 'wp-plugin-clearfy', // необязательно, если не заполнено берется из slug
		'slug' => 'clearfy', // обязательно, slug плагина
		'maybe_rollback' => false,
		'rollback_settings' => [
			'prev_stable_version' => '0.0.0'
		]
	],

	// PLUGIN PREMIUM SETTINGS
	'has_premium' => true,
	'license_settings' => array(
		'provider' => 'freemius',
		'slug' => 'clearfy_package',
		'plugin_id' => '2315',
		'public_key' => 'pk_70e226af07d37d2b9a69720e0952c',
		'price' => 29,
		'has_updates' => true,
		'updates_settings' => array(
			'maybe_rollback' => true,
			'rollback_settings' => array(
				'prev_stable_version' => '0.0.0'
			)
		)
	),

	// PLUGIN SUBSCRIBE FORM
	'subscribe_widget' => true,
	'subscribe_settings' => ['group_id' => '105407119'],

	// PLUGIN ADVERTS
	'render_adverts' => true,
	'adverts_settings' => array(
		'dashboard_widget' => true, // show dashboard widget (default: false)
		'right_sidebar' => true, // show adverts sidebar (default: false)
		'notice' => true, // show notice message (default: false)
	),
	// FRAMEWORK MODULES
	'load_factory_modules' => array(
		array('libs/factory/bootstrap', 'factory_bootstrap_447', 'admin'),
		array('libs/factory/forms', 'factory_forms_444', 'admin'),
		array('libs/factory/pages', 'factory_pages_446', 'admin'),
		array('libs/factory/clearfy', 'factory_clearfy_239', 'all'),
		array('libs/factory/freemius', 'factory_freemius_135', 'all'),
		array('libs/factory/adverts', 'factory_adverts_125', 'admin'),
		array('libs/factory/logger', 'factory_logger_112', 'all'),

	),
	'load_plugin_components' => array(
		'disable_notices' => array(
			'autoload' => 'components/disable-admin-notices/clearfy.php',
			'plugin_prefix' => 'WDN_'
		),
		'cyrlitera' => array(
			'autoload' => 'components/cyrlitera/clearfy.php',
			'plugin_prefix' => 'WCTR_'
		),
		'updates_manager' => array(
			'autoload' => 'components/updates-manager/clearfy.php',
			'plugin_prefix' => 'WUPM_'
		),
		'comments_tools' => array(
			'autoload' => 'components/comments-plus/clearfy.php',
			'plugin_prefix' => 'WCM_'
		),
		'ga_cache' => array(
			'autoload' => 'components/ga-cache/clearfy.php',
			'plugin_prefix' => 'WGA_'
		),
		'assets_manager' => array(
			'autoload' => 'components/assets-manager/clearfy.php',
			'plugin_prefix' => 'WGZ_'
		),
		'minify_and_combine' => array(
			'autoload' => 'components/minify-and-combine/clearfy.php',
			'plugin_prefix' => 'WMAC_'
		),
		'html_minify' => array(
			'autoload' => 'components/html-minify/clearfy.php',
			'plugin_prefix' => 'WHTM_'
		),
	)
);



$clearfy_compatibility = new Wbcr_Factory447_Requirements(__FILE__, array_merge($plugin_info, array(
	'plugin_already_activate' => defined('WCL_PLUGIN_ACTIVE'),
	'required_php_version' => '7.0',
	'required_wp_version' => '5.2.0',
	'required_clearfy_check_component' => false
)));

/**
 * If the plugin is compatible, then it will continue its work, otherwise it will be stopped,
 * and the user will throw a warning.
 */
if( !$clearfy_compatibility->check() ) {
	return;
}

/**
 * -----------------------------------------------------------------------------
 * CONSTANTS
 * Install frequently used constants and constants for debugging, which will be
 * removed after compiling the plugin.
 * -----------------------------------------------------------------------------
 */

// This plugin is activated
define('WCL_PLUGIN_ACTIVE', true);

// For for compatibility with old plugins
define('WBCR_CLEARFY_PLUGIN_ACTIVE', true);

// Plugin version
define('WCL_PLUGIN_VERSION', $clearfy_compatibility->get_plugin_version());
define('WCL_FRAMEWORK_VER', 'FACTORY_447_VERSION');

define('WCL_PLUGIN_DIR', dirname(__FILE__));
define('WCL_PLUGIN_BASE', plugin_basename(__FILE__));
define('WCL_PLUGIN_URL', plugins_url(null, __FILE__));



/**
 * -----------------------------------------------------------------------------
 * PLUGIN INIT
 * -----------------------------------------------------------------------------
 */
try {
	require_once(WCL_PLUGIN_DIR . '/includes/helpers.php');

	// creating a plugin via the factory
	require_once(WCL_PLUGIN_DIR . '/libs/factory/core/boot.php');
	require_once(WCL_PLUGIN_DIR . '/includes/class.plugin.php');

	new WCL_Plugin(__FILE__, array_merge($plugin_info, array(
		'plugin_version' => WCL_PLUGIN_VERSION,
		'plugin_text_domain' => $clearfy_compatibility->get_text_domain(),
	)));
} catch( Exception $e ) {
	// Plugin wasn't initialized due to an error
	define('WRIO_PLUGIN_THROW_ERROR', true);

	$clearfy_plugin_error_func = function () use ($e) {
		$error = sprintf("The %s plugin has stopped. <b>Error:</b> %s Code: %s", 'Clearfy', $e->getMessage(), $e->getCode());
		echo '<div class="notice notice-error"><p>' . $error . '</p></div>';
	};

	add_action('admin_notices', $clearfy_plugin_error_func);
	add_action('network_admin_notices', $clearfy_plugin_error_func);
}

