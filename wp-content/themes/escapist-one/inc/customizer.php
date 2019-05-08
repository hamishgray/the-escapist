<?php
/**
 * Escapist Theme Customizer
 *
 * @package Escapist
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function escapist_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/* Theme Options */
	$wp_customize->add_section( 'escapist_theme_options', array(
		'title'    => __( 'Theme Options', 'escapist' ),
		'priority' => 130,
	) );

	/* Author Bio */
	$wp_customize->add_setting( 'escapist_author_bio', array(
		'sanitize_callback' => 'escapist_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'escapist_author_bio', array(
		'label'             => __( 'Show author bio on single posts.', 'escapist' ),
		'section'           => 'escapist_theme_options',
		'priority'          => 10,
		'type'              => 'checkbox',
	) );
}
add_action( 'customize_register', 'escapist_customize_register' );

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean (true|false).
 */
function escapist_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function escapist_customize_preview_js() {
	wp_enqueue_script( 'escapist-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'escapist_customize_preview_js' );
