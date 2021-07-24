<?php
/**
 * REST BASE class.
 *
 * @package rt-dev-site-alert
 */

namespace Rt_Dev_Site_Alert\Inc\Rest_Api;

use WP_Http;
use WP_REST_Controller;

/**
 * REST BASE class
 */
class Rest_Base extends WP_REST_Controller {

	/**
	 * Namespace.
	 *
	 * @var string Namespace.
	 */
	public $namespace = 'rt-dev-site-alert';

	/**
	 * Version.
	 *
	 * @var string version.
	 */
	public $version = 'v1';

	/**
	 * Permission check
	 *
	 * @param \WP_REST_Request $request WP Rest Request.
	 *
	 * @since 2.0.0
	 *
	 * @return \WP_Error|bool
	 */
	public function admin_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'rtdsa_permission_error',
				__( 'You have no permission to do that', 'rt-dev-site-alert' ),
				[ 'status' => WP_Http::BAD_REQUEST ]
			);
		}

		return true;
	}

	/**
	 * Get full namespace
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_namespace() {
		return $this->namespace . '/' . $this->version;
	}
}
