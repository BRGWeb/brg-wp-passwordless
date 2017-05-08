<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://brgweb.com.br/wordpress
 * @since             1.0.0
 * @package           Brg_Wp_Account_Kit
 *
 * @wordpress-plugin
 * Plugin Name:       BRG WordPress Account Kit
 * Plugin URI:        https://brgweb.com.br/wordpress/plugins/brg-wp-account-kit
 * Description:       Implements Facebook Account kit for passwordless registration and login.
 * Version:           1.0.0
 * Author:            BRGWeb
 * Author URI:        https://brgweb.com.br/wordpress
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       brg-wp-account-kit
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-brg-wp-account-kit-activator.php
 */
function activate_brg_wp_account_kit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brg-wp-account-kit-activator.php';
	Brg_Wp_Account_Kit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-brg-wp-account-kit-deactivator.php
 */
function deactivate_brg_wp_account_kit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brg-wp-account-kit-deactivator.php';
	Brg_Wp_Account_Kit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_brg_wp_account_kit' );
register_deactivation_hook( __FILE__, 'deactivate_brg_wp_account_kit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-brg-wp-account-kit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_brg_wp_account_kit() {

	$plugin = new Brg_Wp_Account_Kit();
	$plugin->run();

}
run_brg_wp_account_kit();
