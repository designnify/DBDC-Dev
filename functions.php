<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Add Settings to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'DBDC Theme' );
define( 'CHILD_THEME_URL', 'http://designnify.com/' );
define( 'CHILD_THEME_VERSION', '2.0' );

//* Enqueue Styles and Scripts
add_action( 'wp_enqueue_scripts', 'dbdc_scripts' );
function dbdc_scripts() {

	//* Add Google Fonts
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700|Oswald:400,700,300 | Raleway:400,500', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'google-fonts' );
	
	//* Add compiled JS
	wp_enqueue_script( 'genesis-sample-scripts', get_stylesheet_directory_uri() . '/js/project-min' . $minnified . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	
	//* Add Backstretch Script
	if ( is_singular( array( 'post', 'page' ) ) && has_post_thumbnail() ) {

		wp_enqueue_script( 'dbdc-backstretch', get_bloginfo( 'stylesheet_directory' ) . '/js/partials/backstretch.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'dbdc-backstretch-set', get_bloginfo( 'stylesheet_directory' ) . '/js/partials/backstretch-init.js' , array( 'jquery', 'dbdc-backstretch' ), '1.0.0', true );
	}
	
	//* Add dashicons
	wp_enqueue_style( 'dashicons' );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add new tumbnail image sizes for post excerpts
add_image_size( 'post-image-large', 700, 990, TRUE );

//* Remove the header right widget area
unregister_sidebar( 'header-right' );

//* Rename menus
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Left Navigation Menu', 'dbdc' ), 'secondary' => __( 'Right Navigation Menu', 'dbdc' ) ) );

//* Hook menus
add_action( 'genesis_after_header', 'dbdc_menus_container' );
function dbdc_menus_container() {

	echo '<div class="navigation-container">';
	do_action( 'dbdc_menus' );
	echo '</div>';
	
}

//* Relocate Primary (Left) Navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'dbdc_menus', 'genesis_do_nav' );

//* Relocate Secondary (Right) Navigation
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'dbdc_menus', 'genesis_do_subnav' );

//* Remove output of primary navigation right extras
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

//* Relocate Post Title and Post Info
add_action( 'genesis_after_header', 'dbdc_relocate_post_title_info' );
function dbdc_relocate_post_title_info() {

	if ( is_singular(array( 'post', 'page' ) ) ) {

		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

		echo '<div class="featured-single"><div class="wrap">';
			genesis_entry_header_markup_open();
			genesis_do_post_title();
			genesis_post_info();
			genesis_entry_header_markup_close();
		echo '</div></div>';

	}

}

//* Localize backstretch script
add_action( 'genesis_after', 'dbdc_set_background_image' );
function dbdc_set_background_image() {
	$image = array( 'src' => has_post_thumbnail() ? genesis_get_image( array( 'format' => 'url' ) ) : '' );
	wp_localize_script( 'dbdc-backstretch-set', 'BackStretchImg', $image );
}

//* Add body class for single Posts and static Pages having Featured images
add_filter( 'body_class', 'ambiance_featured_img_body_class' );
function ambiance_featured_img_body_class( $classes ) {
	if ( is_singular( array( 'post', 'page' ) ) && has_post_thumbnail() ) {
		$classes[] = 'has-featured-image';
	}
	return $classes;
}

//* Add opening div for .site-header and .nav-secondary for single Posts and static Pages having Featured images
add_action( 'genesis_before_header', 'dbdc_opening_div' );
function dbdc_opening_div() {
	if ( is_singular( array( 'post', 'page' ) ) && has_post_thumbnail() ) {
		echo '<div class="entry-background">';
	}
}

//* Add closing div for .site-header and .nav-secondary for single Posts and static Pages having Featured images
add_action( 'genesis_after_header', 'dbdc_closing_div' );
function dbdc_closing_div() {
	if ( is_singular( array( 'post', 'page' ) ) && has_post_thumbnail() ) {
		echo '</div>';
	}
}

//* Add a ID to .site-inner
add_filter( 'genesis_attr_site-inner', 'custom_attributes_content' );
function custom_attributes_content( $attributes ) {

	if ( is_singular('post' ) ) {
		$attributes['id'] = 'site-inner';
	}
	return $attributes;

}

//* Add support for 4-column footer widgets
add_theme_support( 'genesis-footer-widgets', 4 );

//* Customize the credits
add_filter( 'genesis_footer_creds_text', 'sp_footer_creds_text' );
function sp_footer_creds_text() {
	echo '<div class="creds"><p>';
	echo 'Copyright &copy; ';
	echo date('Y');
	echo ' &middot; DBDC &middot; All rights reserved &middot; Built by <a href="http://designnify.com/" target="blank">Designnify - Mauricio Alvarez</a>';
	echo '</p></div>';
}

//* Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'dbdc_entry_meta_header' );
function dbdc_entry_meta_header($post_info) {

	$post_info = '[post_date] [post_edit]';
	return $post_info;

}

//* Remove the entry meta in the entry footer
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

//* Removing emoji code form head
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

//* Register widget areas for home page
genesis_register_sidebar( array(
	'id'            => 'hero-video',
	'name'          => __( 'Homepage Hero Video', 'dbdc' ),
	'description'   => __( 'This is the homepage video section', 'dbdc' ),
));
genesis_register_sidebar( array(
	'id'            => 'homepage-news',
	'name'          => __( 'Homepage News & GCalendar', 'dbdc' ),
	'description'   => __( 'This is the homepage news & google calendar section', 'dbdc' ),
));
genesis_register_sidebar( array(
	'id'            => 'homepage-map',
	'name'          => __( 'Homepage Map', 'dbdc' ),
	'description'   => __( 'This is the homepage google map section', 'dbdc' ),
));
genesis_register_sidebar( array(
	'id'            => 'homepage-newsletter',
	'name'          => __( 'Homepage Newsletter', 'dbdc' ),
	'description'   => __( 'This is the homepage newsletter section', 'dbdc' ),
));
genesis_register_sidebar( array(
	'id'            => 'homepage-social-icons',
	'name'          => __( 'Homepage Social Icons', 'dbdc' ),
	'description'   => __( 'This is the homepage social icons section', 'dbdc' ),
));
