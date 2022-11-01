<?php 

class archivo extends globalController{
	private $Archivo;
	private $Ticket;
	private $Folder;


	function __construct(){
		parent::__construct();
	}

	public function chargeArchive(){

		if(!$this->validateFields('archivo')){
			return array('Status' => 'Incomplete Fields');
		}

		$this->Ticket = $this->cleanString($_POST['ticket']);

		if(!$this->existingTicket($this->Ticket)){
			return array('Status' => 'Invalid Ticket');
		}

		$folderExist = scandir("../assets/tickets/");

		if(!in_array($this->Ticket,$folderExist,true)){
			$this->Folder = '../assets/tickets/'.$this->Ticket.'/';
			mkdir($this->Folder);
		}else{
			$this->Folder = '../assets/tickets/'.$this->Ticket.'/';
		}

		$response = array();

		foreach($_FILES['file']['tmp_name'] as $key => $tmp_name){
			$uploadfile = $this->Folder.str_replace(' ','_',$_FILES['file']['name'][$key]);

			move_uploaded_file($_FILES['file']['tmp_name'][$key], $uploadfile);

			$this->query = 'INSERT INTO Archivo (idTicket, cArchivo, cUrl) VALUES (:Ticket, :Archivo, :Url)';
			$this->data = [':Ticket' => $this->Ticket, ':Archivo' => str_replace(' ','_',$_FILES['file']['name'][$key]), ':Url' => $uploadfile];

			if($this->executeQuery()){
				$response[] = array('File' => $_FILES['file']['tmp_name'][$key], 'Upload' => true);
			}
		}

		return array(
			'Status' => (count($response) > 0) ? 'Success' : 'Error',
			'Archives' => $response
		);
	}

	private function existingTicket($tiket){
		$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE idTicket = :Ticket';
		$this->data = [':Ticket' => $tiket];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function validateFields($call){
		$valid = false;
        switch ($call) {
        	case 'archivo':
        		if(isset($_POST['ticket']) && !empty($_FILES) && !empty($_FILES['file'])){
        			if(!$this->validateEmptyPost(array(''))){
        				$valid = true;
        			}
        		}
        	break;
        	// case 'update':
        	// 	if(isset($_POST['tipo_equipo'], $_POST['equipo_dependencia'], $_POST['patrimonio'], $_POST['usuario_asignado'], $_POST['interno'], $_POST['modelo'], $_POST['marca'], $_POST['id'])){
        	// 		if(!$this->validateEmptyPost(array('modelo', 'marca','usuario_asignado'))){
        	// 			$valid = true;
        	// 		}
        	// 	}
        	// break;
        }
        return $valid;
    }
}


?>