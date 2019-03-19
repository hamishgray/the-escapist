<?php
/*
Plugin Name: UserAgent Content Switcher
Plugin URI: https://wordpress.org/plugins/useragent-content-switcher/
Version: 2.38
Description: Display the html written between the shortcode of each user agent.
Author: Katsushi Kawamori
Author URI: https://riverforest-wp.info/
Text Domain: useragent-content-switcher
Domain Path: /languages
*/

/*  Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

	add_action( 'plugins_loaded', 'useragent_content_switcher_load_textdomain' );
	function useragent_content_switcher_load_textdomain() {
		load_plugin_textdomain('useragent-content-switcher');
	}

	if(!class_exists('UserAgentContentSwitcherRegist')) require_once( dirname(__FILE__).'/lib/UserAgentContentSwitcherRegist.php' );
	if(!class_exists('UserAgentContentSwitcherAdmin')) require_once( dirname(__FILE__).'/lib/UserAgentContentSwitcherAdmin.php' );
	if(!class_exists('UserAgentContentSwitcher')) require_once( dirname(__FILE__).'/lib/UserAgentContentSwitcher.php' );

?>