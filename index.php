<?php
	//Include Salesforce Connector
	include_once('./core/SalesforceConnector.class.php');
  include_once('./core/Logger.class.php');

	//Include Handlers
  include_once('./classes/LanguagesLabels.class.php');
	include_once('./classes/OrdersHandler.class.php');
  include_once('./classes/AttachmentHandler.class.php');
	include_once('./classes/AccountHandler.class.php');
	include_once('./classes/ContactHandler.class.php');
	include_once('./classes/CircuitHandler.class.php');
  include_once('./classes/PODHandler.class.php');
  include_once('./classes/SOFViewHistoryHandler.class.php');  
	include_once('./classes/TemplateHandler.class.php');	

	
	
	if ( empty($_GET['id']) && empty($_POST['id'])) die('id parameter not detected');
	
	$id 	= empty( $_GET['id'] ) ?
						$_POST['id'] :
						$_GET['id'];
		
	$t = new TemplateHandler($id);
	$L = LanguagesLabels::getLabels();

	function e($s) { echo $s; }
?>
<!doctype html>

<html>
	<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">		
	</head>	
	<body>

  </body>
</html>
