<?php
namespace KLA_Tivie_Slides_API;

/**
 * Class Slide_Post_Type
 *
 * This class handles the custom post type for slides.
 *
 * @package Kla_Tivie_Slides_API
 */
class Slide_Post_Type {

	private const POST_TYPE = 'slide';

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Registers the custom post type for slides.
	 *
	 * This function is responsible for registering the custom post type
	 * used for managing slides within the plugin.
	 *
	 * @return void
	 */
	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'      => array(
					'name'          => __( 'Slides', 'kla-tivie-slides-api' ),
					'singular_name' => __( 'Slide', 'kla-tivie-slides-api' ),
				),
				'public'      => true,
				'has_archive' => false,
				'rewrite'     => array( 'slug' => self::POST_TYPE ),
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
			)
		);
	}
}
