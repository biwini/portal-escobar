<?php	
	//Reanudo la Session ya existente.
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				if(isset($_POST['eliminacion']) === true){
					require("conexion.php");
					// Recibo los datos que envia Ajax.
					$id_liquidacion = $_POST['id_registro'];
					$tipo = $_POST['tipo'];
					$nombre_cliente = $_POST['cliente'];
					$zona = $_POST['zona'];
					$descuento = $_POST['descuento'];
					$total = $_POST['total'];
					$id_cliente = 0;
					$id_nomenclatura = 0;
					$id_usuario = $_SESSION['id_usuario'];

  					$datosLiquidacion = $conn->query("SELECT * FROM liquidacion WHERE id_liquidacion = '".$id_liquidacion."'");

  					while($datos = $datosLiquidacion->fetch()){
  						$id_cliente = $datos['id_cliente'];
  						$id_nomenclatura = $datos['id_nomenclatura'];
  					}

  					date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
					$fecha =date('Y-m-d H:i:s', time());

  					$informacion = "Liquidacion : cliente : Id = ".$id_cliente." Nombre = ".$nombre_cliente." Zonificacion = ".$zona." Tipo Liquidacion = ".$tipo." Descuento = ".$descuento." Total = ".$total." ";

					$QueryAuditoria = $conn->query("INSERT INTO auditoria(id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES (".$id_usuario.",".$id_liquidacion.",'Eliminacion de una liquidacion','".$informacion."','".$fecha."')") or die("Error al Cargar la auditoria");

					$QueryUpdate = $conn->query("UPDATE liquidacion SET eliminado = 1,fecha_eliminado ='".$fecha."' WHERE id_liquidacion =".$id_liquidacion." ") or die("Error al eliminar la liquidacion");
					if($QueryAuditoria && $QueryUpdate){
						echo "<center><span style='font-weight:bold;color:green;'>Registro eliminado con exito.</span></center>";
					}
					else{
						echo "<center><span style='font-weight:bold;color:red;'>Error al eliminar la liquidacion.</span></center>";
					}
					$conn = NULL;
				}
			}
		}
	}
?>