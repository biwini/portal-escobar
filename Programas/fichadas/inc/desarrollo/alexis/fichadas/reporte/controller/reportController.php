<?php

    require 'globalController.php';

    class Report extends Main{
        private $search;

        public function getFichadasEmployee($listDni,$from,$to){
            $this->sql="select distinct(FORMAT (fecha, 'yyyy-MM-dd')) AS fecha,FORMAT (fecha, 'HH:mm') AS hora,idEmpleado,fecha AS fullFecha,tipo from fichadas_x_captura 
            where idEmpleado in('".implode("','", $listDni)."') and fecha between :from and :to";
            $this->data=[":from"=>$from,":to"=>$to." 23:59:59.999"];
            //echo $this->sql;
            return $this->group_by("idEmpleado",$this->query()->fetchAll(PDO::FETCH_ASSOC));
        }
        public function getHorariosEmployee($listDni){
            $this->sql="SELECT p.Legajo AS dni,dts.Day,dts.FromTime,dts.ToTime FROM Personas AS p
            inner JOIN AssignedShift AS ash ON ash.PersonId=p.ID_Persona
            inner Join DayTimeShift AS dts ON dts.ShiftId=ash.ShiftId
            WHERE p.Legajo in('".implode("','", $listDni)."') ";
            $this->data=[];
            //echo $this->sql;

            // $this->sql="SELECT dts.Day,dts.FromTime,dts.ToTime FROM Personas AS p
            // inner JOIN AssignedShift AS ash ON ash.PersonId=p.ID_Persona
            // inner Join DayTimeShift AS dts ON dts.ShiftId=ash.ShiftId
            // WHERE p.Legajo=:dni ";
            // $this->data=[":dni"=>$_REQUEST['dni']];
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
            $this->sql='select ID_Persona,Nombre,MiddleName,Apellido,Legajo,Address,BirthDate,Win,Email,SectorId,DependencyId,EmployeeTypeId,d.Name AS nameDependency,s.name as nameSector from Personas as e 
            left join dependencies as d on d.Id=e.DependencyId
            left join sectors as s on s.Id=e.SectorId
            '.$this->getSearchList();
            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }
        public function searchEmployee(){
            $this->sql='select ID_Persona,Nombre,MiddleName,Apellido,Legajo,Address,BirthDate,Win,Email,SectorId,DependencyId,EmployeeTypeId,d.Name AS nameDependency from Personas as e 
            left join dependencies as d on d.Id=e.DependencyId
            where Legajo=:dni';
            $this->data=[":dni"=>$_REQUEST['dni']];
            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }
        private function getSearchList(){
            $s = $_POST['search'];
            if(empty($s['idSecretary']) && empty($s['idDependence']) && empty($s['idEmployee'])){
                return '';
            }

            $listSearch=[];
            $c=0;
            

            if(!empty($s['idSecretary'])){
                $listSearch[$c] = " e.SectorId =:secretaryId ";
                $this->data[':secretaryId'] = $this->format_string($s['idSecretary']);
                $c++;
            }
            if(!empty($s['idDependence'])){
                $listSearch[$c] = " e.DependencyId = :dependenceId ";
                $this->data[':dependenceId'] = $this->format_string($s['idDependence']);
                $c++;
            }
            if(!empty($s['idEmployee'])){
                $listSearch[$c] = " e.id_Persona = :idEmployee ";
                $this->data[':idEmployee'] = $this->format_string($s['idEmployee']);
                $c++;
            }
            // if(!empty($s['fromDate'])){
            //     $listSearch[$c] = " f.fecha >= :fromDate ";
            //     $this->data[':fromDate'] = $this->format_string($s['fromDate']);
            //     $c++;
            // }
            // if(!empty($s['toDate'])){
            //     $listSearch[$c] = " f.fecha <= :toDate ";
            //     $this->data[':toDate'] = $this->format_string($s['toDate']);
            //     $c++;
            // }

            return " where ".implode(" AND ", $listSearch);
        }
        public function getHolidays($from,$to){
            $this->sql="select DateTime,Title from Holiday where DateTime between :from and :to";
            $this->data=[':from'=>$from,':to'=>$to];
            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }
        public function getLicencias($listDni,$from,$to){
            $this->sql="SELECT l.descripcion,p.legajo as dni,fecha_desde,fecha_hasta from licencias AS l 
            LEFT JOIN Personas AS p ON p.ID_Persona=l.empleado
            WHERE p.Legajo in('".implode("','", $listDni)."') 
            and (fecha_desde>=:from and fecha_hasta<=:to)";
            //echo $this->sql;
            $this->data=[':from'=>$from,':to'=>$to];
            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    class Secretary extends Main{
        public function getAllSecretary(){
            $this->sql = 'SELECT id,name from sectors order by name';
            $this->data = [];
            return $this->query()->fetchAll();
        }
        public function getDependence(){
            $this->sql = 'SELECT id,name from dependencies order by name';
            $this->data = [];
            return $this->query()->fetchAll();
        }     
    }

    
?>
