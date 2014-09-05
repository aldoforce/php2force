<?php

//requires Salesforce connector
class SOFViewHistoryHandler {
	private static function getSOQL($pOrdersId, $pIPAddress) {
		//SOF_View_History__c

		$soql = "SELECT " .
				"	Name, " . 
				"	Id, 	" .
				"	IP_Address__c," .
				"	Order__c," .			
				"	PDF_Last_Open__c, " .
				"	PDF_Opened__c, ". 
				"	PDF_Open_Count__c, ". 
				"	Sign_Last_Open__c,".
				"	Sign_Opened__c,".
				"	Sign_Open_Count__c ".				
				"FROM SOF_View_History__c " .
				"WHERE IP_Address__c = '$pIPAddress' AND " . 
				"		Order__c = '$pOrdersId' AND " .
				"		Executed_Signature__c = false " .
				"LIMIT 1";
				
		return $soql;	
	}
	
	public static function init($pOrdersId, $pView, $pSFDC) {
		//get client IP Address
		$ip = $_SERVER['REMOTE_ADDR'];

		//if signature process, force insert of the corresponding audit
		if ($_POST['action'] == 'sign') {
			self::insertSOFViewHistory($pOrdersId, $ip, $pView, $pSFDC, 1);
		}
		else {
			//get SOFViewHistoryPDF
			$svh = self::getSOFViewHistory($pOrdersId, $ip, $pSFDC);

			//debug
			//echo "<pre>". print_r($svh, true) . "</pre><hr/>";
	  
			if ($svh->size > 0) {
				//update			
				self::updateSOFViewHistory($svh, $pView, $pSFDC);
			} 
			else {
				//create new
				self::insertSOFViewHistory($pOrdersId, $ip, $pView, $pSFDC, 0);
			}	
		}


		

	}

	public static function getSOFViewHistory($pOrdersId, $pIPAddress, $pSFDC) {
		if (empty($pOrdersId)) 
			return null;
		
		$q = $pSFDC->query( self::getSOQL($pOrdersId, $pIPAddress) );
		return $q;
	}

	public static function insertSOFViewHistory($pOrdersId, $pIPAddress, $pView, $pSFDC, $pExecutedSignature) {		
		$type	= 'SOF_View_History__c';
		$fields	= array(
			'Order__c'				=> $pOrdersId,
			'IP_Address__c'			=> $pIPAddress,
			'Executed_Signature__c'	=> $pExecutedSignature
		);

		//actual timestamp
		$dt =  gmdate("Y-m-d\TH:i:s\Z");

		if ($pView == 'pdf') {
			$fields['PDF_Opened__c'] 		= $dt;
			$fields['PDF_Last_Open__c'] 	= $dt;
			$fields['PDF_Open_Count__c'] 	= 1;
		}
		else {
			$fields['Sign_Opened__c'] 		= $dt;
			$fields['Sign_Last_Open__c'] 	= $dt;
			$fields['Sign_Open_Count__c'] 	= 1;
		}

		//debug
		//echo "<pre>". print_r($fields, true) . "</pre><hr/>";
		
		//factory builder: create sObject
		$sObject = $pSFDC->create($fields, $type);
		
		//DML
		$pSFDC->insert( array($sObject) );
	}

	public static function updateSOFViewHistory($pSOFViewHistory, $pView, $pSFDC) {
		$MINUTES_FOR_THRESHOLD 	= 5;
		$type					= 'SOF_View_History__c';
		$fields					= array();
		$DML_flag				= false;

		//actual timestamp
		$dt =  gmdate("Y-m-d\TH:i:s\Z");

		//actual datetime
		$now = time();


		if ($pView == 'pdf') {

			//get last datetime
			$last_dt = strtotime( $pSOFViewHistory->records[0]->fields->PDF_Last_Open__c );

			//define a threshold nn minutes from last time
			$threshold = $last_dt + $MINUTES_FOR_THRESHOLD * 60;

			//check if SOF time is expired
			if ($threshold < $now) {
				$fields['PDF_Last_Open__c'] 	= $dt;
				$fields['PDF_Open_Count__c'] 	= $pSOFViewHistory->records[0]->fields->PDF_Open_Count__c + 1;
				$DML_flag 						= true;
			}
		}
		else {
			//get last datetime
			$last_dt = strtotime( $pSOFViewHistory->records[0]->fields->Sign_Last_Open__c );
			

			//define a threshold nn minutes from last time
			$threshold = $last_dt + $MINUTES_FOR_THRESHOLD * 60;
			
			//check if SOF time is expired
			if ($threshold < $now) {
				$fields['Sign_Last_Open__c'] 	= $dt;
				$fields['Sign_Open_Count__c'] 	= $pSOFViewHistory->records[0]->fields->Sign_Open_Count__c + 1;
				$DML_flag 						= true;
			}
		}

		//need a DML operation?
		if ($DML_flag) {
			//factory builder: create sObject
			$sObject = $pSFDC->bind($fields, $type, $pSOFViewHistory->records[0]->Id);
			
			//DML
			$pSFDC->update( array($sObject) );				
		}	
	}
	
}

?>



