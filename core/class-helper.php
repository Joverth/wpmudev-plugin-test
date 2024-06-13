<?php
/**
 * Google Auth block.
 *
 * @link          https://wpmudev.com/
 * @since         1.0.0
 *
 * @author        WPMUDEV (https://wpmudev.com)
 * @package       WPMUDEV\PluginTest
 *
 * @copyright (c) 2023, Incsub (http://incsub.com)
 */

 namespace WPMUDEV\PluginTest;

// Abort if called directly.
defined( 'WPINC' ) || die;

class Helper{
   // Define the function to scan all public posts and pages and update post meta
	public function scan_all_public_posts_pages_common($post_types = array('post', 'page')) {
		// Query the posts and pages
		$args = array(
			'post_type' => $post_types,
			'post_status' => 'publish',
			'posts_per_page' => -1, // Retrieve all posts
		);

		$query = new \WP_Query($args);

		$posts = array();

		// Loop through the posts and add them to the response
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();
				$posts[] = array(
					'ID' => $post_id,
					'title' => get_the_title(),
					'content' => get_the_content(),
					'link' => get_permalink(),
				);
				// Update the last_scan post meta with the current time
				update_post_meta($post_id, 'wpmudev_test_last_scan', current_time('mysql'));
			}
			wp_reset_postdata();
		}

		return $posts;
	}
}
