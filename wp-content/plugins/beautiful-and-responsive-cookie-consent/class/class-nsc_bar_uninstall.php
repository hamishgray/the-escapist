<?php

class nsc_bar_uninstaller
{

    public function nsc_bar_deleteOptions()
    {
        $plugin = new de_nikelschubert_nsc_bar;
        $settings = $plugin->nsc_bar_return_plugin_settings_without_db_settings();
        $prefix = $settings->plugin_prefix;
        foreach ($settings->setting_page_fields->tabs as $tab) {
            foreach ($tab->tabfields as $fields) {
                delete_option($prefix . $fields->field_slug);
            }
        }
    }

    private function removeDirectory($path)
    {
        // The preg_replace is necessary in order to traverse certain types of folder paths (such as /dir/[[dir2]]/dir3.abc#/)
        // The {,.}* with GLOB_BRACE is necessary to pull all hidden files (have to remove or get "Directory not empty" errors)
        $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path) . '/{,.}*', GLOB_BRACE);
        foreach ($files as $file) {
            if ($file == $path . '/.' || $file == $path . '/..') {continue;} // skip special dir entries
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }
}
