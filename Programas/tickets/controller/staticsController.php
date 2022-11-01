<?php 
    require_once realpath(__DIR__ ).'/globalController.php';

	class statics extends globalController{
		private $Id;
		private $Chart;
        private $ResultChart;
        private $filter;

		function __construct(){
			$this->Id = 0;
			$this->Name = '';
			$this->ResultChart = array();

			parent::__construct();
        }

        public function getTicketsData(){

            $tickets = array();

            $tickets['pendientes'] = $this->getTicketsByStatus(1);
            $tickets['curso'] = $this->getTicketsByStatus(2);
            $tickets['finalizados'] = $this->getTicketsByStatus(3);
            $tickets['todos'] = $this->getTicketsByStatus();

            return $tickets;
        }

        private function getTicketsByStatus($status = null){
            if($status == null){
                $this->query = 'SELECT COUNT(idTicket) FROM Ticket';
                $this->data = [];
            }else{
                $this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE nEstado = :status';
                $this->data = [':status' => $status];
            }

            return $this->searchRecords();
        }

        public function getExcelData(){
            $this->Chart = $_POST['chart'];

            switch ($this->Chart) {
                case 'ticketsbyweek':
                    $this->query = 'SELECT CASE (Datename(WEEKDAY,t.dFechaAlta))
                        WHEN \'Monday\' THEN \'Lunes\'
                        WHEN \'Tuesday\' THEN \'Martes\'
                        WHEN \'Wednesday\' THEN \'Miercoles\'
                        WHEN \'Thursday\' THEN \'Jueves\'
                        WHEN \'Friday\' THEN \'Viernes\'
                        WHEN \'Saturday\' THEN \'Sabado\'
                        WHEN \'Sunday\' THEN \'Domingo\' END AS DiaCreado, 
                        t.cCodigo, m.cMotivo, sm.cSubMotivo, t.cObs, CONCAT(u.cNombre,\' \', u.cApellido) AS Usuario, d.cNomDependencia, 
                        CONCAT(tec.cNombre,\' \', tec.cApellido) AS CreadoPor, 
                        CONCAT(enc.cNombre,\' \', enc.cApellido) AS TecnicoEncargado,
                        CONCAT(fin.cNombre,\' \', fin.cApellido) AS FinalizadoPor,
                        t.dFechaAlta AS FechaCreado, t.dFechaToma, t.dFechaFinalizado
                        FROM Ticket AS t 
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        LEFT JOIN Usuario AS u ON t.idUsuario = u.idUsuario
                        LEFT JOIN Usuario AS tec ON t.idAlta = tec.idUsuario
                        LEFT JOIN Usuario AS enc ON t.idTecEncargado = enc.idUsuario
                        LEFT JOIN Usuario AS fin ON t.idFinalizado = fin.idUsuario
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        ORDER BY CASE datename(weekday, t.dFechaAlta)
                            WHEN \'Monday\' THEN 1
                            WHEN \'Tuesday\' THEN 2
                            WHEN \'Wednesday\' THEN 3
                            WHEN \'Thursday\' THEN 4
                            WHEN \'Friday\' THEN 5
                            WHEN \'Saturday\' THEN 6
                            WHEN \'Sunday\' THEN 7
                        END ';
                break;
                case 'getEquiposByTicket':
                    $this->query = 'SELECT te.cTipo AS Equipo, e.nInterno, e.nPatrimonio, em.cModelo, ep.cModelo, es.cNombre, e.nBitsSo, e.nRam, tec.cNombre AS CreadoPor, t.cCodigo, m.cMotivo, sm.cSubMotivo, mt.cFallaEquipo, t.cObs, CONCAT(u.cNombre,\' \', u.cApellido) AS Usuario, d.cNomDependencia,
                        CONCAT(enc.cNombre,\' \', enc.cApellido) AS TecnicoEncargado,
                        CONCAT(fin.cNombre,\' \', fin.cApellido) AS FinalizadoPor,
                        t.dFechaAlta AS FechaCreado, t.dFechaToma, t.dFechaFinalizado
                        FROM Ticket AS t
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        INNER JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        INNER JOIN Equipo AS e ON mt.idEquipo = e.idEquipo AND e.idTipo IS NOT NULL
                        LEFT JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        LEFT JOIN Usuario AS u ON t.idUsuario = u.idUsuario
                        LEFT JOIN Usuario AS tec ON t.idAlta = tec.idUsuario
                        LEFT JOIN Usuario AS enc ON t.idTecEncargado = enc.idUsuario
                        LEFT JOIN Usuario AS fin ON t.idFinalizado = fin.idUsuario
                        LEFT JOIN EquipoPlacaMadre AS em ON e.idPlacaMadre = em.idPlacaMadre
                        LEFT JOIN EquipoProcesador AS ep ON e.idProcesador = ep.idProcesador
                        LEFT JOIN EquipoSistemaOperativo AS es ON e.idSo = es.idSistemaOperativo
                        LEFT JOIN EquipoDisco AS ed ON e.idDisco = ed.idDisco
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        ORDER BY te.cTipo';
                break;
                default:
                    $this->query = '';
                    $this->data = [];
                break;
            }

            $this->data = [
                ':Since' => (isset($_POST['desde']) && !empty($_POST['desde'])) ? $_POST['desde'] : '2019-01-01 00:00:00',
                ':Until' => (isset($_POST['hasta']) && !empty($_POST['hasta'])) ? $_POST['hasta'] : $this->fecha
            ];

            $this->filter = $this->getFilters();

            // return $this->query;

            // $result = $this->executeQuery();
            // $response = array();

            // if(!$result){
            //     return $response;
            // }

            return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getData(){
            $this->Chart = $_POST['chart'];

            $result = $this->getChartValues();

            if(!$result){
                return $this->ResultChart;
            }

            while ($row = $result->fetch()){
                // if($this->Chart == 'getAllTicketsOfWeek' || $this->Chart == 'getCreatedTicketsBy' || $this->Chart == 'getAsignementTicketBy'){
                switch ($this->Chart) {
                    case 'getAverageTimePerTicket':
                        $this->ResultChart[] = array('label' => $row['Nombre1'],
                            'y' => (int) $row['Cantidad1'], 
                            'name' => $row['Nombre1'],
                            'Porcentaje' => (float) (isset($row['Porcentaje'])) ? $row['Porcentaje'] : 0
                        );
                        $this->ResultChart[] = array('label' => $row['Nombre2'],
                            'y' => (int) $row['Cantidad2'], 
                            'name' => $row['Nombre2'],
                            'Porcentaje' => (float) (isset($row['Porcentaje'])) ? $row['Porcentaje'] : 0
                        );
                    break;
                    case 'getEquipmentEntryByTicket':
                        $this->ResultChart[] = array('label' => 'Tickets',
                            'y' => (int) $row['Cantidad1'], 
                            'name' => 'Tickets',
                            'Porcentaje' => $this->getPorcentajeRestante($row['Porcentaje'])
                        );
                        $this->ResultChart[] = array('label' => 'Equipos',
                            'y' => (int) $row['Cantidad2'], 
                            'name' => 'Equipos',
                            'Porcentaje' =>  (float) (isset($row['Porcentaje'])) ? $row['Porcentaje'] : 0
                        );
                    break;
                    default:
                        $this->ResultChart[] = array('label' => $row['Nombre'],
                            'y' => (int) $row['Cantidad'], 
                            'name' => $row['Nombre'],
                            'Porcentaje' => (float) (isset($row['Porcentaje'])) ? $row['Porcentaje'] : 0
                        );
                    break;
                }
                // if($this->Chart == 'getAverageTimePerTicket'){
                //     $this->ResultChart[] = array('label' => $row['Nombre1'],
                //         'y' => (int) $row['Cantidad1'], 
                //         'name' => $row['Nombre1'],
                //         'Porcentaje' => (float) (isset($row['Porcentaje'])) ? $row['Porcentaje'] : 0
                //     );
                //     $this->ResultChart[] = array('label' => $row['Nombre2'],
                //         'y' => (int) $row['Cantidad2'], 
                //         'name' => $row['Nombre2'],
                //         'Porcentaje' => (float) (isset($row['Porcentaje'])) ? $row['Porcentaje'] : 0
                //     );
                // }else{
                //     $this->ResultChart[] = array('label' => $row['Nombre'],
                //         'y' => (int) $row['Cantidad'], 
                //         'name' => $row['Nombre'],
                //         'Porcentaje' => (float) (isset($row['Porcentaje'])) ? $row['Porcentaje'] : 0
                //     );
                // }
            }

            return $this->ResultChart;
        }

        public function getFilters(){
            $filter = '';
            
            $sec = (isset($_POST['secretaria'])) ? $_POST['secretaria'] : '';
            $dep = (isset($_POST['dependencia'])) ? $_POST['dependencia'] : '';

            $enc = (isset($_POST['encargado'])) ? $_POST['encargado'] : '';
            $ate = (isset($_POST['atendido'])) ? $_POST['atendido'] : '';

            $mot = (isset($_POST['motivo'])) ? $_POST['motivo'] : '';
            $sub = (isset($_POST['submotivo'])) ? $_POST['submotivo'] : '';
            
			if(empty($sec) && empty($dep) && empty($enc) && empty($ate) && empty($mot) && empty($sub)){
				return $filter;
			}

			$filter .= 'AND ';

            $filter .= ($sec != 'ALL') ? ' s.idSecretaria = '.$sec.'  AND' : '';
            $filter .= ($dep != 'ALL') ? ' t.idDependencia = '.$dep.'  AND' : '';
            $filter .= ($enc != 'ALL') ? ' t.idTecEncargado = '.$enc.'  AND' : '';
            $filter .= ($ate != 'ALL') ? ' t.idAlta = '.$ate.'  AND' : '';
            $filter .= ($mot != 'ALL') ? ' m.idMotivo = '.$mot.'  AND' : '';
            $filter .= ($sub != 'ALL') ? ' sm.idSubMotivo = '.$sub.'  AND' : '';

            if($filter == 'AND '){
				return '';
			}

			if(substr($filter, -3) == 'AND'){
				$filter = substr($filter, 0, -3);
			}

			return $filter;
		}
        
        private function getChartValues(){
            $this->data = [
                ':Since' => (isset($_POST['desde']) && !empty($_POST['desde'])) ? $_POST['desde'] : '2019-01-01 00:00:00',
                ':Until' => (isset($_POST['hasta']) && !empty($_POST['hasta'])) ? $_POST['hasta'] : $this->fecha
            ];

            $this->filter = $this->getFilters();

            switch ($this->Chart) {
                case 'getAllTicketsOfWeek':
                    $this->query = 'SELECT COUNT(t.idTicket) FROM Ticket AS t 
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until) '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT CASE (Datename(WEEKDAY,t.dFechaAlta))
                            WHEN \'Monday\' THEN \'Lunes\'
                            WHEN \'Tuesday\' THEN \'Martes\'
                            WHEN \'Wednesday\' THEN \'Miercoles\'
                            WHEN \'Thursday\' THEN \'Jueves\'
                            WHEN \'Friday\' THEN \'Viernes\'
                            WHEN \'Saturday\' THEN \'Sabado\'
                            WHEN \'Sunday\' THEN \'Domingo\' END AS Nombre, 
                        COUNT(t.idTicket) AS Cantidad,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(*) * 100.0 / sum(count(*)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Ticket AS t 
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        GROUP BY Datename(WEEKDAY,t.dFechaAlta) 
                        ORDER BY CASE datename(weekday, t.dFechaAlta)
                            WHEN \'Monday\' THEN 1
                            WHEN \'Tuesday\' THEN 2
                            WHEN \'Wednesday\' THEN 3
                            WHEN \'Thursday\' THEN 4
                            WHEN \'Friday\' THEN 5
                            WHEN \'Saturday\' THEN 6
                            WHEN \'Sunday\' THEN 7
                         END';

                    // var_dump($this->query);
                break;
                case 'getCreatedTicketsBy':
                    $this->query = 'SELECT COUNT(c.idUsuario)
                        FROM Usuario AS c
                        INNER JOIN Ticket AS t ON c.idUsuario = t.idAlta
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT c.cNombre AS Nombre, COUNT(t.idTicket) AS Cantidad,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(*) * 100.0 / sum(count(*)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Usuario AS c
                        INNER JOIN Ticket AS t ON c.idUsuario = t.idAlta
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        GROUP BY c.cNombre ORDER BY COUNT(t.idTicket) DESC';
                break;
                case 'getAsignementTicketBy':
                    $this->query = 'SELECT COUNT(c.idUsuario)
                        FROM Usuario AS c
                        INNER JOIN Ticket AS t ON c.idUsuario = t.idAlta
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT c.cNombre AS Nombre, COUNT(t.idTicket) AS Cantidad,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(*) * 100.0 / sum(count(*)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Usuario AS c
                        INNER JOIN Ticket AS t ON c.idUsuario = t.idTecEncargado
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                         GROUP BY c.cNombre ORDER BY COUNT(t.idTicket) DESC';
                break;
                case 'getTicketByZone':
                    $this->query = 'SELECT COUNT(t.idTicket)
                        FROM Ticket AS t
                        INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        INNER JOIN Localidad AS l ON d.idLocalidad = l.idLocalidad
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT l.cLocalidad AS Nombre, COUNT(t.idTicket) AS Cantidad,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(*) * 100.0 / sum(count(*)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Ticket AS t
                        INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        INNER JOIN Localidad AS l ON d.idLocalidad = l.idLocalidad
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        GROUP BY l.cLocalidad ORDER BY COUNT(t.idTicket) DESC';
                break;
                case 'getTicketsByDependency':
                    $this->query = 'SELECT COUNT(t.idTicket)
                        FROM Ticket AS t
                        INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT TOP 10 d.cNomDependencia AS Nombre, COUNT(t.idTicket) AS Cantidad,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(*) * 100.0 / sum(count(*)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Ticket AS t
                        INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        GROUP BY d.cNomDependencia ORDER BY COUNT(t.idTicket) DESC';
                break;
                case 'getAverageTimePerTicket':
                    $this->query = 'SELECT COUNT(t.idTicket) FROM Ticket AS t
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'DECLARE @Name1 varchar(50),
                        @Name2 varchar(50)
                        SET @Name1 = \'Tiempo de respuesta\'
                        SET @Name2 = \'Tiempo de resolucion\'
                        SELECT	 
                        @Name1 AS Nombre1,(SUM(DATEDIFF(hh, t.dFechaAlta, t.dFechaToma)) / COUNT(t.idTicket)) AS Cantidad1,
                        @Name2 AS Nombre2,(SUM(DATEDIFF(hh, t.dFechaToma, t.dFechaFinalizado)) / COUNT(t.idTicket)) AS Cantidad2 FROM Ticket AS t
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'';
                break;
                case 'getEquipmentEntryByTicket':
                    $this->query = 'SELECT COUNT(t.idTicket) FROM Ticket AS t
                        INNER JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN Equipo AS e ON mt.idEquipo = e.idEquipo AND e.idTipo != 0
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT COUNT(t.idTicket) AS Cantidad1, COUNT(e.idEquipo) AS Cantidad2,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(e.idEquipo) * 100.0 / sum(count(*)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Ticket AS t
                        INNER JOIN MotivoxTicket AS mt ON t.idTicket = mt.idTicket
                        LEFT JOIN Equipo AS e ON mt.idEquipo = e.idEquipo AND e.idTipo IS NOT NULL
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'';

                        // var_dump($this->query, $this->filter);
                break;
                case 'getEquipmentEntry':
                    $this->query = 'SELECT COUNT(e.idEquipo) FROM Equipo AS e
                        INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
                        LEFT JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        INNER JOIN MotivoxTicket AS mt ON e.idEquipo = mt.idEquipo
                        LEFT JOIN Ticket AS t ON mt.idTicket = t.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT te.cTipo AS Nombre, COUNT(e.idEquipo) AS Cantidad,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(*) * 100.0 / sum(count(*)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Equipo AS e
                        INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
                        LEFT JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        INNER JOIN MotivoxTicket AS mt ON e.idEquipo = mt.idEquipo
                        LEFT JOIN Ticket AS t ON mt.idTicket = t.idTicket
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        GROUP BY te.cTipo';
                break;
                case 'getTecnicByEquipment':
                    $this->query = 'SELECT COUNT(t.idTicket) FROM Ticket AS t
                        INNER JOIN Usuario AS u ON t.idTecEncargado = u.idUsuario
                        INNER JOIN MotivoxTicket AS mt ON mt.idTicket = t.idTicket
                        LEFT JOIN Equipo AS e ON mt.idEquipo = e.idEquipo AND e.idTipo IS NOT NULL
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter;

                    if($this->searchRecords() == 0){
                        $this->query = '';
                        break;
                    }

                    $this->query = 'SELECT u.cNombre AS Nombre, COUNT(e.idEquipo) AS Cantidad,
                        CONVERT(DECIMAL(10,2), ROUND(CAST((count(e.idEquipo) * 100.0 / sum(count(e.idEquipo)) over ()) AS float), 2, 0)) AS Porcentaje
                        FROM Ticket AS t
                        INNER JOIN Usuario AS u ON t.idTecEncargado = u.idUsuario
                        INNER JOIN MotivoxTicket AS mt ON mt.idTicket = t.idTicket
                        LEFT JOIN Equipo AS e ON mt.idEquipo = e.idEquipo AND e.idTipo IS NOT NULL
                        LEFT JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
                        LEFT JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                        LEFT JOIN SubMotivo AS sm ON mt.idSubMotivo = sm.idSubMotivo
                        LEFT JOIN Motivo AS m ON sm.idMotivo = m.idMotivo
                        WHERE (t.dFechaAlta >= :Since AND t.dFechaAlta <= :Until)
                        '.$this->filter.'
                        GROUP BY u.cNombre';
                break;
                case '':
                    $this->query = 'DECLARE @Name1 varchar(50),
                        @Name2 varchar(50)
                        SET @Name1 = \'Tiempo de respuesta\'
                        SET @Name2 = \'Tiempo de resolucion\'
                        SELECT	 
                        @Name1 AS Nombre1,(SUM(DATEDIFF(hh, dFechaAlta, dFechaToma)) / COUNT(t.idTicket)) AS Cantidad1,
                        @Name2 AS Nombre2,(SUM(DATEDIFF(hh, dFechaToma, dFechaFinalizado)) / COUNT(t.idTicket)) AS Cantidad2 FROM Ticket AS t
                        INNER JOIN MotivoxTicket AS mt on t.idTicket = mt.idTicket
                        WHERE mt.idEquipo IS NOT NULL';
                break;
                default:
                    $this->query = '';
                    $this->data = [];
                break;
            }

            return ($this->query != '') ? $this->executeQuery() : false;
        }

        private function getPorcentajeRestante($porc){
            $porc = (float) $porc;

            $falta = 0;

            if($porc < 100){
                $falta = 100 - $porc;
            }

            return $falta;
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
                            $this->ResultChart[0] =  array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
                            break;
                        case 'Martes':
                            $this->ResultChart[1] = array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
                        break;
                        case 'Miercoles':
                            $this->ResultChart[2] = array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
                        break;
                        case 'Jueves':
                            $this->ResultChart[3] = array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
                        break;
                        case 'Viernes':
                            $this->ResultChart[4] = array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
                        break;
                        case 'Sabado':
                            $this->ResultChart[5] = array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
                        break;
                        case 'Domingo':
                            $this->ResultChart[6] = array('Name' => $row['Nombre'], 'Cantidad' => $row['Cantidad']);
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