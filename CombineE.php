<?php

//Spotify API
$url = "https://api.spotify.com/v1/me/player/currently-playing?market=ES&additional_types=episode";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


// SPOTIFY AUTH TOKEN

$headers = array(
   "Authorization: Bearer REPLACE_THIS_WITH_TOKEN_LIKE_PASTE_OVER_IT",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
//var_dump($resp);

$array = json_decode($resp, true);
$artistname = ($array['item']['artists']['0']['name']);
$songname = ($array['item']['name']);
echo("Song name: " .$songname);
echo "<br>";
echo("Artist: " .$artistname);
echo "<br>";



//Genius API


//Search by song and artist
$url2 = "https://api.genius.com/search?q=";
$bestquery = $songname . ' ' . $artistname;
$encodedquery = (rawurlencode($songname));
$bestencode = (rawurlencode($bestquery));
//echo $bestencode;
echo "<br>";

//echo $bestquery;
//echo "<br>";



$url2 .= $bestencode;

//echo $url2;
//echo "<br>";


$curl = curl_init($url2);
curl_setopt($curl, CURLOPT_URL, $url2);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// GENIUS AUTH TOKEN

$headers = array(
   "Authorization: Bearer REPLACE_THIS_WITH_TOKEN_LIKE_PASTE_OVER_IT",
);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
//echo $resp;
$array = json_decode($resp, true);
//var_dump($array);

$songlist = ($array['response']['hits']);

//echo "<br>";

//Search by Song & Artist 
foreach ($songlist as $value) {
	//echo $value['result']['title'];//, "\n";
	$geniussong = $value['result']['title'];
	$ourartist = $value['result']['primary_artist']['name'];
	//echo $ourartist;
	$geniussong = trim($geniussong);
	//echo ("TEST: " . $geniussong);
	//echo "<br>";
	//echo "test1";
	if (($ourartist == $artistname && $geniussong == $songname) || ($ourartist == $artistname || $geniussong == $songname)){
		
		$flag = true;
		//echo $value['result']['title'];
		//echo "<br>";

		$goodurl = $value['result']['url'];
		break;
	}
	else
	{
		$flag = false;
	}
}

//New Search by Artist only

$flag2 = false;

if($flag == false){
	$url3 = "https://api.genius.com/search?q=";
	$encodedquery = (rawurlencode($artistname));

	$url3 .= $encodedquery;

	//echo $url3;
	//echo "<br>";


	$curl = curl_init($url3);
	curl_setopt($curl, CURLOPT_URL, $url3);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	//GENIUS TOKEN JUST LIKE ABOVE AND BELOW, USE SAME TOKEN
	
	$headers = array(
   	"Authorization: Bearer REPLACE_THIS_WITH_TOKEN_LIKE_PASTE_OVER_IT",
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	//for debug only!
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$resp2 = curl_exec($curl);
	//echo $resp;
	$array2 = json_decode($resp2, true);
	//var_dump($array);

	$songlist2 = ($array2['response']['hits']);
	foreach ($songlist2 as $value) {
	 //print_r("$value <br>");// "$value <br>";
	//echo $value['result']['title'];//, "\n";
	//echo $songname;
	$geniussong2 = $value['result']['title'];
	//$geniussong2 = trim($geniussong2);
	//echo $geniussong2;
	$ourartist = $value['result']['primary_artist']['name'];

	//echo "<br>";
	if ($geniussong2 == $songname || $ourartist == $artistname){
		
		//echo $value['result']['url'];
		$flag = true;
		//echo $value['result']['title'];
		//echo "<br>";
		//echo "test2";

		$goodurl = $value['result']['url'];
		break;
	}
	else
	{
		$flag2 = false;
		//echo("Not found <br>");
	}
}

}


//New Search by Song only




if($flag2 == false)
{
	$url4 = "https://api.genius.com/search?q=";
	$cleanedsong = preg_replace("/\([^)]+\)/", "", $songname);
	$encodedquery2 = (rawurlencode($cleanedsong));
	//echo $cleanedsong;
	//$new_str = str_replace(' ', '', $cleanedsong);
	$new_str = trim($cleanedsong);

	$url4 .= $encodedquery2;
	//echo ("TEST " . $url4);
	//echo "<br>";
	//echo (":" . $new_str);

	$curl = curl_init($url4);
	curl_setopt($curl, CURLOPT_URL, $url4);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$headers = array(
	   "Authorization: Bearer REPLACE_THIS_WITH_TOKEN_LIKE_PASTE_OVER_IT",
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	//for debug only!
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$resp3 = curl_exec($curl);
	//echo $resp;
	$array3 = json_decode($resp3, true);
	//var_dump($array);

	$songlist3 = ($array3['response']['hits']);


	foreach ($songlist3 as $value) {
	 //print_r("$value <br>");// "$value <br>";
		//echo $value['result']['title'];//, "\n";
		$geniussong3 = $value['result']['title'];
		$ourartist = $value['result']['primary_artist']['name'];

		//echo "<br>";
		if (($geniussong3 == $new_str || $geniussong3 == $cleanedsong) && $ourartist == $artistname){
			//echo $value['result']['url'];
			$flag = true;
			//echo $value['result']['title'];
			//echo "<br>";
			//echo "test3";
			$goodurl = $value['result']['url'];
			break;
		}
		else
		{
			$flag3 = false;
			//echo("Not found <br>");
		}
}
}

//$goodurl = ($array['response']['hits'][0]['result']['url']);
//echo "<br>";

//echo ($goodurl);
//echo "<br>";

//print_r($goodurl);

//Web scraping 

require_once 'simple_html_dom.php';
if($flag == true || $flag2 == true || $flag3 == true)
{
	$html = file_get_html($goodurl);
	echo ("Lyrics Link: " . $goodurl);
	echo "<br>";
	echo "<br>";
	echo "<br>";


foreach($html->find('div[class=Lyrics__Container-sc-1ynbvzw-6 krDVEH]') as $element){
	echo $element->innertext . '<br>';
	



} 

}

else
{
	echo ("Could not locate song lyrics from Genius Library...	:( ");
	echo "<br>";

}

//echo $paragraph;



?>
