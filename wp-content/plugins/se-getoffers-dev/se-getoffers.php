<?php
/**
* Plugin Name: SE Get UK Offers - DEV
* Description: Google Sheets data embedder for getting and displaying offer data
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
 #	 Function displays folder on shortcode base: [offers]
 # --------------------------------------------- */

// Include shortcode generation functionality
include(plugin_dir_path(__FILE__).'display.php');
// Register shortcode for content editors
add_shortcode('offers', 'offers_display');

