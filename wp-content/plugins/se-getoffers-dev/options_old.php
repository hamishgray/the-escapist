<?php

/* =============================================
 #	 Options page for site admin
 # --------------------------------------------- */

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
	}</script>";
	// Plugin description and usage guide
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
		</div>
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

