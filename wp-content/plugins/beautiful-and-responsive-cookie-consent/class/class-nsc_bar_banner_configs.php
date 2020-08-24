<?php

class nsc_bar_banner_configs
{
    private $banner_config_array;
    private $banner_config_string;
    private $plugin_configs;

    public function __construct()
    {
        $this->plugin_configs = new nsc_bar_plugin_configs();
    }

    // be carefull: the states of config array and config string can be different.
    public function nsc_bar_get_banner_config_array()
    {
        if (empty($this->banner_config_array)) {
            $this->banner_config_array = $this->initialise_banner_configs("array");
        }
        return $this->banner_config_array;
    }

    public function nsc_bar_get_banner_config_string()
    {
        if (empty($this->banner_config_string)) {
            $this->banner_config_string = $this->initialise_banner_configs("string");
        }
        return $this->banner_config_string;
    }

    public function nsc_bar_update_banner_setting($field_slug, $value, $save_as)
    {

        if (empty($value) && $value !== false && $value !== "" && $value != 0) {
            return false;
        }

        $this->nsc_bar_get_banner_config_array();

        $value = $this->convert_to_save_as($value, $save_as);

        $settings_array = $this->slug_string_to_array($field_slug);
        $depth = count($settings_array);

        switch ($depth) {
            case 1:
                $this->set_level_one_value($settings_array, $value);
                break;
            case 2:
                $this->set_level_two_value($settings_array, $value);
                break;
            case 3:
                $this->set_level_three_value($settings_array, $value);
                break;
        }
    }

    public function nsc_bar_get_cookie_setting($field_slug, $default_value)
    {
        $this->nsc_bar_get_banner_config_array();
        $settings_array = $this->slug_string_to_array($field_slug);
        $depth = count($settings_array);

        switch ($depth) {
            case 1:
                $settings_value = $this->get_level_one_value($settings_array, $default_value);
                break;
            case 2:
                $settings_value = $this->get_level_two_value($settings_array, $default_value);
                break;
            case 3:
                $settings_value = $this->get_level_three_value($settings_array, $default_value);
                break;
        }
        return $settings_value;
    }

    public function nsc_bar_save_banner_settings()
    {
        $this->remove_deactivated_js_function();
        $json_string = json_encode($this->nsc_bar_get_banner_config_array());
        return $this->plugin_configs->nsc_bar_update_option("bannersettings_json", $json_string);
    }

    /*TODO: only needed for update to 2.0 remove after all updated*/
    public function nsc_bar_remove_revokeBtn()
    {
        if (isset($this->banner_config_array["revokeBtn"])) {
            unset($this->banner_config_array["revokeBtn"]);
        }
    }

    private function remove_deactivated_js_function()
    {
        if (isset($this->banner_config_array["onPopupClose"]) && $this->banner_config_array["onPopupClose"] == "0") {
            unset($this->banner_config_array["onPopupClose"]);
        }

        if (isset($this->banner_config_array["onStatusChange"]) && $this->banner_config_array["onStatusChange"] == "0") {
            unset($this->banner_config_array["onStatusChange"]);
        }

        if (isset($this->banner_config_array["dismissOnScroll"]) && empty($this->banner_config_array["dismissOnScroll"])) {
            unset($this->banner_config_array["dismissOnScroll"]);
        }

        if (isset($this->banner_config_array["dismissOnTimeout"]) && empty($this->banner_config_array["dismissOnTimeout"])) {
            unset($this->banner_config_array["dismissOnTimeout"]);
        }
        if (isset($this->banner_config_array["makeButtonsEqual"]) && empty($this->banner_config_array["makeButtonsEqual"])) {
            unset($this->banner_config_array["makeButtonsEqual"]);
        }
    }

    private function slug_string_to_array($field_slug)
    {
        $settings_array = explode("_", $field_slug);
        $depth = count($settings_array);
        if ($depth > 3 || $depth < 1) {
            throw new Exception("depth only allowed from 1 to 3 current: $depth");
        }
        return $settings_array;
    }

    private function get_level_one_value($config_array, $default = false)
    {
        $value = $default;
        if (isset($this->banner_config_array[$config_array[0]])) {
            $value = $this->banner_config_array[$config_array[0]];
        }
        return $value;
    }

    private function get_level_two_value($config_array, $default = false)
    {
        $value = $default;
        if (isset($this->banner_config_array[$config_array[0]]) && isset($this->banner_config_array[$config_array[0]][$config_array[1]])) {
            $value = $this->banner_config_array[$config_array[0]][$config_array[1]];
        }
        return $value;
    }

    private function get_level_three_value($config_array, $default = false)
    {
        $value = $default;
        if (isset($this->banner_config_array[$config_array[0]]) && isset($this->banner_config_array[$config_array[0]][$config_array[1]]) && isset($this->banner_config_array[$config_array[0]][$config_array[1]][$config_array[2]])) {
            $value = $this->banner_config_array[$config_array[0]][$config_array[1]][$config_array[2]];
        }
        return $value;
    }

    private function set_level_one_value($config_array, $value)
    {
        $this->nsc_bar_get_banner_config_array();
        $this->banner_config_array[$config_array[0]] = $value;
    }

    private function set_level_two_value($config_array, $value)
    {
        $this->nsc_bar_get_banner_config_array();
        $this->banner_config_array[$config_array[0]][$config_array[1]] = $value;
    }
    private function set_level_three_value($config_array, $value)
    {
        $this->nsc_bar_get_banner_config_array();
        $this->banner_config_array[$config_array[0]][$config_array[1]][$config_array[2]] = $value;
    }

    /*
    $return_type = "array" || "string"
     */
    private function initialise_banner_configs($return_type)
    {
        $banner_config_string = $this->plugin_configs->nsc_bar_get_option("bannersettings_json");
        $validate = new nsc_bar_input_validation;
        $banner_config_string = $validate->nsc_bar_check_valid_json_string($banner_config_string);
        if (empty($banner_config_string)) {
            $banner_config_string = $validate->nsc_bar_check_valid_json_string(file_get_contents(NSC_BAR_PLUGIN_DIR . "/public/config-default.json"));
        }

        switch ($return_type) {
            case "array":
                return json_decode($banner_config_string, true);
                break;
            case "string":
                return $banner_config_string;
                break;
            default:
                return null;
        }

    }

    private function convert_to_save_as($value, $save_as)
    {
        if ($save_as === "array") {
            return json_decode($value, true);
        }
        if ($save_as === "integer") {
            return intval($value);
        }
        return $value;
    }
}
