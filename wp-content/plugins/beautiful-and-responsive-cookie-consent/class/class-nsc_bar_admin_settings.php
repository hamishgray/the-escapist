<?php

class nsc_bar_admin_settings
{
    private $settings;
    private $default_banner_config_file;
    private $plugin_configs;

    public function __construct()
    {
        //retrieves only the hard coded settings.
        $this->plugin_configs = new nsc_bar_plugin_configs;
        $this->settings = $this->plugin_configs->nsc_bar_return_plugin_settings_without_db_settings();
    }

    public function nsc_bar_add_admin_menu()
    {
        add_options_page($this->settings->settings_page_configs->page_title, $this->settings->settings_page_configs->menu_title, $this->settings->settings_page_configs->capability, $this->settings->plugin_slug, array($this, "nsc_bar_createAdminPage"));
    }

    public function nsc_bar_execute_backend_wp_actions()
    {
        add_action('admin_menu', array($this, 'nsc_bar_add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'nsc_bar_enqueue_script_on_admin_page'));
        add_action('admin_enqueue_scripts', array($this, 'nsc_bar_enqueue_styles_on_admin_page'));
        add_action('admin_enqueue_scripts', array($this, 'nsc_bar_enqueue_admin_preview_banner'), 90);
    }

    public function nsc_bar_enqueue_script_on_admin_page($hook)
    {
        if ($hook == 'settings_page_nsc_bar-cookie-consent') {
            wp_enqueue_script('nsc_bar_cookietypes_js', NSC_BAR_PLUGIN_URL . '/admin/js/cookietypes.v2.js', array(), '6');
        }
    }

    public function nsc_bar_enqueue_styles_on_admin_page($hook)
    {
        if ($hook == 'settings_page_nsc_bar-cookie-consent') {
            wp_enqueue_style('nsc_bar_admin_styles', NSC_BAR_PLUGIN_URL . '/admin/css/nsc_bar_admin.css', array(), '6');
        }
    }

    public function nsc_bar_enqueue_admin_preview_banner($hook)
    {
        if ($this->show_preview($hook)) {
            $nsc_bar_frontend_banner = new nsc_bar_frontend();
            $nsc_bar_banner_config = new nsc_bar_banner_configs();
            $nsc_bar_frontend_banner->nsc_bar_set_json_configs($nsc_bar_banner_config);
            $nsc_bar_frontend_banner->nsc_bar_enqueue_scripts();
            $nsc_bar_frontend_banner->nsc_bar_attachFooter();
        }
    }

    public function nsc_bar_createAdminPage()
    {
        $objSettings = $this->plugin_configs->return_plugin_settings();
        $form_fields = new nsc_bar_html_formfields;
        require NSC_BAR_PLUGIN_DIR . "/admin/tpl/admin.php";
    }

    public function nsc_bar_add_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=nsc_bar-cookie-consent">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    private function show_preview($hook)
    {
        if ($hook == 'settings_page_nsc_bar-cookie-consent' && $this->plugin_configs->nsc_bar_get_option('activate_test_banner') == true) {
            return true;
        }
        return false;
    }
}
