<?php

//requires Salesforce connector
class PODHandler {
	private static function getSOQL($pId) {
		//PODs Record Type
		$recordType = '012d0000000wxVjAAI';

		//PODs 
		$soql = "SELECT ".
							"POD_Product_Name__c, ".
							"POD_Type__c, ".				
							"Qantity__c, ".			
							"MRC_Amount__c, ".
							"NRC_Amount__c, ".
							"Name, ".							
							"Term__c ".
						"FROM Product__c ".				
						"WHERE Order__c = '$pId' AND ".
								"RecordTypeId = '$recordType' ".								
								"AND (MRC_Amount__c > 0 OR NRC_Amount__c > 0) ";
				
		return $soql;	
	}
	
	public static function getPODs($pOrdersId, $pSFDC) {
		if (empty($pOrdersId)) 
			return null;
		
		//send query
		$q 	= $pSFDC->query( self::getSOQL($pOrdersId) );		
		
		//init container
		$rows 	= array();
		
		//break
		$br		= "<br/>";
		
		//no shipping
		foreach($q->records as $record) {			
			$D = array();
			
			$qty = number_format($record->fields->Qantity__c, 0);
			$pod = $record->fields->POD_Type__c;

			//description
			$desc = $record->fields->POD_Product_Name__c != 'Shipping Charge' ?
								"$qty $pod (s)" :
								"Shipping Charge";

			$D["POD_DESCRIPTION"]			=	$desc;
			$D["POD_TERM_MONTHS"]			=	number_format(doubleval($record->fields->Term__c), 0);
			$D["POD_NRC_AMOUNT"]			=	number_format(doubleval($record->fields->NRC_Amount__c),
																		2, '.', ',');
			$D["POD_MRC_AMOUNT"]			=	number_format(doubleval($record->fields->MRC_Amount__c),
																		2, '.', ',');
	
												 
			$rows[] = $D;
		}

		return $rows;
	}
	
}

?>



