<?php

class Pinger {
	
	public function __construct($host,$port=80,$supressWarning=false,$timeout=2) {
		
		if(!is_string($host) OR !is_int($port) OR !is_bool($supressWarning) OR !is_int($timeout)) {
			$connection = @fsockopen($host,$port, $errno, $errstr, $timeout);
		} else {
			$connection = fsockopen($host,$port, $errno, $errstr, $timeout);
		}

		if(!$connection)
		{
			return false;
		}
		
		fclose($connection);
		
		return true;
	}
	
}

?>
