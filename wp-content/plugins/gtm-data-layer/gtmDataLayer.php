<?php
/*
Plugin Name: Data Layer for GTM
Plugin URI: http://wordpress.org/extend/plugins/gtm-data-layer/
Description: Add Google Tag Manager together with the data layer that you can use for advanced tracking.
Version: 1.1
Author: Claudiu Murariu
Author URI: http://www.padicode.com/
*/

function activate_gtmDataLayer() {
  add_option('gtmCodePadi', '');
}

function deactive_gtmDataLayer() {
  delete_option('gtmCodePadi');
}

function admin_init_gtmDataLayer() {
  register_setting('gtmCode', 'gtmCodePadi');
}

function admin_menu_gtmDataLayer() {
  add_options_page('Google Tag Manager', 'Google Tag Manager', 8, 'gtmDataLayer', 'options_page_gtmDataLayer');
}

function options_page_gtmDataLayer() {
  //echo '<script>console.log("'.plugin_dir_path(__FILE__).'options.php");</script>';
  //echo '<script>console.log("'.WP_PLUGIN_DIR.'/gtm-datalayer/options.php");</script>';
  include(plugin_dir_path(__FILE__).'options.php');  
}

function insert_gtmDataLayer($gtmCodePadi) {

echo $gtmCodePadi;

}

function wcount()
{
    ob_start();
    the_content();
    $content = ob_get_clean();
    return sizeof(explode(" ", $content));
}   


function initDataLayer() { 
 
  echo '<script type="text/javascript">	var dataLayer = [{"platform":"wordpress","websiteName":"'.get_bloginfo('name').'"}]; </script>';
      
}

function pushDataLayer() { 
 
  echo '<script type="text/javascript">';
  if (is_archive())
  {
    $category = get_category( get_query_var( 'cat' ) );
    echo 'dataLayer.push ({"pageName":"archive","arhiveCategory":"'.$category->slug.'"});';
  }
  elseif (is_page())
  {
    echo 'dataLayer.push ({"pageName":"page","wordCount":"'.wcount().'"});';
  }
  elseif (is_single())
  {
    
    $categories = get_the_category();
    $separator = '|';
    $output = '';
    if($categories)
    {
	    foreach($categories as $category) 
	    {
		    $output .= $category->slug.$separator;
	    }
	}
    
    echo 'dataLayer.push ({"pageName":"article","postCategory":"'.trim($output, $separator).'","wordCount":"'.wcount().'","postType":"'.get_post_type().'","postTitle":"'.trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags(get_the_title())))))).'"});';
  }
  elseif (is_home())
  {
    echo 'dataLayer.push ({"pageName":"homepage"});';
  }
  else 
  {
    echo 'dataLayer.push ({"pageName":"other"});';
  }
  
  
  
  echo '</script>';
     
}

function commentDataLayer() 
{
    //echo '<script type="text/javascript"> dataLayer.push ({"event":"CommentAdded"});</script>';
}


function gtmDataLayer() { 
  
  $gtmCodePadi = get_option('gtmCodePadi');
  if ($gtmCodePadi != "") insert_gtmDataLayer($gtmCodePadi);
      
}


if (is_admin()) {
	register_activation_hook(__FILE__, 'activate_gtmDataLayer');
	register_deactivation_hook(__FILE__, 'deactive_gtmDataLayer');
	add_action('admin_init', 'admin_init_gtmDataLayer');
	add_action('admin_menu', 'admin_menu_gtmDataLayer');
}

add_action('wp_head', 'initDataLayer');
add_action('wp_footer', 'pushDataLayer');
add_action('wp_footer', 'gtmDataLayer');

//add_action('comment_post','commentDataLayer');



?>
