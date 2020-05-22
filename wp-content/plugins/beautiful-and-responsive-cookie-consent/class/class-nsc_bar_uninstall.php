<?php

class nsc_bar_uninstaller
{

    public function nsc_bar_deleteOptions()
    {
        $plugin_configs = new nsc_bar_plugin_configs;
        $settings = $plugin_configs->nsc_bar_return_plugin_settings_without_db_settings();

        foreach ($settings->setting_page_fields->tabs as $tab) {
            foreach ($tab->tabfields as $fields) {
                $plugin_configs->nsc_bar_delete_option($fields->field_slug);
            }
        }
        delete_option(NSC_BAR_SLUG_DBVERSION);
    }
}
