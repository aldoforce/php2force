<?php
//Include
require_once('simple_html_dom.php');

class FBOItem {
	 
	public $SolNbr; 
	public $Title;
	public $Agency;
	public $Office;
	public $Location;
	public $PostedOn;
	public $CurrentType;
	public $BaseType;
	public $BasePostingDate;
	public $Synopsis;
	public $Link;
	public $ContractingOfficeAddress;
	public $ShipTo;
	public $PrimaryContact;
	public $SecondaryContact;
	public $OriginalSetAside;
	public $NAICSCode;
	public $SetAside;
	public $ClassificationCode;
	public $Lastname;


	public function getSolNbr() {
	return $this->sanitize($this->SolNbr);
	}

	public function setSolNbr($SolNbr) {
	$this->SolNbr = $SolNbr;
	}

	public function getTitle() {
	return $this->sanitize(substr($this->Title, 0, 80) );
	}

	public function setTitle($Title) {
	$this->Title = $Title;
	}

	public function getAgency() {
	return $this->sanitize($this->Agency);
	}

	public function setAgency($Agency) {
	$this->Agency = $Agency;
	}

	public function getOffice() {
	return $this->sanitize($this->Office);
	}

	public function setOffice($Office) {
	$this->Office = $Office;
	}

	public function getLocation() {
	return $this->sanitize($this->Location);
	}

	public function setLocation($Location) {
	$this->Location = $Location;
	}

	public function getPostedOn() {
	return $this->sanitize($this->PostedOn);
	}

	public function setPostedOn($PostedOn) {
	$this->PostedOn = $PostedOn;
	}

	public function getCurrentType() {
	return $this->sanitize($this->CurrentType);
	}

	public function setCurrentType($CurrentType) {
	$this->CurrentType = $CurrentType;
	}

	public function getBaseType() {
	return $this->sanitize($this->BaseType);
	}

	public function setBaseType($BaseType) {
	$this->BaseType = $BaseType;
	}

	public function getBasePostingDate() {
	return $this->sanitize($this->BasePostingDate);
	}

	public function setBasePostingDate($BasePostingDate) {
	$this->BasePostingDate = $BasePostingDate;
	}

	public function getSynopsis() {
	return $this->sanitize($this->Synopsis);
	}

	public function setSynopsis($Synopsis) {
	$this->Synopsis = $Synopsis;
	}

	public function getLink() {
	return $this->sanitize($this->Link);
	}

	public function setLink($Link) {
	$this->Link = $Link;
	}

	public function getContractingOfficeAddress() {
	return $this->sanitize($this->ContractingOfficeAddress);
	}

	public function setContractingOfficeAddress($ContractingOfficeAddress) {
	$this->ContractingOfficeAddress = $ContractingOfficeAddress;
	}

	public function getShipTo() {
	return $this->sanitize($this->ShipTo);
	}

	public function setShipTo($ShipTo) {
	$this->ShipTo = $ShipTo;
	}

	public function getClassificationCode() {
	return $this->sanitize($this->ClassificationCode);
	}

	public function setClassificationCode($ClassificationCode) {
	$this->ClassificationCode = $ClassificationCode;
	}

	public function getPrimaryContact() {	
	return $this->sanitize($this->PrimaryContact);	
	}

	public function setPrimaryContact($PrimaryContact) {
	$this->PrimaryContact = $PrimaryContact;
	}

	public function getSecondaryContact() {
	return $this->sanitize($this->SecondaryContact);
	}

	public function setSecondaryContact($SecondaryContact) {
	$this->SecondaryContact = $SecondaryContact;
	}

	public function getSetAside() {
	return $this->sanitize($this->SetAside);
	}

	public function setSetAside($SetAside) {
	$this->SetAside = $SetAside;
	}

	public function getOriginalSetAside() {
	return $this->sanitize($this->OriginalSetAside);
	}

	public function setOriginalSetAside($OriginalSetAside) {
	$this->OriginalSetAside = $OriginalSetAside;
	}

	public function getNAICSCode() {
	return $this->sanitize($this->NAICSCode);
	}

	public function setNAICSCode($NAICSCode) {
	$this->NAICSCode = $NAICSCode;
	}
	
	public function getLastname() {
	$s = substr($this->getPrimaryContact, 0, 20); 
	return empty($s) ? 'Unknown' : $this->sanitize($s);
	}

	public function setLastname($s) {
	$this->Lastname = $s;
	}

	/** 
	* CONSTRUCTORs
	*
	*/
	function __construct($url = null) {
		//Link
		$this->setLink($url);

		if ($this->isValidURL() ) { 
			$this->parseLink($url);
		}
	}
	
