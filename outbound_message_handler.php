<?php
include_once('./core/Logger.class.php');

function processSObject($pSObject) {
	$id 			= $pSObject->Id;

	Logger::dump("Inbound Message from SFDC: ID: $id");

	//do something
	$url = "http://www.acme.com/salesforce/sof/pdf.php?sof=$id";
	
	//dispatch other page
	$a = file_get_contents($url);
}

function ack($value) {
	return array('Ack' => $value);
}


function notifications($data) {	
	//multiple notifications
	if (is_array($data->Notification)) {
	    $result = array();
	    for ($i = 0; $i < count($data->Notification); $i++) {
      		processSObject($data->Notification[$i]->sObject);
      		array_push($result, ack(true));
    	}
    	return $result;    
  	} 
  	//single notification
  	else {
    	processSObject($data->Notification->sObject);
    	return ack(true);
  	}
}



// MAIN LOADER /////////////////////////////////////////

//load specific wsdl for outbound message handler
$server = new SoapServer("./wsdl/om.wsdl.xml");		 
$server->addFunction("notifications");
$server->handle();  

?>



