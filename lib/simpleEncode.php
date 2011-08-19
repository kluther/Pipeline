<?php
// from http://www.richardwillars.com/articles/php/google-chart-sparkline-encoding-in-php/
function simpleEncode($valueArray,$maxValue) {
	$simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$str = 's:';
	$t = count($valueArray);
	for ($i=0;$i<$t;$i++) {
		$currentValue = $valueArray[$i];
		if ((strlen($currentValue)!=0) && ($currentValue>=0)) {
    		$str .= substr($simpleEncoding,round((strlen($simpleEncoding)-1) * $currentValue / $maxValue),1);
    	}
      	else {
      		$str .= '_';
      	}
  	}
	return $str;
}
