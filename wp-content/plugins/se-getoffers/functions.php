<?php

/* =============================================
 #	 Process data. Generate shortcode.
 # --------------------------------------------- */

function offers_display($atts,$content=null) {

	/* ======================================
	 #	 Setup variables
	 # -------------------------------------- */

 /* -------------------
  * Get data from settings page */
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
	$territory = 'es';
	if(!$getoffers_country == ''){
		$territory = $getoffers_country;
	}

	/* -------------------
	* Shortcode attributes */
	$atts = shortcode_atts(
	  array(
		  'title' => $titleDefault,
		  'limit' => $limitDefault,
		  'keywords' => '',
		  'tags' => '',
	  ), $atts, 'offers'
	);
	$title = $atts['title'];
	$limit = $atts['limit'] - 1;
	// shortcode keyword check
	if($atts['keywords']){
		$keywords = $atts['keywords'];
	}else{
		$keywords = null;
	}
	// shortcode tag check
	if($atts['tags']){
		$tags = $atts['tags'];
	}else{
		$tags = null;
	}

	/* -------------------
	 * Language */
  $salesCardText = [];
	if( $territory == 'it' ){
		$salesCardText['siteUrl'] = 'https://it.secretescapes.com';
		$salesCardText['percentagePre'] = 'Fino al ';
		$salesCardText['callToAction'] = 'Prenota ora';
	}else if( $territory == 'sv' ){
		$salesCardText['siteUrl'] = 'https://www.secretescapes.se';
		$salesCardText['percentagePre'] = 'Upp till ';
		$salesCardText['callToAction'] = 'Se Erbjudande';
	}else if( $territory == 'de' ){
		$salesCardText['siteUrl'] = 'https://www.secretescapes.de';
		$salesCardText['percentagePre'] = 'Bis zu ';
		$salesCardText['callToAction'] = 'Zum Angebot';
	}else{
		$salesCardText['siteUrl'] = 'https://www.secretescapes.com';
		$salesCardText['percentagePre'] = 'Up to ';
		$salesCardText['callToAction'] = 'View deal';
	}

	/* ======================================
	 #	 Create JSON offer data array
	 # -------------------------------------- */

  $seapitoken = "90370f0a-cc20-46a7-9934-a1cc4df00502";
  $saleDataURL = "https://api.secretescapes.com/v4/sales?se-api-token=".$seapitoken."&affiliate=".$territory;
  $json = file_get_contents($saleDataURL);
	$rawsales = json_decode($json, true);
  // check sale is live
  $sales = [];
  foreach($rawsales as $sale){
    $saleStartDate = new DateTime($sale['start']);
    $currentDate = new DateTime();
    if($saleStartDate <= $currentDate){
      // live
      array_push($sales,$sale);
    }
  }



	/* ======================================
   # Filtering
	 # -------------------------------------- */
  $outputArray = [];

  /* -------------------
   * Keyword Filtering */
  if( strlen($keywords) >= 1 ){
    $filterKeywords = $keywords;
    // Separate keywords by comma separation
    $keywordFilterArray = explode(', ', $filterKeywords);
    // Checking set fields against filter
    $mergedArray = [];
    foreach($keywordFilterArray as $filter){
      $filteredArray = array_filter($sales, function($sales) use($filter){
        if( stripos(" ".$sales["editorial"]["destinationName"]." ", $filter ) ){
          return true;
        }else if( stripos(" ".$sales["location"]["city"]["name"]." ", $filter ) ){
          return true;
        }else if( stripos(" ".$sales["location"]["country"]["name"]." ", $filter ) ){
          return true;
        }else if( stripos(" ".$sales["editorial"]["reasonToLove"]." ", $filter ) ){
          return true;
        }else if( stripos(" ".$sales["editorial"]["title"]." ", $filter ) ){
          return true;
        }
      });
      array_push($mergedArray, $filteredArray);
    }
    $mergedArray = call_user_func_array('array_merge', $mergedArray);
    $outputArray = array_values($mergedArray); // reset array key values
  }

  /* -------------------
   * Tag Filtering */
  $filterByTags = $sales;
  if( sizeof($outputArray) > 0 ){
    $filterByTags = $outputArray;
  }

	if( strlen($tags) >= 1 ){
    $filterTags = $tags;
    // Separate tags by comma separation
    $tagFilterArray = explode(', ', $filterTags);
    // Checking set fields against filter
    $mergedArray = [];
    foreach($tagFilterArray as $filter){
      $filteredArray = array_filter($filterByTags, function($filterByTags) use($filter){

        for($i = 0; $i <= count($filterByTags["tags"])-1; $i++){
          $tag = $filterByTags["tags"][$i];
          if( stripos( " ".$tag, $filter ) ){
            return true;
          }
        }
      });
      array_push($mergedArray, $filteredArray);
    }
    $mergedArray = call_user_func_array('array_merge', $mergedArray);
    $outputArray = array_values($mergedArray); // reset array key values

  }

  if( sizeof($outputArray) < 1 ){
    $outputArray = $sales;
  }

  $filteredSales = $outputArray;
	// print_r($filteredSales);

	/* ======================================
	 #	 Build presentation for shortcode
	 # -------------------------------------- */

	$salesData = [];
  for($i = 0; $i <= count($filteredSales)-1; $i++){
    $sale = $filteredSales[$i];
		$currIndex = sizeof($salesData);
		$salesData[$currIndex++] = array(
			"link" => $sale["links"]["sale"],
			"title" => $sale["editorial"]["title"],
			"description" => $sale["editorial"]["reasonToLove"],
			"location" => $sale["editorial"]["destinationName"],
			"image" => str_replace("_nws.jpg",".jpg",$sale["photos"][0]["url"]),
			"discount" => $sale["prices"]["discount"]
		);
  }
	// print_r($salesData);

	// control repetitions on forloop
	$saleCards='';
	$loop_limit = $limit;
	if ( count($salesData) <= $limit ){
		$loop_limit = count($salesData)-1;
	}

	// loop through custom array, build sales cards
	for($i = 0; $i <= $loop_limit; $i++){
		$item = $salesData[$i];

		if ( $item['discount'] > 0 ){
			$discountStr = "<p class='offer__discount'>".$salesCardText['percentagePre']." <span class='offer__discount-num'>-".$item['discount']."%</span></p>";
		}else{
			$discountStr = "";
		}

		$saleCards.=
			"<div class='offers-col'>
				<div class='offer'>
					 <a class='offer__image-link' href='".$salesCardText['siteUrl'].$item['link']."'>
					 	 <img src=\"".$item['image']."\">
					 </a>
			     <div class='offer__content'>
						 <p class='offer__location'>".$item['location']."</p>
					   <p class='offer__title'><a class='offer__title-link' href='".$salesCardText['siteUrl'].$item['link']."'>".$item['title']."</a></p>
						 <p class='offer__description'>".$item['description']."</p>
						 ". $discountStr ."
						 <a class='offer__button' href='".$salesCardText['siteUrl'].$item['link']."'>".$salesCardText['callToAction']."</a>
					</div>
				</div>
			</div>
		";

	}

	// ===================
	// Wrap offers in section
	$salesSection = "
		<div class='offers-section hideApp' id='related-offers'>
			<h3 class='offers-title'>".$title."</h3>
			<div class='offers'>".$saleCards."</div>
		</div>";

	// ===================
	// Return full offer section. (& hide offers on app)
	$result = do_shortcode('[ifurlparam param="fromApp" empty="1"]' . $salesSection . '[/ifurlparam]');

	return $result;

}
