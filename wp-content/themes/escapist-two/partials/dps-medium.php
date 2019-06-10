<?php
/**
 * "Small" layout for Display Posts Shortcode
 *
 * @package      Escapist
 * @author       Hamish Gray
 * @since        1.0.0
**/

echo '<article class="listing-item listing-item--medium hentry format-standard has-post-thumbnail">';
echo '<div class="listing-item__box">';
echo '<a class="listing-item__link" href="' . get_permalink() . '"></a>';
echo '<div class="listing-item__image">';
echo get_the_post_thumbnail( get_the_ID(),"escapist-post-group-thumbnail");
echo '</div>';
echo '<div class="listing-item__content">';
echo '<header class="entry-summary">';
echo escapist_entry_categories();
echo '<h3 class="entry-title">' . get_the_title() . '</h3>';
echo '</header>';
echo '<div class="entry-meta listing-item__content-footer">';
echo escapist_entry_meta();
echo '</div></div>';
echo '</div>';
echo '</article>';
