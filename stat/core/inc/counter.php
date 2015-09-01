<?php

$site = 'http://' . $_SERVER['HTTP_HOST'];

if ($_SERVER['REQUEST_URI'] !== '/favicon.ico')
{
	$page = $_SERVER['REQUEST_URI'];
}
else
{
	$page = 'viewsource';
}

$user = $_SERVER['REMOTE_ADDR'];
$user = substr($user,0,strrpos($user,'.'));
$ipan = '.xxx';
$user = $user . $ipan;

if (isset($_SERVER['HTTP_REFERER']))	{ $refer = $_SERVER['HTTP_REFERER']; } else	{ $refer = ''; }

$lang = $_SESSION['lang'];

$date = date("d.m.Y");

$time = strftime("%X МСК");

$htmlprotocol = $_SERVER['SERVER_PROTOCOL'];

$ip = $_SESSION['ip'];

$ip = substr($ip,0,strrpos($ip,'.'));
$ip = $ip . $ipan;

$sessionid = session_id();

if (!isset($_SESSION['rank']))
{
	include_once $spath . '/core/inc/rank.php';
}

if (isset($_COOKIE['resolution']))
{
	$resolution = $_COOKIE['resolution'];
}
else
{
	$resolution = 'НД';
}

if (isset($_COOKIE['adblock']))
{
	$adblock = 'adblock';
}
else
{
	$adblock = 'НД';
}

$dbs->exec('INSERT INTO stat (site,page,refer,user,os,platform,udevice,browser,resolution,adblock,date,time,lang,ip,bot,session,userhash,countryiso,country,encountry,city,encity,citylat,citylon,protocol,memory,loadtime) VALUES ("'.$site.'","'.$page.'","'.$refer.'","'.$user.'","'.$os.'","'.$platform.'","'.$udevice.'","'.$browser.'","'.$resolution.'","'.$adblock.'","'.$date.'","'.$time.'","'.$lang.'","'.$ip.'","'.$bot.'","'.$sessionid.'","'.$userhash.'","'.$mycountryiso.'","'.$mycountry.'","'.$myencountry.'","'.$mycity.'","'.$myencity.'","'.$mycitylat.'","'.$mycitylon.'","'.$htmlprotocol.'","'.$memory.'","'.$totaltime.'")');

?>
