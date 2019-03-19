<?php
/**
 * UserAgent Content Switcher
 * 
 * @package    UserAgent Content Switcher
 * @subpackage UserAgentContentSwitcher Main Fuctions
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

class UserAgentContentSwitcher {

	/* ==================================================
	 * Construct
	 * @since	2.37
	 */
	public function __construct() {

		add_shortcode( 'agentsw', array($this, 'useragentcontentswitcher_func') );

	}

	/* ==================================================
	 * Main
	 * @param	array	$atts
	 * @return	string	$content
	 */
	public function useragentcontentswitcher_func( $atts, $content = NULL ) {

		$mode = $this->agent_check();

		extract(shortcode_atts(array(
	        'ua'  => ''
		), $atts));

		if ( $ua === 'pc' || empty($ua) ) {
			if ( $mode === 'pc') {
				return do_shortcode($content);
			} else {
				return "";
			}
		} else if ( $ua === 'tb' ) {
			if ( $mode === 'tb') {
				return do_shortcode($content);
			} else {
				return "";
			}
		} else if ( $ua === 'sp' ) {
			if ( $mode === 'sp') {
				return do_shortcode($content);
			} else {
				return "";
			}
		} else if ( $ua === 'mb' ) {
			if ( $mode === 'mb') {
				return do_shortcode($content);
			} else {
				return "";
			}
		}

	}

	/* ==================================================
	* @param	none
	* @return	string	$mode
	* @since	1.0
	*/
	private function agent_check(){

		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		if(preg_match("{".get_option('useragentcontentswitcher_useragent_tb')."}",$user_agent)){
			//Tablet
			$mode = "tb"; 
		}else if(preg_match("{".get_option('useragentcontentswitcher_useragent_sp')."}",$user_agent)){
			//Smartphone
			$mode = "sp";
		}else if(preg_match("{".get_option('useragentcontentswitcher_useragent_mb')."}",$user_agent)){
			//Japanese mobile phone
			$mode = "mb";
		}else{
			//PC or Tablet
			$mode = "pc"; 
		}

		return $mode;

	}

}

?>