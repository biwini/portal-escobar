<?php 
	
	if(!isset($_SESSION)){
        require_once ('controller/sessionController.php');
        $Session = new session();
    }

    if(!$Session->isLogued()){
    	header("location: http://192.168.122.180/");
    }

	require 'controller/pdfController.php';

	if(isset($_GET['ticket'])){
		require 'controller/ticketController.php';

		$Ticket = new ticket();

		$t = $Ticket->getTicketByCode($_GET['ticket']);
		
		$download = (isset($_GET['type'])) ? false : true;

		// var_dump($download);
		// exit();

		$Pdf = new pdf($t,$download);	//CREO EL PDF
		$Pdf->GetPdf();
		
	}

?>