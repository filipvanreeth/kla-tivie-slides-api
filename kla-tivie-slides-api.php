<?php
/**
 * Plugin Name:       KLA Tivie Slides API
 * Description:       KLA Tivie Slides API
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Filip Van Reeth
 * Author URI:        https://filipvanreeth.com
 * License:           GPL v2 or later
 * License URI:
 * Update URI:
 * Text Domain:       kla-tivie-slides-api
 * Domain Path:       /languages
 */

require_once __DIR__ . '/inc/class-slide-post-type.php';
require_once __DIR__ . '/inc/class-slide-rest-api.php';

new KLA_Tivie_Slides_API\Slide_Post_Type();
new KLA_Tivie_Slides_API\Slide_Rest_API();
