<?php

/* =============================================
 #	 Process data. Generate shortcode.
 # --------------------------------------------- */

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
	 #	 Create JSON offer data array
	 # -------------------------------------- */

	$sheeturl =  "https://sheets.googleapis.com/v4/spreadsheets/$file/values/$sheetname!"."2:20?&key=$gsheets_api_key";
	$cell_response_offers = $cell_lookup -> get( $sheeturl);
	$json = json_decode($cell_response_offers['body'],true);




	/* ======================================
	 #	 Build presentation for shortcode
	 # -------------------------------------- */

	// Create array with correct key/value pairs
 	$offers_data=[];
 	for($i = 0; $i < count($json['values']); $i++){

		$item = $json['values'][$i];
		$currIndex = sizeof($offers_data);
		$offers_data[$currIndex++] = array(
			"link" => $item[0],
			"title" => $item[1],
			"description" => $item[2],
			"location" => $item[3],
			"image" => $item[5],
			"discount" => $item[6]
		);

 	}
	shuffle($offers_data); // randomise offers

	$offers='';
	if ( count($offers_data) <= $limit ){
		$loop_limit = count($offers_data)-1;
	}else{
		$loop_limit = $limit;
	}

	for($i = 0; $i <= $loop_limit; $i++){

		$item = $offers_data[$i];
		$offers.=
			"<div class='offers-col'>
				<div class='offer'>
					 <a class='offer__image-link' href='".$item['link']."'>
					 	 <img src=\"".$item['image']."\">
					 </a>
			     <div class='offer__content'>
					   <a class='offer__title' href='".$item['link']."'>".$item['title']."</a></span><br>
						 <span class='offer__location'>".$item['location']."</span><br>
						 <span class='offer__description'>".$item['description']."</span><br>
				 	   Up to <span class='offer__price'>-".$item['discount']."%</span><br>
						 <a class='offer__button' href='".$item['link']."'>View offer</a>
					</div>
				</div>
			</div>
		";

	}

	// ===================
	// Wrap offers in section
	$offer_section = "
		<div class='offers-section' id='related-offers'>
			<h3 class='offers-title'>".$title."</h3>
			<div class='offers'>".$offers."</div>
		</div>";


	// ===================
	// Return full offer section. (& hide offers on app)
	$result = do_shortcode('[agentsw ua="sp"]' . $offer_section . '[/agentsw]');
	return $result;

}
