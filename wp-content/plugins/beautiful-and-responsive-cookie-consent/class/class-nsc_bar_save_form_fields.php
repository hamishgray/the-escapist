<?php

class nsc_bar_save_form_fields
{
    private $plugin_settings;
    private $cookieconfigs_obj;
    private $nsc_object;

    public function __construct()
    {
        $this->nsc_object = new de_nikelschubert_nsc_bar();
        $this->plugin_settings = $this->nsc_object->nsc_bar_return_plugin_settings_without_db_settings();
        $this->cookieconfigs_obj = new nsc_bar_cookie_configs();
    }

    public function nsc_bar_save_submitted_form_fields()
    {
        $tabs = $this->plugin_settings->setting_page_fields->tabs;
        $plugin_prefix = $this->plugin_settings->plugin_prefix;
        foreach ($tabs as $tab_index => $tab) {
            if ($tab->form_action == "options.php") {
                //will be handled by wp options api
                continue;
            }
            foreach ($tab->tabfields as $tabfield_index => $tabfield) {
                $tabfield_slug = $plugin_prefix . $tabfield->field_slug;
                if ($tabfield->save_in_db === false && (isset($_POST[$tabfield_slug]) || isset($_POST[$tabfield_slug . "_hidden"]))) {
                    $post_value = isset($_POST[$tabfield_slug]) ? $_POST[$tabfield_slug] : $_POST[$tabfield_slug . "_hidden"];
                    $new_value = $this->validate_field($tabfield->extra_validation_name, $post_value);
                    $this->cookieconfigs_obj->nsc_bar_set_cookie_setting($tabfield->field_slug, $new_value);
                }

                if ($tabfield->save_in_db === true && (isset($_POST[$tabfield_slug]) || isset($_POST[$tabfield_slug . "_hidden"]))) {
                    $post_value = isset($_POST[$tabfield_slug]) ? $_POST[$tabfield_slug] : $_POST[$tabfield_slug . "_hidden"];
                    $new_value = $this->validate_field($tabfield->extra_validation_name, $post_value);
                    update_option($tabfield_slug, $new_value, true);
                }
            }
        }
        return $this->cookieconfigs_obj->nsc_bar_save_cookie_settings();
    }

    private function validate_field($extra_validation_value, $input)
    {
        $return = $this->nsc_object->sanitize_input($input);
        switch ($extra_validation_value) {
            case "nsc_bar_check_input_color_code":
                $return = $this->check_input_color_code($return);
                break;
        }
        return $return;
    }

    public function nsc_bar_add_settings_errors_color_code()
    {
        add_settings_error(
            'color_code_error',
            'validationError',
            'Color could not be saved: please provide a correct hex color code, like #ffffff.',
            'error');
    }

    private function check_input_color_code($input)
    {
        $valid = preg_match("/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", $input);
        if (empty($valid) && $input != "") {
            add_action('admin_notices', array($this, 'nsc_bar_add_settings_errors_color_code'));
            $input = "";
        }
        return $input;
    }

}
