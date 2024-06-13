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
namespace WPMUDEV\PluginTest\App\ShortCodes;

// Abort if called directly.
defined( 'WPINC' ) || die;

use WPMUDEV\PluginTest\ShortCodes;

class ShortCode extends ShortCodes {
    /**
	 * Scan all public posts functionality here.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function google_oauth_shortcode() {
        ob_start();
        ?>
        <?php
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            ?>
            <div id="welcome-message" style="text-align: center;">
                Welcome, <?php echo esc_html($current_user->display_name); ?>!
            </div>
            <?php
        } else {
            ?>
            <style>
            #google-login-button {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #fff;
                color: #3c4043;
                font-size: 16px;
                font-family: 'Roboto', sans-serif;
                border: 1px solid #dadce0;
                border-radius: 24px;
                padding: 10px 24px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                cursor: pointer;
                transition: background-color 0.3s ease, box-shadow 0.3s ease;
            }
    
            #google-login-button:hover {
                background-color: #f6f6f6;
                box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            }
    
            #google-login-button img {
                margin-right: 8px;
            }
        </style>
        <button id="google-login-button">
            <img src="<?php echo WPMUDEV_PLUGINTEST_ASSETS_URL . '/images/google.png'?>" alt="Google logo" width="20" height="20">
            Continue with Google
        </button>
        <script type="text/javascript">
            document.getElementById('google-login-button').addEventListener('click', async function() {
                await fetch('<?php echo esc_url(rest_url('wpmudev/v1/auth/start')); ?>', {
                    method: 'POST',
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    let response = data.data;
                    if (response.authUrl) {
                        window.location.href = response.authUrl;
                    }
                });
            });
        </script>
            <?php
        }    
        return ob_get_clean();
    }
    
}
