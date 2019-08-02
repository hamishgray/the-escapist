<?php
/**
 * UserAgent Content Switcher
 *
 * @package    UserAgent Content Switcher
 * @subpackage UserAgentContentSwitcherRegist registered in the database
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

$useragentcontentswitcherregist = new UserAgentContentSwitcherRegist();
add_action( 'admin_init', array( $useragentcontentswitcherregist, 'register_settings' ) );

/** ==================================================
 * registered in the database
 */
class UserAgentContentSwitcherRegist {

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.0
	 */
	public function register_settings() {
		register_setting( 'useragentcontentswitcher-settings-group', 'useragentcontentswitcher_useragent_tb' );
		register_setting( 'useragentcontentswitcher-settings-group', 'useragentcontentswitcher_useragent_sp' );
		register_setting( 'useragentcontentswitcher-settings-group', 'useragentcontentswitcher_useragent_mb' );
		add_option( 'useragentcontentswitcher_useragent_tb', 'iPad|^.*Android.*Nexus(((?:(?!Mobile))|(?:(\s(7|10).+))).)*$|Kindle|Silk.*Accelerated|Sony.*Tablet|Xperia Tablet|Sony Tablet S|SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|SC-01D|SC-01E|SC-02D' );
		add_option( 'useragentcontentswitcher_useragent_sp', 'iPhone|iPod|Android.*Mobile|BlackBerry|IEMobile' );
		add_option( 'useragentcontentswitcher_useragent_mb', 'DoCoMo\/|KDDI-|UP\.Browser|SoftBank|Vodafone|J-PHONE|MOT-|WILLCOM|DDIPOCKET|PDXGW|emobile|ASTEL|L-mode' );
	}

}


