<?php

//requires Salesforce connector
class AttachmentHandler {
	
	/**
	*	Attach the binary file $pFilename to the $pOrdersId record from SFDC.
	*	Delete the binary file after insert
	**/
	public static function addAttachment($pFilename, $pOrdersId, $pSFDC) {
		Logger::dump("addAttachment: $pFilename, $pOrdersId");
		
		$handle 		= fopen($pFilename,'rb');
		$file_content 	= fread($handle,filesize($pFilename) );
		fclose($handle);
		
		// encode
		$encoded = chunk_split(base64_encode($file_content));

		// define attachment name
		$name = str_replace("tmp/", "", $pFilename);

		//define attachment fields
		$fields 	= array(
			'Name' 		=> $name,
			'ParentId'	=> $pOrdersId,
			'body' 		=> $encoded
		);

		//create the sObject
		$sObject = $pSFDC->create($fields, 'Attachment');

		//DML
		$pSFDC->insert( array($sObject) );

		//delete the temporary file
		unlink($pFilename);
	}
}

?>



