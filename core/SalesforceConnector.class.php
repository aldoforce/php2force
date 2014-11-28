<?php

//echo getcwd();

//Define BASEDIRs
define("BASEDIR_SOAP", 		"soap/"); 
define("BASEDIR_CONFIG", 	"config/");
define("BASEDIR_WSDL", 		"wsdl/");


//Includes
require_once (BASEDIR_SOAP . 'SforcePartnerClient.php');
require_once (BASEDIR_SOAP . 'SforceHeaderOptions.php');

class SalesforceConnector {
	
	/** 
	*	SALESFORCE CREDENTIALS 
	*	Please don't forget to whitelist PHP server's IP
	**/
	private $USERNAME; 
	private $PASSWORD; 
	private $TOKEN;	 
	private $WSDL;
	
	//Connection variables
	private $cn;
	private $soap;
	private $login;
	
	private static $singleton = null;

	public static function getInstance() {

		if (!isset(SalesforceConnector::$singleton))
			SalesforceConnector::$singleton = new SalesforceConnector();

		return SalesforceConnector::$singleton;
	}

	//Constructor
	private function __construct() {		

		$this->loadConfig();
		$this->connect();		
	}

	function __destruct() {
		/*
		After a few calls and emails with salesforce Developer Support, I got a clear and simple answer:

		"There is no implication by calling only login and no logout()"
		"Client applications do not need to explicitly log out to end a session. Sessions expire automatically after a predetermined length of inactivity".

		You'll get the same Session_Id (your session will be refreshed on Salesforce and will remain active), letting Salesforce kill it when I'm not using it. 

		*/
		//$this->logout();
	}
	
	
	//load configuration file
	private function loadConfig() {
		if ( !$config = parse_ini_file(BASEDIR_CONFIG . 
"config.ini", true) ) die ('Unable to open config file!');

		//config bindings
		$this->USERNAME = $config['sfdc']['username'];
		$this->PASSWORD = $config['sfdc']['password'];
		$this->TOKEN 	= $config['sfdc']['token'];
		$this->WSDL   	= $config['wsdl']['name'];
	}

	//Set the connection object
	private function connect() {		
		try {
			$this->cn	= new SforcePartnerClient();
			$this->soap 	= 
$this->cn->createConnection(BASEDIR_WSDL . $this->WSDL);
			$this->login	= $this->cn->login($this->USERNAME, $this->PASSWORD . $this->TOKEN);
						
		}
		catch (Exception $e) {
			echo "[EXCEPTION] ::::::::::::::: <br/>";
			print_r($this->cn->getLastRequest());
			echo $e->faultstring;
			echo "[END-EXCEPTION] ::::::::::: <br/>";
		}
	}
	
	
	//Internal query implementation
	public function query($soql) {
		try {
	  		$response 		= $this->cn->query($soql);
  			$queryResult 	= new QueryResult($response);
		}
		catch (Exception $e) {
			echo "[EXCEPTION] ::::::::::::::: <br/>";
			print_r($this->cn->getLastRequest());
			echo $e->faultstring;
			echo "[END-EXCEPTION] ::::::::::: <br/>";
		}
		
		return $queryResult;		
	} 
	

	//Internal create sObject implementation
	public function create($fields, $type) {
		$sObject                = new SObject();
                $sObject->fields        = $fields;
                $sObject->type          = $type;
		
		return $sObject;
	}

        //Internal bind sObject implementation
        public function bind($fields, $type, $Id) {
                $sObject                = new SObject();
                $sObject->fields        = $fields;
                $sObject->type          = $type;
                $sObject->Id            = $Id;

                return $sObject;
        }



	//Internal insert implementation
	public function insert($sObjectsArray) {
		try {
			
			//Send Inserts
			$response = $this->cn->create(
							$sObjectsArray
			 );
						
		}
		catch (Exception $e) {
			echo "[EXCEPTION] ::::::::::::::: <br/>";
			print_r($this->cn->getLastRequest());
			echo $e->faultstring;
			echo "[END-EXCEPTION] ::::::::::: <br/>";
		}
		
		return $response;
	}
	
	//Internal Update implementation
	public function update($sObjectsArray) {
		try {			
			//Send update
			$response = $this->cn->update(	
				$sObjectsArray
			 );
						 

		}
		catch (Exception $e) {
			echo "[EXCEPTION] ::::::::::::::: <br/>";
			print_r($this->cn->getLastRequest());
			echo $e->faultstring;
			echo "[END-EXCEPTION] ::::::::::: <br/>";
		}
		
		return $response;
	}
	
	public function logout() {
		$this->cn->logout();
	}
	
}

?>
