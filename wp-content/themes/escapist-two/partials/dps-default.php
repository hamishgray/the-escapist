<?php
/**
 * Default layout for Display Posts Shortcode
 *
 * @package      Escapist
 * @author       Hamish Gray
 * @since        1.0.0
**/

echo '<article class="listing-item listing-item--default hentry format-standard has-post-thumbnail">';
echo '<a class="post-thumbnail" href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(),"escapist-post-thumbnail") . '</a>';
echo '<header class="entry-header">';
echo escapist_entry_categories();
echo '<h3 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
echo '</header>';
echo '<div class="entry-summary">';
echo the_excerpt();
echo '</div>';
echo '<div class="entry-meta">';
echo escapist_entry_meta();
echo '</div>';
echo '</article>';
