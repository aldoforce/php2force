<?php
//Includes
require_once('FBOItem.class.php');
require_once('FBOResult.class.php');
require_once('SalesforceConnector.class.php');

function FBOHandler($Links) {
	//check if there's something to process
	if (empty($Links)) die();

	//Declare FBOItems container
	$FBOItemsList = array();
	
	//Populate container with new FBOItem objects
	foreach($Links as $s) {
		$item 		= new FBOItem($s);
		$FBOItemsList[] = $item;	
	}

	//Declare Salesforce Connector
	$sfc = new SalesforceConnector();
	
	$result = new FBOResult();
	
	//iterate FBOItems collection
	foreach($FBOItemsList as $item) {
		//check for valid item
		if (!$item->isValidURL()) continue;
	
		//process	
		$sfc->saveFBOItem($item, $result);
				
	}
	
	//Close Salesforce connection
	$sfc->logout();
	
	return $result->display();

}



?>
