<?php

function parseSFDatetime($d) {
	$a = date_parse($d);

	$mons = array(	1 => "January", 
					2 => "February", 
					3 => "March", 
					4 => "April", 
					5 => "May", 
					6 => "June", 
					7 => "July", 
					8 => "August", 
					9 => "September", 
					10 => "October", 
					11 => "Nove,ber", 
					12 => "December"
	);

	
	$month 	= $mons[$a['month']];
	$day 	= $a['day'];
	$year	= $a['year'];
	

	return "$month $day, $year";
}

$d = "2012-09-20T22:22:15.000Z";

//echo parseSFDatetime($d);
echo "<pre>" . print_r(date_parse($d), true) . "</pre>";

?>



