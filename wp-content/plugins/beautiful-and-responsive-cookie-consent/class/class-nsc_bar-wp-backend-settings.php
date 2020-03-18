<?php

class nsc_bar_backendsettings extends de_nikelschubert_nsc_bar
{
    private $settings;
    private $prefix;
    private $plugin_dir;
    private $default_banner_config_file;
    private $banner_settings;
    private $banner_settings_object;

    public function __construct()
    {
        //retrieves only the hard coded settings.
        $this->plugin_dir = NSC_BAR_PLUGIN_DIR;
        $this->settings = $this->nsc_bar_return_plugin_settings_without_db_settings();
        $this->prefix = $this->settings->plugin_prefix;
        $this->banner_settings_object = new nsc_bar_cookie_configs();
    }

    public function nsc_bar_add_admin_menu()
    {
        add_options_page($this->settings->settings_page_configs->page_title, $this->settings->settings_page_configs->menu_title, $this->settings->settings_page_configs->capability, $this->settings->plugin_slug, array($this, "nsc_bar_createAdminPage"));
    }

    public function nsc_bar_execute_backend_wp_actions()
    {
        add_action('admin_init', array($this, 'nsc_bar_register_settings'));
        add_action('admin_menu', array($this, 'nsc_bar_add_admin_menu'));
    }

    public function nsc_bar_register_settings()
    {
        //settings werden mit db values angereichert
        $this->settings = $this->return_plugin_settings();
        $this->get_banner_settings_and_add_to_setting();

        foreach ($this->settings->setting_page_fields->tabs as $tab) {
            foreach ($tab->tabfields as $field) {
                $functionForValidation = "nsc_bar_validate_input";
                if ($field->extra_validation_name !== false) {
                    $functionForValidation = $field->extra_validation_name;
                }
                if ($field->save_in_db === true) {
                    register_setting($this->settings->plugin_slug . $tab->tab_slug, $this->prefix . $field->field_slug, array($this, $functionForValidation));
                }
            }
        }
    }

    public function set_and_return_banner_settings_string()
    {
        if (empty($this->banner_settings)) {
            $this->banner_settings = $this->banner_settings_object->nsc_bar_get_cookie_config();
        }
        return $this->nsc_bar_pretty_print_json_string($this->banner_settings);
    }

    private function get_banner_settings_and_add_to_setting()
    {
        foreach ($this->settings->setting_page_fields->tabs as $tabindex => $tab) {
            foreach ($tab->tabfields as $fieldindex => $field) {
                if ($field->field_slug == "bannersettings_json") {
                    //value from DB:
                    $json_config = $this->set_and_return_banner_settings_string();
                    if (empty($json_config)) {
                        add_settings_error(
                            'hasNumberError',
                            'validationError',
                            'Was not able to retrive the settings json. Should never happen. Please visit https://cookieconsent.osano.com/download/ to create one and write a support request.',
                            'error');
                    }
                    $this->settings->setting_page_fields->tabs[$tabindex]->tabfields[$fieldindex]->pre_selected_value = $json_config;
                } else if ($field->save_in_db === false) {
                    $this->settings->setting_page_fields->tabs[$tabindex]->tabfields[$fieldindex]->pre_selected_value = $this->banner_settings_object->nsc_bar_get_cookie_setting($field->field_slug, $field->pre_selected_value);
                }
            }
        }
    }
    public function nsc_bar_save_cookie()
    {
        if ($this->nsc_bar_get_option('activate_banner') == true &&
            $this->nsc_bar_get_option('backend_cookie_conversion') == true) {

            $cookie_configs = new nsc_bar_cookie_configs();
            $cookie_configs->nsc_bar_get_cookie_config();

            $cookiename = $cookie_configs->nsc_bar_get_cookie_setting("cookie_name", $this->return_settings_field_default_value("cookie_name"));
            $cookiepath = $cookie_configs->nsc_bar_get_cookie_setting("cookie_path", "/");
            $cookiedomain = $cookie_configs->nsc_bar_get_cookie_setting("cookie_domain", $this->return_settings_field_default_value("cookie_domain"));
            $cookieexpirydays = $cookie_configs->nsc_bar_get_cookie_setting("cookie_expiryDays", $this->return_settings_field_default_value("cookie_expiryDays"));
            $cookiesecure = $cookie_configs->nsc_bar_get_cookie_setting("cookie_secure", false);

            $cookie_saver = new nsc_bar_cookie_saver();
            $cookie_saver->nsc_bar_save_cookie($cookiename, $cookiepath, $cookiedomain, $cookieexpirydays, $cookiesecure);
            return true;
        }
    }

    public function nsc_bar_delete_cookie()
    {
        $cookie_configs = new nsc_bar_cookie_configs();
        $cookie_configs->nsc_bar_get_cookie_config();

        $cookiename = $cookie_configs->nsc_bar_get_cookie_setting("cookie_name", $this->return_settings_field_default_value("cookie_name"));
        $cookiepath = $cookie_configs->nsc_bar_get_cookie_setting("cookie_path", "/");
        $cookiedomain = $cookie_configs->nsc_bar_get_cookie_setting("cookie_domain", $this->return_settings_field_default_value("cookie_domain"));

        if (isset($_COOKIE[$cookiename])) {
            unset($_COOKIE[$cookiename]);
            setcookie($cookiename, "emptyvalue", time() - 3600, $cookiepath, $cookiedomain);
            //delete itp saver cookie as well, if cookie is deleted
            if (isset($_COOKIE[ITP_SAVER_COOKIE_NAME])) {
                unset($_COOKIE[ITP_SAVER_COOKIE_NAME]);
                setcookie(ITP_SAVER_COOKIE_NAME, "emptyvalue", time() - 3600, $cookiepath, $cookiedomain);
            }

        }
    }

    public function nsc_bar_cookie_cleanup()
    {
        $cookie_configs_obj = new nsc_bar_cookie_configs();
        $cookie_name = $cookie_configs_obj->nsc_bar_get_cookie_setting("cookie_name", $this->return_settings_field_default_value("cookie_name"));
        $compliance_type = $cookie_configs_obj->nsc_bar_get_cookie_setting("type", $this->return_settings_field_default_value("type"));

        if (!isset($_COOKIE[$cookie_name])) {
            return true;
        }

        $current_cookie_value = $_COOKIE[$cookie_name];

        if ($compliance_type == "info" && $current_cookie_value != "dismiss") {
            $this->nsc_bar_delete_cookie();
        }

        if ($compliance_type != "info" && in_array($current_cookie_value, array("deny", "allow")) === false) {
            $this->nsc_bar_delete_cookie();
        }

        if ($this->nsc_bar_get_option('ask_until_acceptance') == "1" && $current_cookie_value == "deny") {
            $this->nsc_bar_delete_cookie();
        }

    }

    public function nsc_bar_check_input_json_settings($input)
    {
        $input = $this->nsc_bar_validate_input($input);
        $valid = $this->nsc_bar_check_valid_json($input);
        if (empty($valid)) {
            add_settings_error(
                'hasNumberError',
                'validationError',
                'Please provide a valid json string. Data was not saved.',
                'error');
            $old_value = $this->set_and_return_banner_settings_string();
            return $old_value;
        } else {
            return $input;
        }

    }

    public function nsc_bar_createAdminPage()
    {
        $this->get_banner_settings_and_add_to_setting();
        $objSettings = $this->settings;
        require $this->plugin_dir . "/admin/tpl/admin.php";
    }

    public function nsc_bar_validate_input($input)
    {
        return $this->sanitize_input($input);
    }

    public function nsc_bar_add_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=nsc_bar-cookie-consent">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }
}
