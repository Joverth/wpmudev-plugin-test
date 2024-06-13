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

class ShortCodes extends Base{
	/**
	 * Instance obtaining method.
	 *
	 * @return static Called class instance.
	 * @since 1.0.0
	 */
	public static function instance() {
		static $instances = array();

		// @codingStandardsIgnoreLine Plugin-backported
		$called_class_name = get_called_class();

		if ( ! isset( $instances[ $called_class_name ] ) ) {
			$instances[ $called_class_name ] = new $called_class_name();
		}

		return $instances[ $called_class_name ];
	}
    public function init() {
		if (!session_id()) {
            session_start();
        }
        add_shortcode('google_oauth', array($this, 'google_oauth_shortcode'));
	}
	/**
	 * Register the shortcode.
	 *
	 * This should be defined in extending class.
	 *
	 * @since 1.0.0
	 */
	public function google_oauth_shortcode() {
       
    }

}
