<?php
//Include Salesforce Connector
include_once('./core/SalesforceConnector.class.php');


$sfdc = new SalesforceConnector();

$q = $sfdc->query("select id, name from account limit 10");
echo "<hr/>";

foreach ($q->records as $record) {
	echo $record->Id . ' -- ' . $record->fields->Name . '<br/>';
}

echo "<hr/>";






?>



