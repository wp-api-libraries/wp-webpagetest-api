<?php
/**
 * WP-WebPagetest-API (https://sites.google.com/a/webpagetest.org/docs/advanced-features/webpagetest-restful-apis)
 *
 * @package WP-WebPagetest-API
 */

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Check if class exists. */
if ( ! class_exists( 'WebPagetestAPI' ) ) {

	/**
	 * WebPagetest API Class.
	 */
	class WebPagetestAPI {

		/**
		 * API Key.
		 *
		 * @var string
		 */
		static private $apikey;

		/**
		 * BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri = 'http://www.webpagetest.org/runtest.php';


		/**
		 * __construct function.
		 *
		 * @access public
		 * @param mixed $apikey API Key.
		 * @return void
		 */
		public function __construct( $apikey ) {
			static::$apikey = $apikey;
		}

		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @param mixed $request Request URL.
		 * @return $body Body.
		 */
		private function fetch( $request ) {

			$response = wp_remote_get( $request );
			$code = wp_remote_retrieve_response_code( $response );

			if ( 200 !== $code ) {
				return new WP_Error( 'response-error', sprintf( __( 'Server response code: %d', 'wp-webpagetest-api' ), $code ) );
			}

			$body = wp_remote_retrieve_body( $response );

			return json_decode( $body );

		}


		/**
		 * Run Test.
		 *
		 * @access public
		 * @param mixed $url URL.
		 * @return void
		 */
		function run_test( $url, $args = array() ) {

			if ( empty( $url ) ) {
				return new WP_Error( 'response-error', __( 'Please provide a URL.', 'wp-webpagetest-api' ) );
			}

			$request = $this->base_uri . '?url=' . $url . '&k=' . $this->apikey;

			return $this->fetch( $request );

		}

	}
}
