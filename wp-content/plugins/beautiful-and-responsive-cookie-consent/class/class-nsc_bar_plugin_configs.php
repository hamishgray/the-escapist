<?php

class nsc_bar_plugin_configs
{
    private $settingsFile;
    private $settings_as_object;
    private $settings_as_object_without_db;
    private $active_tab;

    public function nsc_bar_get_option($option_slug)
    {
        $option_value = false;
        $settings_for_options = $this->nsc_bar_return_plugin_settings_without_db_settings();
        foreach ($settings_for_options->setting_page_fields->tabs as $tab) {
            foreach ($tab->tabfields as $field) {
                if ($field->field_slug == $option_slug) {
                    $option_value = get_option($settings_for_options->plugin_prefix . $option_slug, $field->pre_selected_value);
                    break;
                }
            }
        }
        return $option_value;
    }

    public function nsc_bar_update_option($option_name, $option_value)
    {
        $settings_for_options = $this->nsc_bar_return_plugin_settings_without_db_settings();
        $option_name_with_prefix = $settings_for_options->plugin_prefix . $option_name;
        return update_option($option_name_with_prefix, $option_value, true);
    }

    public function nsc_bar_delete_option($option_name)
    {
        $settings_for_options = $this->nsc_bar_return_plugin_settings_without_db_settings();
        $option_name_with_prefix = $settings_for_options->plugin_prefix . $option_name;
        delete_option($option_name_with_prefix);
    }

    public function nsc_bar_get_running_crawlrequest_datetime()
    {
        $settings_for_options = $this->nsc_bar_return_plugin_settings_without_db_settings();
        return get_option($settings_for_options->plugin_prefix . "running_crawl_request", false);
    }

    public function nsc_bar_return_cookie_table_name()
    {
        global $wpdb;
        $settings_for_options = $this->nsc_bar_return_plugin_settings_without_db_settings();
        return $wpdb->prefix . $settings_for_options->plugin_prefix . "cookies";
    }

    public function return_plugin_settings()
    {
        if (empty($this->settings_as_object)) {
            $this->settings_as_object = $this->nsc_bar_return_plugin_settings_without_db_settings();
            $this->add_current_setting_values();
            $this->add_html_description_templates();
            $this->add_bannersettings_json();
        }
        return $this->settings_as_object;
    }

    public function nsc_bar_plugin_prefix()
    {
        $this->nsc_bar_return_plugin_settings_without_db_settings();
        return $this->settings_as_object_without_db->plugin_prefix;
    }

    public function return_settings_field($searched_field_slug)
    {
        $this->return_plugin_settings();
        foreach ($this->settings_as_object->setting_page_fields->tabs as $tab) {
            $number_of_fields = count($tab->tabfields);
            for ($i = 0; $i < $number_of_fields; $i++) {
                if ($tab->tabfields[$i]->field_slug == $searched_field_slug) {
                    return $tab->tabfields[$i];
                }
            }
        }
    }

    public function return_settings_field_default_value($searched_field_slug)
    {
        $settings_field = $this->return_settings_field($searched_field_slug);
        return $settings_field->pre_selected_value;
    }

    public function nsc_bar_replace_variables_in_config($varname, $replace_value)
    {
        $configs = $this->return_plugin_settings();
        $configs_string = json_encode($configs);
        $configs_string = str_replace("{{" . $varname . "}}", $replace_value, $configs_string);
        $this->settings_as_object = json_decode($configs_string);
    }

    public function nsc_bar_return_plugin_settings_without_db_settings()
    {
        if (empty($this->settings_as_object_without_db)) {
            $this->settings_as_object_without_db = $this->set_settings_as_object();
            if (empty($this->settings_as_object_without_db)) {
                throw new Exception($this->settingsFile . " was not readable. Make sure it contains valid json.");
            }
        }
        return $this->settings_as_object_without_db;
    }

    public function nsc_bar_unixtimestamp_to_date($unix_timestamp, $dateformat = null, $timeformat = null)
    {
        if (empty($dateformat)) {
            $dateformat = get_option('date_format');
        }

        if (empty($timeformat)) {
            $timeformat = get_option('time_format');
        }
        return date_i18n($dateformat . " " . $timeformat, $unix_timestamp + get_option('gmt_offset') * 3600);
    }

