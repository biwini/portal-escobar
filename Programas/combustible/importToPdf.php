<?php 
	
	if(!isset($_SESSION)){
        require_once ('controller/sessionController.php');
        $Session = new session();
    }

    if(!$Session->isLogued()){
    	header("location: http://192.168.122.180/");
    }

	require 'controller/pdfController.php';

	if(isset($_GET['remito'])){
		require 'controller/remitoController.php';

		$Remito = new remito();

		if($Remito->validRemito($_GET['remito'])){


			$dataPdf = $Remito->getRemito($_GET['remito']);
			
			$download = (isset($_GET['type'])) ? false : true;

			// var_dump($download);
			// exit();

			$Pdf = new pdf(
				$dataPdf['Number'],
				$dataPdf['Provider'],
				$dataPdf['Car'],
				$dataPdf['Oc'],
				$dataPdf['LtsNafta'],
				$dataPdf['LtsNaftaPP'],
				$dataPdf['LtsGas'],
				$dataPdf['LtsGasPE'],
				$dataPdf['Date'],
				$download
			);	//CREO EL PDF
			$Pdf->GetPdf();
		}
	}

?>