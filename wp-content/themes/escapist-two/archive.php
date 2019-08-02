<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Escapist
 */

get_header(); ?>

	<div class="site-content-inner archive-page">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="archive-page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>

					<div class="sub-cats">
						<?php
							if (is_category()) {
								$this_category = get_category($cat);
								$this_category = wp_list_categories('orderby=name&show_option_none=0&depth=1&show_count=0
								&title_li=&use_desc_for_title=1&child_of='.$this_category->cat_ID.
								"&echo=0");
								if ($this_category) {
									echo "<ul>";
									echo $this_category;
									echo "</ul>";
								}
							}
						?>
					</div><!-- end .sub-cats-->
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>

				<?php endwhile; ?>

				<?php the_posts_navigation(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
	</div><!-- .site-content-inner -->

<?php get_footer(); ?>