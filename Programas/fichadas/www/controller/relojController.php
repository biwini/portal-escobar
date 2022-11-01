<?php
    require 'globalController.php';

    class reloj extends Main{
        private $id;
        private $code;

        public function getRelojes(){
            $this->sql = 'SELECT Codigo, Descripcion, Dependencia,Secretaria,fecha_ultimo_error,descripcion_ultimo_error,DireccionIP FROM relojes WHERE Eliminado=0 ORDER BY Codigo ASC';
            $this->data = [];
            
            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }


        public function deleteReloj(){
            $this->sql = 'UPDATE relojes set Eliminado=1,id_usuario_elimina=:usuario_baja,fecha_usuario_elimina=:fecha_baja Where Codigo=:codigo ';
            $this->data = [
                ':codigo' => $this->format_string($_REQUEST['codigo']),
                ':usuario_baja' => $_SESSION['ID_USER'],
                ':fecha_baja' => date("Y-m-d")
            ];
            
            return ($this->query()) ? array('status' => 'okey') : array('status' => 'error', 'output' => 'Ocurrio un error durante la eliminacion logica');
        }


        public function searchReloj(){
            $this->id = $this->format_string($_REQUEST['id']);

            $this->sql = 'SELECT id,Codigo,Descripcion,Dependencia,Secretaria,Usuario,Clave,Ubicacion,Marca,Modelo,DireccionIP,Puerto,Usuario,Observaciones,fecha_ultimo_error,descripcion_ultimo_error FROM relojes WHERE Codigo = :Codigo AND Eliminado=0 ORDER BY Codigo ASC';
            $this->data = [':Codigo' => $this->id];
            // $datos=$this->query();

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertReloj(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'error', 'output' => 'No Permisson');
            }

            $this->code = $this->format_string($_REQUEST['codigo']);

            if($this->existingRelojCode($this->code)){
                return $this->updateReloj();
            }

            $this->sql = 'INSERT INTO relojes (Codigo,Descripcion,Dependencia,Secretaria,Usuario,Clave,Ubicacion,Marca,Modelo,DireccionIP,Puerto) 
                VALUES (:codigo,:descripcion,:dependencia,:secretaria,:usuario,:clave,:ubicacion,:marca,:modelo,:direccionip,:puerto)';
            $this->data = [
                //':codigo' => $this->getRelojLastId(),
                ':codigo' => $this->format_string($_REQUEST['codigo']),
                ':descripcion' => $this->format_string($_REQUEST['descripcion']),
                ':dependencia' => $this->format_string($_REQUEST['dependencia']),
                ':secretaria' => $this->format_string($_REQUEST['secretaria']),
                ':usuario' => $this->format_string($_REQUEST['usuario']),
                ':clave' => $_REQUEST['clave'],
                ':ubicacion' => $this->format_string($_REQUEST['ubicacion']),
                ':marca' => $this->format_string($_REQUEST['marca']),
                ':modelo' => $this->format_string($_REQUEST['modelo']),
                ':direccionip' => $_REQUEST['direccionip'],
                ':puerto' => $this->format_string($_REQUEST['puerto'])
            ];
            // var_dump($this->data);
            return ($this->query()) ? array('status' => 'okey') : array('status' => 'error', 'output' => 'Ocurrio un error');
        }

        public function updateReloj(){
            if($_SESSION['FICHADAS'] != 1){
                return array('status' => 'error', 'output' => 'No Permisson');
            }

            $this->id = $this->format_string($_REQUEST['id']);

            $this->sql = 'SELECT COUNT(Codigo) FROM relojes WHERE Codigo = :codigo AND id != :id';
            $this->data = [':codigo' => $this->format_string($_REQUEST['codigo']), ':id' => $this->id];

            if($this->searchRecords() > 0){
                return array('status' => 'error', 'output' => 'El codigo ya existe');
            }

            $this->sql = 'UPDATE relojes set Descripcion=:descripcion,Dependencia=:dependencia,Secretaria=:secretaria,Usuario=:usuario,Clave=:clave,Ubicacion=:ubicacion,Marca=:marca,Modelo=:modelo,DireccionIP=:direccionip,Puerto=:puerto Where Codigo=:codigo ';
            $this->data = [
                ':codigo' => $this->format_string($_REQUEST['codigo']),
                ':descripcion' => $this->format_string($_REQUEST['descripcion']),
                ':dependencia' => $this->format_string($_REQUEST['dependencia']),
                ':secretaria' => $this->format_string($_REQUEST['secretaria']),
                ':usuario' => $this->format_string($_REQUEST['usuario']),
                ':clave' => $_REQUEST['clave'],
                ':ubicacion' => $this->format_string($_REQUEST['ubicacion']),
                ':marca' => $this->format_string($_REQUEST['marca']),
                ':modelo' => $this->format_string($_REQUEST['modelo']),
                ':direccionip' => $_REQUEST['direccionip'],
                ':puerto' => $this->format_string($_REQUEST['puerto'])
            ];

            return ($this->query()) ? array('status' => 'okey') : array('status' => 'error', 'output' => 'Ocurrio un error');
        }
        public function getRelojLastId(){
            $this->sql='SELECT (max(Codigo)+1) AS UltimoID FROM relojes';
            $this->data = [];
            // $datos=$this->query();
            return $this->query()->fetchAll();
        }

        private function existingRelojCode($code){
            $this->sql = 'SELECT COUNT(Codigo) FROM relojes WHERE Codigo = :codigo';
            $this->data = [':codigo' => $code];

            return ($this->query()->fetchColumn() > 0) ? true : false;
        }
    }
?>