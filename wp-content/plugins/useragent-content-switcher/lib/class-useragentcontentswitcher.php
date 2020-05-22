<?php
/**
 * UserAgent Content Switcher
 *
 * @package    UserAgent Content Switcher
 * @subpackage UserAgentContentSwitcher Main Fuctions
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

$useragentcontentswitcher = new UserAgentContentSwitcher();

/** ==================================================
 * Main Functions
 */
class UserAgentContentSwitcher {

	/** ==================================================
	 * Construct
	 *
	 * @since 2.37
	 */
	public function __construct() {

		add_shortcode( 'agentsw', array( $this, 'useragentcontentswitcher_func' ) );

	}

	/** ==================================================
	 * Main
	 *
	 * @param array  $atts  atts.
	 * @param string $content  content.
	 * @since 1.00
	 * @return string $content
	 */
	public function useragentcontentswitcher_func( $atts, $content = null ) {

		$mode = $this->agent_check();

		$a = shortcode_atts(
			array(
				'ua'  => '',
			),
			$atts
		);

		$ua = $a['ua'];

		$ua_switch = get_option( 'uac_switcher' );
		if ( ! empty( $ua_switch ) ) {
			foreach ( $ua_switch as $key => $value ) {
				if ( $key === $ua || empty( $ua ) ) {
					if ( $key === $mode ) {
						return do_shortcode( $content );
					}
				}
			}
		}
		if ( 'pc' === $ua || empty( $ua ) ) {
			if ( 'pc' === $mode ) {
				return do_shortcode( $content );
			}
		}
		return '';

	}

	/** ==================================================
	 * Agent check
	 *
	 * @return string $mode
	 * @since 1.00
	 */
	private function agent_check() {

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		} else {
			return 'pc';
		}

		$mode = 'pc';
		$ua_switch = get_option( 'uac_switcher' );
		if ( ! empty( $ua_switch ) ) {
			foreach ( $ua_switch as $key => $value ) {
				if ( preg_match( '{' . $value['agent'] . '}', $user_agent ) ) {
					$mode = $key;
				}
			}
		}

		return $mode;

	}

}


