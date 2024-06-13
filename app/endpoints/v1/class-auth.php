<?php
/**
 * Google Auth Shortcode.
 *
 * @link          https://wpmudev.com/
 * @since         1.0.0
 *
 * @author        WPMUDEV (https://wpmudev.com)
 * @package       WPMUDEV\PluginTest
 *
 * @copyright (c) 2023, Incsub (http://incsub.com)
 */

namespace WPMUDEV\PluginTest\Endpoints\V1;
// Abort if called directly.
defined( 'WPINC' ) || die;

use WPMUDEV\PluginTest\Endpoint;
use WP_REST_Server;
use WP_Error;
use WPMUDEV\PluginTest\Core\Google_Auth;
class Auth extends Endpoint {
	/**
	 * API endpoint for the current endpoint.
	 *
	 * @since 1.0.0rest_missing_callback_param
	 *
	 * @var string $endpoint
	 */
	protected $endpoint = 'auth/auth-url';

	/**
	 * Register the routes for handling auth functionality.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function register_routes() {
		// TODO
		// Add a new Route to logout.

		// Route to get auth url.
		register_rest_route(
			$this->get_namespace(),
			$this->get_endpoint(),
			array(
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => array($this, 'get_credentials'),
                '	permission_callback' => array($this, 'permissions_check'),
				),
				array(
					'methods' =>WP_REST_Server::CREATABLE,
					'args'    => array(
						'clientId'     => array(
							'required'    => true,
							'description' => __( 'The client ID from Google API project.', 'wpmudev-plugin-test' ),
							'type'        => 'string',
							'validate_callback' => array($this, 'validate_field'),
						),
						'clientId' => array(
							'required'    => true,
							'description' => __( 'The client secret from Google API project.', 'wpmudev-plugin-test' ),
							'type'        => 'string',
							'validate_callback' => array($this, 'validate_field'),
						),
					),
					'callback' => array($this, 'save_credentials'), // Callback function.
					'permission_callback' => array($this, 'permissions_check'),
				)
			)
		);
	}

	/**
	 * Save the client id and secret.
	 *
	 *
	 * @since 1.0.0
	 */
	public function save_credentials($request) {
		// You can access request parameters if needed.
        $parameters = $request->get_params();
		// Update options in the database
		$settings = array(
			'client_id' => sanitize_text_field($parameters['clientId']),
			'client_secret' => sanitize_text_field($parameters['clientSecret'])
		);
		update_option('wpmudev_plugin_test_settings', $settings);
        // Create response data.
        $response_data = array(
            'message' => 'Your data has been saved.',
            'params'  => $parameters
        );

        // Return a standardized response.
        return $this->get_response($response_data);
    }
		/**
	 * Get the client id and secret.
	 *
	 *
	 * @since 1.0.0
	 */
	public function get_credentials($request) {

        $settings = get_option( 'wpmudev_plugin_test_settings' );

        // Return a standardized response.
        return $this->get_response($settings);
    }
}
class Auth_Confirm extends Endpoint {
	/**
	 * API endpoint for the current endpoint.
	 *
	 * @since 1.0.0rest_missing_callback_param
	 *
	 * @var string $endpoint
	 */
	protected $endpoint = 'auth/confirm';
		/**
	 * Register the routes for handling auth functionality.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function register_routes() {
		// TODO
		// Add a new Route to logout.

		// Route to get auth url.
		register_rest_route(
			$this->get_namespace(),
			$this->get_endpoint(),
			array(
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => array($this, 'confirm'),
				)
			)
		);
	}
	/**
     * Confirm.
     * 
     *
     */
	public function confirm($request) {

		$auth_instance = Google_Auth\Auth::instance();
		$client = $auth_instance->client();
		$auth_instance->set_up();
		$client->setRedirectUri(rest_url('wpmudev/v1/auth/confirm'));
		// After user authentication
		if (isset($_GET['code'])) {
			$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
			$client->setAccessToken($token);

			// You can now use the access token to access Google APIs
			$oauth2 = new \Google\Service\Oauth2($client);
			$userInfo = $oauth2->userinfo->get();
			$email = $userInfo->email;
            $user = get_user_by('email', $email);
			if ($user) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
            } else {
                $user_id = wp_create_user($email, wp_generate_password(), $email);
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
            }
            wp_redirect(home_url());
            exit();
		} else {
			$authUrl = $auth_instance->get_auth_url();
			echo $authUrl;
			header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
		}
    }

}
class Auth_Start extends Endpoint {
	/**
	 * API endpoint for the current endpoint.
	 *
	 * @since 1.0.0rest_missing_callback_param
	 *
	 * @var string $endpoint
	 */
	protected $endpoint = 'auth/start';
		/**
	 * Register the routes for handling auth functionality.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function register_routes() {
		// TODO
		// Add a new Route to logout.

		// Route to get auth url.
		register_rest_route(
			$this->get_namespace(),
			$this->get_endpoint(),
			array(
				array(
					'methods' => WP_REST_Server::CREATABLE,
					'callback' => array($this, 'start'),
				)
			)
		);
	}
	/**
     * Start.
     * 
     *
     */
	public function start($request) {
		$auth_instance = Google_Auth\Auth::instance();
		$auth_instance->set_up();
		$client = $auth_instance->client();
		$client->setRedirectUri(rest_url('wpmudev/v1/auth/confirm'));
		$authUrl = $auth_instance->get_auth_url();
        return $this->get_response(array('authUrl' => $authUrl));
    }

}