    private function set_settings_as_object()
    {
        $this->settingsFile = NSC_BAR_PLUGIN_DIR . "/plugin-configs.json";
        $settings = file_get_contents($this->settingsFile);
        $settings = json_decode($settings);
        if (empty($settings)) {
            throw new Exception($this->settingsFile . " was not readable. Make sure it contains valid json.");
        }
        return $settings;
    }

    private function get_active_tab()
    {
        $this->active_tab = "";
        if (isset($_GET["tab"])) {
            $this->active_tab = $_GET["tab"];
        } else {
            $this->active_tab = $this->settings_as_object->setting_page_fields->tabs[0]->tab_slug;
        }
    }

    private function add_html_description_templates()
    {
        $number_of_tabs = count($this->settings_as_object->setting_page_fields->tabs);
        if (strpos($this->settings_as_object->settings_page_configs->description, ".html") !== false &&
            file_exists(NSC_BAR_PLUGIN_DIR . "/admin/tpl/" . $this->settings_as_object->settings_page_configs->description)) {
            $desc = file_get_contents(NSC_BAR_PLUGIN_DIR . "/admin/tpl/" . $this->settings_as_object->settings_page_configs->description);
            $this->settings_as_object->settings_page_configs->description = $desc;
        }
        for ($t = 0; $t < $number_of_tabs; $t++) {
            $this->settings_as_object->setting_page_fields->tabs[$t]->tab_description = $this->get_tab_description_template($t);
        }
    }

    private function get_tab_description_template($t)
    {
        if (!strpos($this->settings_as_object->setting_page_fields->tabs[$t]->tab_description, ".html") !== false ||
            !file_exists(NSC_BAR_PLUGIN_DIR . "/admin/tpl/" . $this->settings_as_object->setting_page_fields->tabs[$t]->tab_description)) {
            return $this->settings_as_object->setting_page_fields->tabs[$t]->tab_description;
        }

        $tab_description = file_get_contents(NSC_BAR_PLUGIN_DIR . "/admin/tpl/" . $this->settings_as_object->setting_page_fields->tabs[$t]->tab_description);
        return $tab_description;
    }

    // this fuctions gets the value saved in wordpress db using get_option
    // and adds it to the settings object in the pre_selected_value field.
    // if no value is set it sets the default value from settings file.
    private function add_current_setting_values()
    {
        $banner_configs = new nsc_bar_banner_configs;

        $this->get_active_tab();
        $this->settings_as_object->setting_page_fields->active_tab_slug = $this->active_tab;
        $numper_of_tabs = count($this->settings_as_object->setting_page_fields->tabs);
        for ($t = 0; $t < $numper_of_tabs; $t++) {
            $numper_of_fields_in_this_tab = count($this->settings_as_object->setting_page_fields->tabs[$t]->tabfields);
            if ($this->active_tab == $this->settings_as_object->setting_page_fields->tabs[$t]->tab_slug) {
                $this->settings_as_object->setting_page_fields->tabs[$t]->active = true;
                $this->settings_as_object->setting_page_fields->active_tab_index = $t;
            }
            for ($f = 0; $f < $numper_of_fields_in_this_tab; $f++) {
                $default_value = $this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->pre_selected_value;
                $field_slug_without_prefix = $this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->field_slug;
                if ($this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->save_in_db === false) {
                    $this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->pre_selected_value = $banner_configs->nsc_bar_get_cookie_setting($field_slug_without_prefix, $default_value);
                    continue;
                }

                $field_slug = $this->settings_as_object->plugin_prefix . $field_slug_without_prefix;
                $this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->pre_selected_value = get_option($field_slug, $default_value);
            }
        }
    }

    private function add_bannersettings_json()
    {
        $banner_configs = new nsc_bar_banner_configs;

        foreach ($this->settings_as_object->setting_page_fields->tabs as $tabindex => $tab) {
            foreach ($tab->tabfields as $fieldindex => $field) {
                if ($field->field_slug == "bannersettings_json") {
                    //value from DB or default file:
                    $json_config = $banner_configs->nsc_bar_get_banner_config_string();
                    $this->settings_as_object->setting_page_fields->tabs[$tabindex]->tabfields[$fieldindex]->pre_selected_value = $json_config;
                    break (2);
                }
            }
        }
    }
}
