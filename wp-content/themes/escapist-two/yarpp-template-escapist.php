<?php
/*
YARPP Template: Escapist
Description: Custom YARPP template for The Escapist
Author: Hamish Gray
*/ ?>

<?php if (have_posts()):?>

<hr>
<h2>Related posts</h2>
<div class="related">
	<?php while (have_posts()) : the_post(); ?>
		<?php if (has_post_thumbnail()):?>
			<div class="related__col">
				<a class="related__post" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail("escapist-post-group-thumbnail"); ?>
					<div class="related__post-content">
						<p class="related__post-title"><?php the_title(); ?></p>
						<p class="related__post-author">By: <strong><?php the_author(); ?></strong></p>
					</div>
				</a>
			</div>
		<?php endif; ?>
	<?php endwhile; ?>
</div>

<?php endif; ?>
