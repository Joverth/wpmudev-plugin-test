<?php
/**
 * Google Auth Class.
 *
 * @link          https://wpmudev.com/
 * @since         1.0.0
 *
 * @author        WPMUDEV (https://wpmudev.com)
 * @package       WPMUDEV\PluginTest
 *
 * @copyright (c) 2023, Incsub (http://incsub.com)
 */
namespace WPMUDEV\PluginTest\App\Commands;

// Abort if called directly.
defined( 'WPINC' ) || die;

use WPMUDEV\PluginTest\Commands;
use WPMUDEV\PluginTest\Helper;
use \WP_CLI;
class Command extends Commands {
    /**
     * Scan all public posts and pages and update their 'wpmudev_test_last_scan' meta field.
     *
     * ## OPTIONS
     *
     * [--post_types=<post_types>]
     * : Comma-separated list of post types to scan.
     * 
     * ## EXAMPLES
     *
     *     wp scan_posts
     *     wp scan_posts --post_types=post,page,custom_post_type
     *
     * @when after_wp_load
     */
	public function scan_all_public_posts_pages_cli() {
        $post_types = isset($assoc_args['post_types']) ? explode(',', $assoc_args['post_types']) : array('post', 'page');

        $helper = new Helper();
		$posts = $helper->scan_all_public_posts_pages_common($post_types);
        // Output the results in the CLI
        if (empty($posts)) {
            WP_CLI::success("No posts found.");
        } else {
            foreach ($posts as $post) {
                WP_CLI::log("ID: {$post['ID']}");
                WP_CLI::log("Title: {$post['title']}");
                WP_CLI::log("Link: {$post['link']}");
                WP_CLI::log("Last Scan Updated to: " . get_post_meta($post['ID'], 'wpmudev_test_last_scan', true));
                WP_CLI::log("---------------------");
            }
            WP_CLI::success("Scan completed.");
        }
    }
    
}
