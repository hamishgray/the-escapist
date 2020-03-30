<?php
/**
 * Escapist functions and definitions
 *
 * @package Escapist
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 720; /* pixels */
}

if ( ! function_exists( 'escapist_content_width' ) ) {

	function escapist_content_width() {
		global $content_width;

		if ( is_page() ) {
			$content_width = 869;
		}
	}

}
add_action( 'template_redirect', 'escapist_content_width' );

if ( ! function_exists( 'escapist_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function escapist_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Escapist, use a find and replace
	 * to change 'escapist' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'escapist', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'escapist-post-thumbnail', 870, 773, true );
	add_image_size( 'escapist-featured-content-thumbnail', 915, 500, true );
	add_image_size( 'escapist-single-thumbnail', 1920, 768, true );
	add_image_size( 'escapist-post-group-thumbnail', 915, 500, true );

	// This theme uses wp_nav_menu() in four locations.
	register_nav_menus( array(
		'primary'   => __( 'Primary Location', 'escapist' ),
		'secondary' => __( 'Secondary Location', 'escapist' ),
		'footer'    => __( 'Footer Location', 'escapist' ),
		'social'    => __( 'Social Location', 'escapist' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'image', 'link', 'gallery',
	) );
}
endif; // escapist_setup
add_action( 'after_setup_theme', 'escapist_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function escapist_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'escapist' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer', 'escapist' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'escapist_widgets_init' );

/**
 * Register Lato and Inconsolata fonts.
 *
 * @return string
 */
function escapist_lato_inconsolata_fonts_url() {
	$fonts_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	$lato = _x( 'on', 'Lato font: on or off', 'escapist' );

	/* translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	$inconsolata = _x( 'on', 'Inconsolata font: on or off', 'escapist' );

	if ( 'off' !== $lato || 'off' !== $inconsolata ) {
		$font_families = array();

		if ( 'off' !== $lato ) {
			$font_families[] = 'Lato:400,700,400italic,700italic';
		}

		if ( 'off' !== $inconsolata ) {
			$font_families[] = 'Inconsolata:400,700';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "https://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Register PT Serif and Playfair Display fonts.
 *
 * @return string
 */
function escapist_pt_serif_playfair_display_font_url() {
	$fonts_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by PT Serif, translate this to 'off'. Do not translate into your own language.
	 */
	$pt_serif = _x( 'on', 'PT Serif font: on or off', 'escapist' );

	/* translators: If there are characters in your language that are not supported
	 * by Playfair Display, translate this to 'off'. Do not translate into your own language.
	 */
	$playfair_display = _x( 'on', 'Playfair Display font: on or off', 'escapist' );

	if ( 'off' !== $pt_serif || 'off' !== $playfair_display ) {
		$font_families = array();

		if ( 'off' !== $pt_serif ) {
			$font_families[] = 'PT Serif:400,700,400italic,700italic';
		}

		if ( 'off' !== $playfair_display ) {
			$font_families[] = 'Playfair Display:400,700,400italic,700italic';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'cyrillic,latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "https://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles.
 */
function escapist_scripts() {
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.3' );

	wp_enqueue_style( 'escapist-pt-serif-playfair-display', escapist_pt_serif_playfair_display_font_url() );

	wp_enqueue_style( 'escapist-lato-inconsolata', escapist_lato_inconsolata_fonts_url() );

	wp_enqueue_style( 'escapist-style', get_stylesheet_uri() );

	wp_enqueue_script( 'escapist-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20150507', true );

	wp_enqueue_script( 'escapist-featured-content', get_template_directory_uri() . '/js/featured-content.js', array( 'jquery' ), '20150507', true );

	wp_enqueue_script( 'escapist-header', get_template_directory_uri() . '/js/header.js', array(), '20150908', true );

	wp_enqueue_script( 'escapist-search', get_template_directory_uri() . '/js/search.js', array( 'jquery' ), '20150507', true );

	wp_enqueue_script( 'escapist-site', get_template_directory_uri() . '/js/site.js', array( 'jquery' ), '20190731', true );

	if ( ( is_single() && ( has_post_thumbnail() && ( ! has_post_format() || has_post_format( 'aside' ) || has_post_format( 'image' ) || has_post_format( 'gallery' ) ) ) ) || ( is_page() && has_post_thumbnail() ) ) {
		wp_enqueue_script( 'escapist-single-thumbnail', get_template_directory_uri() . '/js/single-thumbnail.js', array( 'jquery' ), '20150416', true );
	}

	if ( is_singular() ) {
		wp_enqueue_script( 'escapist-single', get_template_directory_uri() . '/js/single.js', array( 'jquery' ), '20150507', true );
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		wp_enqueue_script( 'escapist-sidebar', get_template_directory_uri() . '/js/sidebar.js', array(), '20150429', true );
	}

	if ( is_home() || is_archive() || is_search() ) {
		wp_enqueue_script( 'escapist-posts', get_template_directory_uri() . '/js/posts.js', array( 'jquery' ), '20150507', true );
	}

	wp_enqueue_script( 'escapist-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'escapist_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


// updater for WordPress.com themes
if ( is_admin() )
	include dirname( __FILE__ ) . '/inc/updater.php';




/**
 * Template Parts with Display Posts Shortcode
 * @see https://www.billerickson.net/template-parts-with-display-posts-shortcode
 *
 * @param string $output, current output of post
 * @param array $original_atts, original attributes passed to shortcode
 * @return string $output
 */
function be_dps_template_part( $output, $original_atts ) {
	// Return early if our "layout" attribute is not specified
	if( empty( $original_atts['layout'] ) )
		return $output;
	ob_start();
	get_template_part( 'partials/dps', $original_atts['layout'] );
	$new_output = ob_get_clean();
	if( !empty( $new_output ) )
		$output = $new_output;
	return $output;
}
add_action( 'display_posts_shortcode_output', 'be_dps_template_part', 10, 2 );


/**
 * Limit excerpt length by words
 * @see https://smallenvelop.com/limit-post-excerpt-length-in-wordpress/
 * example: echo excerpt(30)
 */
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }
  $content = preg_replace('/[.+]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]>', $content);
  return $content;
}


// Function to create a button link
function button_shortcode( $atts ) {
	$atts = shortcode_atts(
	  array(
		  'link'  => 'link',
		  'style' => 'outline',
			'size'  => 'md',
		  'text'  => 'Read more',
	  ), $atts, 'button'
	);
	$text  = $atts['text'];
	$link  = $atts['link'];
	$style = $atts['style'];
	$size  = $atts['size'];

	return( '
		<div class="text--center">
			<a class="btn btn--' . $style . ' btn--' . $size . '" href=' . $link . '>' . $text . '</a>
			<div class="space--xl"></div>
		</div>');
}
add_shortcode('button', 'button_shortcode');



// Add site description field to settings
add_filter('admin_init', 'add_site_description_field');
function add_site_description_field() {
	register_setting('general', 'site_description', 'esc_attr');
	add_settings_field('site_description', '<label for="site_description">'.__('Site Description' , 'site_description' ).'</label>' , 'my_site_description', 'general');
}
function my_site_description() {
	$site_description = get_option( 'site_description', '' );
	echo '<textarea id="site_description" style="width: 35%;" rows="4" type="text" name="site_description">' . $site_description . '</textarea>';
}

/* Disable WordPress Admin Bar for all users but admins. */
show_admin_bar(false);