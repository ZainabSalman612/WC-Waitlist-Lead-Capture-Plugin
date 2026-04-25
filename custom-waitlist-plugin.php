<?php
/**
 * Plugin Name:       WC Waitlist Lead Capture
 * Description:       Combines waitlist lead capture with user account creation for WooCommerce.
 * Version:           1.0.0
 * Author:            Zainab Salman
 * Author URI:        https://github.com/ZainabSalman612
 * Text Domain:       custom-waitlist-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WAITLIST_PLUGIN_VERSION', '1.0.0' );
define( 'WAITLIST_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WAITLIST_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_custom_waitlist_plugin() {
	require_once WAITLIST_PLUGIN_DIR . 'includes/class-activator.php';
	Waitlist_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_custom_waitlist_plugin() {
	require_once WAITLIST_PLUGIN_DIR . 'includes/class-deactivator.php';
	Waitlist_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_waitlist_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_custom_waitlist_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WAITLIST_PLUGIN_DIR . 'includes/class-core.php';

function run_custom_waitlist_plugin() {
	$plugin = new Waitlist_Core();
	$plugin->run();
}
run_custom_waitlist_plugin();
