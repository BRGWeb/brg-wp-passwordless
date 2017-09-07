<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://brgweb.com.br/wordpress
 * @since      1.0.0
 *
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/public
 * @author     BRGWeb <wordpress@brgweb.com.br>
 */
class Brg_Wp_Account_Kit_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $app_data;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $app_data ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->app_data = $app_data;

        $this->login_enqueue_styles();
        $this->login_enqueue_scripts();
	}

	/**
         * Register the JavaScript for the login page.
         *
         * @since    1.0.0
         */
        public function login_enqueue_scripts() {
            wp_enqueue_script(
                $this->plugin_name,
                'https://sdk.accountkit.com/pt_BR/sdk.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/brg-wp-account-kit-public.js'
            );
        }

        /**
         * Register the JavaScript for the login page.
         *
         * @since    1.0.0
         */
        public function login_enqueue_styles() {
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/brg-wp-account-kit-public.css',
                array(),
                $this->version,
                'all'
            );
        }

	/**
	 * Loads custom fields in login form
	 *
	 * @since   1.0.0
	 */
	public function login_form(){
		?>
        <ul class="brg-wp-account-kit-login-options">
            <li>
                <!-- Facebook Account Kit by Email -->
                <a class="fa fa-envelope-o brg-wp-account-kit-login-option-email" title="Email" href="https://www.accountkit.com/v1.0/basic/dialog/email_login?app_id=<?php echo $this->app_data['app_id']; ?>&redirect=<?php echo urlencode(home_url()); ?>/wp-json/brg-wp-account-kit/v1/account-kit/return&state=<?php echo wp_create_nonce('sms_login'); ?>&fbAppEventsEnabled=true"></a>
            </li>
            <li>
                <!-- Facebook Account Kit by SMS -->
                <a class="fa fa-phone brg-wp-account-kit-login-option-sms" title="SMS" href="https://www.accountkit.com/v1.0/basic/dialog/sms_login?app_id=<?php echo $this->app_data['app_id']; ?>&redirect=<?php echo urlencode(home_url()); ?>/wp-json/brg-wp-account-kit/v1/account-kit/return&state=<?php echo wp_create_nonce('sms_login'); ?>&fbAppEventsEnabled=true"></a>
            </li>
            <li>
                <!-- Facebook Login -->
                <a class="fa fa-facebook brg-wp-facebook-login" title="Facebook" href="/wp-json/brg-wp-account-kit/v1/facebook-login/return"></a>
            </li>
            <li>
                <!-- Twitter Login -->
                <a class="fa fa-twitter brg-wp-twitter-login" title="Twitter" href="#"></a>
            </li>
            <li>
                <!-- Google Login -->
                <a class="fa fa-google brg-wp-google-login" title="Google Plus" href="#"></a>
            </li>
        </ul>
		<?php
	}
}
