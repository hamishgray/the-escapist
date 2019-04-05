<?php

/* =============================================
 #	 Process data. Generate shortcode.
 # --------------------------------------------- */

function offers_display($atts,$content=null) {

	// Get data from settings page
	$getoffers_country = esc_attr( get_option('getoffers_country') );
	$getoffers_default_title = esc_attr( get_option('getoffers_default_title') );
	$getoffers_default_limit = esc_attr( get_option('getoffers_default_limit') );
	// default limit, if unset in admin settings
	$limitDefault = '6';
	if(!$getoffers_default_limit == ''){
		$limitDefault = $getoffers_default_limit;
	}
	// default title, if unset in admin settings
	$titleDefault = 'Related sales';
	if(!$getoffers_default_title == ''){
		$titleDefault = $getoffers_default_title;
	}
	// default sales territory, if unset in admin settings
	$territory = 'uk';
	if(!$getoffers_country == ''){
		$territory = $getoffers_country;
	}

	// Handles shortcode attributes. If none set uses defaults below.
	$atts = shortcode_atts(
	  array(
		  'title' => $getoffers_default_title,
		  'limit' => $limitDefault,
		  'keywords' => 'london',
	  ), $atts, 'offers'
	);
	$title = $atts['title'];
	$keywords = $atts['keywords'];
	$limit = $atts['limit'] - 1;



	/* ======================================
	 #	 Create JSON offer data array
	 # -------------------------------------- */

	// cURL configuration
	$seapitoken = "90370f0a-cc20-46a7-9934-a1cc4df00502";
	$params = array(
	  'query' => $keywords,
	  'territory' => $territory
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.secretescapes.com/v3/search/sales/FLASH?se-api-token=".$seapitoken);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json"
	));

	$response = curl_exec($ch);
	curl_close($ch);
	// format JSON to PHP associative array
	$responseJSON = json_decode($response, true);
	// select just the deals from the result
	$responseSales = $responseJSON["match"];



	/* ======================================
	 #	 Build presentation for shortcode
	 # -------------------------------------- */

	// Create array with correct key/value pairs
	$salesData = [];
	for($i = 0; $i < count($responseSales); $i++) {
	  $sale = $responseSales[$i];
		$currIndex = sizeof($salesData);
		$salesData[$currIndex++] = array(
			"urlSlug" => $sale["urlSlug"],
			"title" => $sale["title"],
			"description" => $sale["reasonToLove"],
			"location" => $sale["location"]["displayName"],
			"image" => $sale["photos"][0]["url"],
			"discount" => $sale["price"]["discount"]["discountPercent"]
		);
	}

	// control repetitions on forloop
	$sales='';
	$loop_limit = $limit;
	if ( count($salesData) <= $limit ){
		$loop_limit = count($salesData)-1;
	}

	// loop through custom array, build sales cards
	for($i = 0; $i <= $loop_limit; $i++){

		$item = $salesData[$i];
		$sales.=
			"<div class='offers-col'>
				<div class='offer'>
					 <a class='offer__image-link' href='https://www.secretescapes.com/".$item['urlSlug']."/sale'>
					 	 <img src=\"".$item['image']."\">
					 </a>
			     <div class='offer__content'>
					   <a class='offer__title' href='".$item['link']."'>".$item['title']."</a></span><br>
						 <span class='offer__location'>".$item['location']."</span><br>
						 <span class='offer__description'>".$item['description']."</span><br>
				 	   Up to <span class='offer__price'>-".$item['discount']."%</span><br>
						 <a class='offer__button' href='https://www.secretescapes.com/".$item['urlSlug']."/sale'>View offer</a>
					</div>
				</div>
			</div>
		";

	}

	// ===================
	// Wrap offers in section
	$salesSection = "
		<div class='offers-section' id='related-offers'>
			<h3 class='offers-title'>".$title."</h3>
			<div class='offers'>".$sales."</div>
		</div>";


	// ===================
	// Return full offer section. (& hide offers on app)
	$result = do_shortcode('[agentsw ua="sp"]' . $salesSection . '[/agentsw]');
	return $result;

}
