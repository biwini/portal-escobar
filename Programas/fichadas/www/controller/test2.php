<?php 
require 'globalController.php';

class test extends Main{
    public function saveData(){
        $this->sql = 'UPDATE Dependencies SET idDependencia = :id WHERE Id = :dep';
        $this->data = [':id' => $_POST['id'], ':dep' => $_POST['dep']];
 
        return ($this->query()) ? array('status' => 'ok') : array('status' => 'error');
    }

    public function migrarPersonasAEmpleados(){
        return 'No capo';
        $result = array();

        $personas = $this->getPersonas();

        foreach ($personas as $key => $value) {
            $this->sql = 'INSERT INTO empleado (nombre,apellido,legajo,tipoDocumento,nroDocumento,cuit,fechaNacimiento,
            sexo,estadoCivil,direccion,telefono,email,idSecretaria,idDependencia,idTipoEmpleado,estado,
            dAlta,dModificado,idPersona,idSector,idDependency, EmployeeTypeId) VALUES (
                :nombre,:apellido,:legajo,:tipoDocumento,:nroDocumento,:cuit,:fechaNacimiento,
                :sexo,:estadoCivil,:direccion,:telefono,:email,:idSecretaria,:idDependencia,:idTipoEmpleado,:estado,
                :dAlta,:dModificado,:idPersona,:idSector,:idDependency, :EmployeeTypeId)';
                
            $this->data = [
                ':nombre' => $value['Nombre'],
                ':apellido' => $value['Apellido'],
                ':legajo' => $value['FileNumber'],
                ':tipoDocumento' => 'DNI',
                ':nroDocumento' => $value['Legajo'],
                ':cuit' => $value['Win'],
                ':fechaNacimiento' => $value['BirthDate'],
                ':sexo' => NULL,
                ':estadoCivil' => NULL,
                ':direccion' => $value['Address'],
                ':telefono' => NULL,
                ':email' => $value['Email'],
                ':idSecretaria' => $value['idSecretaria'],
                ':idDependencia' => $value['idDependencia'],
                ':idTipoEmpleado' => $value['idTipo'],
                ':estado' => 0,
                ':dAlta' => NULL,
                ':dModificado' => NULL,
                ':idPersona' => $value['ID_Persona'],
                ':idSector' => $value['SectorId'],
                ':idDependency' => $value['DependencyId'],
                ':EmployeeTypeId' => $value['EmployeeTypeId']
            ];

            $datos = $this->query();

            // if($datos->errorInfo()[1]){
            //     echo $datos->errorInfo()[2];
            // }

            $result[] = ($datos->errorInfo()[1]) ? array('type' => $value['Legajo'], 'Status' => 'error') : array('type' => $datos->errorInfo()[2], 'Status' => 'success');
        }
 
