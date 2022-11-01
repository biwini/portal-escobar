<?php

require_once("globalController.php");

class licencia extends Main
{
    private $id, $motivo, $empleado, $inicio, $fin, $nota;

    public function getLicencias()
    {
        $this->sql = 'SELECT l.idLicencia,l.idMotivo, m.cMotivo, l.idEmpleado, e.idSecretaria, e.idDependencia, e.apellido, 
            e.nombre, e.legajo, e.nroDocumento, l.dFechaInicio, l.dFechaFin, l.cNotas, l.Cerrado, l.CerradoMotivoId FROM licencias AS l 
                LEFT JOIN empleado AS e ON l.idEmpleado = e.id
                LEFT JOIN Motivos AS m ON l.idMotivo = m.idMotivo
                WHERE (l.idBaja IS NULL) and ( l.dFechaFin>= :fecha_ahora) ORDER BY l.dFechaFin DESC';
        //$this->data = [':fecha_ahora' => date('Y-m-d')];
        $this->data = [':fecha_ahora' => '2000-01-01'];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLicencia()
    {
        $this->id = $this->format_string($_REQUEST['id']);

        $this->sql = 'SELECT l.idLicencia,l.idMotivo, m.cMotivo, l.idEmpleado, e.apellido, e.nombre, e.legajo, e.nroDocumento, 
            l.dFechaInicio, l.dFechaFin, l.cNotas, l.Cerrado, l.CerradoMotivoId FROM licencias AS l
                LEFT JOIN empleado AS e ON l.idEmpleado = e.id
                LEFT JOIN Motivos AS m ON l.idMotivo = m.idMotivo
                WHERE idLicencia = :id';
        $this->data = [':id' => $this->id];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistory()
    {
        $this->id = $this->format_string($_REQUEST['id']);

        $this->sql = 'SELECT l.idLicencia,l.idMotivo, m.cMotivo, l.idEmpleado, e.apellido, e.nombre, e.legajo, e.nroDocumento, l.dFechaInicio, l.dFechaFin, l.cNotas FROM licencias AS l
                LEFT JOIN empleado AS e ON l.idEmpleado = e.id
                LEFT JOIN Motivos AS m ON l.idMotivo = m.idMotivo
                WHERE l.idEmpleado = :id AND l.idBaja IS NULL 
                ORDER BY l.dFechaInicio ASC';
        $this->data = [':id' => $this->id];

        return $this->query()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertLicense()
    {
        $this->motivo = (int) $_REQUEST['motivo'];
        $this->empleado = (int) $_REQUEST['empleado'];
        $this->inicio = $_REQUEST['fechainicio'];
        $this->fin = $_REQUEST['fechafin'];
        $this->nota = $_REQUEST['comentario'];
        $this->cerrado = (bool) $_REQUEST["cerrado"];
        $this->cerradoMotivoId = (int) $_REQUEST["cerradoMotivo"];

        // if(!$this->validDate($this->inicio) || !$this->validDate($this->fin)){
        //     return array('status' => 'error', 'output' => 'Fecha Invalida');
        // }

        if ($this->haveLicence($this->empleado, $this->inicio, $this->fin)) {
            return array('status' => 'error', 'output' => 'El empleado ya posee una licencia entre las fechas seleccionadas');
        }

        $this->sql = 'INSERT INTO licencias (idMotivo, idEmpleado, dFechaInicio, dFechaFin, cNotas, idAlta, dAlta, Cerrado, CerradoMotivoId) 
                VALUES (:motivo, :empleado, :inicio, :fin, :notas, :user, :fecha, :cerrado, :cerradoMotivoId)';

        $this->data = [
            ':motivo' => $this->motivo,
            ':empleado' => $this->empleado,
            ':inicio' => $this->inicio,
            ':fin' => $this->fin,
            ':notas' => $this->nota,
            ':user' => $_SESSION['ID_USER'],
            ':fecha' => $this->getActualDateTime(),
            ":cerrado" => $this->cerrado,
            ":cerradoMotivoId" => $this->cerradoMotivoId
        ];

        $datos = $this->query();

        return ($datos->errorInfo()[1]) ? array('status' => "error", 'output' => $datos->errorInfo()[2]) : array('status' => 'ok');
    }

    public function updateLicense()
    {
        $this->id = (int) $this->format_string($_REQUEST['id']);

        if (!$this->existingLicence('ID', $this->id)) {
            return array('status' => 'error', 'output' => 'No se encontro la licencia', $_REQUEST);
        }

        $this->motivo = (int) $_REQUEST['motivo'];
        $this->empleado = (int) $_REQUEST['empleado'];
        $this->inicio = $_REQUEST['fechainicio'];
        $this->fin = $_REQUEST['fechafin'];
        $this->nota = $_REQUEST['comentario'];
        $this->cerrado = (bool) $_REQUEST["cerrado"];
        $this->cerradoMotivoId = (int) $_REQUEST["cerradoMotivo"];

        if (!$this->validDate($this->inicio) || !$this->validDate($this->fin)) {
            return array('status' => 'error', 'output' => 'Fecha Invalida');
        }

        $this->sql = 'SELECT COUNT(idLicencia) FROM licencias WHERE idEmpleado = :id AND (dFechaInicio >= :desde AND dFechaFin <= :hasta) AND idLicencia != :idL';
        $this->data = [
            ':id' => $this->empleado,
            ':desde' => $this->inicio . ' 00:00:00',
            ':hasta' => $this->fin . ' 23:59:59',
            ':idL' => $this->id
        ];

        if ($this->searchRecords() > 0) {
            return array('status' => 'error', 'output' => 'El empleado ya posee una licencia entre las fechas seleccionadas');
        }

        $this->sql = 'UPDATE licencias SET idMotivo = :motivo, idEmpleado = :empleado, 
            dFechaInicio = :inicio, dFechaFin = :fin, cNotas = :notas, 
            idModificado = :user, dModificado = :fecha,
            Cerrado = :cerrado, CerradoMotivoId = :cerradoMotivoId
            WHERE idLicencia = :id';

        $this->data = [
            ':motivo' => $this->motivo,
            ':empleado' => $this->empleado,
            ':inicio' => $this->inicio,
            ':fin' => $this->fin,
            ':notas' => $this->nota,
            ':user' => $_SESSION['ID_USER'],
            ':fecha' => $this->getActualDateTime(),
            ':id' => $this->id,
            ":cerrado" => $this->cerrado,
            ":cerradoMotivoId" => $this->cerradoMotivoId
        ];

        $datos = $this->query();

        return ($datos->errorInfo()[1]) ? array('status' => "error", 'output' => $datos->errorInfo()[2]) : array('status' => 'ok');
    }

    public function deleteLicense()
    {
        $this->id = $this->format_string($_REQUEST['id']);

        if (!$this->existingLicence('ID', $this->id)) {
            return array('status' => 'error', 'output' => 'No se encontro la licencia');
        }

        $this->sql = 'UPDATE licencias SET idBaja = :user, dBaja = :fecha WHERE idLicencia = :id';
        $this->data = [':user' => $_SESSION['ID_USER'], ':fecha' => $this->getActualDateTime(), ':id' => $this->id];

        $datos = $this->query();

        return ($datos->errorInfo()[1]) ? array('status' => "error", 'output' => $datos->errorInfo()[2]) : array('status' => 'ok');
    }

    private function existingLicence($type, $search)
    {
        switch ($type) {
            case 'ID':
                $this->sql = 'SELECT COUNT(idLicencia) FROM licencias WHERE idLicencia = :search';
                break;
            default:
                $this->sql = 'SELECT COUNT(idLicencia) FROM licencias WHERE idLicencia = :search';
                break;
        }

        $this->data = [':search' => $search];

        return ($this->searchRecords() > 0) ? true : false;
    }

    private function haveLicence($user, $desde, $hasta)
    {
        $this->sql =
            'SELECT 
                COUNT(idLicencia) 
            FROM 
                licencias 
            WHERE 
                idEmpleado = :id AND 
                (dFechaInicio >= :desde AND 
                dFechaFin <= :hasta) AND
                idBaja IS NULL';

        $this->data = [
            ':id' => $user,
            ':desde' => $desde . ' 00:00:00',
            ':hasta' => $hasta . ' 23:59:59',
        ];

        return ($this->searchRecords() > 0) ? true : false;
    }
}
