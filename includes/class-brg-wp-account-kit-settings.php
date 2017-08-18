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
class Brg_Wp_Account_Kit_Settings {

    protected $options;

    public function __construct() {
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
        $this->options = get_option('brg-wp-account-kit-settings');
        ?>
        <div class="wrap">
            <h1>Account Kit Settings</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields('brg-wp-account-kit-settings');
                do_settings_sections('brg-wp-account-kit-settings-admin');
                submit_button();
            ?>
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
            'app_id',
            'APP Id',
            array( $this, 'app_id_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'api_version',
            'API Version',
            array( $this, 'api_version_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'app_secret',
            'APP Secret',
            array( $this, 'api_secret_callback' ),
            'brg-wp-account-kit-settings-admin',
            'brg-wp-account-kit-settings'
        );

        add_settings_field(
            'client_token',
            'Client Token',
            array( $this, 'client_token_callback' ),
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
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['app_id'] ) )
            $new_input['app_id'] = absint( $input['app_id'] );

        if( isset( $input['api_version'] ) )
            $new_input['api_version'] = sanitize_text_field( $input['api_version'] );

        if( isset( $input['app_secret'] ) )
            $new_input['app_secret'] = sanitize_text_field( $input['app_secret'] );

        if( isset( $input['client_token'] ) )
            $new_input['client_token'] = sanitize_text_field( $input['client_token'] );

        return $new_input;
    }

    /**
     * Print the Section text
     *
     * @since 1.0.0
     */
    public function print_section_info()
    {
        print 'Access <a href="https://developers.facebook.com/docs/accountkit">Facebook Account Kit Page</a>, create a new application and fill the fields bellow:';
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
            isset( $this->options['app_id'] ) ? esc_attr( $this->options['app_id']) : ''
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
            '<input type="text" id="api_version" name="brg-wp-account-kit-settings[api_version]" value="%s" />',
            isset( $this->options['api_version'] ) ? esc_attr( $this->options['api_version']) : ''
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
            '<input type="text" id="title" name="brg-wp-account-kit-settings[app_secret]" value="%s" />',
            isset( $this->options['app_secret'] ) ? esc_attr( $this->options['app_secret']) : ''
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
            '<input type="text" id="title" name="brg-wp-account-kit-settings[client_token]" value="%s" />',
            isset( $this->options['client_token'] ) ? esc_attr( $this->options['client_token']) : ''
        );
    }
}

if (is_admin()) {
    $brg_wp_account_kit_settings_page = new Brg_Wp_Account_Kit_Settings();
}
