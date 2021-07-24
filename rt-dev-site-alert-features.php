<?php
/**
 * Plugin Name: RT Dev Site URL Alert
 * Description: To avoid having dev site URLs on the live site. It may happen when copying the content from dev to live manually.
 * Plugin URI:  https://rtcamp.com
 * Author:      rtCamp
 * Author URI:  https://rtcamp.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0
 * Text Domain: rt-dev-site-alert
 *
 * @package rt-dev-site-alert
 */

define( 'RT_DEV_SITE_ALERT_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'RT_DEV_SITE_ALERT_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'RT_DEV_SITE_ALERT_BUILD_DIR', RT_DEV_SITE_ALERT_PATH . '/assets/build' );
define( 'RT_DEV_SITE_ALERT_BUILD_URI', RT_DEV_SITE_ALERT_URL . '/assets/build' );
define( 'RT_DEV_SITE_ALERT_BASENAME_FILE', plugin_basename( __FILE__ ) );

// phpcs:disable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once RT_DEV_SITE_ALERT_PATH . '/inc/helpers/autoloader.php';
require_once RT_DEV_SITE_ALERT_PATH . '/inc/helpers/custom-functions.php';
// phpcs:enable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function rt_dev_site_alert_plugin_loader() {
	\Rt_Dev_Site_Alert\Inc\Plugin::get_instance();
}

rt_dev_site_alert_plugin_loader();
