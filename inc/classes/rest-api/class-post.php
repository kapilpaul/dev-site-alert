<?php
/**
 * Post API class.
 *
 * @package rt-dev-site-alert
 */

namespace Rt_Dev_Site_Alert\Inc\Rest_Api;

use WP_Http;
use WP_REST_Server;

/**
 * Class Post API
 */
class Post extends Rest_Base {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		$this->rest_base = 'post';
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->get_namespace(),
			'/' . $this->rest_base . '/validate',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'check_post_content' ),
					'permission_callback' => array( $this, 'admin_permissions_check' ),
					'args'                => $this->get_collection_params(),
				],
				'schema' => array( $this, 'get_item_schema' ),
			]
		);
	}

	/**
	 * Check post content for containing any dev site url.
	 *
	 * @param object $request Request Object.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function check_post_content( $request ) {
		$post_id = (int) $request->get_param( 'post_id' );

		if ( ! is_int( $post_id ) ) {
			return new \WP_Error(
				'rtdsa_invalid_post_id',
				__( 'Invalid Post ID', 'rt-dev-site-alert' ),
				[ 'status' => WP_Http::NOT_ACCEPTABLE ]
			);
		}

		// Fetching the post.
		$post = get_post( $post_id );

		$dev_site_url = get_option( 'rtsa_dev_site_url' );

		if ( false !== strpos( $post->post_content, $dev_site_url ) ) {
			return new \WP_Error(
				'rtdsa_found_url',
				__( 'Dev site URL found in post content!', 'rt-dev-site-alert' ),
				[ 'status' => WP_Http::EXPECTATION_FAILED ]
			);
		}

		// Get all meta related to the post.
		$post_metas = get_post_meta( $post_id );

		foreach ( $post_metas as $key => $post_meta ) {
			foreach ( $post_meta as $value ) {
				if ( false !== strpos( $value, $dev_site_url ) ) {
					return new \WP_Error(
						'rtdsa_found_url',
						__( 'Dev site URL found in post meta!', 'rt-dev-site-alert' ),
						[ 'status' => WP_Http::EXPECTATION_FAILED ]
					);
				}
			}
		}

		return rest_ensure_response( $post_metas );
	}
}
