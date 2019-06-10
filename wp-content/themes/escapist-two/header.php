<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Escapist
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

  <?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>

  <link rel="shortcut icon" href="https://d1gkiy13jtzlp.cloudfront.net/assets/favicon-d216b336b1d64a574e4e945c4a9dbc51.ico" type="image/png"/><link rel="icon" href="https://d1gkiy13jtzlp.cloudfront.net/assets/favicon-d216b336b1d64a574e4e945c4a9dbc51.ico" type="image/png"/>

  <?php wp_head(); ?>

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700|Source+Serif+Pro:400,600,700" rel="stylesheet">

</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'escapist' ); ?></a>

	<?php include('core-header.php'); ?>

	<header id="masthead" class="site-header" role="banner">
		<?php if ( has_nav_menu( 'secondary' ) || has_nav_menu( 'social' ) ) : ?>
			<div class="site-top">
				<div class="site-top-inner">
					<?php if ( has_nav_menu( 'secondary' ) ) : ?>
						<nav class="secondary-navigation" role="navigation">
							<?php
								wp_nav_menu( array(
									'theme_location'  => 'secondary',
									'depth'           => 1,
								) );
							?>
						</nav><!-- .secondary-navigation -->
					<?php endif; ?>
					<?php if ( has_nav_menu( 'social' ) ) : ?>
						<nav class="social-navigation" role="navigation">
							<?php
								wp_nav_menu( array(
									'theme_location'  => 'social',
									'link_before'     => '<span class="screen-reader-text">',
									'link_after'      => '</span>',
									'depth'           => 1,
								) );
							?>
						</nav><!-- .social-navigation -->
					<?php endif; ?>
				</div><!-- .site-top-inner -->
			</div><!-- .site-top -->
		<?php endif; ?>

		<div class="site-branding">
			<?php escapist_the_site_logo(); ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div><!-- .site-branding -->

		<?php if ( get_header_image() ) : ?>
			<div class="header-image">
				<div class="header-image-inner">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt=""></a>
				</div><!-- .header-image-inner -->
			</div><!-- .header-image -->
		<?php endif; // End header image check. ?>


		<div id="search-navigation" class="search-navigation">
      <div id="sitenav">
  			<div id="search-navigation-inner" class="search-navigation-inner">
  				<?php if ( has_nav_menu( 'primary' ) ) : ?>
  					<nav id="site-navigation" class="main-navigation" role="navigation">
  						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Primary Menu', 'escapist' ); ?></span></button>
  						<?php wp_nav_menu( array( 'theme_location'  => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
  					</nav><!-- #site-navigation -->
  				<?php endif; ?>
  				<div id="search-header" class="search-header">
  					<button class="search-toggle" aria-controls="search-form" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Search', 'escapist' ); ?></span></button>
  					<?php get_search_form(); ?>
  				</div><!-- #search-header -->
  			</div><!-- .search-navigation-inner -->
      </div>
		</div><!-- #search-navigation -->

	</header><!-- #masthead -->

	<div id="content" class="site-content">
