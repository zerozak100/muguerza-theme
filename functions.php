<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

 define( 'MG_TEMPLATES_PATH', get_stylesheet_directory() . '/templates//' );
 define( 'MG_THEME_VERSION', '0.0.1' );

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'default',
		get_stylesheet_directory_uri() . '/css/default.css',
		[],
		MG_THEME_VERSION,
	);

	wp_enqueue_style(
		'tingle',
		get_stylesheet_directory_uri() . '/lib/tingle/dist/tingle.min.css',
		[],
		'0.16.0',
	);

	wp_enqueue_style(
		'muguerza-theme',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		MG_THEME_VERSION,
	);

	wp_enqueue_script(
		'tingle',
		get_stylesheet_directory_uri() . '/lib/tingle/dist/tingle.min.js',
		[],
		'0.16.0',
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

include_once 'inc/shortcodes.php';
include_once 'inc/mg-helpers.php';
include_once 'inc/mg-template-functions.php';
include_once 'inc/mg-template-hooks.php';

