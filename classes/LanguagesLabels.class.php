<?php


class LanguagesLabels {
	
	public static function getLabels() {
		$language = $_GET['language'];

		if ($language == 'Portuguese' || $language == 'English and Portuguese') 
			$t = self::getPortuguese();
		else if ($language == 'English' || empty($language) ) 
			$t = self::getEnglish();
		else
			$t = self::getEnglish();
				
		return $t;
	}

	private static function getEnglish() {
		$T = array(
			'YOU_MUST_CHECK_AGREEMENT_OPTIONS' => 'You must check the agreement option to continue', 
			'YOU_MUST_PROVIDE_YOUR_FIRST_NAME' => 'You must provide your name first.', 
			'DOWNLOAD_PDF_VERSION' => 'Download PDF version', 
			'DRAFT_NON_BINDING' => 'DRAFT Non-Binding', 
			'CUSTOMER_DETAILS' => 'CUSTOMER DETAILS', 
			''=>''
		);
		return $T;
	}

	private static function getPortuguese() {
		$T = array(
			'YOU_MUST_CHECK_AGREEMENT_OPTIONS' => 'Você deve marcar a opção de acordo para continuar', 
			'YOU_MUST_PROVIDE_YOUR_FIRST_NAME' => 'Você deve fornecer seu nome em primeiro lugar.', 
			'DOWNLOAD_PDF_VERSION' => 'versão Download PDF', 
			'DRAFT_NON_BINDING' => 'DRAFT Não Vinculado', 
			'CUSTOMER_DETAILS' => 'Detalhes do Cliente', 
			'' => ''
		);
		return $T;
	}
	

	
	
}

?>



