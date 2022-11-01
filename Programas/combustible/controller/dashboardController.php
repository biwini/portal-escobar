<?php 

include_once  realpath(__DIR__ ).'/globalController.php';
include_once  realpath(__DIR__ ).'/userController.php';

class dashboard extends globalController{
	private $Id;
	private $Name;

	private $Session;
	
	function __construct(){
		parent::__construct();

		$this->Session = new session();

	}

	public function getMailSent(){
		$this->query = 'SELECT r.nNumero, p.cProveedor, m.idEnvio, m.dFechaEnvio, m.idReenvio, m.dFechaReenvio FROM MailsEnviados AS m
			INNER JOIN Proveedores AS p ON m.idProveedor = p.idProveedor
			INNER JOIN Remitos AS r ON m.idRemito = r.idRemito';
		$this->data = [];

		$result = $this->executeQuery();
		$mailList = array();
		while ($row = $result->fetch()){
            $mailList[] = array(
                'Remito' => $row['nNumero'],
                'Provider' => $row['cProveedor'],
                'User' => $this->getUserInfo($row['idEnvio']),
                'Date' => $row['dFechaEnvio'],
                'Reenviado' => $row['dFechaReenvio'],
                'UsuarioReenvio' => $this->getUserInfo($row['idReenvio']),
            );
        }

        return $mailList;
	}

	

	private function getUserInfo($userId){
		$User = new usuario();

		return $User->getUserInfo($userId);
	}

	private function validateFields($call){
		$valid = false;
		switch ($call) {
			case 'insert':
				if(isset($_POST['proveedor'])){
					if(!$this->validateEmptyPost(array('id'))){
						$valid = true;
					}
				}
			break;
			case 'update':
				if(isset($_POST['id']) && isset($_POST['proveedor'])){
					if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
						$valid = true;
					}
				}
			break;
			case 'delete':
				if(isset($_POST['id'])){
					if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
						$valid = true;
					}
				}
			break;
		}
		return $valid;
	}
}

?>