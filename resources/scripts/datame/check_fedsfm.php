<?php

$fam = "ахмадов";
$nam = "адам";
$otch = "эмиевич";
$bdate = "20.11.1986";

$session=date('YmdHis').rand(100000,999999);
$api_key = '329eb3a5a0619564fd18fc3d12a6ff69';
$cookies_file = dirname(__FILE__).'/pass/cookies'.$session.'.txt';

	$ch2 = curl_init();
	$curl_params2 = array(
		CURLOPT_URL => 'http://www.fedsfm.ru/TerroristSearchAutocomplete?query='.$fam.'+'.$nam.'+'.$otch.'*%2C+'.$bdate,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_COOKIEFILE => $cookies_file,
		CURLOPT_COOKIEJAR => $cookies_file,
		CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
		CURLOPT_TIMEOUT => 10

	);
	curl_setopt_array($ch2, $curl_params2);
	$result2 = curl_exec($ch2);
	$result2 = json_decode($result2, true);

	$counter = 0;

if ($result2['suggestions']) {
$suspect = $result2['suggestions'][0];
$content = array("rowIndex"=>0,"pageLength"=>50,"searchText"=>"$suspect");

	$ch3 = curl_init();
	$curl_params3 = array(
		CURLOPT_URL => 'http://www.fedsfm.ru/TerroristSearch',
        	CURLOPT_POST => true,
        	CURLOPT_POSTFIELDS => $content,
		CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
		CURLOPT_TIMEOUT => 10

	);
	curl_setopt_array($ch3, $curl_params3);
	$result3 = curl_exec($ch3);
	$result3 = json_decode($result3, true);

$fedsfm = "<b>".$result3['data'][0]['StatusName']."</b>, ".$result3['data'][0]['FullName'].", ".$result3['data'][0]['TerroristTypeName'];

echo $fedsfm; //нашли в реестре!


}
else {
         $fedsfm = "Не найдено";
	 echo $fedsfm;

     }

?>