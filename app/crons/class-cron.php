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
namespace WPMUDEV\PluginTest\App\Crons;

// Abort if called directly.
defined( 'WPINC' ) || die;

use WPMUDEV\PluginTest\Crons;
use WPMUDEV\PluginTest\Helper;
class Cron extends Crons {
    /**
	 * Scan all posts cron functionality here
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function scan_all_public_posts_pages_cron() {
        $helper = new Helper();
		$post_types = array('post', 'page');
        $helper->scan_all_public_posts_pages_common($post_types);
    }
    
}
