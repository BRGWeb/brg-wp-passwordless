<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://brgweb.com.br/wordpress
 * @since      1.0.0
 *
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 * @author     BRGWeb <wordpress@brgweb.com.br>
 */
class Brg_Wp_Account_Kit_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'brg-wp-account-kit',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
