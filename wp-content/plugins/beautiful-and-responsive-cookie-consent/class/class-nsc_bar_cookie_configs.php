<?php

class nsc_bar_cookie_configs
{
    private $cookie_config_array;
    private $nsc_object;

    public function __construct()
    {
        $this->nsc_object = new de_nikelschubert_nsc_bar();
    }

    public function nsc_bar_get_cookie_config()
    {
        if (empty($this->cookie_config_array)) {
            $this->cookie_config_array = $this->get_cookie_banner_configs();
        }
        $this->remove_deactivated_js_function();
        return $this->cookie_config_array;
    }

    public function nsc_bar_set_cookie_setting($field_slug, $value)
    {

        $this->nsc_bar_get_cookie_config();
        if (empty($value) && $value !== false && $value !== "" && $value != 0) {
            return false;
        }

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
        $this->nsc_bar_get_cookie_config();
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

    public function nsc_bar_save_cookie_settings()
    {
        $this->revokable_tab_creator();
        $this->remove_deactivated_js_function();
        $json_string = $this->nsc_object->nsc_bar_pretty_print_json_string($this->nsc_bar_get_cookie_config());
        return update_option("nsc_bar_bannersettings_json", $json_string, true);
    }

    private function revokable_tab_creator()
    {
        if (!isset($this->cookie_config_array["revokeBtn"])) {
            return false;
        }

        if ($this->cookie_config_array["revokeBtn"] == "1") {
            unset($this->cookie_config_array["revokeBtn"]);
        } else if ($this->cookie_config_array["revokeBtn"] == "0") {
            $this->cookie_config_array["revokeBtn"] = "<span></span>";
        }
    }

    private function remove_deactivated_js_function()
    {
        if (!isset($this->cookie_config_array["onPopupClose"])) {
            return false;
        }

        if ($this->cookie_config_array["onPopupClose"] == "0") {
            unset($this->cookie_config_array["onPopupClose"]);
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
        if (isset($this->cookie_config_array[$config_array[0]])) {
            $value = $this->cookie_config_array[$config_array[0]];
        }
        return $value;
    }

    private function get_level_two_value($config_array, $default = false)
    {
        $value = $default;
        if (isset($this->cookie_config_array[$config_array[0]]) && isset($this->cookie_config_array[$config_array[0]][$config_array[1]])) {
            $value = $this->cookie_config_array[$config_array[0]][$config_array[1]];
        }
        return $value;
    }

    private function get_level_three_value($config_array, $default = false)
    {
        $value = $default;
        if (isset($this->cookie_config_array[$config_array[0]]) && isset($this->cookie_config_array[$config_array[0]][$config_array[1]]) && isset($this->cookie_config_array[$config_array[0]][$config_array[1]][$config_array[2]])) {
            $value = $this->cookie_config_array[$config_array[0]][$config_array[1]][$config_array[2]];
        }
        return $value;
    }

    private function set_level_one_value($config_array, $value)
    {
        $this->nsc_bar_get_cookie_config();
        $this->cookie_config_array[$config_array[0]] = $value;
    }

    private function set_level_two_value($config_array, $value)
    {
        $this->nsc_bar_get_cookie_config();
        $this->cookie_config_array[$config_array[0]][$config_array[1]] = $value;
    }
    private function set_level_three_value($config_array, $value)
    {
        $this->nsc_bar_get_cookie_config();
        $this->cookie_config_array[$config_array[0]][$config_array[1]][$config_array[2]] = $value;
    }

    private function get_cookie_banner_configs()
    {
        $cookie_config_array = $this->nsc_object->nsc_bar_get_option("bannersettings_json");
        $cookie_config_array = $this->nsc_object->nsc_bar_check_valid_json($cookie_config_array);
        if (empty($cookie_config_array)) {
            $cookie_config_array = file_get_contents(NSC_BAR_PLUGIN_DIR . "/public/cookieConsentByInsites/config-default.json");
        }
        return json_decode($cookie_config_array, true);
    }
}
