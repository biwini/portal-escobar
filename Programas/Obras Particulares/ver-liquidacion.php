<?php 
	
	if(!isset($_SESSION)){
        require_once ('controller/sessionController.php');
        $Session = new session();
    }

    if(!$Session->isLogued()){
    	header("location: http://192.168.122.180/");
    }

	require 'controller/pdfController.php';

	if(isset($_GET['liquidacion'])){
		require 'controller/liquidacionController.php';

		$Liquidacion = new liquidacion();

		if($Liquidacion->validLiquidacion($_GET['liquidacion'])){

			$dataPdf = $Liquidacion->getFullDataLiquidacion($_GET['liquidacion']);
			
			$download = (isset($_GET['type'])) ? false : true;

			// var_dump($download);
			// exit();

			$Pdf = new pdf(
                $dataPdf['TipoLiquidacion'],
				$dataPdf['Fecha'],
				$dataPdf['RazonSocial'],
				$dataPdf['Nomenclatura'],
				$dataPdf['Zonificacion'],
				$dataPdf['ContratoColegio'],
                $dataPdf['Multas'],
                $dataPdf['Descuento'],
                $dataPdf['Total'],
                $dataPdf['SmMunicipal'],
				$download
            );	//CREO EL PDF
            
			echo $Pdf->GetPdf();
		}
	}

?>