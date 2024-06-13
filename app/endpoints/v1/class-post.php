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
use WPMUDEV\PluginTest\Helper;
class Post extends Endpoint {
	/**
	 * API endpoint for the current endpoint.
	 *
	 * @since 1.0.0rest_missing_callback_param
	 *
	 * @var string $endpoint
	 */
	protected $endpoint = 'post/scan_all';

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
					'callback' => array($this, 'scan_all_public_posts_pages'),
                	'permission_callback' => array($this, 'permissions_check'),
					'args' => array(
						'postTypes' => array(
							'required'    => true,
							'description' => __( 'Post types to scan posts.', 'wpmudev-plugin-test' ),
							'type' => 'string',
							'validate_callback' => array($this, 'validate_field'),
						),
					),
					),
			)
		);
	}

	/**
	 * Scan all posts and pages
	 *
	 *
	 * @since 1.0.0
	 */
	public function scan_all_public_posts_pages($request) {
		$parameters = $request->get_params();
		$post_types = explode(",",$parameters['postTypes']);
		if (!$post_types) {
			$post_types = array('post', 'page');
		}
		// Schedule the cron job to run daily if it's not already scheduled
		   if (!wp_next_scheduled('daily_scan_all_public_posts_pages')) {
			wp_schedule_event(time(), 'daily', 'daily_scan_all_public_posts_pages');
		}
		$helper = new Helper();
		$posts = $helper->scan_all_public_posts_pages_common($post_types);
        // Return a standardized response.
		$response_data = array(
            'message' => 'Your posts are being scanned.',
        );
        return $this->get_response($response_data);
    }
}