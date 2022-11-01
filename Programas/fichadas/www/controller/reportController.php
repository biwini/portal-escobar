<?php

    require 'globalController.php';

    class Report extends Main{
        private $search;
        public function getFichadasEmployee($listDni,$from,$to){
            $this->sql = 'SELECT 
                    distinct(FORMAT (fxc.fecha, \'yyyy-MM-dd\')) AS fecha,
                    FORMAT (fxc.fecha, \'HH:mm\') AS hora,
                    fxc.idEmpleado,
                    fxc.fecha AS fullFecha,
                    fxc.tipo,
                    rel.Descripcion as reloj         
                from fichadas_x_captura as fxc
                left join captura as cap on cap.id = fxc.idCaptura
                left join relojes as rel on rel.id = cap.idReloj
                WHERE fxc.idEmpleado in(\''.implode('\',\'', $listDni).'\') AND (fxc.fecha BETWEEN :from AND :to)';
            
            $this->data=[':from' => $from, ':to' => $to.' 23:59:59.999'];

            $fichadas = $this->group_by('idEmpleado',$this->query()->fetchAll(PDO::FETCH_ASSOC));

            return $fichadas;
        }

        // public function getFichadasEmployee($listDni,$from,$to){
        //     $this->sql="select distinct(FORMAT (fecha, 'yyyy-MM-dd')) AS fecha,FORMAT (fecha, 'HH:mm') AS hora,idEmpleado,fecha AS fullFecha,tipo from fichadas_x_captura 
        //         where idEmpleado in('".implode("','", $listDni)."') and fecha between :from and :to";
        //     $this->data=[":from"=>$from,":to"=>$to." 23:59:59.999"];
        //     //echo $this->sql;
        //     return $this->group_by("idEmpleado",$this->query()->fetchAll(PDO::FETCH_ASSOC));
        // }

        public function getHorariosEmployee($listDni){
            $this->sql = 'SELECT e.nroDocumento AS dni,hd.idDiaSemana,hd.tEntrada,hd.tSalida FROM empleado AS e
                INNER JOIN horarios_empleado AS he ON he.idEmpleado = e.id
                INNER JOIN horarios_x_dia AS hd ON hd.idHorario = he.idHorario AND hd.nActivo = 1
                WHERE e.nroDocumento in(\''.implode('\',\'', $listDni).'\')';           

            $this->data=[];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        private function group_by($key, $data) {
            $result = array();
        
            foreach($data as $val) {
                if(array_key_exists($key, $val)){
                    $result[$val[$key]][] = $val;
                }else{
                    $result[""][] = $val;
                }
            }
        
            return $result;
        }

        public function getEmployee(){
            $this->sql = 'SELECT e.nombre,apellido,legajo,tipoDocumento,nroDocumento,fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,
                e.idSecretaria,e.idDependencia,e.idTipoEmpleado,cuit,s.cNomSecretaria as nombreSecretaria,t.nombre as nombreTipoEmpleado,
                d.cNomDependencia as nombreDependencia,nHorasDia
                FROM empleado AS e
                LEFT JOIN PortalEscobar.dbo.Secretaria AS s ON s.idSecretaria=e.idSecretaria
                LEFT JOIN tipoEmpleado AS t ON t.id=e.idTipoEmpleado
                LEFT JOIN PortalEscobar.dbo.Dependencia AS d ON d.idDependencia=e.idDependencia'.$this->getSearchList()." AND e.idBaja IS NULL";
            // $this->data = [];
            // echo $this->sql;

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function searchEmployee(){
            $this->sql = 'SELECT e.nombre,apellido,legajo,tipoDocumento,nroDocumento,fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,
                e.idDependencia,cuit,d.cNomDependencia as nombreDependencia
                from empleado as e 
                LEFT JOIN PortalEscobar.dbo.Dependencia AS d ON d.idDependencia= e.DependencyId
                WHERE nroDocumento = :dni';
            $this->data=[':dni' => $_REQUEST['dni']];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }
        private function getSearchList(){
            $s = $_POST['search'];
            if(empty($s['idSecretary']) && empty($s['idDependence']) && empty($s['idEmployee']) && empty($s['name']) && empty($s['dni']) && empty($s['legajo'])){
                return '';
            }
            $listSearch=[];
            $c=0;

            if(!empty($s['idSecretary'])){
                $listSearch[$c] = ' e.idSecretaria =:secretaryId ';
                $this->data[':secretaryId'] = $this->format_string($s['idSecretary']);
                $c++;
            }
            if(!empty($s['idDependence'])){
                $listSearch[$c] = ' e.idDependencia = :dependenceId ';
                $this->data[':dependenceId'] = $this->format_string($s['idDependence']);
                $c++;
            }
            if(!empty($s['idEmployee'])){
                $listSearch[$c] = ' e.id = :idEmployee ';
                $this->data[':idEmployee'] = $this->format_string($s['idEmployee']);
                $c++;
            }
            if(!empty($s['name'])){
                $listSearch[$c] = ' concat(e.Apellido,\' \',e.Nombre) like :name ';
                $this->data[':name'] = "%".$this->format_string($s['name'])."%";
                $c++;
            }
            if(!empty($s['dni'])){
                $listSearch[$c] = ' e.nroDocumento = :dni ';
                $this->data[':dni'] = $this->format_string($s['dni']);
                $c++;
            }
            if(!empty($s['legajo'])){
                $listSearch[$c] = ' e.legajo = :legajo ';
                $this->data[':legajo'] = $this->format_string($s['legajo']);
                $c++;
            }

            $where = ' WHERE '.implode(' AND ', $listSearch);
            return $where;
        }
        public function getHolidays($from,$to){
            $this->sql = 'SELECT dFecha,cTitulo FROM Feriados WHERE dFecha BETWEEN :from AND :to';
            $this->data = [':from' => $from, ':to' => $to];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getLicencias($listDni,$from,$to){
            $this->sql='SELECT m.cMotivo AS descripcion,e.nroDocumento as dni,dFechaInicio AS fecha_desde, dFechaFin AS fecha_hasta from licencias AS l 
                LEFT JOIN empleado AS e ON e.id=l.idEmpleado
                LEFT JOIN Motivos AS m ON l.idMotivo = m.idMotivo
                WHERE l.idBaja IS NULL AND e.nroDocumento in(\''.implode('\',\'', $listDni).'\') ';
                // AND (dFechaInicio >= :from AND dFechaFin <= :to OR dFechaFin >= :to2)';

            // $this->data=[':from' => $from, ':to' => $to, ':to2' => $to];
            $this->data = [];
            // return $this->sql;

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    class Secretary extends Main{
        public function getAllSecretary(){
            $this->sql = 'SELECT idSecretaria,cNomSecretaria from PortalEscobar.dbo.Secretaria order by cNomSecretaria';
            $this->data = [];
            return $this->query()->fetchAll();
        }
        public function getDependence(){
            $this->sql = 'SELECT idDependencia,cNomDependencia from PortalEscobar.dbo.Dependencia order by cNomDependencia';
            $this->data = [];
            return $this->query()->fetchAll();
        }     
    }

    
?>
