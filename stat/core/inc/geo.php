<?php

// http://sypexgeo.net/ru/download/

if (!isset($_SESSION['ip']))
{
	getip();
}
else
{
	$ip = $_SESSION['ip'];
}

//$ip = '212.188.96.1'; // Москва
//$ip = '15.15.15.15'; // usa
//$ip = '79.133.89.15';

if (!isset($_SESSION['mycountry']))
{
	include_once $spath . '/core/Class/SxGeo.php';
		
	$SxGeo = new SxGeo($spath . '/core/dat/SxGeoCity.dat', SXGEO_FILE);
	//$SxGeo = new SxGeo($spath . '/core/dat/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
	
	$city = $SxGeo->getCityFull($ip);
	
	if($city['city']['name_ru'] != null)
	{
		
		$mycountry = $city['country']['name_ru'];
		$_SESSION['mycountry'] = $mycountry;
		
		$myencountry = $city['country']['name_en'];
		$_SESSION['myencountry'] = $myencountry;
		
		$mycity = $city['city']['name_ru'];
		$_SESSION['mycity'] = $mycity;
		
		$myencity = $city['city']['name_en'];
		$_SESSION['myencity'] = $myencity;
		
		$mycitylat = $city['city']['lat'];
		$_SESSION['mycitylat'] = $mycitylat;
		
		$mycitylon = $city['city']['lon'];
		$_SESSION['mycitylon'] = $mycitylon;
		
		$mycountryiso = $city['country']['iso'];
		$_SESSION['mycountryiso'] = $mycountryiso;
		
		$_SESSION['okgeo'] = 'ok';
	}
	else
	{
		
		$mycountry = 'НД';
		$_SESSION['mycountry'] = $mycountry;

		$myencountry = 'НД';
		$_SESSION['myencountry'] = $myencountry;
		
		$mycity = 'НД';
		$_SESSION['mycity'] = $mycity;

		$myencity = 'НД';
		$_SESSION['myencity'] = $myencity;

		$mycitylat = 'НД';
		$_SESSION['mycitylat'] = $mycitylat;

		$mycitylon = 'НД';
		$_SESSION['mycitylon'] = $mycitylon;

		$mycountryiso = 'НД';
		$_SESSION['mycountryiso'] = $mycountryiso;
		
	}
		
	unset($SxGeo);
	
}
else
{
	$mycountry = $_SESSION['mycountry'];

	$myencountry = $_SESSION['myencountry'];
	
	$mycity = $_SESSION['mycity'];

	$myencity = $_SESSION['myencity'];

	$mycitylat = $_SESSION['mycitylat'];

	$mycitylon = $_SESSION['mycitylon'];

	$mycountryiso = $_SESSION['mycountryiso'];
	
}

?>
