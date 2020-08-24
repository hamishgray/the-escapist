<?php

class nsc_bar_save_form_fields
{
    private $plugin_settings;
    private $banner_configs_obj;
    private $plugin_configs;

    public function __construct()
    {
        $this->plugin_configs = new nsc_bar_plugin_configs();
        $this->plugin_settings = $this->plugin_configs->nsc_bar_return_plugin_settings_without_db_settings();
        $this->banner_configs_obj = new nsc_bar_banner_configs();
    }

    public function nsc_bar_save_submitted_form_fields()
    {
        $tabs = $this->plugin_settings->setting_page_fields->tabs;
        $plugin_prefix = $this->plugin_settings->plugin_prefix;
        $validate = new nsc_bar_input_validation;
        $banner_settings_updated = false;

        foreach ($tabs as $tab_index => $tab) {
            foreach ($tab->tabfields as $tabfield_index => $tabfield) {
                $tabfield_slug = $plugin_prefix . $tabfield->field_slug;
                if ($tabfield->save_in_db === false && (isset($_POST[$tabfield_slug]) || isset($_POST[$tabfield_slug . "_hidden"]))) {
                    $post_value = isset($_POST[$tabfield_slug]) ? $_POST[$tabfield_slug] : $_POST[$tabfield_slug . "_hidden"];
                    $new_value = $validate->nsc_bar_validate_field_custom_save($tabfield->extra_validation_name, $post_value);
                    $this->banner_configs_obj->nsc_bar_update_banner_setting($tabfield->field_slug, $new_value, $tabfield->save_as);
                    $banner_settings_updated = true;
                }

                if ($tabfield->save_in_db === true && (isset($_POST[$tabfield_slug]) || isset($_POST[$tabfield_slug . "_hidden"]))) {
                    $post_value = isset($_POST[$tabfield_slug]) ? $_POST[$tabfield_slug] : $_POST[$tabfield_slug . "_hidden"];
                    $new_value = $validate->nsc_bar_validate_field_custom_save($tabfield->extra_validation_name, $post_value);
                    $this->plugin_configs->nsc_bar_update_option($tabfield->field_slug, $new_value);
                }
            }
        }
        $validate->return_errors_obj()->nsc_bar_display_errors();
        if ($banner_settings_updated) {
            $this->banner_configs_obj->nsc_bar_save_banner_settings();
        }
        //needed for testing
        return $this->banner_configs_obj->nsc_bar_get_banner_config_array();
    }
}
