<?php

class de_nikelschubert_nsc_bar
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

    public function nsc_bar_check_valid_json($json_string)
    {

        $php_version_good = $this->php_version_good();
        if ($php_version_good) {
            $check = json_encode(json_decode($json_string), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            $check = json_encode(json_decode($json_string));
        }
        if (empty($check) || $check == "null") {
            return null;
        } else {
            return $check;
        }
    }

    public function nsc_bar_pretty_print_json_string($array)
    {
        $php_version_good = $this->php_version_good();
        if ($php_version_good) {
            $json_string = json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            $json_string = json_encode($array);
        }
        return $json_string;
    }

    protected function return_plugin_settings()
    {
        if (empty($this->settings_as_object)) {
            $this->settings_as_object = $this->nsc_bar_return_plugin_settings_without_db_settings();
            $this->add_current_setting_values();
            $this->add_html_description_templates();
        }
        return $this->settings_as_object;
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

    protected function php_version_good()
    {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            return true;
        } else {
            return false;
        }
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

    private function set_settings_as_object()
    {
        $this->settingsFile = NSC_BAR_PLUGIN_DIR . "/settings.json";
        $settings = file_get_contents($this->settingsFile);
        $settings = json_decode($settings);
        if (empty($settings)) {
            throw new Exception($this->settingsFile . " was not readable. Make sure it contains valid json.");
        }
        return $settings;
    }

    public function sanitize_input($input)
    {
        $cleandValue = strip_tags(stripslashes($input));
        return sanitize_text_field($cleandValue);
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
            if (strpos($this->settings_as_object->setting_page_fields->tabs[$t]->tab_description, ".html") !== false &&
                file_exists(NSC_BAR_PLUGIN_DIR . "/admin/tpl/" . $this->settings_as_object->setting_page_fields->tabs[$t]->tab_description)) {
                $tab_desc = file_get_contents(NSC_BAR_PLUGIN_DIR . "/admin/tpl/" . $this->settings_as_object->setting_page_fields->tabs[$t]->tab_description);
                $this->settings_as_object->setting_page_fields->tabs[$t]->tab_description = $tab_desc;
            }
        }
    }

    // this fuctions gets the value saved in wordpress db using get_option
    // and adds it to the settings object in the pre_selected_value field.
    // if no value is set it sets the default value from settings file.
    private function add_current_setting_values()
    {
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
                if ($this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->save_in_db === false) {
                    continue;
                }
                $option_slug = $this->settings_as_object->plugin_prefix . $this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->field_slug;
                $default_value = $this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->pre_selected_value;
                $this->settings_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->pre_selected_value = get_option($option_slug, $default_value);
            }
        }
    }
}
