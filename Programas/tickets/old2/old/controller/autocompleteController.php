<?php 
    include_once  realpath(__DIR__ ).'/globalController.php';

	class autocomplete extends globalController{

		public function searchUser(){
            $search = $this->cleanString($_POST['search']);
            
            $this->query = 'SELECT TOP 10 u.idUsuario,u.cNombre,u.cApellido,u.cSexo,u.cNLegajo,u.nMonotributo,u.nTelefono,u.cEmail,s.idSecretaria,s.cNomSecretaria,d.idDependencia,d.cNomDependencia FROM Usuario AS u
                INNER JOIN Dependencia AS d ON u.idDependencia = d.idDependencia
                INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                WHERE CONCAT(u.cNombre, \' \', u.cApellido) LIKE :User OR u.cNLegajo LIKE :Legajo ORDER BY u.idUsuario ASC';
            $this->data = [':User' => '%'.$search.'%', ':Legajo' => '%'.$search.'%'];
            
			$result = $this->executeQuery();
			$response = array();

			while($row = $result->fetch()){
				$response[] = array(
					'Id' => $row['idUsuario'],
					'Name' => $row['cNombre'],
					'Surname' => $row['cApellido'],
					'FullName' => $row['cNombre'].' '.$row['cApellido'],
					'Gender' => $row['cSexo'],
					'Legajo' => trim($row['cNLegajo']),
					'Monotributo' => $row['nMonotributo'],
					'Cellphone' => $row['nTelefono'],
					'Email' => $row['cEmail'],
					'IdSecretaria' => $row['idSecretaria'],
					'Secretaria' => $row['cNomSecretaria'],
					'IdDependencia' => $row['idDependencia'],
					'Dependencia' => $row['cNomDependencia'],
					'Suggestion' => trim($row['cNLegajo']).' | '.$row['cNombre'].' '.$row['cApellido']
				);
			}

			return $response;
        }
        
        public function searchEquipo($type = 'INTERNO'){
            $search = $this->cleanString($_POST['search']);
            
            switch ($type) {
                case 'PATRIMONIO':
                    $this->query = 'SELECT TOP 10 u.cNLegajo, e.idEquipo, e.idUsuario, e.idDependencia, e.idTipo, e.cMarca, e.cModelo, e.nPatrimonio, e.nInterno, e.cMotivoBaja, e.idBaja, e.dFechaBaja, d.cNomDependencia, d.idSecretaria, te.cTipo FROM Equipo AS e
                        INNER JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
                        INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
                        LEFT JOIN Usuario AS u ON e.idUsuario = u.idUsuario
                        WHERE e.nPatrimonio LIKE :Search ORDER BY e.idEquipo ASC';
                break;
                case 'INTERNO':
                    $this->query = 'SELECT TOP 10 u.cNLegajo, e.idEquipo, e.idUsuario, e.idDependencia, e.idTipo, e.cMarca, e.cModelo, e.nPatrimonio, e.nInterno, e.cMotivoBaja, e.idBaja, e.dFechaBaja, d.cNomDependencia, d.idSecretaria, te.cTipo FROM Equipo AS e
                        INNER JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
                        INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
                        LEFT JOIN Usuario AS u ON e.idUsuario = u.idUsuario
                        WHERE e.nInterno LIKE :Search ORDER BY e.idEquipo ASC';    
                break;
            }
            

            $this->data = [':Search' => '%'.$search.'%'];

			$result = $this->executeQuery();
			$equipos = array();

			while ($row = $result->fetch()){
				$equipos[] = array(
					'Id' => $row['idEquipo'],
					'IdType' => $row['idTipo'],
				 	'Type' => $row['cTipo'],
				 	'Brand' => $row['cMarca'], 
				 	'Model' => $row['cModelo'], 
				 	'Patrimony' => $row['nPatrimonio'], 
					'Intern' => $row['nInterno'],
					'User' => ($row['cNLegajo'] == null) ? 'SIN ASIGNAR' : $row['cNLegajo'],
					'IdSecretary' => $row['idSecretaria'],
					'Secretary' => $this->searchSecretary($row['idSecretaria']),
					'IdDependence' => $row['idDependencia'],
					'Dependence' => $row['cNomDependencia'],
					'State' => ($row['idBaja'] == null) ? 1 : 0,
					'DateBaja' => $row['dFechaBaja'],
                    'MotivoBaja' => $row['cMotivoBaja'],
                    'Suggestion' => ($type == 'PATRIMONIO') ? $row['nPatrimonio'].' | '.trim($row['cTipo']) : $row['nInterno'].' | '.trim($row['cTipo'])
				);
			}

			return $equipos;
		}
	}
?>