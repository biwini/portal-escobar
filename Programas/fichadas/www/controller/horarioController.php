<?php

require_once("globalController.php");

class horario extends Main{

    private $id;
    private $trabajaFeriados,$horario,$descripcion, $tol1, $tol2, $listHorarios;

    function __construct(){
        parent::__construct();
    }

    public function getHorario(){
        $this->id = $this->format_string($_POST['id']);

        $this->sql = 'SELECT id_horario, nombre_horario,c_descripcion,n_trabaja_feriados, d_tolerancia_entrada, d_tolerancia_salida FROM HORARIOS
            WHERE ID_HORARIO = :id';
        $this->data = [':id' => $this->id];

        $result = $this->query();
        $list = array();

        while ($row = $result->fetch()){
            $list = array(
                'id_horario' => $row['id_horario'],
                'nombre_horario' => $row['nombre_horario'],
                'c_descripcion' => $row['c_descripcion'],
                'n_trabaja_feriados' => $row['n_trabaja_feriados'],
                'd_tolerancia_entrada' => $row['d_tolerancia_entrada'],
                'd_tolerancia_salida' => $row['d_tolerancia_salida'],
                'dias' => $this->getHorariosxDia($row['id_horario'])
            );
        }

        return $list;
    }

    public function getHorarios(){
        $this->sql = 'SELECT id_horario, nombre_horario,c_descripcion,n_trabaja_feriados, d_tolerancia_entrada, d_tolerancia_salida FROM horarios';
        $this->data = [];

        $result = $this->query();
        $list = array();

        while ($row = $result->fetch()){
            $list[] = array(
                'id_horario' => $row['id_horario'],
                'nombre_horario' => $row['nombre_horario'],
                'c_descripcion' => $row['c_descripcion'],
                'n_trabaja_feriados' => $row['n_trabaja_feriados'],
                'd_tolerancia_entrada' => $row['d_tolerancia_entrada'],
                'd_tolerancia_salida' => $row['d_tolerancia_salida'],
                'dias' => $this->getHorariosxDia($row['id_horario'])
            );
        }

        return $list;
    }

