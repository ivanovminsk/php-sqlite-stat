<?php

// плагин статистики


function stime()
{
	
	global $mtime, $tstart, $mem_start;
	
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$tstart = $mtime[1] + $mtime[0];
	
	$mem_start = memory_get_usage();
	
}

function etime()
{
	
	global $ememory, $memory, $mtime, $tstart, $mem_start, $totaltime;
	
	$ememory = memory_get_usage() - $mem_start;
	$memory = round($ememory/1024/1024, 2) . 'MB';

	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$totaltime = ($mtime - $tstart);//Вычисляем разницу
	$totaltime = round($totaltime, 5) . ' секунд';
	
}

function startsession()
{
	if (!session_start())
	{
		session_start();
	}
	if (!isset($_SESSION))
	{
		die('Сессiя не работаетъ!');
	}
}

function whoisonline()
{
	global $usersonline, $dbs, $time4online;
	
	$testtime = $dbs->query('
		SELECT count(id) FROM online LIMIT 1;
	')->fetchColumn();
	
	if($testtime == 0)
	{
		$date = date("d.m.Y");
		$starttime = time();
		$endtime = time() + $time4online;
		$nstarttime = strftime('%X МСК', $starttime);
		$nendtime = strftime('%X МСК', $endtime);
		$dbs->exec('INSERT INTO online (date,starttime,endtime,usersonline,nstarttime,nendtime) VALUES ("'.$date.'","'.$starttime.'","'.$endtime.'","1","'.$nstarttime.'","'.$nendtime.'")');
	}
	
	$nowtime = time();
	
	$lasttime = $dbs->query('
		SELECT endtime FROM online ORDER BY id DESC LIMIT 1;
	')->fetchColumn();
	
	if ($nowtime - $lasttime > $time4online)
	{
		$date = date("d.m.Y");
		$starttime = time();
		$endtime = time() + $time4online;
		$nstarttime = strftime('%X МСК', $starttime);
		$nendtime = strftime('%X МСК', $endtime);
		$dbs->exec('INSERT INTO online (date,starttime,endtime,usersonline,nstarttime,nendtime) VALUES ("'.$date.'","'.$starttime.'","'.$endtime.'","1","'.$nstarttime.'","'.$nendtime.'")');
		
		$idonline = $dbs->query('SELECT id FROM online ORDER BY id DESC LIMIT 1;')->fetchColumn();
		$_SESSION['onlineid'] = $idonline;
	}
	else
	{
		$idonline = $dbs->query('SELECT id FROM online ORDER BY id DESC LIMIT 1;')->fetchColumn();
		if(!isset($_SESSION['onlineid']))
		{
			$_SESSION['onlineid'] = $idonline;
		}
		
		
		if ($idonline !== $_SESSION['onlineid'])
		{
			$usersonline = $dbs->query('SELECT usersonline FROM online ORDER BY id DESC LIMIT 1;')->fetchColumn();
			$usersonline = $usersonline + 1;
			$dbs->query('UPDATE online SET usersonline='. $usersonline .' WHERE id='. $idonline .';');
			
			$_SESSION['onlineid'] = $idonline;
		}
	}
	
	$usersonline = $dbs->query('SELECT usersonline FROM online WHERE id='. $idonline .' LIMIT 1;')->fetchColumn();
	
}



function request_url()
{
  $result = '';
  $default_port = 80;
  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
    $result .= 'https://';
    $default_port = 443;
  } else {
    $result .= 'http://';
  }
  $result .= $_SERVER['SERVER_NAME'];
  if ($_SERVER['SERVER_PORT'] != $default_port) {
    $result .= ':'.$_SERVER['SERVER_PORT'];
  }
  $result .= $_SERVER['REQUEST_URI'];
  return $result;
}

function request_mainurl()
{
  $result = '';
  $default_port = 80;
  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
    $result .= 'https://';
    $default_port = 443;
  } else {
    $result .= 'http://';
  }
  $result .= $_SERVER['SERVER_NAME'];
  if ($_SERVER['SERVER_PORT'] != $default_port) {
    $result .= ':'.$_SERVER['SERVER_PORT'];
  }
  return $result;
}





function getip()
{

	global $ip;
	
	if (!empty($_SERVER['HTTP_X_REAL_IP']))   {
			$ip = $_SERVER['HTTP_X_REAL_IP'];
        }
	elseif (!empty($_SERVER['HTTP_CLIENT_IP']))   {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
	else	{
            $ip = $_SERVER['REMOTE_ADDR'];
	}
	
	$_SESSION['ip'] = $ip;

}



function helloworld(){
	if (!isset($_SESSION['helloworld']))
	{
		$_SESSION['helloworld'] = time();
	}
}



function generateunique() {	
	return sha1( time() . session_name() );
}







function getdberror()
{

global $dberror, $spath, $dbs, $dbpath, $dbstatname;
	
if(isset($dberror))
{
	
	getip();
	
	$session = session_id();
	
	if(!$dbs)
	{
		date_default_timezone_set('Europe/Minsk');
		
		$dbscheck = 'ok';

		$dbs = new PDO('sqlite:' . $dbpath . $dbstatname);
		
		$dbs->exec('PRAGMA journal_mode = MEMORY;');
		$dbs->exec('PRAGMA synchronous = OFF;');
		$dbs->exec('PRAGMA PAGE_SIZE = 4096;');
		$dbs->exec('PRAGMA busyTimeout = 7000;');
		$dbs->exec('PRAGMA encoding = "UTF-8";');
		$dbs->exec('PRAGMA count_changes = off;');
		$dbs->exec('PRAGMA temp_store = MEMORY;');
		$dbs->exec('PRAGMA foreign_keys = true;');
		$dbs->exec('PRAGMA locking_mode = exclusive;');
		$dbs->exec('BEGIN IMMEDIATE;');
	}
	
	$errorname = 'DB ошибка';
	$date = date("d.m.Y");
	$time = strftime("%X МСК");
	$page = $dberror;
	
	$dbs->exec('INSERT INTO errors (name,page,date,time,ip,session) VALUES ("'.$errorname.'","'.$page.'","'.$date.'","'.$time.'","'.$ip.'","'.$session.'")');
	
	if(isset($dbscheck))
	{
		$dbs->exec('COMMIT;');
		$dbs = null;
	}
	
}

}

?>