        return (count($result) > 0) ? array('status' => 'ok', 'Data' => $result, 'response' => count($result)) : array('status' => 'error');
    }

    public function migrarTipoEmpelado(){
        return 'No capo';
        $result = array();
        $tipoEmpelados = $this->getEmployeeTypes();

        foreach ($tipoEmpelados as $key => $value) {
            $this->sql = 'INSERT INTO tipoEmpleado (nombre,descripcion,estado,idAlta,fechaAlta,idModificado,fechaModificado,employeeTypeId)
                VALUES (:nombre,:descripcion,:estado,:idAlta,:fechaAlta,:idModificado,:fechaModificado,:employeeTypeId)';

            $this->data = [
                ':nombre' => $value['Name'],
                ':descripcion' => $value['Description'],
                ':estado' => $value['Version'],
                ':idAlta' => NULL,
                ':fechaAlta' => $value['CreatedTimestamp'],
                ':idModificado' => NULL,
                ':fechaModificado' => $value['LastModifiedTimestamp'],
                ':employeeTypeId' => $value['Id']
            ];

            $result[] = ($this->query()) ? array('type' => $value['Name'], 'Status' => 'success') : array('type' => $value['Name'], 'Status' => 'error');
        }

        return $result;
    }

    public function migrarShiftToHorarios(){
        return 'No capo';
        $result = array();
        $Shifts = $this->getShifts();

        foreach ($Shifts as $key => $value) {
            $this->sql = 'INSERT INTO HORARIOS (NOMBRE_HORARIO, C_DESCRIPCION, N_TRABAJA_FERIADOS, D_TOLERANCIA_ENTRADA,D_TOLERANCIA_SALIDA,N_ESTADO,ID_SHIFT)
                VALUES (:NOMBRE_HORARIO, :C_DESCRIPCION, :N_TRABAJA_FERIADOS, :D_TOLERANCIA_ENTRADA, :D_TOLERANCIA_SALIDA, :N_ESTADO, :ID_SHIFT)';

            $this->data = [
                ':NOMBRE_HORARIO' => $value['Name'],
                ':C_DESCRIPCION' => $value['Description'],
                ':N_TRABAJA_FERIADOS' => $value['WhichHolidays'],
                ':D_TOLERANCIA_ENTRADA' => $value['TimeBeforeShift'],
                ':D_TOLERANCIA_SALIDA' => $value['TimeAfterShift'],
                ':N_ESTADO' => 1,
                ':ID_SHIFT' => $value['Id'],
            ];

            $datos = $this->query();

            // if($datos->errorInfo()[1]){
            //     echo $datos->errorInfo()[2];
            // }

            $result[] = ($datos->errorInfo()[1]) ? array('type' => $value['Id'], 'Status' => $datos->errorInfo()[2]) : array('type' => $value['Id'], 'Status' => 'success');
        }

        return $result;
    }

    public function migrarDayTimeShiftToHorariosDia(){
        return 'No capo';
        $result = array();
        $dayTimeShifts = $this->getDayTimeShift();

        foreach ($dayTimeShifts as $key => $value) {

            $this->data = [
                ':idHorario' => $this->getIdHorarioFromShiftId($value['ShiftId']),
                ':idDiaSemana' => $value['Day'],
                ':tEntrada' => $value['FromTime'],
                ':tSalida' => $value['ToTime'],
                ':tEntradaAnticipada' => $value['TimeBeforeShift'],
                ':tSalidaAdelantada' => $value['TimeAfterShift'],
                ':nSemana' => $value['Week'],
                ':nDia' => $value['ToDay'],
                ':nFeriado' => $value['IsHoliday'],
                ':idDayTimeShift' => $value['Id'],
                ':idShift' => $value['ShiftId']
            ];

            $this->sql = 'INSERT INTO HORARIOS_X_DIA (idHorario,idDiaSemana,tEntrada,tSalida,tEntradaAnticipada,tSalidaAdelantada,nSemana,nDia,nFeriado, idDayTimeShift,idShift)
                VALUES (:idHorario,:idDiaSemana,:tEntrada,:tSalida,:tEntradaAnticipada,:tSalidaAdelantada,:nSemana,:nDia,:nFeriado,:idDayTimeShift,:idShift)';

            $result[] = ($this->query()) ? array('type' => $value['ShiftId'], 'Status' => 'success') : array('type' => $value['ShiftId'], 'Status' => 'error');
        }

        return $result;
    }

    public function migrarFeriados(){
        return 'No capo';

        $result = array();
        $dayTimeShifts = $this->getHolidays();

        foreach ($dayTimeShifts as $key => $value) {
            $this->sql = 'INSERT INTO Feriados (dFecha,cTitulo,nCategoria)
                VALUES (:dFecha, :cTitulo, :nCategoria)';

            $this->data = [
                ':dFecha' => $value['DateTime'],
                ':cTitulo' => $value['Title'],
                ':nCategoria' => $value['Category'],
            ];

            $result[] = ($this->query()) ? array('type' => $value['Title'], 'Status' => 'success') : array('type' => $value['Title'], 'Status' => 'error');
        }

        return $result;
    }

    public function migrarAssignedShiftToHorariosEmpleado(){
        return 'No capo';
        $result = array();
        $dayTimeShifts = $this->getAssignedShift();

        foreach ($dayTimeShifts as $key => $value) {

            $this->data = [
                ':idEmpleado' => $this->getEmpleadoIdByPersonId($value['PersonId']),
                ':idHorario' => $this->getHorarioIdByShiftId($value['ShiftId']),
                ':dDesde' => $value['FromDate'],
                ':dHasta' => $value['ToDate'],
                ':nSemana' => $value['StartingWeek'],
                ':idAssignedShift' => $value['Id']
            ];
            
            $this->sql = 'INSERT INTO HORARIOS_EMPLEADO (idEmpleado,idHorario,dDesde,dHasta,nSemana,idAssignedShift)
                VALUES (:idEmpleado,:idHorario,:dDesde,:dHasta,:nSemana,:idAssignedShift)';

            $result[] = ($this->query()) ? array('type' => $value['Id'], 'Status' => 'success') : array('type' => $value['Id'], 'Status' => 'error');
        }

        return $result;
    }

    public function migrarLeaveToLicencias(){

        // return 'No capo';
        $result = array();
        $licencias = $this->getLeaves();

        foreach ($licencias as $key => $value) {
            // $this->data = [
            //     ':idEmpleado' => $this->getEmpleadoIdByPersonId($value['EmployeeId']),
            //     ':idMotivo' => $this->getIdMotivoByCaseId($value['MotiveId']),
            //     ':dFechaInicio' => $value['StartDate'],
            //     ':dFechaFin' => $value['EndDate'],
            //     ':cNotas' => $value['Notes'],
            //     ':idLeave' => $value['Id'],
            //     ':EmployeeId' => $value['EmployeeId'],
            // ];
            $idMotivo = $this->getIdMotivoByCaseId($value['MotiveId']);
            $this->data = [':idMotivo' => $idMotivo, ':idLeave' => $value['Id']];
            $this->sql = 'UPDATE licencias SET idMotivo = :idMotivo WHERE idLeave = :idLeave AND idAlta IS NULL';
        
            
            // $this->sql = 'INSERT INTO licencias (idEmpleado,idMotivo,dFechaInicio,dFechaFin,cNotas,idLeave,EmployeeId)
            //     VALUES (:idEmpleado,:idMotivo,:dFechaInicio,:dFechaFin,:cNotas,:idLeave, :EmployeeId)';

            $result[] = ($this->query()) ? array('type' => $value['Id'], 'Status' => $this->data) : array('type' => $value['Id'], 'Status' => 'error');
        }

        return $result;
    }

    public function migrarControladorasToRelojes(){
        return 'No capo';
        $result = array();
        $controladoras = $this->getControladoras();

        foreach ($controladoras as $key => $value) {
            
            $this->sql = 'INSERT INTO relojes (Codigo,Descripcion,Dependencia,Secretaria,Usuario,Clave,Ubicacion,Marca,Modelo,DireccionIP,Puerto,Observaciones,fecha_ultimo_error,descripcion_ultimo_error)
                VALUES (:Codigo,:Descripcion,:Dependencia,:Secretaria,:Usuario,:Clave,:Ubicacion,:Marca,:Modelo,:DireccionIP,:Puerto,:Observaciones,:fecha_ultimo_error,:descripcion_ultimo_error)';
            $this->data = [
                ':Codigo' => NULL,
                ':Descripcion' => $value['Name'],
                ':Dependencia' => NULL,
                ':Secretaria' => NULL,
                ':Usuario' => NULL,
                ':Clave' => NULL,
                ':Ubicacion' => NULL,
                ':Marca' => $value['DeviceType'],
                ':Modelo' => NULL,
                ':DireccionIP' => $value['IPAddress'],
                ':Puerto' => NULL,
                ':Observaciones' => NULL,
                ':fecha_ultimo_error' => NULL,
                ':descripcion_ultimo_error' => NULL,
            ];
            $datos = $this->query();

            // if($datos->errorInfo()[1]){
            //     echo $datos->errorInfo()[2];
            // }

            $result[] = ($datos->errorInfo()[1]) ? array('type' => $value['Name'], 'Status' => $datos->errorInfo()[2]) : array('type' => $value['Name'], 'Status' =>  $datos->errorInfo()[2]);
        }

        return $result;
    }

    public function getControladoras(){
        $this->sql = 'SELECT * FROM Controladoras';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdMotivoByCaseId($id){
        $this->sql = 'SELECT idMotivo FROM CaseMotive WHERE idCaseMotive = :id';
        $this->data = [':id' => $id];

        return $this->query()->fetchColumn(0);
    }

    public function getLeaves(){
        $this->sql = 'SELECT * FROM Leave';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmpleadoIdByPersonId($id){
        $this->sql = 'SELECT id FROM empleado WHERE idPersona = :id';
        $this->data = [':id' => $id];

        return $this->query()->fetchColumn(0);
    }

    public function getHorarioIdByShiftId($id){
        $this->sql = 'SELECT ID_HORARIO FROM HORARIOS WHERE ID_SHIFT = :id';
        $this->data = [':id' => $id];

        return $this->query()->fetchColumn(0);
    }

    public function getAssignedShift(){
        $this->sql = 'SELECT * FROM AssignedShift';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHolidays(){
        $this->sql = 'SELECT * FROM Holiday';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getShifts(){
        $this->sql = 'SELECT * FROM Shift';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdHorarioFromShiftId($ShiftId){
        $this->sql = 'SELECT ID_HORARIO FROM HORARIOS WHERE ID_SHIFT = :id';
        $this->data = [':id' => $ShiftId];

        return $this->query()->fetchColumn(0);
    }

    public function getEmployeeTypes(){
        $this->sql = 'SELECT * FROM EmployeeTypes';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDayTimeShift(){
        $this->sql = 'SELECT * FROM DayTimeShift';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSectors(){
        $this->sql = 'SELECT * FROM Sectors';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDependences(){
        $this->sql = 'SELECT * FROM Dependencies';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPersonas(){
        $this->sql = 'SELECT p.*,s.*,d.*, t.id AS idTipo FROM Personas AS p
            LEFT JOIN Sectors AS s ON p.SectorId = s.Id
            LEFT JOIN Dependencies AS d ON p.DependencyId = d.Id
            LEFT JOIN tipoEmpleado AS t ON p.EmployeeTypeId = t.employeeTypeId';
        // $this->sql = 'SELECT p.*,s.*,d.*, t.id AS idTipo FROM Personas AS p
        //     LEFT JOIN empleado AS e ON p.ID_Persona = e.idPersona
        //     LEFT JOIN Sectors AS s ON p.SectorId = s.Id
        //     LEFT JOIN Dependencies AS d ON p.DependencyId = d.Id
        //     LEFT JOIN tipoEmpleado AS t ON p.EmployeeTypeId = t.employeeTypeId
        //     WHERE e.idPersona IS NULL';
        // $this->sql = 'SELECT * FROM empleado';
        $this->data = [];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        // return $this->query();
    }
}
$Test = new test();

switch ($_POST['hola']) {
    case '1':
        echo json_encode($Test->saveData());
    break;
    case '2':
        echo json_encode($Test->migrarPersonasAEmpleados());
    break;
    case '3':
        echo json_encode($Test->migrarTipoEmpelado());
    break;
    case '4':
        echo json_encode($Test->migrarShiftToHorarios());
    break;
    case '5':
        echo json_encode($Test->migrarDayTimeShiftToHorariosDia());
    break;
    case '6':
        echo json_encode($Test->migrarFeriados());
    break;
    case '7':
        echo json_encode($Test->migrarAssignedShiftToHorariosEmpleado());
    break;
    case '8':
        echo json_encode($Test->migrarLeaveToLicencias());
    break;
}

?>