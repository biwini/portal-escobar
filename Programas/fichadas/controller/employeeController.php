<?php

    require 'globalController.php';

    class employee extends Main{
        private $id, $dni, $horario, $legajo;
        private $search;

        public function getPersonas(){
            $this->sql = 'SELECT e.id, e.nombre, e.apellido, e.nHorasDia, e.dFechaAdmision, legajo,tipoDocumento,nroDocumento,
                fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,e.idSecretaria,e.idDependencia,e.idTipoEmpleado,cuit, t.nombre AS nombreTipoEmpleado,
                h.id_horario, h.nombre_horario FROM empleado AS e
                LEFT JOIN tipoEmpleado AS t ON e.idTipoEmpleado = t.id
                LEFT JOIN HORARIOS_EMPLEADO AS he ON he.idEmpleado = e.id
                LEFT JOIN HORARIOS AS h ON he.idHorario = h.id_horario
                WHERE e.idBaja IS NULL';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);

            // $result = $this->query();
            // $response = [];

            // while($row = $result->fetch(PDO::FETCH_ASSOC)){
            //     $row['telefonos'] = $this->getTelefonosEmpleado($row['id']);

            //     $response[] = $row;
            // }
            
            // return $response;
        }

        public function getInactivePersonas(){
            $this->sql = 'SELECT e.id, e.nombre, e.apellido, e.nHorasDia, e.dFechaAdmision, legajo,tipoDocumento,nroDocumento,
                fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,e.idSecretaria,e.idDependencia,e.idTipoEmpleado,cuit, t.nombre AS nombreTipoEmpleado,
                h.id_horario, h.nombre_horario FROM empleado AS e
                LEFT JOIN tipoEmpleado AS t ON e.idTipoEmpleado = t.id
                LEFT JOIN HORARIOS_EMPLEADO AS he ON he.idEmpleado = e.id
                LEFT JOIN HORARIOS AS h ON he.idHorario = h.id_horario
                WHERE e.idBaja IS NOT NULL';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);

            // $result = $this->query();
            // $response = [];

            // while($row = $result->fetch(PDO::FETCH_ASSOC)){
            //     $row['telefonos'] = $this->getTelefonosEmpleado($row['id']);

            //     $response[] = $row;
            // }
            
            // return $response;
        }

        public function getEmployee(){
            $this->id = (int) $this->format_string($_REQUEST['id']);

            $this->sql = 'SELECT e.id, e.nombre, e.apellido, e.nHorasDia, e.dFechaAdmision, legajo,tipoDocumento,nroDocumento,fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,e.idSecretaria,e.idDependencia,e.idTipoEmpleado,cuit, t.nombre AS nombreTipoEmpleado, h.id_horario, h.nombre_horario FROM empleado AS e
                LEFT JOIN tipoEmpleado AS t ON t.id = e.idTipoEmpleado
                LEFT JOIN HORARIOS_EMPLEADO AS he ON he.idEmpleado = e.id
                LEFT JOIN HORARIOS AS h ON he.idHorario = h.id_horario
                WHERE e.id = :id AND e.idBaja IS NULL';
            $this->data = [':id' => $this->id];

            $result = $this->query();
            $response = [];

            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $row['telefonos'] = $this->getTelefonosEmpleado($row['id']);

                $response[] = $row;
            }
            
            return $response;
        }

        public function getEmployeesSimple(){
            $this->sql = 'SELECT id, nombre, apellido, legajo FROM empleado';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getEmployeesAmount(){
            $this->data = [];

            $this->sql='SELECT count(id) AS total from empleado AS e WHERE estado = 0';

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getEmpoloyeesTypes(){
            $this->sql='SELECT id, nombre, descripcion, estado from tipoEmpleado order by nombre';
            $this->data = [];

            return $this->query()->fetchAll();
        }

        public function insertEmployee(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'error', 'output' => 'No posee permisos para realizar la acción');
            }

            $this->horario = $_POST['horario'];
            $this->dni = $_POST['nroDoc'];
            $this->legajo = $_POST['legajo'];

            if(!$this->existingHorario('ID', $this->horario)){
                // return array('status' => 'error', 'output' => 'El horario seleccionado es invalido', $_POST);
                $this->horario = NULL;
            }

            if($this->existingEmpleado('DNI', $this->dni)){
                return array('status' => 'error', 'output' => 'El D.N.I. Ingresado ya esta registrado', $_POST);
            }
            if($this->existingEmpleado('LEGAJO', $this->legajo)){
                return array('status' => 'error', 'output' => 'El Legajo ingresado ya esta registrado', $_POST);
            }


            $this->sql='INSERT INTO empleado(
                nombre, apellido, nHorasDia,legajo,tipoDocumento,nroDocumento,fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,idSecretaria,idDependencia,idTipoEmpleado, dFechaAdmision,cuit, idAlta, dAlta)
                values(:name, :surname, :workHours,:legajo,:typeDoc,:nroDoc,:birthDate,:gender,:stateCivil,:address,:phone,:email,:secretary,:dependence,:employeeTypes, :adminision,:cuit, :user, :fecha)';
            $this->data = [
                ':name' => $_POST['name'],
                ':surname' => $_POST['surname'],
                ':workHours' => $_POST['workHours'],
                ':legajo' => $_POST['legajo'],
                ':typeDoc' => $_POST['typeDoc'],
                ':nroDoc' => $this->dni,
                ':birthDate' => $_POST['birthDate'],
                ':gender' => $_POST['gender'],
                ':stateCivil' => $_POST['stateCivil'],
                ':address' => $_POST['address'],
                ':phone' => $_POST['phone'],
                ':email' => $_POST['email'],
                ':secretary' => $_POST['secretaria'],
                ':dependence' => $_POST['dependencia'],
                ':employeeTypes' => $_POST['employeeTypes'],
                ':adminision' => $_POST['date_admission'],
                ':cuit' => $_POST['cuit'],
                ':user' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime()
            ];

            $datos = $this->query();

            if($datos->errorInfo()[1]){
                array('status'=>"error",'output'=>$datos->errorInfo()[2]);
            }

            $this->id = $this->last_id();

            $this->sql = 'INSERT INTO EmpleadoTelefonos (idEmpleado, cTipo, cNumero) VALUES (:empleado, :tipo, :numero)';
            $this->data = [
                ':empleado' => $this->id,
                ':tipo' => $_POST['typePhone'],
                ':numero' => $_POST['phone']
            ];

            $datos = $this->query();

            $fecha = date('Y-m-d');

            if($this->horario != NULL){
                $this->sql = 'INSERT INTO HORARIOS_EMPLEADO (idEmpleado,idHorario,dDesde) VALUES (:empleado, :horario, :desde)';
                $this->data = [
                    ':empleado' => (int) $this->id,
                    ':horario' => (int) $this->horario,
                    ':desde' => $fecha,
                ];

                $datos = $this->query();
            }

            return ($datos->errorInfo()[1]) ? array('status'=>"error",'output'=>$datos->errorInfo()[2]) : array('status'=>'ok');
        }

        public function editEmployee(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'error', 'output' => 'No posee permisos para realizar la acción');
            }
            if(empty($_REQUEST['id'])){
                return array('status' => 'error', 'output' => 'Faltan variables');
            }

            $this->horario = $_POST['horario'];

            if(!$this->existingHorario('ID', $this->horario)){
                // return array('status' => 'error', 'output' => 'El horario seleccionado es invalido');
                $this->horario = NULL;
            }

            if($this->existingEmpleado('DNI', $this->dni)){
                return array('status' => 'error', 'output' => 'El D.N.I. Ingresado ya esta registrado', $_POST);
            }
            if($this->existingEmpleado('LEGAJO', $this->legajo)){
                return array('status' => 'error', 'output' => 'El Legajo ingresado ya esta registrado', $_POST);
            }

            $this->id = $_POST['id'];

            $this->sql = 'UPDATE empleado SET
                nombre = :name, apellido = :surname, nHorasDia = :workHours, legajo = :legajo,tipoDocumento = :typeDoc,nroDocumento = :nroDoc,
                fechaNacimiento = :birthDate,sexo = :gender,estadoCivil = :stateCivil,direccion = :address,
                telefono = :phone, email = :email,idSecretaria = :secretary,idDependencia = :dependence,
                idTipoEmpleado = :employeeTypes, dFechaAdmision = :adminision, cuit = :cuit, idModificado = :idUser, dModificado = :fecha WHERE id = :id';

            $this->data = [
                ':name' => $_POST['name'],
                ':surname' => $_POST['surname'],
                ':workHours' => $_POST['workHours'],
                ':legajo' => $_POST['legajo'],
                ':typeDoc' => $_POST['typeDoc'],
                ':nroDoc' => $_POST['nroDoc'],
                ':birthDate' => $_POST['birthDate'],
                ':gender' => $_POST['gender'],
                ':stateCivil' => $_POST['stateCivil'],
                ':address' => $_POST['address'],
                ':phone' => $_POST['phone'],
                ':email' => $_POST['email'],
                ':secretary' => $_POST['secretaria'],
                ':dependence' => $_POST['dependencia'],
                ':employeeTypes' => $_POST['employeeTypes'],
                ':adminision' => $_POST['date_admission'],
                ':cuit' => $_POST['cuit'],
                ':idUser' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime(),
                ':id' => $this->id,
            ];

            $datos = $this->query();

            if($datos->errorInfo()[1]){
                array('status'=>"error",'output'=>$datos->errorInfo()[2]);
            }

            $this->sql = 'UPDATE EmpleadoTelefonos SET cTipo = :tipo, cNumero = :numero WHERE idEmpleado = :id';
            $this->data = [
                ':tipo' => $_POST['typePhone'],
                ':numero' => $_POST['phone'],
                ':id' => $this->id
            ];

            $datos = $this->query();

            $fecha = date('Y-m-d');
            if($this->horario != NULL){
                $this->sql = 'SELECT COUNT(idEmpleado) FROM HORARIOS_EMPLEADO WHERE idEmpleado = :id';
                $this->data = [':id' => $this->id];

                if($this->searchRecords() > 0){
                    $this->sql = 'UPDATE HORARIOS_EMPLEADO SET idHorario = :horario,dDesde = :desde,idModificado = :user,dModificado = :fecha WHERE idEmpleado = :empleado';
                }else{
                    $this->sql = 'INSERT INTO HORARIOS_EMPLEADO (idHorario,dDesde, idAlta, dAlta, idEmpleado) VALUES (:horario, :desde, :user, :fecha, :empleado)';
                }

                $this->data = [
                    ':horario' => (int) $this->horario,
                    ':desde' => $fecha,
                    ':user' => $_SESSION['ID_USER'],
                    ':fecha' => $this->getActualDateTime(),
                    ':empleado' => (int) $this->id
                ];
                $datos = $this->query();
            }

            return ($datos->errorInfo()[1]) ? array('status'=>"error",'output'=>$datos->errorInfo()[2]) : array('status'=>'ok', $this->sql, $this->data);
        }

        public function deleteEmployee(){
            $this->id = $_POST['id'];

            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'error', 'output' => 'No posee permisos para realizar la acción');
            }

            if(empty($this->id)){
                return array('status' => 'error', 'output' => 'empleado invalido');
            }

            $this->sql = 'UPDATE empleado SET estado = 1, idBaja = :user, dBaja = :fecha WHERE id = :id';
            $this->data = [
                ':user' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime(),
                ':id' => (int) $this->id
            ];

            $datos=$this->query();

            return ($datos->errorInfo()[1]) ? array('status'=>"error",'output'=>$datos->errorInfo()[2]) : array('status'=>'ok', $this->data);
        }

        public function restoreEmployee(){
            $this->id = $_POST['id'];

            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'error', 'output' => 'No posee permisos para realizar la acción');
            }

            if(empty($this->id)){
                return array('status' => 'error', 'output' => 'empleado invalido');
            }

            $this->sql = 'UPDATE empleado SET estado = 0, idBaja = NULL, dAlta = :fecha WHERE id = :id';
            $this->data = [
                ':fecha' => $this->getActualDateTime(),
                ':id' => (int) $this->id
            ];

            $datos=$this->query();

            return ($datos->errorInfo()[1]) ? array('status'=>"error",'output'=>$datos->errorInfo()[2]) : array('status'=>'ok', $this->data);
        }

        private function existingHorario($type, $search){
            switch($type){
                case 'ID': $this->sql = 'SELECT COUNT(ID_HORARIO) FROM HORARIOS WHERE ID_HORARIO = :search'; break;
                default : $this->sql = 'SELECT COUNT(ID_HORARIO) FROM HORARIOS WHERE ID_HORARIO = :search'; break;
            }
    
            $this->data = [':search' => $search];
    
            return ($this->searchRecords() > 0) ? true : false;
        }

        private function existingEmpleado($type, $search){
            switch($type){
                case 'ID': $this->sql = 'SELECT COUNT(id) FROM empleado WHERE id = :search AND idBaja IS NULL'; break;
                case 'DNI': $this->sql = 'SELECT COUNT(nroDocumento) FROM empleado WHERE nroDocumento = :search AND idBaja IS NULL'; break;
                case 'LEGAJO': $this->sql = 'SELECT COUNT(legajo) FROM empleado WHERE legajo = :search AND idBaja IS NULL'; break;
                default : $this->sql = 'SELECT COUNT(id) FROM empleado WHERE id = :search AND idBaja IS NULL'; break;
            }
    
            $this->data = [':search' => $search];
    
            return ($this->searchRecords() > 0) ? true : false;
        }

        private function getTelefonosEmpleado($empleado){
            if($empleado == NULL || $empleado  <= 0){
                return '';
            }

            $this->sql = 'SELECT idEmpleadoTelefonos, idEmpleado, cTipo, cNumero FROM EmpleadoTelefonos WHERE idEmpleado = :id';
            $this->data = [':id' => $empleado];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);

        }


        // public function validateFields($call){
        //     $valid = false;
        //     switch ($call) {
        //         case 'insert':
        //             if(isset($_POST['descrip'], $_POST['tol1'], $_POST['tol2'], $_POST['feriado'], $_POST['horario'])){
        //                 if(!$this->validateEmptyRequest(array('id','listhorarios','tol1','tol2','descrip','feriado','feriado_trabajo'))){
        //                     $valid = true;
        //                 }
        //             }
        //         break;
        //         case 'update':
        //             if(isset($_POST['descrip'], $_POST['tol1'], $_POST['tol2'], $_POST['horario'], $_POST['feriado'], $_POST['id'])){
        //                 if(!$this->validateEmptyRequest(array('listhorarios','tol1','tol2','descrip','feriado','feriado_trabajo')) && intval(trim($_POST['id'])) != 0){
        //                     $valid = true;
        //                 }
        //             }
        //         break;
        //         case 'delete':
        //             if(isset($_POST['id'])){
        //                 if(!$this->validateEmptyRequest(array()) && intval(trim($_POST['id'])) != 0){
        //                     $valid = true;
        //                 }
        //             }
        //         break;
        //     }
        //     return $valid;
        // }
    }
?>
