<?php
    require 'globalController.php';

    class motivo extends Main{
        private $id;
        private $motivo;

        public function getMotivos(){
            $this->sql = 'SELECT idMotivo AS id, cMotivo, nCodigo, nDisponibleHoras, nDisponibleLicencias, nDisponibleFaltaMedica FROM Motivos
                WHERE idBaja IS NULL ORDER BY cMotivo ASC';
            $this->data = [];
            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getMotivo($id){
            $this->sql = 'SELECT idMotivo AS id, cMotivo, nCodigo, nDisponibleHoras, nDisponibleLicencias, nDisponibleFaltaMedica FROM Motivos
            WHERE idMotivo = :id ORDER BY cMotivo ASC';
            $this->data = [':id' => $id];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC)[0];
        }

        public function insertMotivo(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'No posee permisos', 'output' => '');
            }

            $this->motivo = $this->format_string($_POST['motivo']);

            if($this->existingMotivo('NAME',$this->motivo)){
                return array('status' => 'Motivo Existente', 'output' => 'El motivo ingresado ya existe');
            }

            $this->sql = 'INSERT INTO Motivos (cMotivo, idAlta, dAlta) VALUES (:motivo, :user, :fecha)';
            $this->data = [
                ':motivo' => $this->motivo,
                ':user' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime()
            ];

            return ($this->query()) ? array('status' => 'success', 'response' => (object) $this->getMotivo($this->last_id()), 'hola' => $this->last_id()) : $this->errorMessage;
        }

        public function updateMotivo(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'No posee permisos', 'output' => '');
            }

            $this->id = $this->format_string($_POST['id']);
            $this->motivo = $this->format_string($_POST['motivo']);

            if(!$this->existingMotivo('ID',$this->id)){
                return array('status' => 'No se encontro el motivo', 'output' => 'El motivo que intenta modificar no existe');
            }

            $this->sql = 'SELECT COUNT(idMotivo) FROM Motivos WHERE (cMotivo = :motivo AND idMotivo != :id) AND idBaja IS NULL';
            $this->data = [':motivo' => $this->motivo, ':id' => $this->id];

            if(($this->searchRecords() > 0)){
                return array('status' => 'Motivo Existente', 'output' => 'El motivo ingresado ya existe');
            }

            $this->sql = 'UPDATE Motivos SET cMotivo = :motivo, idModificado = :user, dModificado = :fecha WHERE idMotivo = :id';
            $this->data = [
                ':motivo' => $this->motivo,
                ':user' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime(),
                ':id' => $this->id
            ];

            return ($this->query()) ? array('status' => 'success', 'response' => (object) $this->getMotivo($this->id)) : $this->errorMessage;
        }

        public function deleteMotivo(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'No posee permisos', 'output' => '');
            }
            
            $this->id = $this->format_string($_POST['id']);
            
            if(!$this->existingMotivo('ID',$this->id)){
                return array('status' => 'No se encontro el motivo2', 'output' => 'El motivo que intenta eliminar no existe');
            }

            $this->sql = 'UPDATE Motivos SET idBaja = :user, dBaja = :fecha WHERE idMotivo = :id';
            $this->data = [':user' => $_SESSION['ID_USER'], ':fecha' => $this->getActualDateTime(), ':id' => $this->id];

            return ($this->query()) ? array('status' => 'success') : $this->errorMessage;
        }

        private function existingMotivo($type, $search){
            switch ($type) {
                case 'NAME': $this->sql = 'SELECT COUNT(idMotivo) FROM Motivos WHERE cMotivo = :search AND idBaja IS NULL'; break;
                case 'ID': $this->sql = 'SELECT COUNT(idMotivo) FROM Motivos WHERE idMotivo = :search AND idBaja IS NULL'; break;
            }

            $this->data = [':search' => $search];

            return ($this->searchRecords() > 0) ? true : false;
        }
    }
?>