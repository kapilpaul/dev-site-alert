<?php
/**
 * Settings API class.
 *
 * @package rt-dev-site-alert
 */

namespace Rt_Dev_Site_Alert\Inc\Rest_Api;

use WP_Http;
use WP_REST_Server;

/**
 * Class Settings API
 */
class Settings extends Rest_Base {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		$this->rest_base = 'settings';
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->get_namespace(),
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings_data' ),
					'permission_callback' => array( $this, 'admin_permissions_check' ),
					'args'                => $this->get_collection_params(),
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_items' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
					'permission_callback' => array( $this, 'admin_permissions_check' ),
				],
				'schema' => array( $this, 'get_item_schema' ),
			]
		);
	}

	/**
	 * Get settings data.
	 *
	 * @param object $request Request Object.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_settings_data( $request ) {
		$settings = array(
			'dev_site_url' => get_option( 'rtsa_dev_site_url', '' ),
		);

		return rest_ensure_response( $settings );
	}

	/**
	 * Update items.
	 *
	 * @param object $request Request Object.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function update_items( $request ) {
		$url = sanitize_text_field( $request->get_param( 'dev_site_url' ) );

		if ( empty( $url ) ) {
			return new \WP_Error(
				'rtdsa_empty_url',
				__( 'Dev site URL cannot be empty!', 'rt-dev-site-alert' ),
				[ 'status' => WP_Http::BAD_REQUEST ]
			);
		}

		update_option( 'rtsa_dev_site_url', $url, false );

		return $this->get_settings_data( $request );
	}
}
