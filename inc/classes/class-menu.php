<?php
/**
 * Menu class.
 *
 * @package rt-dev-site-alert
 */

namespace Rt_Dev_Site_Alert\Inc;

use \Rt_Dev_Site_Alert\Inc\Traits\Singleton;

/**
 * Class Menu
 */
class Menu {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'plugin_action_links_' . RT_DEV_SITE_ALERT_BASENAME_FILE, array( $this, 'plugin_action_links' ) );

	}

	/**
	 * Register menu.
	 *
	 * @return void
	 */
	public function admin_menu() {
		$parent_slug = 'options-general.php';
		$capability  = 'manage_options';

		add_submenu_page( $parent_slug, 'Dev Site Alert', 'Dev Site Alert', $capability, 'rt-dev-site-alert', array( $this, 'dev_site_alert_page' ) );
	}

	/**
	 * Render menu page.
	 *
	 * @return void
	 */
	public function dev_site_alert_page() {
		printf( '<div class="rt-dev-site-alert-container" id="rt-dev-site-alert-container"></div>' );
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links plugin links.
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		array_unshift( $links, '<a href="' . admin_url( 'options-general.php?page=rt-dev-site-alert' ) . '">' . __( 'Settings', 'rt-dev-site-alert' ) . '</a>' );

		return $links;
	}
}
