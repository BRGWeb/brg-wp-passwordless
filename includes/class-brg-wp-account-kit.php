<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://brgweb.com.br/wordpress
 * @since      1.0.0
 *
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 * @author     BRGWeb <wordpress@brgweb.com.br>
 */
class Brg_Wp_Account_Kit
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Brg_Wp_Account_Kit_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The Facebook Account Kit App Id.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $app_id    The Account Kit App Id.
     */
    protected $app_id;

    /**
     * The Account Kit API version.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $api_version    The Account Kit API version.
     */
    protected $api_version;

    /**
     * The Facebook App Secret.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $app_secret    The Facebook app secret.
     */
    protected $app_secret;

    /**
     * The Facebook Client Token.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $client_token    The Facebook client token.
     */
    protected $client_token;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'brg-wp-account-kit';
        $this->version = '1.0.0';

        //Account kit variables
        $options = get_option('brg-wp-account-kit-settings');
        $this->app_id = $options['app_id'];
        $this->api_version = $options['api_version'];
        $this->app_secret = $options['app_secret'];
        $this->client_token= $options['client_token'];

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_rest_api_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Brg_Wp_Account_Kit_Loader. Orchestrates the hooks of the plugin.
     * - Brg_Wp_Account_Kit_i18n. Defines internationalization functionality.
     * - Brg_Wp_Account_Kit_Admin. Defines all hooks for the admin area.
     * - Brg_Wp_Account_Kit_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-brg-wp-account-kit-loader.php';

        /**
         * The class responsible for defining the endpoints and routes for WP REST API
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-brg-wp-account-kit-rest-api.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-brg-wp-account-kit-i18n.php';

        /**
         * The class responsible for defining all settings
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-brg-wp-account-kit-settings.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-brg-wp-account-kit-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-brg-wp-account-kit-public.php';

        $this->loader = new Brg_Wp_Account_Kit_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Brg_Wp_Account_Kit_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Brg_Wp_Account_Kit_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Brg_Wp_Account_Kit_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $app_data = array(
            'app_id'    =>    $this->app_id,
            'api_version'    =>    $this->api_version,
            'app_secret'    =>    $this->app_secret,
            'client_token'    =>  $this->client_token,
            );

        $plugin_public = new Brg_Wp_Account_Kit_Public($this->get_plugin_name(), $this->get_version(), $app_data);

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('login_form', $plugin_public, 'login_form');
    }

    /**
     * Register all of the hooks related to the WP REST API
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_rest_api_hooks()
    {
        $app_data = array(
            'app_id'        =>    $this->app_id,
            'api_version'    =>    $this->api_version,
            'app_secret'    =>    $this->app_secret,
            'client_token'    =>  $this->client_token,
            );

        $plugin_rest_api = new Brg_Wp_Account_Kit_REST_API($this->get_plugin_name(), $this->get_version(), $app_data);
        $this->loader->add_action('rest_api_init', $plugin_rest_api, 'register_routes');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Brg_Wp_Account_Kit_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
