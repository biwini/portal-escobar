<?php
    require 'globalController.php';

    class tipoEmpleado extends Main{
        private $id;
        private $tipo;
        private $desc;

        public function getTiposEmpleado(){
            $this->sql = 'SELECT id, nombre, descripcion, estado FROM tipoEmpleado
                WHERE idBaja IS NULL ORDER BY nombre';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getTipoEmpleado($id){
            $this->sql = 'SELECT id, nombre, descripcion, estado FROM tipoEmpleado
                WHERE id = :id AND idBaja IS NULL';
            $this->data = [':id' => $id];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC)[0];
        }

        public function insertTipo(){

            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'No posee permisos', 'output' => '');
            }

            $this->tipo = $this->format_string($_POST['tipo']);
            $this->desc = $this->format_string($_POST['desc']);

            if($this->existingTipoEmpleado('NAME',$this->tipo)){
                return array('status' => 'Tipo de empleado Existente', 'output' => 'El tipo de empleado ingresado ya existe');
            }

            $this->sql = 'INSERT INTO tipoEmpleado (nombre, descripcion, idAlta, fechaAlta) VALUES (:tipo, :desc, :user, :fecha)';
            $this->data = [
                ':tipo' => $this->tipo,
                ':desc' => $this->desc,
                ':user' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime()
            ];

            // $datos = $this->query();

            // return $datos->errorInfo()[2];

            return ($this->query()) ? array('status' => 'success', 'response' => (object) $this->getTipoEmpleado($this->last_id()), 'hola' => $this->last_id()) : $this->errorMessage;
        }

        public function updateTipo(){

            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'No posee permisos', 'output' => '');
            }

            $this->id = $this->format_string($_POST['id']);
            $this->tipo = $this->format_string($_POST['tipo']);
            $this->desc = $this->format_string($_POST['desc']);

            if(!$this->existingTipoEmpleado('ID',$this->id)){
                return array('status' => 'No se encontro el tipo de empleado', 'output' => 'El tipo de empleado que intenta modificar no existe');
            }

            $this->sql = 'SELECT COUNT(id) FROM tipoEmpleado WHERE (nombre = :tipo AND id != :id) AND idBaja IS NULL';
            $this->data = [':tipo' => $this->tipo, ':id' => $this->id];

            if(($this->searchRecords() > 0)){
                return array('status' => 'Tipo de empleado Existente', 'output' => 'El tipo de empleado ingresado ya existe');
            }

            $this->sql = 'UPDATE tipoEmpleado SET nombre = :tipo, descripcion = :desc, idModificado = :user, fechaModificado = :fecha
                WHERE id = :id';
            $this->data = [
                ':tipo' => $this->tipo,
                ':desc' => $this->desc,
                ':user' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime(),
                ':id' => $this->id
            ];

            return ($this->query()) ? array('status' => 'success', 'hoasl' => $this->data,'asd' => $this->tipo, 'response' => (object) $this->getTipoEmpleado($this->id)) : $this->errorMessage;
        }

        public function deleteTipo(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'No posee permisos', 'output' => '');
            }
            
            $this->id = $this->format_string($_POST['id']);
            
            if(!$this->existingTipoEmpleado('ID',$this->id)){
                return array('status' => 'No se encontro el tipo de empleado', 'output' => 'El tipo de empleado que intenta eliminar no existe');
            }

            $this->sql = 'UPDATE tipoEmpleado SET idBaja = :user, dBaja = :fecha WHERE id = :id';
            $this->data = [':user' => $_SESSION['ID_USER'], ':fecha' => $this->getActualDateTime(), ':id' => $this->id];

            return ($this->query()) ? array('status' => 'success') : $this->errorMessage;
        }

        private function existingTipoEmpleado($type, $search){
            switch ($type) {
                case 'NAME': $this->sql = 'SELECT COUNT(id) FROM tipoEmpleado WHERE nombre = :search AND idBaja IS NULL'; break;
                case 'ID': $this->sql = 'SELECT COUNT(id) FROM tipoEmpleado WHERE id = :search AND idBaja IS NULL'; break;
            }

            $this->data = [':search' => $search];

            return ($this->searchRecords() > 0) ? true : false;
        }
    }
?>