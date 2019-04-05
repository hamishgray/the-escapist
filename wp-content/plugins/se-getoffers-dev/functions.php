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
	$territory = 'uk';
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
	if( $territory == 'it' ){
		$salesCardText['percentagePre'] = 'Fino al ';
		$salesCardText['callToAction'] = 'Prenota ora';
	}else if( $territory == 'se' ){
		$salesCardText['percentagePre'] = 'Upp till ';
		$salesCardText['callToAction'] = 'Se Erbjudande';
	}else{
		$salesCardText['percentagePre'] = 'Up to ';
		$salesCardText['callToAction'] = 'View offer';
	}

	/* ======================================
	 #	 Create JSON offer data array
	 # -------------------------------------- */

  $seapitoken = "90370f0a-cc20-46a7-9934-a1cc4df00502";
  $saleDataURL = "https://api.secretescapes.com/v3/sales?se-api-token=".$seapitoken."&territory=".$territory;
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
        if( stripos( $sales["location"]["displayName"], $filter ) ){
          return true;
        }else if( stripos(" ".$sales["location"]["city"]["name"], $filter ) ){
          return true;
        }else if( stripos(" ".$sales["location"]["country"]["name"], $filter ) ){
          return true;
        }else if( stripos(" ".$sales["reasonToLove"], $filter ) ){
          return true;
        }else if( stripos(" ".$sales["title"], $filter ) ){
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
          if( stripos( " ".$tag["key"], $filter ) ){
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
			"urlSlug" => $sale["urlSlug"],
			"title" => $sale["title"],
			"description" => $sale["reasonToLove"],
			"location" => $sale["location"]["displayName"],
			"image" => $sale["photos"][0]["url"],
			"discount" => $sale["price"]["discount"]["discountPercent"]
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
		$saleCards.=
			"<div class='offers-col'>
				<div class='offer'>
					 <a class='offer__image-link' href='https://www.secretescapes.com/".$item['urlSlug']."/sale'>
					 	 <img src=\"".$item['image']."\">
					 </a>
			     <div class='offer__content'>
					   <a class='offer__title' href='".$item['link']."'>".$item['title']."</a></span><br>
						 <span class='offer__location'>".$item['location']."</span><br>
						 <span class='offer__description'>".$item['description']."</span><br>
				 	   ".$salesCardText['percentagePre']." <span class='offer__price'>-".$item['discount']."%</span><br>
						 <a class='offer__button' href='https://www.secretescapes.com/".$item['urlSlug']."/sale'>".$salesCardText['callToAction']."</a>
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
			<div class='offers'>".$saleCards."</div>
		</div>";

	// ===================
	// Return full offer section. (& hide offers on app)
	$result = do_shortcode('[agentsw ua="sp"]' . $salesSection . '[/agentsw]');
	return $result;

}
