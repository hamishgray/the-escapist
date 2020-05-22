<?php

class nsc_bar_frontend
{

    private $json_config_string;
    private $plugin_url;
    private $plugin_configs;

    public function __construct()
    {
        $this->plugin_url = NSC_BAR_PLUGIN_URL;
        $this->active_tab = "";
        $this->plugin_configs = new nsc_bar_plugin_configs();
    }

    public function nsc_bar_set_json_configs($json_config)
    {
        $this->json_config_string = $json_config;
    }

    public function nsc_bar_execute_frontend_wp_actions()
    {
        add_action('wp_head', array($this, 'nsc_bar_attachHeader'));
        add_shortcode('cc_revoke_settings_link_nsc_bar', array($this, 'nsc_bar_shortcode_revoke_settings_link'));
        add_shortcode('cc_show_cookie_banner_nsc_bar', array($this, 'nsc_bar_shortcode_show_cookie_banner'));
    }

    public function nsc_bar_attachHeader()
    {
        wp_register_style('nice-cookie-consent', $this->plugin_url . 'public/v5/cookieNSCconsent.min.css');
        wp_enqueue_style('nice-cookie-consent');
        wp_register_script('nice-cookie-consent_js', $this->plugin_url . 'public/v5/cookieNSCconsent.min.js');
        wp_enqueue_script('nice-cookie-consent_js');
        echo '<script>window.addEventListener("load", function(){window.cookieconsent.initialise(' . $this->nsc_bar_json_with_js_function() . ')});</script>';
    }

    public function nsc_bar_json_with_js_function()
    {
        $popUpCloseJsFunction = '"onPopupClose": function(){location.reload();}';
        if (is_admin()) {
            $popUpCloseJsFunction = '"onPopupClose": function(){}';
        }
        $json_config_string_with_js = str_replace(array('"onPopupClose": "1"', '"onPopupClose":"1"'), $popUpCloseJsFunction, $this->json_config_string);
        return $json_config_string_with_js;
    }

    public function nsc_bar_shortcode_show_cookie_banner()
    {
        $linktext = $this->plugin_configs->nsc_bar_get_option("shortcode_link_show_banner_text");
        return "<a id='nsc_bar_link_show_banner' style='cursor: pointer;'>" . esc_html($linktext) . "</a>";
    }

    public function nsc_bar_shortcode_revoke_settings_link()
    {
        $banner_configs_obj = new nsc_bar_banner_configs();

        $cookie_name = $banner_configs_obj->nsc_bar_get_cookie_setting("cookie_name", $this->plugin_configs->return_settings_field_default_value("cookie_name"));
        $cookie_domain = $banner_configs_obj->nsc_bar_get_cookie_setting("cookie_domain", $this->plugin_configs->return_settings_field_default_value("cookie_domain"));
        $cookie_expiry_days = $banner_configs_obj->nsc_bar_get_cookie_setting("cookie_expiryDays", $this->plugin_configs->return_settings_field_default_value("cookie_expiryDays"));
        $compliance_type = $banner_configs_obj->nsc_bar_get_cookie_setting("type", $this->plugin_configs->return_settings_field_default_value("type"));

        $link_text_opted_in = $this->plugin_configs->nsc_bar_get_option("shortcode_link_text_opted_in");
        $link_text_opted_out = $this->plugin_configs->nsc_bar_get_option("shortcode_link_text_opted_out");

        $wordpressUrl = get_bloginfo('url');
        $hostname = parse_url($wordpressUrl, PHP_URL_HOST);
        if ($hostname == "localhost") {
            $domain[0] = "localhost";
        } else {
            $hostname = $cookie_domain;
        }

        switch ($compliance_type) {
            case "opt-out":
                $current_cookie_value = "allow";
                break;
            case "opt-in":
                $current_cookie_value = "deny";
                break;
            default:
                return null;
        }

        if (isset($_COOKIE[$cookie_name]) && $current_cookie_value != "dismiss") {
            $current_cookie_value = $_COOKIE[$cookie_name];
        }

        switch ($current_cookie_value) {
            case "allow":
                $linktext = $link_text_opted_in;
                $linktext_after_click = $link_text_opted_out;
                $cookie_value_after_click = "deny";
                break;
            case "deny":
                $linktext = $link_text_opted_out;
                $linktext_after_click = $link_text_opted_in;
                $cookie_value_after_click = "allow";
                break;
            default:
                $linktext = "";
                $linktext_after_click = "";
                $cookie_value_after_click = "";
        }

        if (isset($_COOKIE[$cookie_name]) && $current_cookie_value != $_COOKIE[$cookie_name]) {
            $linktext = $atts['link_text_opted_out'];
        }

        $expire = time() + 60 * 60 * 24 * $cookie_expiry_days;
        $js_code = preg_replace("/\r|\n/", "", file_get_contents(NSC_BAR_PLUGIN_DIR . "/public/revoke_shortcode.js"));
        return "<a id='nsc_bar_optout_link' data-link_text_after_click='" . esc_attr($linktext_after_click) . "' data-link_text_before_click='" . esc_attr($linktext) . "' data-cookiename='" . esc_attr($cookie_name) . "' data-current_cookie_value='" . esc_attr($current_cookie_value) . "'data-cookie_value_after_click='" . esc_attr($cookie_value_after_click) . "' data-expires='" . esc_attr($expire) . "' data-domain='" . esc_attr($hostname) . "' style='cursor: pointer;' onclick='" . $js_code . "'>" . esc_html($linktext) . "</a>";
    }

}
