<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Escapist
 */

function escapist_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer'         => 'page',
		'footer_widgets' => array( 'sidebar-2' ),
	) );

	/**
	 * Add theme support for Featured Content.
	 * See: http://jetpack.me/support/featured-content/
	 */
	add_theme_support( 'featured-content', array(
		'filter'      => 'escapist_get_featured_posts',
		'description' => __( 'The featured content section displays on the front page above the header.', 'escapist' ),
		'max_posts'   => 5,
		'post_types'  => array( 'post', 'page' ),
	) );

	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );

	/**
	 * Add theme support for Logo upload.
	 */
	add_image_size( 'escapist-logo', 400, 90 );
	add_theme_support( 'site-logo', array( 'size' => 'escapist-logo' ) );
}
add_action( 'after_setup_theme', 'escapist_jetpack_setup' );

/**
 * Featured Posts
 */
function escapist_has_multiple_featured_posts() {
	$featured_posts = apply_filters( 'escapist_get_featured_posts', array() );
	if ( is_array( $featured_posts ) && 1 < count( $featured_posts ) ) {
		return true;
	}
	return false;
}
function escapist_get_featured_posts() {
	return apply_filters( 'escapist_get_featured_posts', false );
}

/**
 * Remove sharedaddy from excerpt.
 */
function escapist_remove_sharedaddy() {
    remove_filter( 'the_excerpt', 'sharing_display', 19 );
}
add_action( 'loop_start', 'escapist_remove_sharedaddy' );

/**
 * Return early if Site Logo is not available.
 */
function escapist_the_site_logo() {
	if ( ! function_exists( 'jetpack_the_site_logo' ) ) {
		return;
	} else {
		jetpack_the_site_logo();
	}
}
