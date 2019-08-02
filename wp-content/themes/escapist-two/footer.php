<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Escapist
 */
?>

</div><!-- #content -->

<div class="site-footer">

	<div class="site-footer__logo">
		<?php escapist_the_site_logo(); ?>
	</div>

	<?php get_sidebar( 'footer' ); ?>

	<?php if ( has_nav_menu( 'footer' ) ) : ?>
		<nav class="footer-navigation" role="navigation">
			<?php
				wp_nav_menu( array(
					'theme_location'  => 'footer',
					'depth'           => 1,
				) );
			?>
		</nav><!-- .footer-navigation -->
	<?php endif; ?>

	<?php if ( has_nav_menu( 'secondary' ) ) : ?>
		<nav class="bottom-navigation" role="navigation">
			<?php
				wp_nav_menu( array(
					'theme_location'  => 'secondary',
					'depth'           => 1,
				) );
			?>
		</nav><!-- .bottom-navigation -->
	<?php endif; ?>

	<?php if ( has_nav_menu( 'social' ) ) : ?>
		<nav class="social-navigation bottom-social" role="navigation">
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

	<div id="core-footer"></div>

	<hr class="hr--center hr--white20">
	<small class="text--center text--grey">© Copyright <?php echo date('Y'); ?> Secret Escapes</small>
</div><!-- .site-footer -->

	<?php wp_footer(); ?>

</div><!-- #page -->

</body>
</html>