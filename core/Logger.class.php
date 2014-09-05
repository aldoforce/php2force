<?php

//echo getcwd();

//Define BASEDIRs
define("BASEDIR_LOGS", 		"logs/");

class Logger {
	
	public static function dump($data) {
		// Write message to file
		$fh=fopen(BASEDIR_LOGS . 'dump.log','a'); // change location
	
		$stamp = "\n+++ " . date('r') . " : " . time() . " : +++\n";
		
		fwrite($fh,$stamp);
		fwrite($fh, print_r($data, true) );
		fwrite($fh,"\n---------------------------------------------------\n");
		fclose($fh);	
	}

}

?>
