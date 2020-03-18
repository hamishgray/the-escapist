<?php
/*
Plugin Name: Beautiful and responsive cookie consent
Description: An easy way to get a beautiful GDPR Cookie Consent Banner. Customize it to match your compliance requirements and website layout. Highly customisable and responsive.
Author: Nikel Schubert
Version: 1.7.1
Author URI: https://nikelschubert.de/
Text Domain: bar-cookie-consent
License:     GPLv3

Beautiful and responsive cookie consent is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Beautiful and responsive cookie consent is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Beautiful and responsive cookie consent. If not, see {License URI}.
 */

if (!defined('ABSPATH')) {
    exit;
}
define("NSC_BAR_PLUGIN_DIR", dirname(__FILE__));
define("NSC_BAR_PLUGIN_URL", plugin_dir_url(__FILE__));
define("ITP_SAVER_COOKIE_NAME", "nsc_bar_cs_done");

require dirname(__FILE__) . "/class/class-de-nikelschubert-nsc_bar-plugin.php";
require dirname(__FILE__) . "/class/class-nsc_bar_cookie_configs.php";
require dirname(__FILE__) . "/class/class-nsc_bar-cookie-consent.php";
require dirname(__FILE__) . "/class/class-nsc_bar_save_form_fields.php";
require dirname(__FILE__) . "/class/class-nsc_bar-wp-backend-settings.php";
require dirname(__FILE__) . "/class/class-nsc_bar_cookie_saver.php";

//backend action
$save_formfields = new nsc_bar_save_form_fields();
$save_formfields->nsc_bar_save_submitted_form_fields();

$nsc_bar_backendpage = new nsc_bar_backendsettings;
$nsc_bar_backendpage->nsc_bar_cookie_cleanup();
add_action('get_header', array($nsc_bar_backendpage, 'nsc_bar_save_cookie'));

$nsc_bar_backendpage->nsc_bar_execute_backend_wp_actions();

add_filter("plugin_action_links_" . plugin_basename(__FILE__), array($nsc_bar_backendpage, 'nsc_bar_add_settings_link'));

add_action('admin_enqueue_scripts', 'nsc_bar_enqueue_admin_script_on_cookie_page');
function nsc_bar_enqueue_admin_script_on_cookie_page($hook)
{
    $nsc_bar_backendpage = new nsc_bar_backendsettings;
    if ($hook == 'settings_page_nsc_bar-cookie-consent' && $nsc_bar_backendpage->nsc_bar_get_option('activate_test_banner') == true) {
        $nsc_bar_backendpage->nsc_bar_delete_cookie();
        $nsc_bar_frontend_banner = new nsc_bar_cookie_consent();
        $nsc_bar_frontend_banner->nsc_bar_set_json_configs($nsc_bar_backendpage->set_and_return_banner_settings_string());
        $nsc_bar_frontend_banner->nsc_bar_attachHeader();
    }
}

//frontend action

if ($nsc_bar_backendpage->nsc_bar_get_option('activate_banner') == true) {
    $nsc_bar_frontend_banner = new nsc_bar_cookie_consent();
    $nsc_bar_frontend_banner->nsc_bar_set_json_configs($nsc_bar_backendpage->set_and_return_banner_settings_string());
    $nsc_bar_frontend_banner->nsc_bar_execute_frontend_wp_actions();
}
