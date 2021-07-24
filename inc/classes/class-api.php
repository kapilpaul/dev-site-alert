<?php
/**
 * API class.
 *
 * @package rt-dev-site-alert
 */

namespace Rt_Dev_Site_Alert\Inc;

use Rt_Dev_Site_Alert\Inc\Rest_Api\Post;
use Rt_Dev_Site_Alert\Inc\Rest_Api\Settings;
use Rt_Dev_Site_Alert\Inc\Traits\Singleton;

/**
 * Class API
 */
class API {

	use Singleton;

	/**
	 * Holds the api classes.
	 *
	 * @var array
	 */
	private $classes;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		$this->classes = array(
			Settings::class,
			Post::class,
		);

		add_action( 'rest_api_init', array( $this, 'register_api' ) );

	}

	/**
	 * Register the API
	 *
	 * @return void
	 */
	public function register_api() {
		foreach ( $this->classes as $class ) {
			$object = new $class();
			$object->register_routes();
		}
	}
}
