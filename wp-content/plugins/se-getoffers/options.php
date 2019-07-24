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
		<div style='max-width: 720px; padding-top:30px;'>
			<h1>SE Get Offers Tool</h1>
			<p>Welcome to the custom tool for displaying Secret Escapes sales on relevant articles. With this tool you can build a custom grouping of sales that matches the theme or destination of the article. </p>

			<h2>Search tool for testing</h2>
			<p>When creating your offer grouping it can help to preview the results to ensure it is accurate and shows enough sales. Visit the following example search page to test your keywords and tags.</p>
			<p><a href='https://www.secretescapes.com/magazine-uk/_search/' target='_blank'>https://www.secretescapes.com/magazine-uk/_search/</a></p>

			<h2>Shortcode:</h2>
			<p>
				Shortcode example:<br />
				<code>
					[offers keywords=\"london\" tags=\"spa\" limit=\"6\" title=\"Related London sales\"]
				</code>
			</p>
			<p>With the shortcode you have control of the following attributes:</p>
			<p><code>keywords=\"\"</code> Use this to build a group of sales. E.g. 'london, paris, rome' would show sales from all three cities. Use a comma to separate your keywords. To avoid 'rome' returning sales with the word 'promenade' for example, put a space on the end of your keyword e.g. 'rome '.</p>
			<p><code>tags=\"\"</code> With tags you can filter upon your custom grouping. For example on the above group if you use 'food' you will get all sales from the three cities with tags including the word 'food'</p>
			<p><code>title=\"\"</code> Set a custom title for the related sales section, e.g. London breaks for you!</p>
			<p><code>limit=\"\"</code> Limit the number of sales to be shown in the section.</p>


			<h2>Settings:</h2>
			<p>Here you can change the default settings for the Secret Escapes sale widget on article pages. Enter the affiliate ID for your territory below.</p>
			<table id=\"gsheets-settings\" class=\"form-table\" aria-live=\"assertive\">
				<tr>
					<td style='padding:5px 10px 5px 0px;'><strong>Territory (affiliate ID):</strong></td>
					<td style='padding:5px 10px 5px 0px;'>
						<input type=\"text\" name=\"getoffers_country\" value=\"".esc_attr( get_option('getoffers_country') )."\" />
					</td>
				</tr>
				<tr>
					<td style='padding:5px 10px 5px 0px;'><strong>Default title for sale sections:</strong></td>
			    <td style='padding:5px 10px 5px 0px;'>
						<input type=\"text\" name=\"getoffers_default_title\" value=\"".esc_attr( get_option('getoffers_default_title') )."\" />
					</td>
				</tr>
				<tr>
					<td style='padding:5px 10px 5px 0px;'><strong>How many sales to show by default:</strong></td>
			    <td style='padding:5px 10px 5px 0px;'>
						<input type=\"number\" name=\"getoffers_default_limit\" value=\"".esc_attr( get_option('getoffers_default_limit') )."\" />
					</td>
				</tr>
			</table>
		</div>";

	submit_button();
	echo "</form>";
}

