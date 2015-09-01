<?php

if (class_exists('DB'))
{
	dbclose();
}

include_once 'spath.php';

include_once $spath . '/core/functions.php';

startsession();

stime();

setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');

date_default_timezone_set('Europe/Minsk');

include_once $spath . '/core/class/DataBase.php';

include_once $spath . '/core/class/Pinger.php';

if (isset($_COOKIE['lang']))	{
	$_SESSION['lang'] = $_COOKIE['lang'];
	$langok = $_SESSION['lang'];
}
else
{
	include_once $spath . '/core/inc/langs.php';
	include_once $spath . '/core/inc/langdetect.php';
	$langok = $_SESSION['lang'];
}

include_once $spath . '/core/inc/geo.php';

if (!isset($_SESSION['browser']))
{
	include_once $spath . '/core/class/BrowserDetect.php';
}
else
{
	$browser = $_SESSION['browser'];
}

if(!isset($_SESSION['os'])){
	include_once $spath . '/core/class/OsDetect.php';
}
else
{
	$os = $_SESSION['os'];
}

if(!isset($_SESSION['platform'])){
	
	include_once $spath . '/core/Class/Mobile_Detect.php';
	$detect = new Mobile_Detect;
	include_once $spath . '/core/inc/platform.php';
	
}
else
{
	$platform = $_SESSION['platform'];
}

if(!isset($_SESSION['udevice'])){
	include_once $spath . '/core/class/DeviceDetect.php';
}
else
{
	$udevice = $_SESSION['udevice'];
}

if (!isset($_SESSION['bot']))
{
	include_once $spath . '/core/class/BotDetect.php';
}
else
{
	$bot = $_SESSION['bot'];
}

if (!isset($_COOKIE["userhash"])) {
    $userhash = md5(time() . session_name());
    setcookie("userhash", $userhash);
}
else
{
	$userhash = $_COOKIE["userhash"];
}









?>