	private function parseLink($link) {
		// get DOM from URL or file
		$html = file_get_html($link);

		//---------------------------------------------//
		//Solicitation Number
		$SolNbr = $html->find('div#dnf_class_values_procurement_notice__solicitation_number__widget', 0)->innertext;
		$this->setSolNbr($SolNbr);

		//---------------------------------------------//
		//Title
		$Title 	= $html->find('div.agency-header h2', 0)->innertext;
		$this->setTitle($Title);

		//---------------------------------------------//
		//Agency Office Location Wrapper
		$s = explode("<br>",
				$html->find('div.agency-name',0)->innertext
		     );

		//Agency 
		$Agency = trim(substr(
				 trim($s[0]),
				 7

				));
		$this->setAgency($Agency);			

		//---------------------------------------------//
		//Office
		$Office = trim(substr(
				 trim($s[1]),
				 7
				));
		$this->setOffice($Office);		

		//---------------------------------------------//
		//Location
		$Location = trim(substr(
				 trim($s[2]),
				 9
				));
		$this->setLocation($Location);

		//---------------------------------------------//
		//Posted On
		$PostedOn = date('Y-m-d',
				 strtotime(
				  	  $html->find('div#dnf_class_values_procurement_notice__posted_date__widget', 0)->innertext
				     	 )
				);
		$this->setPostedOn($PostedOn);

		//---------------------------------------------//
		//Current Type
		$CurrentType = trim(
				    $html->find('div#dnf_class_values_procurement_notice__procurement_type__widget', 0)->innertext
			       );
		$this->setCurrentType($CurrentType);
			
		//---------------------------------------------//
		//Base Type
		$BaseType = $CurrentType;
		$this->setBaseType($BaseType);

		//---------------------------------------------//
		//Base Posting Date
		$BasePostingDate = $PostedOn;
		$this->setBasePostingDate($BasePostingDate);

		//---------------------------------------------//
		//Synopsis
		$Synopsis = $html->find('div#dnf_class_values_procurement_notice__description__widget',0)->children(0)->innertext = '';
		$Synopsis = $html->find('div#dnf_class_values_procurement_notice__description__widget',0)->innertext;
		$this->setSynopsis($Synopsis);

		//---------------------------------------------//
		//OfficeAddress Wrapper
		$ContractingOfficeAddress = $html->find('div#dnf_class_values_procurement_notice__office_address__widget', 0)->innertext;
		$this->setContractingOfficeAddress($ContractingOfficeAddress);

		//---------------------------------------------//
		//Ship To
		$ShipTo = $html->find('div#dnf_class_values_procurement_notice__place_of_performance__widget', 0)->innertext;
		$this->setShipTo($ShipTo);

		//---------------------------------------------//
		//Primary Contact Wrapper
		$s = $html->find('div#dnf_class_values_procurement_notice__primary_poc__widget', 0)->innertext;

		$PrimaryContactName = $s;  
				
		$PrimaryContactTitle;
		$PrimaryContactEmail;
		$PrimaryContactPhone;
		$PrimaryContactFax;

		$this->setPrimaryContact($PrimaryContactName);

		//---------------------------------------------//
		//Secondary Contact Wrapper
		$s = $html->find('div#dnf_class_values_procurement_notice__secondary_poc__widget', 0)->innertext;

		$SecondaryContactName = $s;
		$SecondaryContactTitle;
		$SecondaryContactEmail;
		$SecondaryContactPhone;
		$SecondaryContactFax;
		$this->setSecondaryContact($SecondaryContactName);

		//---------------------------------------------//
		//Original Set Aside
		$OriginalSetAside = $html->find('div#dnf_class_values_procurement_notice__original_set_aside__widget', 0)->innertext;
		$this->setOriginalSetAside($OriginalSetAside);

		//---------------------------------------------//
		//Set Aside
		$SetAside = $html->find('div#dnf_class_values_procurement_notice__set_aside__widget', 0)->innertext;
		$this->setSetAside($SetAside);

		//---------------------------------------------//
		//Classification Code
		$ClassificationCode = $html->find('div#dnf_class_values_procurement_notice__classification_code__widget', 0)->innertext;
		$this->setClassificationCode($ClassificationCode);

		//---------------------------------------------//
		//NAICS Code
		$NAICSCode = $html->find('div#dnf_class_values_procurement_notice__naics_code__widget', 0)->innertext;
		$this->setNAICSCode($NAICSCode);
		
	}

	public function isValidURL() {
		$URL = $this->getLink();
		$validSite = "www.fbo.gov";
		return !empty($URL) && (strpos($URL, $validSite) !== false);
	}
	
	private function sanitize($s) {
		$s = strval($s);
		$s = trim($s);
		
		//remove all <div> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<div.*?>/';
		$patterns[1] = '/<\/div>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove strong
		$patterns = array();
		$patterns[0] = '/<strong.*?>/';
		$patterns[1] = '/<\/strong>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <br> tags
		$patterns = array();
		$patterns[0] = '/<br.*?>/';
		$replacements = array();
		$replacements[0] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all nbsp
		$patterns = array();
		$patterns[0] = '/&nbsp;/';
		$replacements = array();
		$replacements[0] = ' ';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <a> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<a.*?>/';
		$patterns[1] = '/<\/a>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <span> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<span.*?>/';
		$patterns[1] = '/<\/span>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <input> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<input.*?>/';
		$patterns[1] = '/<\/input>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		$s = preg_replace($patterns, $replacements, $s); 
		
		//remove all <form> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<form.*?>/';
		$patterns[1] = '/<\/form>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <p> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<p.*?>/';
		$patterns[1] = '/<\/p>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <ul> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<ul.*?>/';
		$patterns[1] = '/<\/ul>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <li> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<li.*?>/';
		$patterns[1] = '/<\/li>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
		
		//remove all <table> tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/<table.*?>/';
		$patterns[1] = '/<\/table>/';
		$replacements = array();
		$replacements[0] = '';
		$replacements[1] = '';
		//$s = preg_replace($patterns, $replacements, $s);
								
		
		//remove all & tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/&/';
		$replacements = array();
		$replacements[1] = '&amp;';
		$s = preg_replace($patterns, $replacements, $s);

		//remove all mode tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/</';
		$replacements = array();
		$replacements[0] = '&lt;';
		$s = preg_replace($patterns, $replacements, $s);

		//remove all mode tags -- keep the info within
		$patterns = array();
		$patterns[0] = '/>/';
		$replacements = array();
		$replacements[0] = '&gt;';
		$s = preg_replace($patterns, $replacements, $s);

		//if not UTF-8 force convert
		if (!mb_check_encoding($s, 'UTF-8'))
			$s = utf8_encode($s);
			
			
		return $s;
	}
		
}
