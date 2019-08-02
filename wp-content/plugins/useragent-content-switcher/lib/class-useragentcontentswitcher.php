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

		if ( 'pc' === $ua || empty( $ua ) ) {
			if ( 'pc' === $mode ) {
				return do_shortcode( $content );
			} else {
				return '';
			}
		} else if ( 'tb' === $ua ) {
			if ( 'tb' === $mode ) {
				return do_shortcode( $content );
			} else {
				return '';
			}
		} else if ( 'sp' === $ua ) {
			if ( 'sp' === $mode ) {
				return do_shortcode( $content );
			} else {
				return '';
			}
		} else if ( 'mb' === $ua ) {
			if ( 'mb' === $mode ) {
				return do_shortcode( $content );
			} else {
				return '';
			}
		}

	}

	/** ==================================================
	 * Agent check
	 *
	 * @return string $mode
	 * @since 1.0
	 */
	private function agent_check() {

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		} else {
			return 'pc';
		}

		if ( preg_match( '{' . get_option( 'useragentcontentswitcher_useragent_tb' ) . '}', $user_agent ) ) {
			/* Tablet */
			$mode = 'tb';
		} else if ( preg_match( '{' . get_option( 'useragentcontentswitcher_useragent_sp' ) . '}', $user_agent ) ) {
			/* Smartphone */
			$mode = 'sp';
		} else if ( preg_match( '{' . get_option( 'useragentcontentswitcher_useragent_mb' ) . '}', $user_agent ) ) {
			/* Japanese mobile phone */
			$mode = 'mb';
		} else {
			/* PC or Tablet */
			$mode = 'pc';
		}

		return $mode;

	}

}


