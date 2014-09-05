<?php

//requires Salesforce connector
class OrdersHandler {	
	private static function getSOQL($pId) {
		//Orders
		$soql = "SELECT " .
				"	o.Name, " . 
				"	o.Id, 	" .
				"FROM Orders__c o " .
				"WHERE o.Id = '$pId'";
				
		return $soql;	
	}
	
	public static function getOrders($pOrdersId, $pSFDC) {
		if (empty($pOrdersId)) 
			return null;
		
		$q = $pSFDC->query( self::getSOQL($pOrdersId) );
		return $q;
	}
	
	public static function setSignature($pOrdersId, $pSignedBy, $pSignedByTitle, $pMSA, $pOther, $pSFDC) {
		$Id 	= $pOrdersId;
		$type 	= 'Orders__c';
		$fields = array(
			'Stage__c' 				=> 'Won',
			'SOF_Signed__c' 		=> gmdate("Y-m-d\TH:i:s\Z"),
			'SOF_Signed_by__c' 		=> $pSignedBy,	
			'SOF_Signed_Title__c' 	=> $pSignedByTitle,
			'SOF_Term_Selected__c' 	=> $pMSA,
			'SOF_Other_Agreement__c'=> $pMSA == 'MSA' ?
										'' :
										$pOther
		);
		$sObject = $pSFDC->bind($fields, $type, $Id);
		
		$pSFDC->update(array($sObject));
	}
	 
}

?>



