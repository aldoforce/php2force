<?php

class TemplateHandler {
	public $SFDC;
	
	//Constructor
	function __construct($pOId_encoded) {
		//Create Salesforce Connector		
		$this->SFDC = SalesforceConnector::getInstance();
			
		//init container

		//init logic
	}
}

?>
