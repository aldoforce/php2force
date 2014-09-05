<?php

//requires Salesforce connector
class ContactHandler {
	private static function getSOQL($pId) {
		$soql = "SELECT ".
				"	Phone, ".
				"	Email, ".
				"	Firstname, ".
				"	Lastname, ".
				"	Name, ".
				"	Title, ".
				"	MobilePhone ".								
				"FROM ".
				"	Contact ".
				"WHERE ".
				"	Id = '{ID}'";
		
		$soql = str_replace('{ID}', $pId, $soql);
		
		return $soql;	
	}
	
	public static function getContact($pContactId, $pSFDC) {
		if (empty($pContactId)) 
			return null;
		
		$q = $pSFDC->query( self::getSOQL($pContactId) );
		return $q;
	}
	
}

?>