    private function getHorariosxDia($horario){
        $this->sql = 'SELECT idHorario,idDiaSemana,tEntrada,tSalida,tEntradaAnticipada,tSalidaAdelantada,nActivo FROM HORARIOS_X_DIA
            WHERE idHorario = :id';
        $this->data = [':id' => $horario];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertHorario(){
        if($_SESSION['FICHADAS'] != 1){
            return array('status' => 'error', 'output' => 'No Permisson');
        }

        if(!$this->validateFields('insert')){
            return array('status' => 'error', 'output' => 'Campos incompletos', $_POST);
        }

        $this->trabajaFeriados = $_POST['feriado'];
        $this->horario = $this->format_string($_POST['horario']);
        $this->descripcion = $this->format_string($_POST['descrip']);
        $this->tol1 = $this->format_string($_POST['tol1']);
        $this->tol2 = $this->format_string($_POST['tol2']);
        $this->listHorarios = $_POST['listhorarios'];
        
        if(count($this->listHorarios) == 0){
            return array('status' => 'error', 'output' => 'No se encontraron horarios');
        }

        $this->sql = 'INSERT INTO HORARIOS (nombre_horario,c_descripcion,n_trabaja_feriados, d_tolerancia_entrada, d_tolerancia_salida, idAlta, dAlta) VALUES (:horario, :desc,:feriado,:tol1,:tol2,:user, :fecha)';
        $this->data = [
            ':horario' => $this->horario,
            ':desc' => $this->descripcion,
            ':feriado' => $this->trabajaFeriados,
            ':tol1' => $this->tol1,
            ':tol2' => $this->tol2,
            ':user' => $_SESSION['ID_USER'],
            ':fecha' => $this->getActualDateTime()
        ];

        $result = $this->query();

        //return ($result->errorInfo()[1]) ? array('status'=>"error",'output'=>$result->errorInfo()[2]) : array('status'=>'ok');
        if($result->errorInfo()[1]){
            return array('status' =>"error", 'output' => $result->errorInfo()[2]);
        }

        $this->id = $this->last_id();
        $horariosInsertados = array();

        foreach($this->listHorarios as $h){
            $this->sql = 'INSERT INTO HORARIOS_X_DIA (idHorario,idDiaSemana,tEntrada,tSalida,tEntradaAnticipada,tSalidaAdelantada,nActivo,idAlta,dAlta) 
                VALUES (:id,:dia,:entrada,:salida,:eAnticipada,:sAdelantada,:activo,:user,:fecha)';
            $this->data = [
                ':id' => $this->id,
                ':dia' => $h['dia'],
                ':entrada' => $h['entrada'],
                ':salida' => $h['salida'],
                ':eAnticipada' => ($h['antes'] == '') ? NULL : $h['antes'],
                ':sAdelantada' => ($h['despues'] == '') ? NULL : $h['despues'],
                ':activo' => $h['activo'],
                ':user' => $_SESSION['ID_USER'],
                ':fecha' => $this->getActualDateTime()
            ];
            
            $result = $this->query();

            $horariosInsertados[] = array(
                'id' => $this->id,
                'status' => ($result->errorInfo()[1]) ? 'error' : 'ok',
                'output' => ($result->errorInfo()[1]) ? $result->errorInfo()[2] : ''
            );
        }

        return (count($horariosInsertados) > 0) ? array('status' => 'ok', 'output' => $horariosInsertados) : array('status' => 'error', 'output' => 'Ocurrio un error al guardar los horarios');
    }

    public function updateHorario(){
        if($_SESSION['FICHADAS'] != 1){
            return array('status' => 'error', 'output' => 'No Permisson');
        }
        
        if(!$this->validateFields('update')){
            return array('status' => 'error', 'output' => 'Campos incompletos', $_POST);
        }

        $this->id = $this->format_string($_POST['id']);

        if(!$this->existingHorario('ID',$this->id)){
            return array('status' => 'error', 'output' => 'Horario seleccionado invalido');
        }

        $this->trabajaFeriados = $_POST['feriado'];
        $this->horario = $this->format_string($_POST['horario']);
        $this->descripcion = $this->format_string($_POST['descrip']);
        $this->tol1 = $this->format_string($_POST['tol1']);
        $this->tol2 = $this->format_string($_POST['tol2']);
        $this->listHorarios = $_POST['listhorarios'];
        
        if(count($this->listHorarios) == 0){
            return array('status' => 'error', 'output' => 'No se selecciono ningun rango de dia y hora');
        }

        $this->sql = 'UPDATE HORARIOS SET nombre_horario = :horario, c_descripcion = :desc,n_trabaja_feriados = :feriado, d_tolerancia_entrada = :tol1, d_tolerancia_salida = :tol2, idModificado = :user, dModificado = :fecha WHERE id_horario = :id';
        $this->data = [
            ':horario' => $this->horario,
            ':desc' => $this->descripcion,
            ':feriado' => $this->trabajaFeriados,
            ':tol1' => $this->tol1,
            ':tol2' => $this->tol2,
            ':user' => $_SESSION['ID_USER'],
            ':fecha' => $this->getActualDateTime(),
            ':id' => $this->id
        ];

        $result = $this->query();

        //return ($result->errorInfo()[1]) ? array('status'=>"error",'output'=>$result->errorInfo()[2]) : array('status'=>'ok');
        
        if($result->errorInfo()[1]){
            return array('status' =>"error", 'output' => $result->errorInfo()[2]);
        }

        $horariosInsertados = array();

        foreach($this->listHorarios as $h){
            $this->sql = 'UPDATE HORARIOS_X_DIA SET idDiaSemana = :dia,tEntrada = :entrada,tSalida = :salida,tEntradaAnticipada = :eAnticipada,tSalidaAdelantada = :sAdelantada,nActivo = :activo WHERE idHorario = :id AND idDiaSemana = :idDia';
            $this->data = [
                ':dia' => $h['dia'],
                ':entrada' => $h['entrada'],
                ':salida' => $h['salida'],
                ':eAnticipada' => ($h['antes'] == '') ? NULL : $h['antes'],
                ':sAdelantada' => ($h['despues'] == '') ? NULL : $h['despues'],
                ':activo' => $h['activo'],
                ':id' => $this->id,
                ':idDia' => $h['dia']
            ];
            
            $result = $this->query();

            $horariosInsertados[] = array(
                'id' => $this->id,
                'status' => ($result->errorInfo()[1]) ? 'error' : 'ok',
                'output' => ($result->errorInfo()[1]) ? $result->errorInfo()[2] : ''
            );
        }

        return (count($horariosInsertados) > 0) ? array('status' => 'ok') : array('status' => 'error', 'output' => 'Ocurrio un error al guardar los horarios');
    }

    private function existingHorario($type, $search){
        switch($type){
            case 'ID': $this->sql = 'SELECT COUNT(ID_HORARIO) FROM HORARIOS WHERE ID_HORARIO = :search'; break;
            default : $this->sql = 'SELECT COUNT(ID_HORARIO) FROM HORARIOS WHERE ID_HORARIO = :search'; break;
        }

        $this->data = [':search' => $search];

        return ($this->searchRecords() > 0) ? true : false;
    }

    public function validateFields($call){
        $valid = false;
        switch ($call) {
            case 'insert':
                if(isset($_POST['descrip'], $_POST['tol1'], $_POST['tol2'], $_POST['feriado'], $_POST['horario'])){
                    if(!$this->validateEmptyRequest(array('id','listhorarios','tol1','tol2','descrip','feriado','feriado_trabajo'))){
                        $valid = true;
                    }
                }
            break;
            case 'update':
                if(isset($_POST['descrip'], $_POST['tol1'], $_POST['tol2'], $_POST['horario'], $_POST['feriado'], $_POST['id'])){
                    if(!$this->validateEmptyRequest(array('listhorarios','tol1','tol2','descrip','feriado','feriado_trabajo')) && intval(trim($_POST['id'])) != 0){
                        $valid = true;
                    }
                }
            break;
            case 'delete':
                if(isset($_POST['id'])){
                    if(!$this->validateEmptyRequest(array()) && intval(trim($_POST['id'])) != 0){
                        $valid = true;
                    }
                }
            break;
        }
        return $valid;
    }
}
?>
