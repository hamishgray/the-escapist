<?php
/**
 * UserAgent Content Switcher
 *
 * @package    UserAgent Content Switcher
 * @subpackage UserAgentContentSwitcherAdmin Management screen
/*
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

/** ==================================================
 * Management screen
 *
 * @since 1.00
 */
class UserAgentContentSwitcherAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 2.37
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_settings' ) );

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
		add_action( 'admin_print_footer_scripts', array( $this, 'useragentcontentswitcher_add_quicktags' ) );

	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'useragent-content-switcher/useragentcontentswitcher.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=UserAgentContentSwitcher' ) . '">' . __( 'Settings' ) . '</a>';
		}
		return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_options_page( 'UserAgentContentSwitcher Options', 'UserAgentContentSwitcher', 'manage_options', 'UserAgentContentSwitcher', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Add Css and Script
	 *
	 * @since 2.00
	 */
	public function load_custom_wp_admin_style() {
		if ( $this->is_my_plugin_screen() ) {
			wp_enqueue_style( 'jquery-responsiveTabs', plugin_dir_url( __DIR__ ) . 'css/responsive-tabs.css', array(), '1.4.0' );
			wp_enqueue_style( 'jquery-responsiveTabs-style', plugin_dir_url( __DIR__ ) . 'css/style.css', array(), '1.4.0' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-responsiveTabs', plugin_dir_url( __DIR__ ) . 'js/jquery.responsiveTabs.min.js', array(), '1.4.0', false );
			wp_enqueue_script( 'useragentcontentswitcher-js', plugin_dir_url( __DIR__ ) . 'js/jquery.useragentcontentswitcher.js', array( 'jquery' ), '1.00', false );
		}
	}

	/** ==================================================
	 * For only admin style
	 *
	 * @since 2.30
	 */
	private function is_my_plugin_screen() {
		$screen = get_current_screen();
		if ( is_object( $screen ) && 'settings_page_UserAgentContentSwitcher' == $screen->id ) {
			return true;
		} else {
			return false;
		}
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'options-general.php?page=UserAgentContentSwitcher' );
		$ua_switch = get_option( 'uac_switcher' );

		?>
		<div class="wrap">
			<h2>UserAgent Content Switcher</h2>
			<div id="useragentcontentswitcher-tabs">
				<ul>
				<li><a href="#useragentcontentswitcher-tabs-1"><?php esc_html_e( 'Settings' ); ?></a></li>
				<li><a href="#useragentcontentswitcher-tabs-2"><?php esc_html_e( 'How to use', 'useragent-content-switcher' ); ?></a></li>
				<li><a href="#useragentcontentswitcher-tabs-3"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></a></li>
				</ul>
				<div id="useragentcontentswitcher-tabs-1">
					<div class="wrap">

						<h3><?php esc_html_e( 'Type editing', 'useragent-content-switcher' ); ?></h3>
						<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
						<?php wp_nonce_field( 'uac_set', 'uac_switcher_set' ); ?>
						<?php
						foreach ( $ua_switch as $key => $value ) {
							?>
							<details style="margin-bottom: 5px;">
							<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><input type="checkbox" name="del_uas[<?php echo esc_attr( $key ); ?>]"><?php echo esc_html( $value['name'] ); ?></summary>
								<div style="display:block;padding:20px 0">
								<?php esc_html_e( 'Short Code Attribute', 'useragent-content-switcher' ); ?><code>ua='<?php echo esc_html( $key ); ?>'</code>
								</div>
								<div><?php esc_html_e( 'User Agent[Regular expression is possible.]', 'useragent-content-switcher' ); ?></div>
								<div style="display:block">
								<textarea name="uac_useragent_<?php echo esc_attr( $key ); ?>" rows="4" style="width: 100%;"><?php echo esc_textarea( $value['agent'] ); ?></textarea>
								</div>
							</details>
							<?php
						}
						?>
						<?php submit_button( __( 'Save Changes' ), 'large', 'Manageset', false ); ?>
						&nbsp;
						<?php submit_button( __( 'Default' ), 'large', 'Default', false ); ?>
						&nbsp;
						<?php submit_button( __( 'Delete checked types', 'useragent-content-switcher' ), 'large', 'Deletetype', false ); ?>
						<p class="description">
						<?php esc_html_e( 'If you delete them all, they will return to their default state.', 'useragent-content-switcher' ); ?>
						</p>
						</form>
						<hr>
						<h3><?php esc_html_e( 'Add Type', 'useragent-content-switcher' ); ?></h3>
						<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
						<?php wp_nonce_field( 'uac_add', 'uac_switcher_add' ); ?>
						<div><?php esc_html_e( 'Type name', 'useragent-content-switcher' ); ?> : <input type="text" name="uac_name"></div>
						<div><?php esc_html_e( 'Short Code Attribute', 'useragent-content-switcher' ); ?> : <input type="text" name="uac_attr"></div>
						<div><?php esc_html_e( 'User Agent[Regular expression is possible.]', 'useragent-content-switcher' ); ?></div>
						<div style="display:block">
						<textarea name="uac_useragent_new_type" rows="4" style="width: 100%;"></textarea>
						</div>
						<?php submit_button( __( 'Add type', 'useragent-content-switcher' ), 'large', 'Addtype', false ); ?>
						</form>
					</div>
				</div>

				<div id="useragentcontentswitcher-tabs-2">
					<div class="wrap">
						<h2><?php esc_html_e( 'How to use', 'useragent-content-switcher' ); ?></h2>
						<div style="padding:10px;"><?php esc_html_e( 'Please add new Page. Please write a short code in the text field of the Page. Please go in Text mode this task.', 'useragent-content-switcher' ); ?></div>
						<div style="padding:10px;"><?php esc_html_e( '"UserAgent Content Switcher" activation, you to include additional buttons for Shortcode in the Text (HTML) mode of the WordPress editor.', 'useragent-content-switcher' ); ?></div>
						<div style="padding:10px;">
						<div><code>[agentsw]</code></div>
						<div style="padding:10px;"><?php esc_html_e( 'Please code the HTML for the PC here.', 'useragent-content-switcher' ); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='pc']</code></div>
						<div style="padding:10px;"><?php esc_html_e( 'Please code the HTML for the PC here.', 'useragent-content-switcher' ); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='tb']</code></div>
						<div style="padding:10px;"><?php esc_html_e( 'Please code the HTML for the Tablet here.', 'useragent-content-switcher' ); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='sp']</code></div>
						<div style="padding:10px;"><?php esc_html_e( 'Please code the HTML for the Smartphone here.', 'useragent-content-switcher' ); ?></div>
						<div><code>[/agentsw]</code></div>
						</div>
						<div style="padding:10px;">
						<div><code>[agentsw ua='mb']</code></div>
						<div style="padding:10px;"><?php esc_html_e( 'Please code the HTML for the Featurephone here.', 'useragent-content-switcher' ); ?></div>
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

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( $plugin_path );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( esc_html__( 'https://wordpress.org/plugins/%s/faq', '%s' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = sprintf( esc_html__( 'https://shop.riverforest-wp.info/donate/', '%s' ), $slug );

		?>
			<span style="font-weight: bold;">
			<div>
		<?php echo esc_html( $plugin_version ); ?> | 
			<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank"><?php esc_html_e( 'FAQ' ); ?></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank"><?php esc_html_e( 'Support Forums' ); ?></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank"><?php sprintf( esc_html_e( 'Reviews', '%s' ), $slug ); ?></a>
			</div>
			<div>
			<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank">
			<?php
			/* translators: Plugin translation link */
			echo sprintf( esc_html__( 'Translations for %s' ), esc_html( $plugin_name ) );
			?>
			</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank"><span class="dashicons dashicons-video-alt3"></span></a>
			</div>
			</span>

			<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
			<h3><?php sprintf( esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', '%s' ), $slug ); ?></h3>
			<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
			<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
			</div>

			<?php

	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 3.00
	 */
	private function options_updated() {

		if ( isset( $_POST['Manageset'] ) && ! empty( $_POST['Manageset'] ) ) {
			if ( check_admin_referer( 'uac_set', 'uac_switcher_set' ) ) {
				$ua_switch = get_option( 'uac_switcher' );
				foreach ( $ua_switch as $key => $value ) {
					if ( isset( $_POST[ 'uac_useragent_' . $key ] ) && ! empty( $_POST[ 'uac_useragent_' . $key ] ) ) {
						$ua_switch[ $key ]['agent'] = sanitize_textarea_field( wp_unslash( $_POST[ 'uac_useragent_' . $key ] ) );
					}
				}
				update_option( 'uac_switcher', $ua_switch );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Settings' ) . ' --> ' . esc_html__( 'Settings saved.' ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['Default'] ) && ! empty( $_POST['Default'] ) ) {
			if ( check_admin_referer( 'uac_set', 'uac_switcher_set' ) ) {
				$ua_tb = 'iPad|^.*Android.*Nexus(((?:(?!Mobile))|(?:(\s(7|10).+))).)*$|Kindle|Silk.*Accelerated|Sony.*Tablet|Xperia Tablet|Sony Tablet S|SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|SC-01D|SC-01E|SC-02D';
				$ua_sp = 'iPhone|iPod|Android.*Mobile|BlackBerry|IEMobile';
				$ua_mb = 'DoCoMo\/|KDDI-|UP\.Browser|SoftBank|Vodafone|J-PHONE|MOT-|WILLCOM|DDIPOCKET|PDXGW|emobile|ASTEL|L-mode';
				$ua_switch = array(
					'tb' => array(
						'name' => __( 'Tablet', 'useragent-content-switcher' ),
						'agent' => $ua_tb,
					),
					'sp' => array(
						'name' => __( 'Smartphone', 'useragent-content-switcher' ),
						'agent' => $ua_sp,
					),
					'mb' => array(
						'name' => __( 'Featurephone', 'useragent-content-switcher' ),
						'agent' => $ua_mb,
					),
				);
				update_option( 'uac_switcher', $ua_switch );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Settings' ) . ' --> ' . esc_html__( 'Default' ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['Deletetype'] ) && ! empty( $_POST['Deletetype'] ) ) {
			if ( check_admin_referer( 'uac_set', 'uac_switcher_set' ) ) {
				$delete_uas = array();
				if ( isset( $_POST['del_uas'] ) && ! empty( $_POST['del_uas'] ) ) {
					$tmps = filter_var(
						wp_unslash( $_POST['del_uas'] ),
						FILTER_CALLBACK,
						[
							'options' => function( $value ) {
								return sanitize_text_field( $value );
							},
						]
					);
					$del_names = array();
					$ua_switch = get_option( 'uac_switcher' );
					foreach ( $tmps as $key => $value ) {
						$del_names[] = $ua_switch[ $key ]['name'];
						unset( $ua_switch[ $key ] );
					}
					$del_name = implode( ',', $del_names );
					update_option( 'uac_switcher', $ua_switch );
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Delete' ) . ' --> ' . esc_html( $del_name ) . '</li></ul></div>';
				}
			}
		}

		if ( isset( $_POST['Addtype'] ) && ! empty( $_POST['Addtype'] ) ) {
			if ( check_admin_referer( 'uac_add', 'uac_switcher_add' ) ) {
				$ua_switch = get_option( 'uac_switcher' );
				if ( isset( $_POST['uac_attr'] ) && ! empty( $_POST['uac_attr'] ) ) {
					$uac_attr = substr( strtolower( sanitize_file_name( wp_unslash( $_POST['uac_attr'] ) ) ), 0, 8 );
					if ( array_key_exists( $uac_attr, $ua_switch ) ) {
						echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'The same name cannot be used.', 'useragent-content-switcher' ) . '</li></ul></div>';
						return;
					}
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'There are some unentered items.', 'useragent-content-switcher' ) . '</li></ul></div>';
					return;
				}
				if ( isset( $_POST['uac_name'] ) && ! empty( $_POST['uac_name'] ) ) {
					$uac_name = sanitize_text_field( wp_unslash( $_POST['uac_name'] ) );
					foreach ( $ua_switch as $key => $value ) {
						if ( $uac_name === $value['name'] ) {
							echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'The same name cannot be used.', 'useragent-content-switcher' ) . '</li></ul></div>';
							return;
						}
					}
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'There are some unentered items.', 'useragent-content-switcher' ) . '</li></ul></div>';
					return;
				}
				if ( isset( $_POST['uac_useragent_new_type'] ) && ! empty( $_POST['uac_useragent_new_type'] ) ) {
					$uac_agent = sanitize_textarea_field( wp_unslash( $_POST['uac_useragent_new_type'] ) );
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'There are some unentered items.', 'useragent-content-switcher' ) . '</li></ul></div>';
					return;
				}
				$ua_switch[ $uac_attr ]['name'] = $uac_name;
				$ua_switch[ $uac_attr ]['agent'] = $uac_agent;
				update_option( 'uac_switcher', $ua_switch );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Settings' ) . ' --> ' . esc_html__( 'Settings saved.' ) . '</li></ul></div>';
			}
		}

	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.00
	 */
	public function register_settings() {

		$ua_tb = 'iPad|^.*Android.*Nexus(((?:(?!Mobile))|(?:(\s(7|10).+))).)*$|Kindle|Silk.*Accelerated|Sony.*Tablet|Xperia Tablet|Sony Tablet S|SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|SC-01D|SC-01E|SC-02D';
		$ua_sp = 'iPhone|iPod|Android.*Mobile|BlackBerry|IEMobile';
		$ua_mb = 'DoCoMo\/|KDDI-|UP\.Browser|SoftBank|Vodafone|J-PHONE|MOT-|WILLCOM|DDIPOCKET|PDXGW|emobile|ASTEL|L-mode';
		/* old -> new ver3.00 later */
		if ( get_option( 'useragentcontentswitcher_useragent_tb' ) ) {
			$ua_tb = get_option( 'useragentcontentswitcher_useragent_tb' );
			delete_option( 'useragentcontentswitcher_useragent_tb' );
		}
		if ( get_option( 'useragentcontentswitcher_useragent_sp' ) ) {
			$ua_sp = get_option( 'useragentcontentswitcher_useragent_sp' );
			delete_option( 'useragentcontentswitcher_useragent_sp' );
		}
		if ( get_option( 'useragentcontentswitcher_useragent_mb' ) ) {
			$ua_mb = get_option( 'useragentcontentswitcher_useragent_mb' );
			delete_option( 'useragentcontentswitcher_useragent_mb' );
		}

		if ( ! get_option( 'uac_switcher' ) ) {
			$ua_switch = array(
				'tb' => array(
					'name' => __( 'Tablet', 'useragent-content-switcher' ),
					'agent' => $ua_tb,
				),
				'sp' => array(
					'name' => __( 'Smartphone', 'useragent-content-switcher' ),
					'agent' => $ua_sp,
				),
				'mb' => array(
					'name' => __( 'Featurephone', 'useragent-content-switcher' ),
					'agent' => $ua_mb,
				),
			);
			update_option( 'uac_switcher', $ua_switch );
		}

	}

	/** ==================================================
	 * Add Quick Tag
	 *
	 * @since 2.35
	 */
	public function useragentcontentswitcher_add_quicktags() {
		if ( wp_script_is( 'quicktags' ) ) {
			?>
			<script type="text/javascript">
				QTags.addButton( 'useragentcontentswitcher', 'agentsw', '[agentsw]', '[/agentsw]' );
			</script>
			<?php
		}
	}

}


