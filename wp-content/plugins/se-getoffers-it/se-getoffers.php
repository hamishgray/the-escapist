<?php
/**
* Plugin Name: SE Get IT Offers
* Description: Google Sheets data embedder for getting and displaying offer data
* Version: 1.0
* Author: Hamish Gray
* Text Domain: se-getoffers
**/

//creates an entry on the admin menu for plugin
add_action('admin_menu', 'se_getoffers_plugin_menu');
//creates a menu page with the following settings
function se_getoffers_plugin_menu() {
	add_submenu_page('options-general.php', 'Get SE Offers', 'Get SE Offers', 'manage_options', 'se-getoffers-settings', 'se_getoffers_display_settings');
}

//on-load, sets up the following settings for the plugin
add_action( 'admin_init', 'se_getoffers_settings' );
function se_getoffers_settings() {
	register_setting( 'se-getoffers-settings-group', 'gsheets_api_key' ); //api key
	register_setting( 'se-getoffers-settings-group', 'gsheets_sheetsids' ); //array of sheet ids
}

//displays the settings page
function se_getoffers_display_settings() {
	//form to save api key and sheet settings
	echo "<form method=\"post\" action=\"options.php\">";
	settings_fields( 'se-getoffers-settings-group' );
	do_settings_sections( 'se-getoffers-settings-group' );
	echo "<script>function addRow(nextnum,nextdisp){
		var toremove = 'addrowbutton';
		var elem = document.getElementById(toremove);
	    elem.parentNode.removeChild(elem);
		var table = document.getElementById(\"gsheets-settings\");
		var row = table.insertRow(-1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		c1var = '<b>Google Sheet ID ('+nextdisp+')</b>';
		cell1.innerHTML = c1var;
		var newnextdisp= nextdisp+1;
		c2var = '<input type=\"text\" name=\"gsheets_sheetsids['+nextnum+']\" size=\"80\"><button type=\"button\" id=\"addrowbutton\" onClick=\"addRow('+nextdisp+','+newnextdisp+')\">Add Row</button>';
		cell2.innerHTML = c2var;
	}</script>
";
//paragraph giving plugin explanation, api setup instructions, and shortcode information
echo "
	<div>
		<h1>SE Get Offers Settings</h1>
		<p>Welcome! This is a basic Google Sheets integration plugin, with the following features.
		<ul style=\"list-style-type:disc;list-style-position:inside;\">
			<li>Displays cells or ranges of cells from any public Google Sheet</li>
			<li>Individual cells are displayed as spans</li>
			<li>Spans of cells displayed as tables, with optional headers</li>
			<li>All options are configured via shortcode</li>
			<li>Option to set custom class on a per-item basis for styling</li>
		</ul>
		<br>
		<b>Shortcodes:</b>
		<ul style=\"list-style-type:disc;list-style-position:inside;\">
			<li>Basic: [offers] (defaults to first document, default tab name)</li>
			<li>Single Cell: <code>[offers file=1 sheetname=\"Sheet1\" cell=A1 class=\"gsheets-special\"]</code></li>
			<li>Range of Cells: <code>[offers file=1 sheetname=Sheet1 cell=A1:C2 theaders=\"Col 1,Col 2,Col 3\" class=\"gsheets-special2\"]</code></li>
		</ul>

		<b>Optional Attributes:</b>
		<ul style=\"list-style-type:disc;list-style-position:inside;\">
			<li>file=# (number of the Google Doc you have set in the settings page)
			<li>sheetname= name of sheet in doc</li>
			<li>cell= Cell Number or range, with : </li>
			<li>class=custom class name or names here </li>
			<li>theaders= comma seperated list of table column headers, for range view (optional)</li>
		</ul>

		<p>To create API key, visit <a href=\"https://console.developers.google.com/\" target=\"_blank\">Google Developers Console</a> Then, follow below;</p>
		<ul style=\"list-style-type:disc;list-style-position:inside;\">
			<li>Create new project (or use project you created before).</li>
			<li>Check \"APIs & auth\" -> \"Credentials\" on side menu.</li>
			<li>Hit \"Create new Key\" button on \"Public API access\" section.</li>
			<li>Choose \"Browser key\" and keep blank on referer limitation.</li>
		</ul>
	</div>";

//Settings to be saved
echo "
<table id=\"gsheets-settings\" class=\"form-table\" aria-live=\"assertive\">
	<tr>
		<td colspan=\"2\" style=\"padding-bottom:0;\">
			<h2 style=\"margin-bottom:0;\">API KEY - Google Sheet Viewer (All REQUIRED)</h2>
		</td>
	</tr>
	<tr valign=\"top\">
		<td>Google Sheets API Key</td>
    <td>
			<input type=\"text\" name=\"gsheets_api_key\" size=\"80\" value=\"".esc_attr( get_option('gsheets_api_key') )."\" />
		</td>
	</tr>
	<tr>
		<td colspan=\"2\" style=\"padding-bottom:0;\">
			<h2 style=\"margin-bottom:0;\">Google Sheets Folder IDs</h2>
		</td>";

$gsheets_sheetsids = get_option('gsheets_sheetsids');
$num_sheets = 0;
$num_sheets = count($gsheets_sheetsids);
if ($num_sheets > 1) $showrows=$num_sheets;
else $showrows = 1;
for ($i=0;$i < $showrows; $i++) {
	$nextid = $i+1;
	$nextdisp = $i+2;
	$sheetnum = $i+1;
	echo "
	<tr valign=\"top\">
		<td>Google Sheet ID ($sheetnum)</td>
		<td><input type=\"text\" name=\"gsheets_sheetsids[$i]\" size=\"80\" value=\"$gsheets_sheetsids[$i]\"/>";

	if (($showrows -1) == $i) {
		echo "<button type=\"button\" id=\"addrowbutton\" onClick=\"addRow($nextid,$nextdisp)\">Add Row</button>";
	}
		echo "</td></tr>";
	}
	echo" </table>";
	submit_button();
	echo "</form>";
}








/* =============================================
	 Function displays folder on shortcode base: [offers]
	 --------------------------------------------- */

function offers_display($atts,$content=null) {
	$gsheets_api_key = esc_attr( get_option('gsheets_api_key') );
	if ($gsheets_api_key == '') {

		$error = 'You must first enter a valid Google Sheets API key.';
		return $error;
	}
	//Handles attribures. If none are specified, defaults to no scroll, 1st sheet
	$atts = shortcode_atts(
  array(
	  'file' => 1,
	  'title' => 'Related offers',
	  'sheetname' => 'london',
	  'cell' => '',
	  'theaders' => '',
	  'class' => '',
	  'limit' => '3'
  ), $atts, 'offers' );

	$file = $atts['file'];
	$title = $atts['title'];
	$sheetname = $atts['sheetname'];
	$limit = $atts['limit'] - 1;

	$cell = $atts['cell'];
	$theaders = $atts['theaders'];
	$class = $atts['class'];

	$sheetlist = get_option('gsheets_sheetsids');
	//print_r($sheetlist);
	$num = $file-1;
	$file = $sheetlist[$num];
	//print_r($result);

	if ($file == '' || $file == 'broken') {

		$error = 'You must first enter a valid Google Sheets id.'.$file;
		return $error;
	}



	$cell_lookup = new WP_Http(); //WP_Http function to connect



	/* ======================================
	 	 Build presentation : get data and apply to variables
		 -------------------------------------- */

	// $get_url_title = "https://sheets.googleapis.com/v4/spreadsheets/$file/values/$sheetname!"."G"."$cell?&key=$gsheets_api_key";
	// $get_url_image = "https://sheets.googleapis.com/v4/spreadsheets/$file/values/$sheetname!"."AA"."$cell?&key=$gsheets_api_key";
	// $get_url_fromprice = "https://sheets.googleapis.com/v4/spreadsheets/$file/values/$sheetname!"."J"."$cell?&key=$gsheets_api_key";
	// $get_url_location = "https://sheets.googleapis.com/v4/spreadsheets/$file/values/$sheetname!"."V"."$cell?&key=$gsheets_api_key";
	$get_offers =  "https://sheets.googleapis.com/v4/spreadsheets/$file/values/$sheetname!"."2:20?&key=$gsheets_api_key";


	$cell_response_offers = $cell_lookup -> get( $get_offers);
	// $cell_response_title = $cell_lookup -> get( $get_url_title);
	// $cell_response_image = $cell_lookup -> get( $get_url_image);
	// $cell_response_fromprice = $cell_lookup -> get( $get_url_fromprice);
	// $cell_response_location = $cell_lookup -> get( $get_url_location);

	// Gets the body response from the API call and converts to an array
	$json_body_offers = json_decode($cell_response_offers['body'],true);
	// $json_body_title = json_decode($cell_response_title['body'],true);
	// $json_body_image = json_decode($cell_response_image['body'],true);
	// $json_body_fromprice = json_decode($cell_response_fromprice['body'],true);
	// $json_body_location = json_decode($cell_response_location['body'],true);

	if (strpos($cell, ':') !== false) {

		$table_to_return = "<table class='gsheets-table $class'>";

		if ($theaders != '') {

			$table_to_return.='<thead><tr>';
			$heads = explode(',',$theaders);

			foreach ($heads as $head) {

				$table_to_return.="<th>$head</th>";

			}

			$table_to_return.="</tr></thead>";
		}

		$table_to_return.='<tbody>';

		foreach ($json_body['values'] as $row) {

			$table_to_return .="<tr>";

			foreach ($row as $cell) {

				$table_to_return.="<td>$cell</td>";

			}

			$table_to_return.="</tr>";

		}

		$table_to_return.="</tbody></table>";

		return $table_to_return;

	}
	else {
		shuffle($json_body_offers['values']);
		$offer='';
		$offer.= "
		<div class='offers-section' id='related-offers'>
			<h3 class='offers-title'>".$title."</h3>
			<div class='offers'>";

			for($count = 0; $count <= $limit; $count++){
				$offer_link = $json_body_offers['values'][$count][0];
				$offer_link2 = "secretescapes://open/sale/offers/" . $json_body_offers['values'][$count][12];
				if( isset($offer_link) ){
					$offer.=
						"<div class='offers-col'>
							<div class='offer'>
								 <a class='offer__image-link' href='".$offer_link."'>
								 	 <img src=\"".$json_body_offers['values'][$count][5]."\">
								 </a>
						     <div class='offer__content'>
								   <a class='offer__title' href='".$offer_link."'>".$json_body_offers['values'][$count][1]."</a></span><br>
									 <span class='offer__location'>".$json_body_offers['values'][$count][3]."</span><br>
									 <span class='offer__description'>".$json_body_offers['values'][$count][2]."</span><br>
							 	   Fino al <span class='offer__price'>-".$json_body_offers['values'][$count][6]."%</span><br>
									 <a class='offer__button' href='".$offer_link."'>Prenota ora</a>
								</div>
							</div>
						</div>
					";
				}
			}

		$offer.= "</div></div>";

		$offer_result = do_shortcode('[agentsw ua="sp"]' . $offer . '[/agentsw]');

		return $offer_result;
	}
}
add_shortcode('offers', 'offers_display');
