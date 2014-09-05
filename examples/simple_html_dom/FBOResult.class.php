<?php

class FBOResult {
	
	private $UL_ok 			= 0;
	private $UL_fail		= 0;
	private $IL_ok 			= 0;
	private $IL_fail		= 0;
	private $UO_ok 			= 0;
	private $UO_fail		= 0;
	private $FailedLinks 	= array();
	
	//Constructor
	function __construct() {		
	}
	
	public function leadUpdated()	 			{ $this->UL_ok++; }
	public function leadUpdated_fail($k) 		{ $this->UL_fail++; $this->addFailedLink($k); }
	
	public function leadInserted()	 			{ $this->IL_ok++; }
	public function leadInserted_fail($k) 		{ $this->IL_fail++; $this->addFailedLink($k); }
	
	public function opportunityUpdated()		{ $this->UO_ok++; }
	public function opportunityUpdated_fail($k)	{ $this->UO_fail++; $this->addFailedLink($k); }
	
	public function addFailedLink($link) {
		$this->FailedLinks[] = $link;
	}
	
	public function display() {
		$s  = '<br/>';
		$s .= "Leads inserted 			: $this->IL_ok  <br/>";		
		$s .= "Leads updated  			: $this->UL_ok  <br/>";
		$s .= "Opportunities updated  	: $this->UO_ok  <br/>";
		
		if ($this->IL_fail > 0) $s .= "Leads failures (insert): $this->IL_fail <br/>";
		if ($this->UL_fail > 0) $s .= "Leads failures (update): $this->UL_fail <br/>";
		if ($this->UO_fail > 0) $s .= "Opportunities failures (update): $this->UO_fail <br/>";
	
		//check for failed links
		if ($this->IL_fail > 0 ||
			$this->UL_fail > 0 ||
			$this->UO_fail > 0) {
			
			$s .= "<br/><br/>Failed links:<br/>";
			foreach($this->FailedLinks as $k) $s .= $k . "<br/>"; 
		}
			
			
		
		return $s;
	}

}

?>
