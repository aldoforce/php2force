<?php

//requires Salesforce connector
class CircuitHandler {
	

	private static function getSOQL($pId) {
		//Circuit Record Type
		$recordType = '012d0000000qfcYAAQ';
		
		//Circuits
		$soql = "SELECT ".
					"A_End_Demarcation__c, ".
					"A_End_Address__c, ".
					"A_End_City__c, ".
					"A_End_Contact__c, ".
					"A_End_Contact_2__c, ".
					"A_End_Country__c, ".
					"A_End_State__c, ".
					"A_End_Zip__c, ".
					"A_Location_Code__c, ".
					"A_NPA_NXX__c, ".
					"Account_Name__c, ".
					"Account_Number__c, ".
					"A_End_Connector_Type__c, ".
					"Billing_Reviewed__c, ".
					"Name, ".
					//"Circuit_Name__c, ".
					//"Circuit_Number2__c, ".
					//"Circuit_Number3__c, ".
					"Circuit_Type__c, ".
					//"Experation__c, ".
					"CreatedById, ".
					"CreatedDate, ".
					"CurrencyIsoCode, ".
					"Customer_Billing_Start_Date__c, ".
					"Description__c, ".
					"Jumbo_Frames_Allowed__c, ".
					"LastActivityDate, ".
					"LastModifiedById, ".
					"LastModifiedDate, ".
					"MRC_Amount__c, ".
					"Network_Protection__c, ".
					"NRC_Amount__c, ".		
					"Order_Number__c, ".
					"Order_Status__c, ".
					"Order__c, ".
					"Id, ".
					"Request_for_Service_Date__c, ".
					"Request_for_Service_ASAP__c, ".
					"SystemModstamp, ".
					"Target_MRC_Bid__c, ".
					"Target_RTD__c, ".
					"Term__c, ".
					//"Total_MRC_Contracted__c, ".
					//"Total_Sold_Contract_Value__c, ".
					"Z_End_Demarcation__c, ".
					"Z_End_Address__c, ".
					"Z_End_City__c, ".
					"Z_End_Contact__c, ".
					"Z_End_Contact_2__c, ".
					"Z_End_Country__c, ".
					"Z_End_State__c, ".
					"Z_End_Zip__c, ".
					"Z_Location_Code__c, ".
					"Z_NPA_NXX__c, ".
					"Z_End_Connector_Type__c ".
				"FROM Product__c  ". 
				"WHERE Order__c = '$pId' AND ".
						"RecordTypeId = '$recordType' ".
						"AND (MRC_Amount__c > 0 OR NRC_Amount__c > 0) ".
				"ORDER BY Product_Sort_Order__c";
				
		return $soql;	
	}
	
	public static function getCircuits($pOrdersId, $pSFDC) {
		if (empty($pOrdersId)) 
			return null;
		
		//send query
		$q 		= $pSFDC->query( self::getSOQL($pOrdersId) );		

		//init container
		$rows 	= array();
		
		//break
		$br		= "<br/>";
		
		
		
		
		foreach($q->records as $record) {  
			//$record->fields->Name
			$D = array();
			$D["CIRCUIT_ID"]						=	$record->Id;
			$D["CIRCUIT_A_END"]						= 	$record->fields->A_End_Address__c 	. $br .
														$record->fields->A_End_City__c 		. ", " .
														$record->fields->A_End_State__c 	. " " . 
														$record->fields->A_End_Zip__c 		. $br .
														$record->fields->A_End_Country__c 	. $br .
														$record->fields->A_End_Demarcation__c 	. $br;														
														
			$D["CIRCUIT_Z_END"]						= 	$record->fields->Z_End_Address__c 	. $br .
														$record->fields->Z_End_City__c 		. ", " .
														$record->fields->Z_End_State__c 	. " " . 
														$record->fields->Z_End_Zip__c 		. $br .
														$record->fields->Z_End_Country__c 	. $br .
														$record->fields->Z_End_Demarcation__c 	. $br;
																														 												
			$D["CIRCUIT_BANDWIDTH"]					= $record->fields->Circuit_Type__c;
			$D["CIRCUIT_NETWORK_PROTECTION"]		= $record->fields->Network_Protection__c;
			$D["CIRCUIT_A_END_CONNECTOR_TYPE"]		= $record->fields->A_End_Connector_Type__c;
			$D["CIRCUIT_B_END_CONNECTOR_TYPE"]		= $record->fields->Z_End_Connector_Type__c;			
			$D["CIRCUIT_REQUEST_FOR_SERVICE_DATE"]	= $record->fields->Request_for_Service_ASAP__c == 'true' ?
														'ASAP' :
														$record->fields->Request_for_Service_Date__c;

			$D["CIRCUIT_NAME"]						= $record->fields->Name;
			$D["CIRCUIT_JUMBO_FRAMES_ALLOWED"]		= $record->fields->Jumbo_Frames_Allowed__c;
			$D["CIRCUIT_TERM_MONTHS"]				= number_format($record->fields->Term__c,
																	0);
			$D["CIRCUIT_NRC_RETAIL_AMOUNT"]			= number_format((float)$record->fields->NRC_Amount__c, 
																	2, '.', ',');
			$D["CIRCUIT_MRC_AMOUNT"]				= number_format((float)$record->fields->MRC_Amount__c,
																	2, '.', ',');

			$rows[] = $D;
		}

		return $rows;
	}
	
}

?>



