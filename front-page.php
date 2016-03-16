<?php
/**
 * This file adds the Home Page to the DBDC Theme.
 *
 * @author Mauricio Alvarez
 * @package DBDC
 * @subpackage Customizations
 */
 
add_action( 'wp_enqueue_scripts', 'dbdc_enqueue_scripts' );
/**
 * Enqueue Scripts
 */
function dbdc_enqueue_scripts() {

	if ( is_active_sidebar( 'hero-video' ) || is_active_sidebar( 'homepage-news' ) || is_active_sidebar( 'homepage-map' )  || is_active_sidebar( 'homepage-newsletter' ) || is_active_sidebar( 'homepage-social-icons' ) ) {
	
		
	}
}

add_action( 'genesis_meta', 'dbdc_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function dbdc_home_genesis_meta() {

	if ( is_active_sidebar( 'hero-video' ) || is_active_sidebar( 'homepage-news' ) || is_active_sidebar( 'homepage-map' )  || is_active_sidebar( 'homepage-newsletter' ) || is_active_sidebar( 'homepage-social-icons' ) ) {

		//* Force content-sidebar layout setting
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
		
		//* Add home body class
		add_filter( 'body_class', 'dbdc_body_class' );
		
		//* Remove breadcrumbs
		remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Add homepage widgets
		add_action( 'genesis_loop', 'dbdc_homepage_widgets' );

	}

}

function dbdc_body_class( $classes ) {

	$classes[] = 'dbdc-home';
	return $classes;
	
}

//* Enqueue Backstretch script and prepare images for loading
add_action( 'wp_enqueue_scripts', 'dbdc_enqueue_backstretch_scripts' );
function dbdc_enqueue_backstretch_scripts() {

	$image = get_option( 'dbdc-backstretch-image', sprintf( '%s/images/default-bg-opt.jpg', get_stylesheet_directory_uri() ) );

	//* Load scripts only if custom backstretch image is being used
	if ( ! empty( $image ) ) {

		wp_enqueue_script( 'dbdc-backstretch', get_bloginfo( 'stylesheet_directory' ) . '/js/min/backstretch-min.js', array( 'jquery' ), '1.0.0' );
		wp_enqueue_script( 'dbdc-backstretch-set', get_bloginfo( 'stylesheet_directory' ).'/js/min/backstretch-set-min.js' , array( 'jquery', 'dbdc-backstretch' ), '1.0.0' );

		wp_localize_script( 'dbdc-backstretch-set', 'BackStretchImg', array( 'src' => str_replace( 'http:', '', $image ) ) );

	}

}

function dbdc_homepage_widgets() {
	
	genesis_widget_area( 'hero-video', array(
	'before'	=> '<div class="hero-video">',
	'after'		=> '</div>',
	));
	genesis_widget_area( 'homepage-news', array(
		'before'	=> '<div id="homepage-news" class="homepage-news"><div class="wrap">',
		'after'		=> '</div></div>',
	));
	genesis_widget_area( 'homepage-map', array(
		'before'	=> '<div class="homepage-map">',
		'after'		=> '</div>',
	));
	genesis_widget_area( 'homepage-newsletter', array(
		'before'	=> '<div class="homepage-newsletter"><div class="wrap">',
		'after'		=> '</div></div>',
	));
	genesis_widget_area( 'homepage-social-icons', array(
		'before'	=> '<div id="homepage-social-icons" class="homepage-social-icons"><div class="wrap">',
		'after'		=> '</div></div>',
	));
	
}

genesis();
