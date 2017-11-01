<?php

/**
 * Handle plugin settings
 *
 * @link       https://brgweb.com.br/wordpress
 * @since      1.0.0
 *
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 */

/**
 * Handle plugin settings.
 *
 * This class defines all settings necessary to run the plugin.
 *
 * @since      1.0.0
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 * @author     BRGWeb <wordpress@brgweb.com.br>
 */
class Brg_Wp_Account_Kit_Settings
{
    protected $options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page
     *
     * @since 1.0.0
     */
    public function add_plugin_page()
    {
        add_options_page(
            'Settings Admin',
            'Account Kit Settings',
            'manage_options',
            'brg-wp-account-kit-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Print options page
     *
     * @since 1.0.0
     */
    public function create_admin_page()
    {
        $this->options = get_option('brg-wp-account-kit-settings'); ?>
        <div class="wrap">
            <h1>Account Kit Settings</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields('brg-wp-account-kit-settings');
        do_settings_sections('brg-wp-account-kit-settings-admin');
        submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     *
     * @since 1.0.0
     */
    public function page_init()
    {
        register_setting(
            'brg-wp-account-kit-settings',
            'brg-wp-account-kit-settings',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'brg-wp-account-kit-settings',
            '',
            array( $this, 'print_section_info' ),
            'brg-wp-account-kit-settings-admin'
        );

        add_settings_field(
            'use_facebook_ak',
            'Use Facebook Account Kit (Email + SMS) ?',
            array( $this, 'use_facebook_ak_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'use_facebook_login',
            'Use Facebook Login ?',
            array( $this, 'use_facebook_login_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'app_id',
            'Facebook APP Id',
            array( $this, 'app_id_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'api_version',
            'Facebook API Version',
            array( $this, 'api_version_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'app_secret',
            'Facebook APP Secret',
            array( $this, 'api_secret_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'client_token',
            'Facebook Client Token',
            array( $this, 'client_token_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'use_twitter',
            'Use Twitter ?',
            array( $this, 'use_twitter_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'twitter_oauth_access_token',
            'Twitter Oauth Access Token',
            array( $this, 'twitter_oauth_access_token_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'twitter_oauth_access_token_secret',
            'Twitter Oauth Access Token Secret',
            array( $this, 'twitter_oauth_access_token_secret_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'twitter_consumer_key',
            'Twitter Consumer Key',
            array( $this, 'twitter_consumer_key_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'twitter_consumer_secret',
            'Twitter Consumer Secret',
            array( $this, 'twitter_consumer_secret_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'use_google',
            'Use Google ?',
            array( $this, 'use_google_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'google_application_id',
            'Google Application Id',
            array( $this, 'google_application_id_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'google_application_secret',
            'Google Application Secret',
            array( $this, 'google_application_secret_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     * @since 1.0.0
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['app_id'])) {
            $new_input['app_id'] = absint($input['app_id']);
        }

        if (isset($input['api_version'])) {
            $new_input['api_version'] = sanitize_text_field($input['api_version']);
        }

        if (isset($input['app_secret'])) {
            $new_input['app_secret'] = sanitize_text_field($input['app_secret']);
        }

        if (isset($input['client_token'])) {
            $new_input['client_token'] = sanitize_text_field($input['client_token']);
        }

        if (isset($input['twitter_oauth_access_token'])) {
            $new_input['twitter_oauth_access_token'] = sanitize_text_field($input['twitter_oauth_access_token']);
        }

        if (isset($input['twitter_oauth_access_token_secret'])) {
            $new_input['twitter_oauth_access_token_secret'] = sanitize_text_field($input['twitter_oauth_access_token_secret']);
        }

        if (isset($input['twitter_consumer_key'])) {
            $new_input['twitter_consumer_key'] = sanitize_text_field($input['twitter_consumer_key']);
        }

        if (isset($input['twitter_consumer_secret'])) {
            $new_input['twitter_consumer_secret'] = sanitize_text_field($input['twitter_consumer_secret']);
        }

        if (isset($input['google_application_id'])) {
            $new_input['google_application_id'] = sanitize_text_field($input['google_application_id']);
        }

        if (isset($input['google_application_secret'])) {
            $new_input['google_application_secret'] = sanitize_text_field($input['google_application_secret']);
        }

        return $new_input;
    }

    /**
     * Print the Section text
     *
     * @since 1.0.0
     */
    public function print_section_info()
    {
        print 'Links: <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Apps</a>, <a href="https://apps.twitter.com/" target="_blank">Twitter Apps</a> and <a href="https://console.cloud.google.com/home/dashboard" target="_blank">Google Console</a>';
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function use_facebook_ak_callback()
    {
        printf(
            '<input type="checkbox" id="use_facebook_ak" name="brg-wp-account-kit-settings[use_facebook_ak]" value="%s" />',
            isset($this->options['use_facebook_ak']) ? esc_attr($this->options['use_facebook_ak']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function use_facebook_login_callback()
    {
        printf(
            '<input type="checkbox" id="use_facebook_login" name="brg-wp-account-kit-settings[use_facebook_login]" value="%s" />',
            isset($this->options['use_facebook_login']) ? esc_attr($this->options['use_facebook_login']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function use_twitter_callback()
    {
        printf(
            '<input type="checkbox" id="use_twitter" name="brg-wp-account-kit-settings[use_twitter]" value="%s" />',
            isset($this->options['use_twitter']) ? esc_attr($this->options['use_twitter']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function use_google_callback()
    {
        printf(
            '<input type="checkbox" id="use_google" name="brg-wp-account-kit-settings[use_google]" value="%s" />',
            isset($this->options['use_google']) ? esc_attr($this->options['use_google']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function app_id_callback()
    {
        printf(
            '<input type="text" id="app_id" name="brg-wp-account-kit-settings[app_id]" value="%s" />',
            isset($this->options['app_id']) ? esc_attr($this->options['app_id']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function api_version_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[api_version]" value="%s" />',
            isset($this->options['api_version']) ? esc_attr($this->options['api_version']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function api_secret_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[app_secret]" value="%s" />',
            isset($this->options['app_secret']) ? esc_attr($this->options['app_secret']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function client_token_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[client_token]" value="%s" />',
            isset($this->options['client_token']) ? esc_attr($this->options['client_token']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function twitter_oauth_access_token_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[twitter_oauth_access_token]" value="%s" />',
            isset($this->options['twitter_oauth_access_token']) ? esc_attr($this->options['twitter_oauth_access_token']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function twitter_oauth_access_token_secret_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[twitter_oauth_access_token_secret]" value="%s" />',
            isset($this->options['twitter_oauth_access_token_secret']) ? esc_attr($this->options['twitter_oauth_access_token_secret']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function twitter_consumer_key_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[twitter_consumer_key]" value="%s" />',
            isset($this->options['twitter_consumer_key']) ? esc_attr($this->options['twitter_consumer_key']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function twitter_consumer_secret_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[twitter_consumer_secret]" value="%s" />',
            isset($this->options['twitter_consumer_secret']) ? esc_attr($this->options['twitter_consumer_secret']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function google_application_id_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[google_application_id]" value="%s" />',
            isset($this->options['google_application_id']) ? esc_attr($this->options['google_application_id']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @since 1.0.0
     */
    public function google_application_secret_callback()
    {
        printf(
            '<input type="text" name="brg-wp-account-kit-settings[google_application_secret]" value="%s" />',
            isset($this->options['google_application_secret']) ? esc_attr($this->options['google_application_secret']) : ''
        );
    }
}

if (is_admin()) {
    $brg_wp_account_kit_settings_page = new Brg_Wp_Account_Kit_Settings();
}
