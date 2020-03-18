<?php

if (defined('WP_UNINSTALL_PLUGIN') === false) {
    echo "no way";
    exit;
}

define("NSC_BAR_PLUGIN_DIR", dirname(__FILE__));
define("NSC_BAR_PLUGIN_URL", plugin_dir_url(__FILE__));

require dirname(__FILE__) . "/class/class-de-nikelschubert-nsc_bar-plugin.php";
require dirname(__FILE__) . "/class/class-nsc_bar_uninstall.php";

$uninstaller = new nsc_bar_uninstaller();
$uninstaller->nsc_bar_deleteOptions();
