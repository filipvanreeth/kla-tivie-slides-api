<?php

namespace KLA_Tivie_Slides_API;

use DateTime;
use WP_Query;
use WP_REST_Response;

/**
 * Class Slide_Rest_API
 *
 * This class handles the REST API endpoints for the slides.
 *
 * @package Kla_Tivie_Slides_API
 */
class Slide_Rest_API {

	private const POST_TYPE = 'slide';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

    /**
     * Retrieves the post type.
     *
     * @return string The post type.
     */
	private function get_post_type(): string {
		return apply_filters( 'kla_tivie_slides_api_post_type', self::POST_TYPE );
	}

    /**
     * Registers the custom REST API routes for the slides.
     *
     * This method is responsible for defining and registering the custom REST API
     * routes that will be used to interact with the slides.
     *
     * @return void
     */
	public function register_rest_routes(): void {
		register_rest_route(
			'kla-tivie-slides/v1',
			'/by-date',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_slides_by_date' ),
			)
		);
	}

    /**
     * Retrieves slides based on the provided date.
     *
     * @param WP_REST_Request $request The REST request object containing the date parameter.
     * @return WP_REST_Response The REST response object containing the slides data.
     */
	public function get_slides_by_date( WP_REST_Request $request ): WP_REST_Response {
		$date_param = $request->get_param( 'date' );
		$date_key   = $request->get_param( 'date_key' ) ?? 'ss_ts_start_date';

		try {
			$api_date = $date_param ? new DateTime( $date_param ) : new DateTime();
			$api_date->setTime( 0, 0 );
		} catch ( \Throwable $th ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Invalid date format',
				),
				400
			);
		}

		$requested_date = $api_date->format( DateTime::ATOM );

		$args = array(
			'post_type'      => $this->get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => $date_key,
					'value'   => $api_date->format( 'Ymd' ),
					'compare' => 'LIKE',
				),
			),
		);

		$query = new WP_Query( $args );

		if ( $query->post_count === 0 ) {
			return new WP_REST_Response(
				array(
					'success'        => false,
					'requested_date' => $requested_date,
					'slides'         => null,
					'total'          => 0,
				),
				200
			);
		}

		return new WP_REST_Response(
			array(
				'success'        => true,
				'requested_date' => $requested_date,
				'slides'         => $this->get_slide_data(
					slides: $query->posts,
					data: array( 'date_key' => $date_key )
				),
				'total'          => $query->post_count,
			),
			200
		);
	}

    /**
     * Retrieves slide data based on the provided slides and data arrays.
     *
     * @param array $slides An array of slides to retrieve data for.
     * @param array $data An array of data to be used in the retrieval process.
     * @return array An array containing the retrieved slide data.
     */
	private function get_slide_data( array $slides, array $data ): array {
		return array_map(
			function ( $slide ) use ( $data ): array {
				$date = new DateTime( get_post_meta( post_id: $slide->ID, key: $data['date_key'], single: true ) );
				$date->setTime( 0, 0 );

				return array(
					'id'    => $slide->ID,
					'title' => $slide->post_title,
					'url'   => get_permalink( $slide->ID ),
					'dates' => array(
						'date' => $date->format( DateTime::ATOM ),
					),
				);
			},
			array: $slides
		);
	}
}
