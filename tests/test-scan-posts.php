<?php
/**
 * Class SampleTest
 *
 * @package Wpmudev_Plugin_Test
 */

/**
 * Sample test case.
 */
use WPMUDEV\PluginTest\Helper;
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_scan_all_public_posts_pages_common() {
		$post_id1 = wp_insert_post([
            'post_title' => 'Test Post 1',
            'post_content' => 'Content of test post 1',
            'post_status' => 'publish',
            'post_type' => 'post',
        ]);

        $post_id2 = wp_insert_post([
            'post_title' => 'Test Post 2',
            'post_content' => 'Content of test post 2',
            'post_status' => 'publish',
            'post_type' => 'page',
        ]);
		$helper = new Helper();
		$posts = $helper->scan_all_public_posts_pages_common(['post', 'page']);
		$post_ids = [];
        foreach ($posts as $post) {
            $post_ids[] = $post['ID'];
        }
		// Check if specific posts are in the result
        $this->assertContains($post_id1, $post_ids);
        $this->assertContains($post_id2, $post_ids);
		// Check if the post meta wpmudev_test_last_scan was updated
		$this->assertEquals(current_time('mysql'), get_post_meta($post_id1, 'wpmudev_test_last_scan', true));
        $this->assertEquals(current_time('mysql'), get_post_meta($post_id2, 'wpmudev_test_last_scan', true));
	}
}
