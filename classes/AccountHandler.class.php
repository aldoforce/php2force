<?php

//requires Salesforce connector
class AccountHandler {
	private static function getSOQL($pId) {
		//Account
		$soql = "SELECT ".
					//"CurrencyIsoCode, ".
					"Fax, ".
					"Name, ".
					"Account_Number__c, ".
					"Phone, ".
					"BillingCity, ".
					"BillingCountry, ".
					"BillingState, ".
					"BillingStreet, ".
					"BillingPostalCode, ".
					"Carrier_License_Number__c, ".
					"HQ_Address__c, ".
					"HQ_Address_2__c, ".
					"HQ_City__c, ".
					"HQ_Country__c, ".
					"HQ_State__c, ".
					"HQ_Zip__c, ".
					"MSA_On_File__c, ".
					"MSA_Executed_Date__c, ".
					"Primary_Contact__c, ".
					"Technical_Contact__c, ".
					"Billing_Contact__c, ".
					"Billing_Entity__c, ".
					"Back_Up_Technical_Contact__c, ".
					"NOC_Contact__c, ".
					"Payment_Method__c ".				
				"FROM Account  ".
				"WHERE Id = '$pId'";
				
		return $soql;	
	}
	
	public static function getAccount($pAccountId, $pSFDC) {
		if (empty($pAccountId)) 
			return null;
		
		$q = $pSFDC->query( self::getSOQL($pAccountId) );
		return $q;
	}
	
}

?>



