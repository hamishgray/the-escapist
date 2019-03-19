<?php
/**
 * UserAgent Content Switcher
 * 
 * @package    UserAgent Content Switcher
 * @subpackage UserAgentContentSwitcherAdmin Management screen
    Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$useragentcontentswitcheradmin = new UserAgentContentSwitcherAdmin();

class UserAgentContentSwitcherAdmin {

	/* ==================================================
	 * Construct
	 * @since	2.37
	 */
	public function __construct() {

		add_action( 'admin_menu', array($this, 'plugin_menu'));
		add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style') );
		add_filter( 'plugin_action_links', array($this, 'settings_link'), 10, 2 );
		add_action( 'admin_print_footer_scripts', array($this, 'useragentcontentswitcher_add_quicktags'));

	}

	/* ==================================================
	 * Add a "Settings" link to the plugins page
	 * @since	1.0
	 */
	public function settings_link($links, $file) {
		static $this_plugin;
		if ( empty($this_plugin) ) {
			$this_plugin = 'useragent-content-switcher/useragentcontentswitcher.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="'.admin_url('options-general.php?page=UserAgentContentSwitcher').'">'.__( 'Settings').'</a>';
		}
		return $links;
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	public function plugin_menu() {
		add_options_page( 'UserAgentContentSwitcher Options', 'UserAgentContentSwitcher', 'manage_options', 'UserAgentContentSwitcher', array($this, 'plugin_options') );
	}

	/* ==================================================
	 * Add Css and Script
	 * @since	2.0
	 */
	public function load_custom_wp_admin_style() {
		if ($this->is_my_plugin_screen()) {
			wp_enqueue_style( 'jquery-responsiveTabs', plugin_dir_url( __DIR__ ).'css/responsive-tabs.css' );
			wp_enqueue_style( 'jquery-responsiveTabs-style', plugin_dir_url( __DIR__ ).'css/style.css' );
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'jquery-responsiveTabs', plugin_dir_url( __DIR__ ).'js/jquery.responsiveTabs.min.js' );
			wp_enqueue_script( 'useragentcontentswitcher-js', plugin_dir_url( __DIR__ ).'js/jquery.useragentcontentswitcher.js', array('jquery') );
		}
	}

	/* ==================================================
	 * For only admin style
	 * @since	2.3
	 */
	private function is_my_plugin_screen() {
		$screen = get_current_screen();
		if (is_object($screen) && $screen->id == 'settings_page_UserAgentContentSwitcher') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	public function plugin_options() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		?>
		<div class="wrap">
			<h2>UserAgentContentSwitcher</h2>
			<div id="useragentcontentswitcher-tabs">
				<ul>
				<li><a href="#useragentcontentswitcher-tabs-1"><?php _e('Settings'); ?></a></li>
				<li><a href="#useragentcontentswitcher-tabs-2"><?php _e('How to use', 'useragent-content-switcher'); ?></a></li>
				<li><a href="#useragentcontentswitcher-tabs-3"><?php _e('Donate to this plugin &#187;'); ?></a></li>
				</ul>
				<div id="useragentcontentswitcher-tabs-1">
					<div class="wrap">

					<form method="post" action="options.php">

						<?php settings_fields('useragentcontentswitcher-settings-group'); ?>

						<h2><?php _e('Device Settings', 'useragent-content-switcher') ?></h2>

						<div style="padding:10px;border:#CCC 2px solid; margin:0 0 20px 0">
							<div style="display:block"><?php _e('Tablet', 'useragent-content-switcher'); ?></div>
							<div style="display:block;padding:20px 0">
							<?php _e('Short Code Attribute', 'useragent-content-switcher'); ?><code>ua='tb'</code>
							</div>
							<div><?php _e('User Agent[Regular expression is possible.]', 'useragent-content-switcher'); ?></div>
							<div style="display:block">
							<textarea id="useragentcontentswitcher_useragent_tb" name="useragentcontentswitcher_useragent_tb" rows="4" style="width: 100%;"><?php echo get_option('useragentcontentswitcher_useragent_tb') ?></textarea>
							</div>
							<div style="clear:both"></div>
						</div>

						<div style="padding:10px; border:#CCC 2px solid">
							<div style="display:block"><?php _e('Smartphone', 'useragent-content-switcher'); ?></div>
							<div style="display:block;padding:20px 0">
							<?php _e('Short Code Attribute', 'useragent-content-switcher'); ?><code>ua='sp'</code>
							</div>
							<div><?php _e('User Agent[Regular expression is possible.]', 'useragent-content-switcher'); ?></div>
							<div style="display:block">
							<textarea id="useragentcontentswitcher_useragent_sp" name="useragentcontentswitcher_useragent_sp" rows="4" style="width: 100%;"><?php echo get_option('useragentcontentswitcher_useragent_sp') ?></textarea>
							</div>
							<div style="clear:both"></div>
						</div>

						<div style="margin:20px 0; padding:10px; border:#CCC 2px solid">
							<div style="display:block"><?php _e('Featurephone', 'useragent-content-switcher'); ?></div>
							<div style="display:block;padding:20px 0">
							<?php _e('Short Code Attribute', 'useragent-content-switcher'); ?><code>ua='mb'</code>
							</div>
							<div><?php _e('User Agent[Regular expression is possible.]', 'useragent-content-switcher'); ?></div>
							<div style="display:block">
							<textarea id="useragentcontentswitcher_useragent_mb" name="useragentcontentswitcher_useragent_mb" rows="4" style="width: 100%;"><?php echo get_option('useragentcontentswitcher_useragent_mb') ?></textarea>
							</div>
							<div style="clear:both"></div>
						</div>

						<div style="clear:both"></div>

						<?php submit_button( __('Save Changes'), 'primary', 'Submit', FALSE ); ?>

					</form>

			  		</div>
		  		</div>

				<div id="useragentcontentswitcher-tabs-2">
					<div class="wrap">
						<h2><?php _e('How to use', 'useragent-content-switcher'); ?></h2>
						<div style="padding:10px;"><?php _e('Please add new Page. Please write a short code in the text field of the Page. Please go in Text mode this task.', 'useragent-content-switcher'); ?></div>
						<div style="padding:10px;"><?php _e('"UserAgent Content Switcher" activation, you to include additional buttons for Shortcode in the Text (HTML) mode of the WordPress editor.', 'useragent-content-switcher'); ?></div>
						<div style="padding:10px;">
						<div><code>[agentsw]</code></div>
						<div style="padding:10px;"><?php _e('Please code the HTML for the PC here.', 'useragent-content-switcher'); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='pc']</code></div>
						<div style="padding:10px;"><?php _e('Please code the HTML for the PC here.', 'useragent-content-switcher'); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='tb']</code></div>
						<div style="padding:10px;"><?php _e('Please code the HTML for the Tablet here.', 'useragent-content-switcher'); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='sp']</code></div>
						<div style="padding:10px;"><?php _e('Please code the HTML for the Smartphone here.', 'useragent-content-switcher'); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='mb']</code></div>
						<div style="padding:10px;"><?php _e('Please code the HTML for the Featurephone here.', 'useragent-content-switcher'); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
					</div>
				</div>

				<div id="useragentcontentswitcher-tabs-3">
				<div class="wrap">
				<?php $this->credit(); ?>
				</div>
				</div>

			</div>
		</div>
		<?php
	}

	/* ==================================================
	 * Credit
	 */
	private function credit() {

		$plugin_name = NULL;
		$plugin_ver_num = NULL;
		$plugin_path = plugin_dir_path( __DIR__ );
		$plugin_dir = untrailingslashit($plugin_path);
		$slugs = explode('/', $plugin_dir);
		$slug = end($slugs);
		$files = scandir($plugin_dir);
		foreach ($files as $file) {
			if($file == '.' || $file == '..' || is_dir($plugin_path.$file)){
				continue;
			} else {
				$exts = explode('.', $file);
				$ext = strtolower(end($exts));
				if ( $ext === 'php' ) {
					$plugin_datas = get_file_data( $plugin_path.$file, array('name'=>'Plugin Name', 'version' => 'Version') );
					if ( array_key_exists( "name", $plugin_datas ) && !empty($plugin_datas['name']) && array_key_exists( "version", $plugin_datas ) && !empty($plugin_datas['version']) ) {
						$plugin_name = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __('Version:').' '.$plugin_ver_num;
		$faq = __('https://wordpress.org/plugins/'.$slug.'/faq', $slug);
		$support = 'https://wordpress.org/support/plugin/'.$slug;
		$review = 'https://wordpress.org/support/view/plugin-reviews/'.$slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/'.$slug;
		$facebook = 'https://www.facebook.com/katsushikawamori/';
		$twitter = 'https://twitter.com/dodesyo312';
		$youtube = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate = __('https://riverforest-wp.info/donate/', $slug);

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo $plugin_version; ?> | 
		<a style="text-decoration: none;" href="<?php echo $faq; ?>" target="_blank"><?php _e('FAQ'); ?></a> | <a style="text-decoration: none;" href="<?php echo $support; ?>" target="_blank"><?php _e('Support Forums'); ?></a> | <a style="text-decoration: none;" href="<?php echo $review; ?>" target="_blank"><?php _e('Reviews', 'media-from-ftp'); ?></a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo $translate; ?>" target="_blank"><?php echo sprintf(__('Translations for %s'), $plugin_name); ?></a> | <a style="text-decoration: none;" href="<?php echo $facebook; ?>" target="_blank"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo $twitter; ?>" target="_blank"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo $youtube; ?>" target="_blank"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php _e('Please make a donation if you like my work or would like to further the development of this plugin.', $slug); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo $donate; ?>')"><?php _e('Donate to this plugin &#187;'); ?></button>
		</div>

		<?php

	}

	/* ==================================================
	 * Add Quick Tag
	 * @since	2.35
	 */
	public function useragentcontentswitcher_add_quicktags() {
		if (wp_script_is('quicktags')){
	?>
		<script type="text/javascript">
			QTags.addButton( 'useragentcontentswitcher', 'agentsw', '[agentsw]', '[/agentsw]' );
		</script>
	<?php
		}
	}

}

?>