<?php
$GOOGLEHOST='toolbarqueries.google.com'; 
$USERAGENT='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.6) Gecko/20060728 Firefox/1.5'; 

function StrToNum($Str, $Check, $Magic) { 
    $Int32Unit = 4294967296; 

    $length = strlen($Str); 
    for ($i = 0; $i < $length; $i++) { 
        $Check *= $Magic;      
        if ($Check >= $Int32Unit) { 
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit)); 
            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check; 
        } 
        $Check += ord($Str{$i});  
    } 
    return $Check; 
} 

function HashURL($String) { 
    $Check1 = StrToNum($String, 0x1505, 0x21); 
    $Check2 = StrToNum($String, 0, 0x1003F); 

    $Check1 >>= 2;      
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F); 
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF); 
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);     
     
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F ); 
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 ); 
     
    return ($T1 | $T2); 
} 

function CheckHash($Hashnum) { 
    $CheckByte = 0; 
    $Flag = 0; 

    $HashStr = sprintf('%u', $Hashnum) ; 
    $length = strlen($HashStr); 
     
    for ($i = $length - 1;  $i >= 0;  $i --) { 
        $Re = $HashStr{$i}; 
        if (1 === ($Flag % 2)) {               
            $Re += $Re;      
            $Re = (int)($Re / 10) + ($Re % 10); 
        } 
        $CheckByte += $Re; 
        $Flag ++;     
    } 

    $CheckByte %= 10; 
    if (0 !== $CheckByte) { 
        $CheckByte = 10 - $CheckByte; 
        if (1 === ($Flag % 2) ) { 
            if (1 === ($CheckByte % 2)) { 
                $CheckByte += 9; 
            } 
            $CheckByte >>= 1; 
        } 
    } 

    return '7'.$CheckByte.$HashStr; 
} 

function getch($url) { return CheckHash(HashURL($url)); } 

function getpr($url) { 
    global $GOOGLEHOST,$USERAGENT; 
    $ch = getch($url); 
    $fp = fsockopen($GOOGLEHOST, 80, $errno, $errstr, 30); 
                if ($fp)
                { 
                $out = "GET /tbr?features=Rank&sourceid=navclient-ff&client=navclient-auto-ff&ch=$ch&q=info:$url HTTP/1.1\r\n"; 
                $out .= "User-Agent: $USERAGENT\r\n"; 
                $out .= "Host: $GOOGLEHOST\r\n"; 
                $out .= "Connection: Close\r\n\r\n"; 
     
                fwrite($fp, $out); 
                        while (!feof($fp))
                        {
            $data = fgets($fp, 128); 
            $pos = strpos($data, "Rank_"); 
                                if($pos === false)
                                {}
                                else
                                { 
                $gpr=substr($data, $pos + 9); 
                $gpr=trim($gpr); 
                $gpr=str_replace("\n",'',$gpr); 
                                if (isset($gpr)) $pr=$gpr;
                        } 
               }
                if (!isset($pr)) $pr="0";
                return $pr;
                fclose($fp); 
                } 
}

function name_get_tiq($domain) {
  $xml_data = file_get_contents('http://bar-navig.yandex.ru/u?ver=2&show=32&url=' . $domain);
  $tiq = $xml_data ? (int) substr(strstr($xml_data, 'value="'), 7) : 'N/A';
  return $tiq;
}

$ranksite = 'http://' . $_SERVER['HTTP_HOST'];

include_once $spath . '/core/class/Pinger.php';

$ping = new Pinger('bar-navig.yandex.ru');
if ($ping)
{
	$yarank = name_get_tiq($ranksite);
}
else
{
	$yarank = 'НД';
}

$ping = new Pinger('toolbarqueries.google.com');
if ($ping)
{
	$googlerank = getpr($ranksite);
}
else
{
	$googlerank = 'НД';
}

$rankdate = date("d.m.Y");
$ranktime = strftime("%X МСК");

$dbs->exec('INSERT INTO rank (site,googlerank,yarank,date,time) VALUES ("'.$ranksite.'","'.$googlerank.'","'.$yarank.'","'.$rankdate.'","'.$ranktime.'")');

$_SESSION['rank'] = 'ranked';

?>
