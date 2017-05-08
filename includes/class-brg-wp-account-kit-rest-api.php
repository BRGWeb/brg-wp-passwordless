<?php

/**
 * Define the WP REST API routes and endpoints
 *
 * Defines the WP REST API routes and endpoints
 * that will be used in the plugin.
 *
 * @link       https://brgweb.com.br/wordpress
 * @since      1.0.0
 *
 * @package    Brg_Wp_Account_Kit
 * @subpackage Brg_Wp_Account_Kit/includes
 */

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
class Brg_Wp_Account_Kit_REST_API {

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
	public function __construct( $plugin_name, $version, $app_data ) {
        $this->namespace     = '/brg-wp-account-kit/v1';
		$this->app_data = $app_data;
    }
 
    // Register our routes.
    public function register_routes() {
		//the route that will receive return data from facebook
        register_rest_route( $this->namespace, '/return', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'return_function' )
            ),
        ) );
		//the webhook that Facebook can call 
        register_rest_route( $this->namespace, '/listener', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'listener_function' ),
            ),
        ) );
    }

	//the route that will receive return data from facebook
	public function return_function(){
		
		//TODO verify nonce in state field
		switch ($_GET['status']){
			case 'PARTIALLY_AUTHENTICATED' :
				$token_exchange_url = 'https://graph.accountkit.com/'.$this->app_data['api_version'].'/access_token?'.
				  'grant_type=authorization_code'.
				  '&code='.$_GET['code'].
				  "&access_token=AA|".$this->app_data['app_id']."|".$this->app_data['app_secret'];
				$data = $this->doCurl($token_exchange_url);
				//data returned				
				$user_id = $data['id'];
				$user_access_token = $data['access_token'];
				$refresh_interval = $data['token_refresh_interval_sec'];					
				// Get Account Kit information
				$appsecret_proof = hash_hmac('sha256', $user_access_token, $this->app_data['app_secret']); 
				$me_endpoint_url = 'https://graph.accountkit.com/'.$this->app_data['api_version'].'/me?'.
				  'access_token='.$user_access_token.'&appsecret_proof='.$appsecret_proof;
				$data = $this->doCurl($me_endpoint_url);
				$phone = isset($data['phone']) ? $data['phone']['number'] : '';
				$email = isset($data['email']) ? $data['email']['address'] : '';
				$username = 'ak_'.$user_id;
				//check if user exists
				$uid = username_exists($username);
				$user_email = email_exists($email); 
				if ($uid){
					//this user already authenticated through Account Kit API
					//let's update the access_token
					update_user_meta($uid,'_brg_wp_account_kit_token', $user_access_token);
				}elseif ($user_email){
					//this user email is already registered but not authenticated though Account Kit API 
					//let's update the access_token
					update_user_meta($uid,'_brg_wp_account_kit_token', $user_access_token);
				}else{
					//first time login for this user
					$uid = wp_create_user( $username, $user_access_token, $email );		
				}
				//everything is working great! So let's set auth cookie and redirect user to home_url();
				//TODO choose URL to redirect user
				wp_set_auth_cookie( $uid, true);
				wp_redirect(home_url());
			break;
			case 'NOT_AUTHENTICATED' :
				//TODO handle this
				wp_die();
			break;
			case 'BAD_PARAMS' :
				//TODO handle this
				wp_die();
			break;
			default :
				wp_die();
			break;
		}
		
	}

	//the webhook that Facebook can call 	
	public function listener_function(){
		//TODO define listeners		
	}

	// Method to send Get request to url
	private function doCurl($url) {
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  $data = json_decode(curl_exec($ch), true);
	  curl_close($ch);
	  return $data;
	}
}
