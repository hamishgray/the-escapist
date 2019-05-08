<?php
/**
 * The template used for displaying content in page-home.php
 *
 * @package Escapist
 */
?>


	<div class="home-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'escapist' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
