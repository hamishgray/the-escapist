<?php

/* =============================================
 #	 Options page for site admin
 # --------------------------------------------- */

//on-load, sets up the following settings for the plugin
add_action( 'admin_init', 'se_getoffers_settings' );
function se_getoffers_settings() {
	register_setting( 'se-getoffers-settings-group', 'getoffers_country' ); // Country for API
	register_setting( 'se-getoffers-settings-group', 'getoffers_default_title' ); // Default sale section title
	register_setting( 'se-getoffers-settings-group', 'getoffers_default_limit' ); // Default number of offers to show
}

// displays the settings page
function se_getoffers_display_settings() {
	//form to save api key and sheet settings
	echo "<form method=\"post\" action=\"options.php\">";
	settings_fields( 'se-getoffers-settings-group' );
	do_settings_sections( 'se-getoffers-settings-group' );

	// Plugin description and usage guide
	echo "
		<div>
			<h1>SE Get Offers Tool</h1>
			<p>Welcome to the custom tool for displaying Secret Escapes sales on relevant articles. </p>
			<ul style=\"list-style-type:disc;list-style-position:inside;\">
				<li>Select your territory with 2-digit country code to get local sales.</li>
				<li>Using keywords you can display search results on articles. To test this simply search on the main site, and replicate your search terms within the shortcode</li>
				<li>Give your sale section a custom title</li>
			</ul>

			<h2>Shortcode:</h2>
			<code>
				[offers keyword=\"london\" limit=\"6\" title=\"Related London sales\"]
			</code>

			<h2>Optional Attributes:</h2>
			<p><code>keywords=\"\"</code> This is a search phrase identical to the core site search. Keep this broad as the search is can be overly limiting in its results.</p>
			<p><code>title=\"\"</code> Set the title for the related sales, e.g. London breaks for you!</p>
			<p><code>limit=\"\"</code> Limit the number of sales to be shown in the section.</p>
		</div>

		<h2>Settings:</h2>
		<p>Here you can change the default settings for the Secret Escapes sale widget on article pages. </p>
		<table id=\"gsheets-settings\" class=\"form-table\" aria-live=\"assertive\">
			<tr>
				<td><strong>Country code:</strong></td>
				<td>
					<input type=\"text\" name=\"getoffers_country\" value=\"".esc_attr( get_option('getoffers_country') )."\" />
				</td>
			</tr>
			<tr>
				<td><strong>Default title for sale sections:</strong></td>
		    <td>
					<input type=\"text\" name=\"getoffers_default_title\" value=\"".esc_attr( get_option('getoffers_default_title') )."\" />
				</td>
			</tr>
			<tr>
				<td><strong>How many sales to show by default:</strong></td>
		    <td>
					<input type=\"number\" name=\"getoffers_default_limit\" value=\"".esc_attr( get_option('getoffers_default_limit') )."\" />
				</td>
			</tr>
		</table>";

	submit_button();
	echo "</form>";
}

