<?php 
	if(!isset($_SESSION)){
		require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

    include 'userController.php';
    include 'equipoController.php';
    include 'mailController.php';

	class ticket extends globalController{
		public $allUserTicket = false;
		public $listTicket = array();

		private $ticket = array();
		private $idTecnico;
		private $participante;
		private $isUserAlta = false;
		private $responseUserAlta;
		private $Code;
		private $ValidCode;
		public $a;

		public function getTicketByCode($code){
			$code = $this->cleanString($code);
			$this->query = 'SELECT t.idTicket,t.idTecEncargado,t.idPrioridad,t.cCodigo,t.cObs,t.cObsTecnica,t.nEstado,t.dFechaToma,t.dFechaFinalizado,t.nCierreConfirmado,t.dFechaCierreConfirmado,t.idAlta,t.dFechaAlta,t.idModificado,t.dFechaModificado, t.nPausa, t.idPausa, t.dFechaPausa, u.cNombre,u.cApellido, u.nDni,u.cNLegajo,u.nTelefono,u.cEmail,ue.cNombre AS nomEncargado,ue.cApellido AS apEncargado, uc.cNombre AS Creador,d.cNomDependencia, d.cDireccion, sec.cNomSecretaria, mxt.cDesc, mxt.idEquipo, mxt.cFallaEquipo, mxt.nRetiroListo, mxt.idRetirado, mxt.dFechaRetirado, ur.cNombre AS nomRetirado,ur.cApellido AS apRetirado, ur.cNLegajo AS LegRetirado, l.cLocalidad FROM Ticket AS t
				INNER JOIN Usuario AS u ON t.idUsuario = u.idUsuario	
				LEFT JOIN Usuario AS ue ON t.idTecEncargado = ue.idUsuario
				LEFT JOIN Usuario AS uc ON t.idAlta = uc.idUsuario
				INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
				INNER JOIN Secretaria AS sec ON d.idSecretaria = sec.idSecretaria
				LEFT JOIN MotivoxTicket AS mxt ON t.idTicket = mxt.idTicket
				LEFT JOIN Usuario AS ur ON mxt.idRetirado = ur.idUsuario
				INNER JOIN Localidad AS l ON d.idLocalidad = l.idLocalidad
				WHERE t.cCodigo = :Code ORDER BY idTicket ASC';

			$this->data = [':Code' => $code];

			$result = $this->executeQuery();

			return ($result) ? $this->setTicket($result) : array('Status' => 'Error');
		}

		public function getTicket(){
			$this->checkTickets();

			$this->where = $this->setFilter();

			$this->query = 'SELECT t.idTicket,t.idTecEncargado,t.idPrioridad,t.cCodigo,t.cObs,t.cObsTecnica,t.nEstado,t.dFechaToma,t.dFechaFinalizado,t.nCierreConfirmado,t.dFechaCierreConfirmado,t.idAlta,t.dFechaAlta,t.idModificado,t.dFechaModificado, t.nPausa, t.idPausa, t.dFechaPausa, u.cNombre,u.cApellido, u.nDni,u.cNLegajo,u.nTelefono,u.cEmail,ue.cNombre AS nomEncargado,ue.cApellido AS apEncargado, uc.cNombre AS Creador,d.cNomDependencia, d.cDireccion, sec.cNomSecretaria, mxt.cDesc, mxt.idEquipo, mxt.cFallaEquipo, mxt.nRetiroListo, mxt.idRetirado, mxt.dFechaRetirado, ur.cNombre AS nomRetirado,ur.cApellido AS apRetirado, ur.cNLegajo AS LegRetirado, l.cLocalidad FROM Ticket AS t
				INNER JOIN Usuario AS u ON t.idUsuario = u.idUsuario	
				LEFT JOIN Usuario AS ue ON t.idTecEncargado = ue.idUsuario
				LEFT JOIN Usuario AS uc ON t.idAlta = uc.idUsuario
				INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
				INNER JOIN Secretaria AS sec ON d.idSecretaria = sec.idSecretaria
				LEFT JOIN MotivoxTicket AS mxt ON t.idTicket = mxt.idTicket
				LEFT JOIN Usuario AS ur ON mxt.idRetirado = ur.idUsuario
				INNER JOIN Localidad AS l ON d.idLocalidad = l.idLocalidad '.$this->where.'  ORDER BY idTicket ASC';	//Comando sql para obtener todos los tickets
			$this->data = [];	//Datos a pasar en el comando sql
			$result = $this->executeQuery();	//Obtengo el resultado

			return ($result) ? $this->setTicket($result) : array('Status' => 'Error');	//Si '$result' posee datos devuelve el array correspondiente con todos los datos del ticket, en caso contrario devuelve un array con un 'Status' de error
		}

		private function setFilter(){
			$this->where = 'WHERE ';	//Where de la consulta
			$haveFilter = false;	//Identificador si tiene filtros
			$filterUser = (isset($_POST['user']) && !empty($_POST['user'])) ? true : false;	//Si tiene filtros para el usuario

			if($filterUser){	//Si tiene filtros para el usuario añade al 'where' el comando SQL correspondiente
				$this->where .= ($_POST['user'] == 'SIN_ASIGNAR') ? '(t.idTecEncargado IS NULL ' : 't.idTecEncargado IS NOT NULL AND (t.idTecEncargado = '.intval($this->cleanString($_POST['user']), 10).' ';
				$haveFilter = true;	//Tiene filtros
			}
			if(isset($_POST['state']) && !empty($_POST['state'])){	//Identifica si tiene filtros por estado de ticket
				$filterList = explode(",", $_POST['state']);	//Separo los estados en lineas individuales. estan concatenados en un string con comas (1,2,5)
				if(count($filterList) > 0){	//Si es mayor a 0
					$haveFilter = true;	//tiene filtros
					foreach($filterList as $i=>$f){	//Recorro la lista de filtros por estado
						if($f != 0 && strlen($f) >= 1){	//Si el '$f' = filtro contiene datos
							if($i != 0){	//Si no es el comienzo de la lista "$i es igual a la key(posicion) de la lista de filtros"
								$this->where .= ' OR t.nEstado = '.$f.' ';
							}else{	//si es el comienzo de la lista
								$this->where .= ($filterUser) ? 'AND (t.nEstado = '.$f.' ' : '(t.nEstado = '.$f.' ';	//Identifico si tiene filtros para usuario y añade el al 'where' el comando SQL correspondiente
							}
						}
					}
					$this->where .= ')';	//Luego de recorrer la lista cierro el parentesis
				}
			}
			$this->where .= ($filterUser) ? ')' : '';	//si tiene filtros de usuario cierro el parentesis correspondiente, sino nada

			if(isset($_POST['date_since']) && !empty($_POST['date_since']) && $this->validDate($_POST['date_since'])){	//Identifico si tiene filtros de fecha y si son fechas validas
				$this->where .= ($haveFilter) ? ' AND t.dFechaAlta >= \''.$_POST['date_since'].' 00:00:00 \'' : 't.dFechaAlta >= \''.$_POST['date_since'].' 00:00:00\'';	//Añado al 'where' el comando sql correspondiente
				$haveFilter = true;
			}
			if(isset($_POST['date_until']) && !empty($_POST['date_until']) && $this->validDate($_POST['date_until'])){	//Lo mismo que arriba xd
				$this->where .= ($haveFilter) ? ' AND t.dFechaAlta <= \''.$_POST['date_until'].' 23:59:59 \'' : 't.dFechaAlta <= \''.$_POST['date_until'].' 23:59:59\'';
				$haveFilter = true;
			}

			if (!isset($_POST['state']) && !isset($_POST['user']) && (!isset($_POST['date_since']) || !isset($_POST['date_until'])) && !$haveFilter) {	//Identifico si al menos tiene 1 filtro activo. En caso de no poseer ninguno el 'where' queda vacio
				$this->where = '';
			}
			

			return $this->where;
		}

		private function setTicket($data){	//Creo un array a base de los registros del ticket
			while($row = $data->fetch()){
				$Equipo = new equipo();

				$detail = ($row['idEquipo'] !== NULL) ? $Equipo->getEquipo($row['idEquipo']) : array(array('Patrimony' => null, 'Intern' => null, 'TypeName' => null, 'Brand' => null, 'Model' => null));

				$this->listTicket[] = array(
					'Status' => 'Success',
					'IdTicket' => $row['idTicket'],
					'Codigo' => $row['cCodigo'],
					'Secretaria' => $row['cNomSecretaria'],
					'Dependencia' => $row['cNomDependencia'],
					'Direccion' => $row['cDireccion'],
					'Usuario' => $row['cNombre'].' '.$row['cApellido'].'-'.$row['cNLegajo'],
					'UserName' => $row['cNombre'].' '.$row['cApellido'],
					'Legajo' => $row['cNLegajo'],
					'Telefono' => $row['nTelefono'],
					'Email' => $row['cEmail'],
					'Motivo' => $this->getTicketReason($row['idTicket']),
					'Archivos' => $this->getArchives($row['idTicket']),
					'Otro' => $row['cDesc'],
					'Localidad' => $row['cLocalidad'],
					'Patrimony' => $detail[0]['Patrimony'],
					'NroEquipo' => $detail[0]['Intern'],
					'Equipo' => $detail[0]['TypeName'],
					'Marca' => $detail[0]['Brand'],
					'Modelo' => $detail[0]['Model'],
					'TecnicFailure' => $row['cFallaEquipo'],
					'ListoParaRetiro' => $row['nRetiroListo'],
					'RetiradoPor' => ($row['idRetirado'] != NULL) ? $row['nomRetirado'].' '.$row['apRetirado'].'-'.$row['LegRetirado'] : NULL,
					'UserRetiro' => ($row['idRetirado'] != NULL) ? ['Name' => $row['nomRetirado'], 'Surname' => $row['apRetirado'], 'Legajo' => $row['LegRetirado']] : NULL,
					'FechaRetiro' => $row['dFechaRetirado'],
					'SimpleDateRetiro' => ($row['idRetirado'] != NULL) ? date_format(date_create(trim($row['dFechaRetirado'])), 'd/m/Y') : NULL,
					'IdEncargado' => $row['idTecEncargado'],
					'Encargado' => $row['nomEncargado'].' '.$row['apEncargado'],
					'Participantes' => $this->getTicketParticipantes($row['idTicket']),
					'Prioridad' => $row['idPrioridad'],
					'Comentario_Interno' => $row['cObs'],
					'Comentario_Tecnico' => $row['cObsTecnica'],
					'Estado' => $row['nEstado'],
					'Fecha_Toma' => $row['dFechaToma'],
					'Fecha_Finalizado' => $row['dFechaFinalizado'],
					'CierreConfirmado' => $row['nCierreConfirmado'],
					'FechaConfirmado' => $row['dFechaCierreConfirmado'],
					'IdAlta' => $row['idAlta'],
					'Creador' => $row['Creador'],
					'Fecha_Alta' => date_format(date_create(trim($row['dFechaAlta'])), 'd/m/Y H:i:s'),
					'SimpleDate' => date_format(date_create(trim($row['dFechaAlta'])), 'd/m/Y'),
					'IdModificado' => $row['idModificado'],
					'Fecha_Modificado' => $row['dFechaModificado'],
					'Paused' => $row['nPausa'],
					'IdPaused' => $row['idPausa'], 
					'ASD' => $detail,
					// 'WHERE' => $this->validDate($_POST['date_since']),
					// 'WHERE2' => $this->where
				);
			}
			return $this->listTicket;
		}

		private function checkTickets(){
			// $this->query = 'SELECT t.idTicket, DATEDIFF(day,t.dFechaAlta, :ActualDate) AS days, mxt.idSubMotivo FROM Ticket AS t
			// 	LEFT JOIN MotivoxTicket AS mxt ON t.idTicket = mxt.idTicket
			// 	WHERE idFinalizado IS NULL ORDER BY t.idTicket DESC';
			$this->query = 'SELECT t.idTicket, datediff(day,t.dFechaAlta, :ActualDate)
				+ CASE WHEN datepart(dw, t.dFechaAlta) = 7 then 1 else 0 end 
				- (datediff(wk, t.dFechaAlta, :ActualDate2) * 2) 
				- CASE WHEN datepart(dw, t.dFechaAlta) = 6 then 1 else 0 end 
				+ CASE WHEN datepart(dw, :ActualDate3) = 6 then 1 else 0 end AS days,
				mxt.idSubMotivo FROM Ticket AS t
				LEFT JOIN MotivoxTicket AS mxt ON t.idTicket = mxt.idTicket
				WHERE idFinalizado IS NULL ORDER BY t.idTicket DESC';
			$this->data = [':ActualDate' => $this->fecha, ':ActualDate2' => $this->fecha, ':ActualDate3' => $this->fecha,];

			$result = $this->executeQuery();
			$a = array();
			while ($row = $result->fetch()) {
				$tiempoEstimado = $this->getEstimatedTime($row['idSubMotivo']);
				if($row['days'] > $tiempoEstimado){
					$this->query = 'UPDATE Ticket SET idPrioridad = 1 WHERE idTicket = :Id';
					$this->data = [':Id' => $row['idTicket']];

					$this->executeQuery();
				}
			}
		}

		private function getArchives($ticket){
			$this->query = 'SELECT cArchivo, cUrl FROM Archivo WHERE idTicket = :Ticket';
			$this->data = [':Ticket' => $ticket];

			$result = $this->executeQuery();	//Ejecuto y obtengo el resultado de la consulta
			$archivos = '';	//Creo una bariable temporal de archivo

			while($row = $result->fetch()){	//Recorro el resultado
				$archivos .= '<a href=\'http://192.168.122.180/portal-escobar/Programas/tickets/assets/'.$row['cUrl'].'\' target=\'_blank\'>'.$row['cArchivo'].'</a><br>';	//Añadp a la variable un string con los datos del archivos
			}
			
			return $archivos;
		}

		private function getTicketParticipantes($id){	//Funcion para obtener todos los participantes de un ticket
			$this->query = 'SELECT t.idUsuario,t.cNombre,t.cApellido FROM TecnicoxTicket AS txt
				INNER JOIN Usuario AS t ON txt.idTecnico = t.idUsuario
				WHERE txt.idTicket = :Id';	//Codigo SQL para obtener los participantes de un ticket
			$this->data = [':Id' => $id];	//Datos a pasar al comando SQL

			$result = $this->executeQuery();	//Ejecuto y obtengo el resultado de la consulta
			$participante = array();	//Creo una lista temporal de participantes
			if($result){	//Si la consulta se ejecuto correcatemnte
				while($row = $result->fetch()){	//Recorro el resultado
					$participante[] = array('Id' => $row['idUsuario'], 'Name' => $row['cNombre'], 'LastName' => $row['cApellido']);	//Añadp a la lista un array con los datos del participante
				}
			}
			return $participante;	//Devuelvo la lista de participantes
		}
		private function getTicketReason($id){	//Obtengo todas las razones que puede llegar a poseer un ticket
			$this->query = 'SELECT m.cMotivo,s.cSubMotivo FROM MotivoxTicket AS mt
				INNER JOIN SubMotivo AS s ON mt.idSubMotivo = s.idSubMotivo
				INNER JOIN Motivo AS m ON s.idMotivo = m.idMotivo
				WHERE mt.idTicket = :Id';	//Comando SQL para obtener todas las razones de un ticket
			$this->data = [':Id' => $id];	//Datos a pasar al comando SQL

			$result = $this->executeQuery(); //Ejecuto y obtengo el resultado de la consulta
			$motivos = '';	//Creo un string temporal para almacenar el motivo del ticket
			if($result){
				while($row = $result->fetch()){
					$motivos .= $row['cMotivo'].'/'.$row['cSubMotivo'];	//Añado al string el motivo del ticket
				}
			}
			return $motivos;	//Devuelvo los motivos
		}
		private function validReason($Id){	//Funcion para validar si el motivo de un ticket es valido( si existe en la DB)
			$this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE idSubMotivo = :Id';	//Comando SQL 
			$this->data = [':Id' => $Id];

			return ($this->searchRecords() > 0) ? true : false; //Obtengo la cantidad de registros y si el motivo es valido(encuentra coincidencias) devuelve true, 
		}

		private function GenerateCode(){	//Funcion para generar un codigo correspondiente a un ticket
			$this->Code = 0;
			$this->ValidCode = false;

			while(!$this->ValidCode){	//mientras que no sea un codigo valido
				$Id = $this->getLastTicketInserted();	//Obtiene el id del ultimo ticket insertado
				$H = '';	//Variable para completar el codigo correspondiente

				while (strlen($H.$Id) < 5){
					$H .= '0';
				}

				$format = (($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306') && isset($_POST['fecha_alta'])) ? date_format(date_create($_POST['fecha_alta']), 'ymd') : '';

				$this->Code = ($format != '') ? $format.$H.$Id : date("ymd").$H.$Id;	//El codigo es igual a la fecha actual + 'H' + el Id del ticket. Ej: 19122700004

				$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE cCodigo = :Codigo'; //Compruebo si el ticket ya existe.
				$this->data = [':Codigo' => (int) $this->Code];

				$this->ValidCode = ($this->searchRecords() == 0) ? true : false;
			}

			return ($this->Code != 0) ? true : false;	//Si el codigo es distinto de '0' devuelve 'true'
		}

		public function insertTicket(){	//Funcion para Insertar un ticket en la DB

			$isTecnico = $this->isTecnico($_SESSION['ID_USER']);	//Compruebo si el usuario logeuado que esta dando de alta es un tecnico

			$User = new usuario();	//Creo el objeto usuario

			$this->ticket['Client'] = (isset($_POST['legajo']) && $isTecnico) ? trim($User->getUserId($_POST['legajo'])) : $_SESSION['ID_USER'];	//Si recibe 'legajo' enviado por 'POST' y el usuario que esta creando el ticket es tecnico, obtengo el Id del responsable a travez del legajo, sino el responsable es el usuario logueado 
			// $this->ticket['Client'] = (isset($_POST['legajo']) && $isTecnico) ? $this->getClientId($_POST['legajo']) : $_SESSION['ID_USER'];
			$this->ticket['Dependence'] = ($isTecnico) ? $this->getUserDependence($this->ticket['Client']) : $_SESSION['DEPENDENCIA'];	//Lo mismo que lo de arriba pero con la dependencia xd
			$this->ticket['Otro'] = (isset($_POST['otro'])) ? $this->cleanString($_POST['otro']) : NULL;
			$this->ticket['Obs'] = (isset($_POST['obs']) && $isTecnico) ? $this->cleanString($_POST['obs']) : NULL;
			$this->ticket['Reason'] = $this->cleanString($_POST['motivo']);
			$this->ticket['Priority'] = (isset($_POST['prioridad']) && !empty($_POST['prioridad'])) ? $this->cleanString($_POST['prioridad']) : 2;

			if(!$this->validReason($this->ticket['Reason'])){	//Compruebo si es un motivo valido,
				return array('Status' => 'Invalid Reason');	//si no es un motivo valido devuelvo un array con el error
			}

//-------------------------------------------------- OBSOLETO POR EL MOMENTO ¡¡¡NO DESCOMENTAR!!!! -----------------------------------------------------
			// verifico si el motivo del ticket es igual al id de alta de usaurio.
			// $this->isUserAlta = ($this->ticket['Reason'] == 11) ? true : false; // Remplazar '11' de ser necesario por el id correspondiente al alta de usuario
			// if($this->isUserAlta){
			// 	$User = new usuario();
			// 	$this->responseUserAlta = ($User->validateFields('insert')) ? $User->InsertInactiveUser() : array('Status' => 'Invalid Call');

			// 	if($this->responseUserAlta['Status'] != 'Success'){
			// 		return $this->responseUserAlta
			// 	}
			// }
//-------------------------------------------------- OBSOLETO POR EL MOMENTO ¡¡¡NO DESCOMENTAR!!!! -----------------------------------------------------

			if($this->ticket['Client'] == 0 || $this->ticket['Dependence'] == 0){	//Si el responsable y la dependencia es igual a 0 devuelve un array con el error
				return array('Status' => 'Invalid User');
			}

			if(!$this->GenerateCode()){	//Si no se pudo generar el codigo devuelve un array con el error
				return array('Status' => 'Invalid Call');
			}

			$this->query = 'INSERT INTO Ticket (cCodigo,idDependencia,idUsuario,idPrioridad,cObs,nEstado,idAlta,dFechaAlta) VALUES (:Codigo,:Dependencia, :User, :Prioridad, :Obs, :Estado, :IdAlta, :FechaAlta)';	//Comando SQL para generar el ticket
			$this->data = [
				':Codigo' => (int) $this->Code,
				':Dependencia' => intval($this->ticket['Dependence']),
				':User' => intval($this->ticket['Client']),
				':Prioridad' => $this->ticket['Priority'],
				':Obs' => (empty($this->ticket['Obs'])) ? NULL : $this->ticket['Obs'],
				':Estado' => 1,
				':IdAlta' => intval($_SESSION['ID_USER']),
				':FechaAlta' => (($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306') && isset($_POST['fecha_alta'])) ? $_POST['fecha_alta'].' 10:00:00' : $this->fecha,
			];	//Datos a pasar al comando sQL

			if(!$this->executeQuery()){	//Si la sentencia no se ejecuto correctamente devuelve un array con el error
				return array('Status' => 'Error');
			}

			$state = '';
			if($User->setUserById($this->ticket['Client'])){
				$Mail = new mail();
				$Mail->Address = $User->Email;
				$Mail->Name = $User->Name.' '.$User->Surname;
				$Mail->Subject = 'Estado de Ticket';
				$Mail->Body = 'Su numero de ticket es: <strong>'.$this->Code.'</strong> Puede consultar el estado actual de su ticket haciendo click \'Aqui\' ';

				//$state = $Mail->send();
			}

			$this->ticket['Id'] = $this->getLastTicketInserted();	//Obtengo 'id' del ultimo ticket insertado
			if($this->ticket['Id'] == 0){	//si el id del ticket es igual a '0' devuelve un array con el error
				return array('Status' => 'Error');	
			}

			$IdEquipo = NULL;
			if(isset($_POST['ingreso']) && $_POST['ingreso'] == 1){	// Remplazar '24' de ser necesario por el id correspondiente al 'ingreso de pc'
				$Equipo = new equipo();
				$IdEquipo = $Equipo->getEquipoIdByIntern($this->cleanString($_POST['interno_ingreso']), $this->ticket['Dependence']);
			}
			//Añado los motivos del ticket
			$this->query = 'INSERT INTO MotivoxTicket (idSubMotivo,cDesc,idTicket,idEquipo,cFallaEquipo) VALUES (:Motivo,:Otro,:Ticket,:Equipo,:Falla)';
			$this->data = [
				':Motivo' => $this->ticket['Reason'],
				':Otro' => $this->ticket['Otro'],
				':Ticket' => $this->ticket['Id'],
				':Equipo' => $IdEquipo,
				':Falla' => (isset($_POST['falla_ingreso']) && !empty($_POST['falla_ingreso'])) ? $this->cleanString($_POST['falla_ingreso']) : NULL
			];
																							//, 'Alta Usuario' => $this->responseUserAlta
			return ($this->executeQuery()) ? array('Status' => 'Success','Ticket' => $this->Code,'Id' => $this->getLastInsertedId()) : array('Status' => 'Error');	//
		}
		private function getLastTicketInserted(){	//Funcion para obtener el id del ultimo ticket insertado
			$this->query = 'SELECT TOP 1 idTicket FROM Ticket ORDER BY idTicket DESC';
			$this->data = [];

			return $this->executeQuery()->fetchColumn(0);	//Devuelvo el id del ticket
		}

		public function setTecnico(){	//funcion para asocias a un tecnico responsable a un ticket
			$this->idTecnico = $this->cleanString($_POST['tec']);
			$this->ticket = $this->cleanString($_POST['ticket']);	

			if(!$this->existingTicket($this->ticket) || !$this->isTecnico($this->idTecnico)){	//Si el ticket no existe o el tecnico no existe devuelve un array con el error correspondiente
				return array('Status' => 'Unknown Fields');
			}

			$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE idTicket = :Id AND idTecEncargado IS NULL';	//Comando SQL para saber si el ticket ya tiene asociado un ticnico encargado
			$this->data = [':Id' => $this->ticket];

			if($this->searchRecords() > 0){	//si no tiene asociado un tecnico encargado updatea el ticket con el tecnico correspondiente y le añade la fecha de toma del ticket
				if(($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306')){
					$this->query = 'UPDATE Ticket SET nEstado = :State, idTecEncargado = :Id, idTomaTicket = :IdToma, dFechaToma = dFechaAlta WHERE idTicket = :Ticket';
					$this->data = [':State' => '2', ':Id' => $this->idTecnico, ':IdToma' => $_SESSION['ID_USER'], ':Ticket' => $this->ticket];
				}else{
					$this->query = 'UPDATE Ticket SET nEstado = :State, idTecEncargado = :Id, idTomaTicket = :IdToma, dFechaToma = :Fecha WHERE idTicket = :Ticket';
					$this->data = [':State' => '2', ':Id' => $this->idTecnico, ':IdToma' => $_SESSION['ID_USER'], ':Fecha' => $this->fecha, ':Ticket' => $this->ticket];
				}
			}else{	//Si ya tenia asociado un tecnico encargado posteriormente solo updatea al tecnico encargado
				$this->query = 'UPDATE Ticket SET nEstado = :State, idTecEncargado = :Id WHERE idTicket = :Ticket';
				$this->data = [':State' => '2', ':Id' => $this->idTecnico, ':Ticket' => $this->ticket];
			}

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');	//si el comando SQL se ejecuto correctamente devuelve un array 
		}
		public function getAllUserTicket(){	//Funcion para obtener todos los tickets de un usuario
			if($this->allUserTicket){

				if($_SESSION['LEGAJO'] == 'L12306' || $_SESSION['LEGAJO'] == 'L13848'){
					$this->query = 'SELECT t.idTicket,t.cCodigo,t.nEstado,t.cObsTecnica, t.idTecEncargado, t.dFechaAlta, t.dFechaFinalizado,t.nCierreConfirmado,t.dFechaCierreConfirmado, u.cNombre, u.cApellido, ue.cNombre AS nomEncargado, ue.cApellido AS apEncargado, d.cNomDependencia, sec.cNomSecretaria FROM Ticket AS t
					INNER JOIN Usuario AS u ON t.idUsuario = u.idUsuario
					LEFT JOIN Usuario AS ue ON t.idTecEncargado = ue.idUsuario
					INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
					INNER JOIN Secretaria AS sec ON d.idSecretaria = sec.idSecretaria ORDER BY idTicket ASC';

					$this->data = [];
				}else{
					$this->query = 'SELECT t.idTicket,t.cCodigo,t.nEstado,t.cObsTecnica, t.idTecEncargado, t.dFechaAlta, t.dFechaFinalizado,t.nCierreConfirmado,t.dFechaCierreConfirmado, u.cNombre, u.cApellido, ue.cNombre AS nomEncargado, ue.cApellido AS apEncargado, d.cNomDependencia, sec.cNomSecretaria FROM Ticket AS t
						INNER JOIN Usuario AS u ON t.idUsuario = u.idUsuario
						LEFT JOIN Usuario AS ue ON t.idTecEncargado = ue.idUsuario
						INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
						INNER JOIN Secretaria AS sec ON d.idSecretaria = sec.idSecretaria
						WHERE u.IdUsuario = :User AND t.idDependencia = :Dependence ORDER BY idTicket ASC';
					$this->data = [':User' => $_SESSION['ID_USER'],':Dependence' => $_SESSION['DEPENDENCIA']];
				}

				$result = $this->executeQuery();
				while($row = $result->fetch()){
					$this->listTicket[] = array(
						'Status' => 'Success',
						'IdTicket' => $row['idTicket'],
						'Codigo' => $row['cCodigo'],
						'Secretaria' => $row['cNomSecretaria'],
						'Dependencia' => $row['cNomDependencia'],
						'Usuario' => $row['cNombre'].' '.$row['cApellido'],
						'Motivo' => $this->getTicketReason($row['idTicket']),
						'Comentario_Tecnico' => $row['cObsTecnica'],
						'Encargado' => $row['nomEncargado'].' '.$row['apEncargado'],
						'Estado' => $row['nEstado'],
						'FechaAlta' => date_format(date_create(trim($row['dFechaAlta'])), 'd/m/Y H:i:s'),
						'FechaFinalizado' => ($row['dFechaFinalizado'] != null) ? date_format(date_create(trim($row['dFechaFinalizado'])), 'd/m/Y H:i:s') : null,
						'CierreConfirmado' => $row['nCierreConfirmado'],
						'FechaConfirmado' => $row['dFechaCierreConfirmado'],
					);
				}
				
				return $this->listTicket;
			}
			return array();
		}
		public function getStateTicket(){	//Obtiene los datos de un ticket segun el codigo del mismo
			$this->ticket['Id'] = $this->cleanString($_POST['ticket']);

			if(!$this->existingCode($this->ticket['Id'])){
				return array('Status' => 'Unkown Ticket');
			}

			$this->query = 'SELECT t.idTicket,T.cCodigo,t.nEstado, t.cObsTecnica, t.idTecEncargado, t.dFechaAlta, t.dFechaFinalizado,t.nCierreConfirmado,t.dFechaCierreConfirmado, u.cNombre, u.cApellido, ue.cNombre AS nomEncargado, ue.cApellido AS apEncargado, d.cNomDependencia, sec.cNomSecretaria, mxt.cFallaEquipo, mxt.nRetiroListo, mxt.idRetirado, mxt.dFechaRetirado, ur.cNombre AS nomRetirado,ur.cApellido AS apRetirado, ur.cNLegajo AS LegRetirado FROM Ticket AS t
				INNER JOIN Usuario AS u ON t.idUsuario = u.idUsuario
				LEFT JOIN Usuario AS ue ON t.idTecEncargado = ue.idUsuario
				INNER JOIN Dependencia AS d ON t.idDependencia = d.idDependencia
				INNER JOIN Secretaria AS sec ON d.idSecretaria = sec.idSecretaria
				LEFT JOIN MotivoxTicket AS mxt ON t.idTicket = mxt.idTicket
				LEFT JOIN Usuario AS ur ON mxt.idRetirado = ur.idUsuario
				WHERE t.cCodigo = :Id ORDER BY idTicket ASC';
			$this->data = [':Id' => $this->ticket['Id']];

			$result = $this->executeQuery();

			return ($result) ? $this->ticketState($result) : array('Status' => 'Error');
		}
		public function addParticipant(){	//Funcion para añadir un participante(tecnico) a un ticket
			$response = false;
			$this->ticket = $this->cleanString($_POST['ticket']);
			if(!$this->existingTicket($this->ticket)){	//si el ticket no existe
				return array('Status' => 'Unknown Ticket');
			}
			$participantes = explode(",", $_POST['participante']);	//Separo los participantes en lineas individuales.
			foreach($participantes as $key => $id){
				if($id != ""){
					if($this->isTecnico($id)){
						$this->query = 'SELECT COUNT(idTecnicoxTicket) FROM TecnicoxTicket WHERE idTicket = :Ticket AND idTecnico = :Tecnico';
						$this->data = [':Ticket' => $this->ticket, ':Tecnico' => $id];

						if($this->searchRecords() == 0){
							$this->query = 'INSERT INTO TecnicoxTicket (idTicket,idTecnico) VALUES (:Ticket, :Tecnico)';
							$response = ($this->executeQuery()) ? true : false;
						}
					}
				}
			}
			return ($response) ? array('Status' => 'Success') : array('Status' => 'Error');
		}
		public function deleteParticipante(){	//Funcion para eliminar a un participante(tecnico) de un ticket
			$response = false;
			$this->ticket = $this->cleanString($_POST['ticket']);
			if(!$this->existingTicket($this->ticket)){
				return array('Status' => 'Unknown Ticket');
			}
			$this->participante = $this->cleanString($_POST['participante']);
			if($this->isTecnico($this->participante)){
				$this->query = 'SELECT COUNT(idTecnicoxTicket) FROM TecnicoxTicket WHERE idTicket = :Ticket AND idTecnico = :Tecnico';
				$this->data = [':Ticket' => $this->ticket, ':Tecnico' => $this->participante];

				if($this->searchRecords() != 0){
					$this->query = 'DELETE FROM TecnicoxTicket WHERE idTicket = :Ticket AND idTecnico = :Tecnico';
					$response = ($this->executeQuery()) ? true : false;
				}
			}
			return ($response) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		public function ConfirmClosure(){	//Funcion para confirmar el cierre de un ticket
			$this->ticket = $_POST['code'];

			if(!$this->existingCode($this->ticket)){
				return array('Status' => 'Unknown Ticket');
			}
			if($this->isClosed($this->ticket) || !$this->isMyTicket($this->ticket)){
				return array('Status' => 'Error1', 'ASD' => $this->isClosed($this->ticket));
			}

			$this->query = 'UPDATE Ticket SET nCierreConfirmado = 1, dFechaCierreConfirmado = :Fecha WHERE cCodigo = :Codigo';
			$this->data = [':Fecha' => $this->fecha, ':Codigo' => $this->ticket];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		public function changeState(){
			$this->ticket['Id'] = $this->cleanString($_POST['ticket']);

			if(!$this->existingTicket($this->ticket['Id'])){
				return array('Status' => 'Unknown Ticket');
			}
			$this->ticket['NewState'] = $this->cleanString($_POST['state']);
			$this->ticket['ActualState'] = $this->getActualState($this->ticket['Id']);

			if(($this->ticket['ActualState'] != 0 && $this->ticket['NewState'] < $this->ticket['ActualState']) || ($this->ticket['NewState'] > 3 || $this->ticket['NewState'] <= 0)){
				return array('Status' => 'Invalid State');
			}

			if($this->ticket['NewState'] == 3){
				$this->query = 'UPDATE Ticket SET nEstado = :State, idFinalizado = :IdFinalizado, dFechaFinalizado = :Fecha WHERE idTicket = :Id';
				$this->data = [
					':State' => $this->ticket['NewState'],
					'IdFinalizado' => $_SESSION['ID_USER'],
					':Fecha' => (($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306') && isset($_POST['fecha_fin'])) ? $_POST['fecha_fin'].' 10:00:00' : $this->fecha,
					':Id' => $this->ticket['Id']
				];
			}else{ // el nuevo estado si no es finalizado es 2 'en curso'
				$this->query = 'UPDATE Ticket SET nEstado = :State WHERE idTicket = :Id';
				$this->data = [':State' => $this->ticket['NewState'],':Id' => $this->ticket['Id']];
			}

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		public function pauseTicket(){
			$this->ticket['Id'] = $this->cleanString($_POST['ticket']);

			if(!$this->existingTicket($this->ticket['Id'])){
				return array('Status' => 'Unknown Ticket');
			}

			$this->ticket['State'] = intval($this->cleanString($_POST['state']), 10);

			if($this->ticket['State'] != 0 && $this->ticket['State'] > 3){
				return array('Status' => 'Invalid State','asd' => $this->ticket['State']);
			}

			if($this->isPaused($this->ticket['Id'])){
				$this->query = 'UPDATE Ticket SET nPausa = :State, dFechaReactivado = :Fecha WHERE idTicket = :Id';
				$this->data = [':State' => NULL, ':Fecha' => $this->fecha, ':Id' => $this->ticket['Id']];
			}else{
				$this->query = 'UPDATE Ticket SET nPausa = :State, idPausa = :IdPausa, dFechaPausa = :Fecha WHERE idTicket = :Id';
				$this->data = [':State' => 1, ':IdPausa' => $_SESSION['ID_USER'], ':Fecha' => $this->fecha, ':Id' => $this->ticket['Id']];
			}

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		public function addInternalComment(){
			$User = new usuario();	//Creo el objeto usuario

			if(isset($_POST['fecha_fin'])){
				if(!empty($_POST['fecha_fin'])){
					$this->query = 'UPDATE Ticket SET idFinalizado = :IdFinalizado, dFechaFinalizado = :Fecha WHERE idTicket = :Id';
					$this->data = [':IdFinalizado' => $_SESSION['ID_USER'], ':Fecha' => $_POST['fecha_fin'], ':Id' => intval($this->cleanString($_POST['ticket']), 10)];

					$this->executeQuery();
				}
			}

			$this->ticket['Id'] = intval($this->cleanString($_POST['ticket']), 10);
			$this->ticket['Obs'] = $this->cleanString($_POST['detail_comentario']);
			$this->ticket['TecnicObs'] = $this->cleanString($_POST['detail_comentario_tecnico']);
			$this->ticket['Priority'] = $this->cleanString($_POST['detail_prioridad']);
			$this->ticket['Retirado'] = (isset($_POST['detail_retirado']) && !empty($_POST['detail_retirado'])) ? trim($User->getUserId($this->cleanString($_POST['detail_retirado']))) : 0;

			if(!$this->existingTicket($this->ticket['Id'])){
				return array('Status' => 'Unknown Ticket');
			}

			if($this->ticket['Retirado'] != 0 && !$this->isAlreadyRetired($this->ticket['Id'])){
				$this->query = 'UPDATE MotivoxTicket SET nRetiroListo = 1, dFechaRetiroListo = :FechaListo, idRetirado = :Retiro, dFechaRetirado = :Fecha WHERE idTicket = :Id';
				$this->data = [':FechaListo' => $this->fecha, ':Retiro' => $this->ticket['Retirado'], ':Fecha' => $this->fecha, ':Id' => $this->ticket['Id']];

				$this->executeQuery();
			}

			if(isset($_POST['detail_btn_retiro_listo']) && intval($_POST['detail_btn_retiro_listo'], 10) == 1){
				$this->query = 'UPDATE MotivoxTicket SET nRetiroListo = :Retiro, dFechaRetiroListo = :Fecha2 WHERE idTicket = :Id';
				$this->data = [':Retiro' => 1, ':Fecha2' => $this->fecha, ':Id' => $this->ticket['Id']];

				$this->executeQuery();
			}

			$this->query = 'UPDATE Ticket SET cObs = :Obs, cObsTecnica = :TecnicObs, idPrioridad = :Priority WHERE idTicket = :Id';
			$this->data = [':Obs' => $this->ticket['Obs'], ':TecnicObs' => $this->ticket['TecnicObs'], ':Priority' => $this->ticket['Priority'], ':Id' => $this->ticket['Id']];

			return ($this->executeQuery()) ? array('Status' => 'Success','ASD' => $_POST) : array('Status' => 'Error');
		}
		// public function addReason(){
		// 	$this->ticket['Id'] = $this->cleanString($_POST['ticket']);

		// 	if(!$this->existingTicket($this->ticket['Id'])){
		// 		return array('Status' => 'Unknown Ticket');
		// 	}

		// 	$this->ticket['reason'] = $this->cleanString($_POST['motivo']);
		// 	if($this->existingReason($this->ticket['reason'])){
				
		// 	}
		// }
		private function isPaused($TicketId){
			$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE nPausa IS NULL AND idTicket = :Id';
			$this->data = [':Id' => $TicketId];

			return ($this->searchRecords() == 0) ? true : false;
		}
		private function ticketState($data){
			$this->listTicket = array('Status' => 'Unkown Ticket');
			while($row = $data->fetch()){
				$this->listTicket = array(
					'Status' => 'Success',
					'IdTicket' => $row['idTicket'],
					'Codigo' => $row['cCodigo'],
					'Secretaria' => $row['cNomSecretaria'],
					'Dependencia' => $row['cNomDependencia'],
					'Usuario' => $row['cNombre'].' '.$row['cApellido'],
					'Motivo' => $this->getTicketReason($row['idTicket']),
					'Comentario_Tecnico' => $row['cObsTecnica'],
					'TecnicFailure' => $row['cFallaEquipo'],
					'ListoParaRetiro' => $row['nRetiroListo'],
					'RetiradoPor' => ($row['idRetirado'] != NULL) ? $row['nomRetirado'].' '.$row['apRetirado'].'-'.$row['LegRetirado'] : NULL,
					'FechaRetiro' => $row['dFechaRetirado'],
					'Encargado' => $row['nomEncargado'].' '.$row['apEncargado'],
					'Estado' => $row['nEstado'],
					'FechaAlta' => date_format(date_create(trim($row['dFechaAlta'])), 'd/m/Y H:i:s'),
					'FechaFinalizado' => ($row['dFechaFinalizado'] != null) ? date_format(date_create(trim($row['dFechaFinalizado'])), 'd/m/Y H:i:s') : null,
					// 'CierreConfirmado' => $row['nCierreConfirmado'],
					// 'FechaConfirmado' => $row['dFechaCierreConfirmado'],
				);
			}
			return $this->listTicket;
		}
		private function isMyTicket($ticket){

			if($_SESSION['LEGAJO'] == 'L12306' || $_SESSION['LEGAJO'] == 'L13848'){
				return true;
			}

			$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE cCodigo = :Codigo AND idUsuario = :User';
			$this->data = [':Codigo' => intval($ticket), ':User' => $_SESSION['ID_USER']];

			// if($this->searchRecords() != 0){
			// 	return true;
			// }

			// $this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE idTicket = :Ticket AND idUsuario = :User';
			// $this->data = [':Ticket' => intval($ticket), ':User' => $_SESSION['ID_USER']];

			return ($this->searchRecords() != 0) ? true : false;
		}
		private function isAlreadyRetired($ticket){
			$this->query = 'SELECT COUNT(idMotivoxTicket) FROM MotivoxTicket WHERE idRetirado IS NULL AND idTicket = :Id';
			$this->data = [':Id' => $ticket];

			return ($this->searchRecords() == 0) ? true : false;
		}
		private function isClosed($search){
			$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE cCodigo = :Codigo AND dFechaFinalizado IS NOT NULL';
			$this->data = [':Codigo' => intval($search)];

			if($this->searchRecords() != 0){
				return false;
			}

			$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE idTicket = :Ticket AND dFechaFinalizado IS NOT NULL';
			$this->data = [':Ticket' => intval($search)];

			return ($this->searchRecords() != 0) ? false : true;
		}	
		private function existingCode($codigo){
			$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE cCodigo = :Codigo';
			$this->data = [':Codigo' => intval($codigo)];

			return ($this->searchRecords() != 0) ? true : false;
		}
		private function existingTicket($tiket){
			$this->query = 'SELECT COUNT(idTicket) FROM Ticket WHERE idTicket = :Id';
			$this->data = [':Id' => intval($tiket)];

			return ($this->searchRecords() != 0) ? true : false;
		}
		private function isTecnico($tecnico){
			$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE idUsuario = :Id AND idDependencia = 2';
			$this->data = [':Id' => intval($tecnico)];

			return ($this->searchRecords() != 0 AND $_SESSION['TICKETS'] == 1) ? true : false;
		}
		private function getEstimatedTime($SubMotivo){
			$this->query = 'SELECT nTiempoEstimado FROM SubMotivo WHERE idSubMotivo = :Id ORDER BY idSubMotivo DESC';
			$this->data = [':Id' => $SubMotivo];

			return (int) $this->executeQuery()->fetchColumn(0) / 24;
		}
		private function getActualState($ticket){
			$this->query = 'SELECT nEstado FROM ticket WHERE idTicket = :Id AND idTecEncargado IS NOT NULL ORDER BY idTicket ASC';
			$this->data = [':Id' => $ticket];

			$result = $this->executeQuery();

			$state = 0;
			while($row = $result->fetch()){
				$state = $row['nEstado'];
			}
			return $state;
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['motivo'])){
						if(!$this->validateEmptyPost(array('obs','alta_compartida','marca_ingreso','modelo_ingreso','patrimonio_ingreso','ingreso'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['id']) && isset($_POST['modProgram']) && isset($_POST['modProgramUrl']) && isset($_POST['modProgramDependencia'])){
						if(!$this->validateEmptyPost(array('modProgramUrl')) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
				case 'setTecnico':
					if(isset($_POST['tec']) && isset($_POST['ticket'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['tec'])) != 0){
							$valid = true;
						}
					}
				break;
				case 'state_ticket':
					if(isset($_POST['ticket']) && isset($_POST['state'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['ticket'])) != 0 && intval(trim($_POST['state'])) != 0){
							$valid = true;
						}
					}
				break;
				case 'consult_ticket':
					if(isset($_POST['ticket'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['ticket'])) != 0){
							$valid = true;
						}
					}
				break;
				case 'add_internal_comment':
					if(isset($_POST['ticket']) && isset($_POST['detail_comentario']) && ($_SESSION['TICKETS'] == 1 && $this->isTecnico($_SESSION['ID_USER']))){
						if(!$this->validateEmptyPost(array('detail_comentario','detail_comentario_tecnico','detail_prioridad','detail_btn_retiro_listo','detail_retirado')) && intval(trim($_POST['ticket'])) != 0){
							$valid = true;
						}
					}
				break;
				case 'add_participant':
					if(isset($_POST['ticket']) && isset($_POST['participante'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['ticket'])) != 0 && trim($_POST['ticket']) != ''){
							$valid = true;
						}
					}
				break;
				case 'delete_participante':
					if(isset($_POST['ticket']) && isset($_POST['participante'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['ticket'])) != 0){
							$valid = true;
						}
					}
				break;
				case 'confirm_closure':
					if(isset($_POST['code'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['code'])) != 0){
							$valid = true;
						}
					}
				break;
				default:
					# code...
				break;
			}
			return $valid;
		}
	}
?>