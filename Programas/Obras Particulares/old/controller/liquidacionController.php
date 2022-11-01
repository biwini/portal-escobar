<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class liquidacion extends globalController{
		public $liquidacion = array();

		public function getLiquidacion(){
			$this->filterDesde = '-';
			$this->filterHasta = '-';

			$this->query = 'SELECT c.razon_social,tp.tipo,l.id_liquidacion,l.fecha_liquidacion,l.zonificacion,l.descuento,l.total FROM liquidacion AS l
				INNER JOIN cliente AS c ON l.id_cliente = c.id_cliente
				INNER JOIN tipo_liquidacion AS tp ON l.id_tipo_liquidacion = tp.id_tipo_liquidacion
				WHERE l.eliminado IS NULL '.$this->WHERE.' ORDER BY l.id_liquidacion ASC';
			$this->data = [];
			$_SESSION['REGISTROS_AUDITORIAS'] = 1;
			$result = $this->executeQuery();

			while($row = $result->fetch()){
				$date = date_create(trim($row['fecha_liquidacion']));
				$FechaLiquidacion=date_format($date, 'Y-m-d');
				$this->liquidacion[] =array(
					'Id' => $row['id_liquidacion'],
					'FechaLiquidacion' => $FechaLiquidacion,
					'FullFechaLiquidacion' => trim($row['fecha_liquidacion']),
					'RazonSocial' => $row['razon_social'],
					'Zonificacion' => $row['zonificacion'],
					'Tipo' => $row['tipo'],
					'Descuento' => $row['descuento'],
					'Total' => $row['total'],
				);
			}
			// $response[] =array(
			// 		'Id' => $this->query
			// 	);

			return $this->liquidacion;
		}
		public function deleteLiquidacion(){
			// Recibo los datos que envia Ajax.
			$Id = $this->cleanString($_POST['id_registro']);
			$tipo = $this->cleanString($_POST['tipoLiq']);
			$nombre_cliente = $this->cleanString($_POST['cliente']);
			$zona = $this->cleanString($_POST['zona']);
			$descuento = $this->cleanString($_POST['descuento']);
			$total = $this->cleanString($_POST['total']);
			$id_cliente = 0;
			$id_nomenclatura = 0;

			$this->query = 'SELECT id_cliente,id_nomenclatura FROM liquidacion WHERE id_liquidacion = :Id ORDER BY id_liquidacion ASC';
			$this->data = ['Id' => $Id];

			$DatosLiquidacion = $this->executeQuery();
			if($DatosLiquidacion){
				while($datos = $DatosLiquidacion->fetch()){
					$id_cliente = $datos['id_cliente'];
					$id_nomenclatura = $datos['id_nomenclatura'];
				}

				$informacion = 'Liquidacion ID : '.$Id.' cliente : Id = '.$id_cliente.' Nombre = '.$nombre_cliente.' Zonificacion = '.$zona.' Tipo Liquidacion = '.$tipo.' Descuento = '.$descuento.' Total = '.$total.' ';

				$this->query = 'INSERT INTO auditoria(id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES (:IdUser, :IdLiq,\'Eliminacion de una liquidacion\', :Details, :Fecha)';
				$this->data = [
					':IdUser' => $_SESSION['ID_USER'],
					':IdLiq' => $Id,
					':Details' => $informacion,
					':Fecha' => $this->fecha,
				];
				$this->query = 'UPDATE liquidacion SET eliminado = 1,fecha_eliminado = :Fecha WHERE id_liquidacion = :Id';
				$this->data = [':Fecha' => $this->fecha, ':Id' => $Id];

				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Error');
			}
		}
	}
?>