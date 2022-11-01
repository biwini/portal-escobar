<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class statics extends globalController{
		private $Id;
		private $Chart;
		private $ResultChart;

		function __construct(){
			$this->Id = 0;
			$this->Name = '';
			$this->ResultChart = array();

			parent::__construct();
		}

        public function getStatics(){
            if(!isset($_POST['chart']) && $_SESSION['TICKETS'] != 1 && !$this->isTecnicUser){
                return array('Status' => 'Invalid Call');
            }
            $this->Chart = intval($this->cleanString($_POST['chart']), 10);

            if($this->Chart == 4){
                return $this->getTimes();
            }

            if($this->Chart == 1){
                $this->ResultChart[0] = array('Name' => 'Lunes', 'Cantidad' => 0);
                $this->ResultChart[1] = array('Name' => 'Martes', 'Cantidad' => 0);
                $this->ResultChart[2] = array('Name' => 'Miercoles', 'Cantidad' => 0);
                $this->ResultChart[3] = array('Name' => 'Jueves', 'Cantidad' => 0);
                $this->ResultChart[4] = array('Name' => 'Viernes', 'Cantidad' => 0);
                $this->ResultChart[5] = array('Name' => 'Sabado', 'Cantidad' => 0);
                $this->ResultChart[6] = array('Name' => 'Domingo', 'Cantidad' => 0);
            }

            $result = $this->getValues();

            if(!$result){
                return array();
            }

            while ($row = $result->fetch()){
                if($this->Chart == 1){
                    switch ($row['Nombre']) {
                        case 'Lunes':
                            $this->ResultChart[0] = ($row['Nombre'] == 'Lunes') ? array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']) : array('Name' => 'Lunes', 'Cantidad' => 0);
                            break;
                        case 'Martes':
                            $this->ResultChart[1] = ($row['Nombre'] == 'Martes') ? array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']) : array('Name' => 'Martes', 'Cantidad' => 0);
                        break;
                        case 'Miercoles':
                            $this->ResultChart[2] = ($row['Nombre'] == 'Miercoles') ? array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']) : array('Name' => 'Miercoles', 'Cantidad' => 0);
                        break;
                        case 'Jueves':
                            $this->ResultChart[3] = ($row['Nombre'] == 'Jueves') ? array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']) : array('Name' => 'Jueves', 'Cantidad' => 0);
                        break;
                        case 'Viernes':
                            $this->ResultChart[4] = ($row['Nombre'] == 'Viernes') ? array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']) : array('Name' => 'Viernes', 'Cantidad' => 0);
                        break;
                        case 'Sabado':
                            $this->ResultChart[5] = ($row['Nombre'] == 'Sabado') ? array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']) : array('Name' => 'Sabado', 'Cantidad' => 0);
                        break;
                        case 'Domingo':
                            $this->ResultChart[6] = ($row['Nombre'] == 'Domingo') ? array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']) : array('Name' => 'Domingo', 'Cantidad' => 0);
                        break;
                    }
                }else{
                    $this->ResultChart[] = ($this->Chart == 4) ? array('t' => $row['Nombre'], 'y' => $row['Cantidad']) : array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
                }
                
            }

            return $this->ResultChart;
        }
        private function getTimes(){
            if(!isset($_POST['chart']) && $_SESSION['TICKETS'] != 1 && !$this->isTecnicUser){
                return array('Status' => 'Invalid Call');
            }
            $tecnico = (isset($_POST['tecnico']) && !empty($_POST['tecnico'])) ? '= '.$_POST['tecnico'] : '!= 0';
        
            $Start = array();
            $Taking = array();
            $Finalizado = array();

            $this->query = 'SELECT dFechaAlta AS Nombre, FORMAT(dFechaAlta, \'hh.mm\') AS Cantidad,
                dFechaAlta AS Nombre2, CONCAT(((DATEDIFF(Minute,dFechaAlta,dFechaToma)/60) + FORMAT (dFechaToma, \'hh\')),\'.\', (DATEDIFF(Minute,dFechaAlta,dFechaToma)%60) + FORMAT (dFechaToma, \'mm\')) Cantidad2, DATEADD(SECOND, DATEDIFF(SECOND,dFechaAlta,dFechaToma),dFechaAlta) AS DiffToma,
                dFechaAlta AS Nombre3, FORMAT(dFechaFinalizado, \'hh.mm\') AS TiempoFinalizado, CONCAT(((DATEDIFF(Minute,dFechaAlta,dFechaFinalizado)/60) + FORMAT (dFechaFinalizado, \'hh\')),\'.\', (DATEDIFF(Minute,dFechaAlta,dFechaFinalizado)%60) + FORMAT(dFechaFinalizado, \'mm\')) Cantidad3, DATEADD(SECOND, DATEDIFF(SECOND,dFechaToma,dFechaFinalizado),dFechaToma) AS DiffEnd
                FROM Ticket WHERE (dFechaAlta >= :Since AND dFechaAlta <= :Until) AND idAlta '.$tecnico.' ORDER BY idTicket';
            $this->data = [
                ':Since' => (isset($_POST['desde']) && !empty($_POST['desde'])) ? $_POST['desde'] : '2019-01-01 00:00:00',
                ':Until' => (isset($_POST['hasta']) && !empty($_POST['hasta'])) ? $_POST['hasta'] : $this->fecha
            ];

            $result = $this->executeQuery();

            while ($row = $result->fetch()){
                $Start[] = array('t' => $row['Nombre'], 'y' => $row['Cantidad']);
                $Taking[] = array('t' => $row['Nombre2'], 'y' => $row['Cantidad2'], 'Diff' => $row['DiffToma']);
                $Finalizado[] = array('t' => $row['Nombre3'], 'y' => $row['Cantidad3'], 'Diff' => $row['DiffEnd']);
            }

            return $this->ResultChart = array('Inicio' => $Start, 'Toma' => $Taking, 'Finalizado' => $Finalizado);
        }

        private function getValues(){
            $tecnico = (isset($_POST['tecnico']) && !empty($_POST['tecnico'])) ? '= '.$_POST['tecnico'] : '!= 0';
            $this->data = [
                ':Since' => (isset($_POST['desde']) && !empty($_POST['desde'])) ? $_POST['desde'] : '2019-01-01 00:00:00',
                ':Until' => (isset($_POST['hasta']) && !empty($_POST['hasta'])) ? $_POST['hasta'] : $this->fecha
            ];
            switch ($this->Chart) {
                case 1:                    
                    $this->query = 'SELECT CASE (Datename(WEEKDAY,dFechaAlta))
                            WHEN \'Monday\' THEN \'Lunes\'
                            WHEN \'Tuesday\' THEN \'Martes\'
                            WHEN \'Wednesday\' THEN \'Miercoles\'
                            WHEN \'Thursday\' THEN \'Jueves\'
                            WHEN \'Friday\' THEN \'Viernes\'
                            WHEN \'Saturday\' THEN \'Sabado\'
                            WHEN \'Sunday\' THEN \'Domingo\' END AS Nombre, 
                        COUNT(idTicket) AS Cantidad FROM Ticket WHERE (dFechaAlta >= :Since AND dFechaAlta <= :Until) AND idAlta '.$tecnico.'
                        GROUP BY Datename(WEEKDAY,dFechaAlta) 
                        ORDER BY CASE datename(weekday, dFechaAlta)
                            WHEN \'Monday\' THEN 1
                            WHEN \'Tuesday\' THEN 2
                            WHEN \'Wednesday\' THEN 3
                            WHEN \'Thursday\' THEN 4
                            WHEN \'Friday\' THEN 5
                            WHEN \'Saturday\' THEN 6
                            WHEN \'Sunday\' THEN 7
                            END';
                break;
                case 2:
                    $this->query = 'SELECT c.cNombre AS Nombre, COUNT(t.idTicket) AS Cantidad FROM Usuario AS c
                        INNER JOIN Ticket AS t ON c.idUsuario = t.idAlta
                        WHERE c.idDependencia = 2 AND (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until) AND t.idAlta '.$tecnico.' GROUP BY c.cNombre ORDER BY COUNT(t.idTicket) DESC';
                break;
                case 3:
                    $this->query = 'SELECT c.cNombre AS Nombre, COUNT(t.idTicket) AS Cantidad FROM Usuario AS c
                        INNER JOIN Ticket AS t ON c.idUsuario = t.idTecEncargado
                        WHERE c.idDependencia = 2 AND (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until) AND t.idAlta '.$tecnico.' GROUP BY c.cNombre ORDER BY COUNT(t.idTicket) DESC';
                break;
                case 5:
                    $this->query = 'SELECT sm.cSubMotivo AS Nombre,COUNT(t.idTicket) AS Cantidad FROM Ticket AS t
                        INNER JOIN MotivoxTicket AS mxt ON t.idTicket = mxt.idTicket
                        INNER JOIN SubMotivo AS sm ON mxt.idSubMotivo = sm.idSubMotivo
                        WHERE sm.idMotivo = :Motivo AND (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until) AND t.idAlta '.$tecnico.'
                        GROUP BY sm.cSubMotivo
                        ORDER BY COUNT(t.idTicket) DESC';
                    $this->data = [
                        ':Since' => (isset($_POST['desde']) && !empty($_POST['desde'])) ? $_POST['desde'] : '2019-01-01 00:00:00',
                        ':Until' => (isset($_POST['hasta']) && !empty($_POST['hasta'])) ? $_POST['hasta'] : $this->fecha,
                        ':Motivo' => (isset($_POST['motivo'])) ? $this->cleanString($_POST['motivo']) : 1
                    ];
                break;
                default:
                    $this->query = '';
                    $this->data = [];
                break;
            }

            return ($this->query != '') ? $this->executeQuery() : false;
        }

        public function validateFields($call){
            $valid = false;
            switch ($call) {
                case 'getStatics':
                    if(isset($_POST['chart']) && $_SESSION['TICKETS'] == 1 && $this->isTecnicUser){
                        if(!$this->validateEmptyPost(array('desde','hasta','motivo','tecnico'))){
                            $valid = true;
                        }
                    }
                break;
            }
            return $valid;
        }
	}
?>