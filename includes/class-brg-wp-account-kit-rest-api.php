<?php

/**
 * Define the WP REST API routes and endpoints.
 *
 * Defines the WP REST API routes and endpoints
 * that will be used in the plugin.
 *
 * @since      1.0.0
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 * @author     BRGWeb <wordpress@brgweb.com.br>
 */
class Brg_Wp_Account_Kit_REST_API
{

    /**
     * The App data.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $app_data    The Facebook Account Kit App Data.
     */
    protected $app_data;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     * @param      array    $app_data    The app_data for Facebook Account kit.
     */
    public function __construct($plugin_name, $version, $app_data)
    {
        $this->namespace     = '/brg-wp-account-kit/v1';
        $this->app_data = $app_data;
    }

    // Register our routes.
    public function register_routes()
    {
        register_rest_route($this->namespace, 'account-kit/return', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'account_kit_return' )
            ),
        ));

        register_rest_route($this->namespace, 'facebook-login/return', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'facebook_login_return' )
            ),
        ));

        register_rest_route($this->namespace, 'twitter-login/return', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'twitter_login_return' )
            ),
        ));

        register_rest_route($this->namespace, 'google-login/return', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'google_login_return' )
            ),
        ));
    }

    public function account_kit_return()
    {
        if (!isset($_GET['status'])) {
            wp_die('<strong>Account Kit Error</strong>: "status" not informed', 'Account Kit');
        }

        switch ($_GET['status']) {
            case 'PARTIALLY_AUTHENTICATED':
                $token_exchange_url = 'https://graph.accountkit.com/'
                    . $this->app_data['api_version']
                    . '/access_token?'
                    . 'grant_type=authorization_code'
                    . '&code='.$_GET['code']
                    . "&access_token=AA|"
                    . $this->app_data['app_id']
                    . "|"
                    . $this->app_data['app_secret'];
                $data = $this->doCurl($token_exchange_url);

                //data returned
                $user_id = $data['id'];

                if (!$user_id) {
                    wp_die('<strong>Account Kit Error</strong>: invalid user id', 'Account Kit');
                }

                $user_access_token = $data['access_token'];
                $refresh_interval = $data['token_refresh_interval_sec'];

                // Get Account Kit information
                $appsecret_proof = hash_hmac('sha256', $user_access_token, $this->app_data['app_secret']);
                $me_endpoint_url = 'https://graph.accountkit.com/'
                    . $this->app_data['api_version'].'/me?'
                    . 'access_token='
                    . $user_access_token
                    . '&appsecret_proof='
                    . $appsecret_proof;
                $data = $this->doCurl($me_endpoint_url);
                $phone = isset($data['phone']) ? $data['phone']['number'] : '';
                $email = isset($data['email']) ? $data['email']['address'] : '';
                $username = 'ak_'.$user_id;

                //check if user exists
                $uid = null;
                if ($email) {
                    $uid = email_exists($email);
                }

                if (!$uid) {
                    $uid = username_exists($username);
                }

                if ($uid) {
                    //this user already authenticated through Account Kit API
                    //let's update the access_token
                    update_user_meta($uid, '_brg_wp_account_kit_token', $user_access_token);
                } else {
                    //first time login for this user
                    $uid = wp_create_user($username, $user_access_token, $email);
                }
                //everything is working great! So let's set auth cookie and redirect user to admin_url();
                wp_set_current_user($uid, $user_login);
                wp_set_auth_cookie($uid);
                wp_redirect(admin_url('index.php'));
                exit;
            break;
            case 'NOT_AUTHENTICATED':
                wp_die('<strong>Account Kit Error</strong>: not authenticated', 'Account Kit');
            break;
            case 'BAD_PARAMS':
                wp_die('<strong>Account Kit Error</strong>: bad params', 'Account Kit');
            break;
            default:
                wp_die('<strong>Account Kit Error</strong>: invalid "status"', 'Account Kit');
            break;
        }
    }

    public function facebook_login_return()
    {
        if (!session_id()) {
            session_start();
        }

        $fb = new \Facebook\Facebook([
          'app_id' => $this->app_data['app_id'],
          'app_secret' => $this->app_data['app_secret'],
          'default_graph_version' => 'v2.10',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        if (!array_key_exists('code', $_GET)) {
            $permissions = ['email'];
            $redirectUrl = htmlspecialchars(home_url() . "/wp-json/brg-wp-account-kit/v1/facebook-login/return");
            header('Location: ' . $helper->getLoginUrl($redirectUrl, $permissions));
            die();
        }

        try {
            $token = $helper->getAccessToken();
            $response = $fb->get('/me?fields=id,name,email', $token);

            $user = $response->getGraphUser();
            $id = $user->getId();
            $name = $user->getName();
            $email = $user->getEmail();

            $uid = null;
            if ($email) {
                $uid = email_exists($email);
            }

            if ($uid) {
                update_user_meta($uid, '_brg_wp_account_kit_token', $token);
            } else {
                //first time login for this user
                $uid = wp_create_user($name, $token, $email);
            }

            wp_set_current_user($uid, $user_login);
            wp_set_auth_cookie($uid);
            wp_redirect(admin_url('index.php'));
            exit;
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            wp_die('<strong>Account Kit Error</strong>: ' . 'Graph returned an error: ' . $e->getMessage(), 'Account Kit');
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            wp_die('<strong>Account Kit Error</strong>: ' . 'Facebook SDK returned an error: ' . $e->getMessage(), 'Account Kit');
            exit;
        }
    }

    public function twitter_login_return()
    {
        if (!session_id()) {
            session_start();
        }

        $settings = array(
            'oauth_access_token' => $this->app_data['twitter_oauth_access_token'],
            'oauth_access_token_secret' => $this->app_data['twitter_oauth_access_token_secret'],
            'consumer_key' => $this->app_data['twitter_consumer_key'],
            'consumer_secret' => $this->app_data['twitter_consumer_secret']
        );

        $twitter = new TwitterAPIExchange($settings);

        if (!array_key_exists('oauth_verifier', $_GET)) {
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $url = 'https://api.twitter.com/oauth/request_token';
            $method = 'POST';
            $postfields = array(
                'oauth_callback' => urlencode($actual_link),
            );
            $result = $twitter->buildOauth($url, $method)
                ->setPostfields($postfields)
                ->performRequest();

            header('Location: ' . 'https://api.twitter.com/oauth/authenticate?' . $result);
            die();
        }

        if (array_key_exists('oauth_verifier', $_GET)) {
            $verifier = $_GET['oauth_verifier'];
            $token = $_GET['oauth_token'];
            $url = 'https://api.twitter.com/oauth/access_token';
            $method = 'POST';
            $postfields = array(
                'oauth_verifier' => $verifier,
                'oauth_token' => $token
            );

            $result = $twitter->buildOauth($url, $method)
                ->setPostfields($postfields)
                ->performRequest();
            parse_str($result, $token_response);

            $connection = new \Abraham\TwitterOAuth\TwitterOAuth(
                $settings['consumer_key'],
                $settings['consumer_secret'],
                $token_response['oauth_token'],
                $token_response['oauth_token_secret']
            );

            $user = $connection->get("account/verify_credentials", ['include_email' => 'true']);

            $id = $user->id;
            $name = $user->name;
            $email = $user->email;

            $uid = null;
            if ($email) {
                $uid = email_exists($email);
            }

            if ($uid) {
                update_user_meta($uid, '_brg_wp_account_kit_token', $token);
            } else {
                //first time login for this user
                $uid = wp_create_user($name, $token, $email);
            }

            wp_set_current_user($uid, $user_login);
            wp_set_auth_cookie($uid);
            wp_redirect(admin_url('index.php'));
            exit;
        }

        wp_die('<strong>Account Kit Error</strong>: Invalid params', 'Account Kit');
        exit;
    }

    public function google_login_return()
    {
        session_start();

        $config = [
            'callback' => site_url() . '/wp-json/brg-wp-account-kit/v1/google-login/return?hauth.done=google',
            'keys' => [
                'id' => $this->app_data['google_application_id'],
                'secret' => $this->app_data['google_application_secret']
            ],
            'scope' => 'profile https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
        ];

        $adapter = new Hybridauth\Provider\Google($config);

        $adapter->authenticate();

        $user = $adapter->getUserProfile();

        $uid = email_exists($user->email);

        if (!$uid) {
            $uid = wp_create_user($user->displayName, $user->identifier, $user->email);
        }

        wp_set_current_user($uid, $user_login);
        wp_set_auth_cookie($uid);
        wp_redirect(admin_url('index.php'));
        exit;
    }

    // Method to send Get request to url
    private function doCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $data;
    }
}
