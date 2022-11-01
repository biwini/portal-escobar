<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class secretaria extends globalController{
		private $Id;
		private $Name;
		public $ListSecretary;

		function __construct(){
			$this->Id = 0;
			$this->Name = '';
			$this->ListSecretary = array();

			parent::__construct();
		}

		protected function getAll(){
            $this->query = 'SELECT idSecretaria,cNomSecretaria,nEstado,dFechaAlta FROM Secretaria ORDER BY idSecretaria ASC';
            $this->data = [];

            $result = $this->executeQuery();

            while ($row = $result->fetch()){
                // $fecha = new DateTime($row['dFechaAlta']);
                $this->ListSecretary[] = array(
                    'IdSecretaria' => $row['idSecretaria'],
                    'Name' => $row['cNomSecretaria'], 
                    'State' => $row['nEstado'],
                    'Dependences' => $this->getSecretaryDependences($row['idSecretaria'])
                );
            }

            return $this->ListSecretary;
        }

        private function getSecretaryDependences($secretary){
            $this->query = 'SELECT d.idDependencia,d.cNomDependencia,d.cDireccion,d.idLocalidad,l.cLocalidad,d.nEstado FROM Dependencia AS d
            	INNER JOIN localidad AS l ON d.idLocalidad = l.idLocalidad
            	WHERE d.idSecretaria = :Secretary ORDER BY d.idDependencia ASC';

            $this->data = [':Secretary' => $secretary];

            $result = $this->executeQuery();

            $listDependence = array();
            while ($row = $result->fetch()){
                $listDependence[] = array(
                    'IdSecretary' => $secretary,
                    'IdDependence' => $row['idDependencia'],
                    'Name' => $row['cNomDependencia'], 
                    'IdLocation' => $row['idLocalidad'],
                    'Location' => $row['cLocalidad'],
                    'Address' => $row['cDireccion'],
                    'State' => $row['nEstado']
                );
            }
            return $listDependence;
        }


	}
?>