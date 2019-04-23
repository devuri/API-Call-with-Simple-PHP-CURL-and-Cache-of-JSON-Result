<?php


// settings
$url 			= "https://example.com/queryforjson"; // json source
$cache 			= __DIR__."/json.cache"; // make this file in same dir
$force_refresh	 	= false; // dev
$refresh		= 60*60; // once an hour
// cache json results so to not over-query (api restrictions)
if ($force_refresh || ((time() - filectime($cache)) > ($refresh) || 0 == filesize($cache))) {
	// read json source
	$ch = curl_init($url) or die("curl issue");
	$curl_options = array(
		CURLOPT_RETURNTRANSFER	=> true,
		CURLOPT_HEADER 		=> false,
		CURLOPT_FOLLOWLOCATION	=> false,
		CURLOPT_ENCODING	=> "",
		CURLOPT_AUTOREFERER 	=> true,
		CURLOPT_CONNECTTIMEOUT 	=> 7,
		CURLOPT_TIMEOUT 	=> 7,
		CURLOPT_MAXREDIRS 	=> 3,
		CURLOPT_SSL_VERIFYHOST	=> false,
		CURLOPT_USERAGENT	=> "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13"
	);
	curl_setopt_array($ch, $curl_options);
	$curlcontent = curl_exec( $ch );
	curl_close( $ch );
	
	$handle = fopen($cache, 'wb') or die('no fopen');	
	$json_cache = $curlcontent;
	fwrite($handle, $json_cache);
	fclose($handle);
} else {
	$json_cache = file_get_contents($cache); //locally
}
$jsonobject = new SimpleXMLElement($json_cache);
echo "<pre>".print_r($jsonobject,1)."</pre>";
?>
