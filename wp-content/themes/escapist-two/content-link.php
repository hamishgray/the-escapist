<?php
/**
 * @package Escapist
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<a class="post-link" href="<?php echo esc_url( escapist_get_link_url() ); ?>" target="_blank"><span class="genericon genericon-link"><span class="screen-reader-text"><?php printf( __( 'External link to %s', 'escapist' ), the_title( '', '', false ) ); ?></span></span></a>

	<header class="entry-header">
		<?php
			escapist_entry_categories();
			the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( escapist_get_link_url() ) ), '</a></h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->

	<div class="entry-meta">
		<?php escapist_entry_meta(); ?>
	</div><!-- .entry-meta -->
</article><!-- #post-## -->
