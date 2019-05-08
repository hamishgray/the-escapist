<?php
/* Template Name: Home Page Template
 *
 * @package Escapist
 */

get_header(); ?>

	<?php
		// Include the featured content template.
		get_template_part( 'featured-content' );
	?>

	<div class="site-content-inner">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content-home', 'page' ); ?>

				<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
	</div><!-- .site-content-inner -->

<?php get_footer(); ?>
