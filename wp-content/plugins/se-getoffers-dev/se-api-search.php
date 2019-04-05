<?php
$ch = curl_init();

$seapitoken = "90370f0a-cc20-46a7-9934-a1cc4df00502";
$territory = "uk";
$params = array(
  'query' => 'italy',
  'territory' => $territory
);

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


// TESTING
$responseJSON = json_decode($response, true); // formats array correctly
print_r($responseJSON);

$deals = $responseJSON["match"];
// print_r($deals);

$titles = [];
for($i = 0; $i < count($deals); $i++) {
  $sale = $deals[$i];
  array_push($titles, $sale["title"]);
}
print_r($titles);

