<?php
/**
* Plugin Name: SE Get Offers - DEV
* Description: A tool to display relevant sales on articles through a shortcode.
* Version: 1.0
* Author: Hamish Gray
* Text Domain: se-getoffers
**/



/* =============================================
 #	 Install admin options interface
 # --------------------------------------------- */

// Creates an entry on the admin menu for plugin
add_action('admin_menu', 'se_getoffers_plugin_menu');
function se_getoffers_plugin_menu() {
	add_options_page('Get SE Offers', 'Get SE Offers', 9, 'se-getoffers-settings', 'se_getoffers_display_settings');
}

// Generate options page and functionality
include(plugin_dir_path(__FILE__).'options.php');



/* =============================================
 #	 Functions for shortcode
 # --------------------------------------------- */

// Include shortcode generation functionality
include(plugin_dir_path(__FILE__).'functions.php');
// Register shortcode for content editors
add_shortcode('offers', 'offers_display');

