<?php

/* https://github.com/serbanghita/Mobile-Detect/wiki/Code-examples */

if( !$detect->isMobile() && !$detect->isTablet() ){
		$platform = 'pc';
		$_SESSION['platform'] = $platform;
	}
else	{
	if( $detect->isMobile() ){
			$platform = 'mobile';
			$_SESSION['platform'] = $platform;
		}
	elseif	( $detect->isTablet() ){
			$platform = 'tablet';
			$_SESSION['platform'] = $platform;
		}
	else	{
			$platform = 'НД';
			$_SESSION['platform'] = $platform;
	}
}

?>
