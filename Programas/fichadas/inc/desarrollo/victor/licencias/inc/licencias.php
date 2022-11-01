<?php
  require_once("conn.class.php");
  $con=new Conexion();
  $action=$_REQUEST['action'];
  switch ($action) {
  	case "getLastID":
  		$con->sql='select (max(codigo)+1) as UltimoID from licencias';
		$con->data = [];
		$datos=$con->query();
		echo json_encode($datos->fetchAll());
  		break;

	 case "getLicencias":
		$con->sql='select Codigo,Empleado,e.apellidoNombre,fecha_desde,fecha_hasta from licencias left join empleado e on e.id=licencias.empleado';
		//echo "get";
		$con->data = [];
		$datos=$con->query();
		echo json_encode($datos->fetchAll());
		break;


    case "get":
		$con->sql='select id,apellidoNombre from empleado order by apellidoNombre asc';
		//echo "get";
		$con->data = [];
		$datos=$con->query();
		echo json_encode($datos->fetchAll());
		break;


    case "send":
		$con->sql="Select Codigo from licencias where Codigo=:codigo";
		$con->data = [':codigo'=>$_REQUEST['codigo']];
		$data=$con->query();
	   	 if($fila = $data->fetch()) { //encontro, entonces borra
	     		$con->sql="delete from licencias where Codigo=:codigo";
			$con->data = [':codigo'=>$_REQUEST['codigo']];
	        	$data=$con->query();
	   	 }else{ //no encontro

		}
		$con->sql="insert into licencias(Codigo,Descripcion,empleado,fecha_desde,hora_desde,fecha_hasta,hora_hasta) values(:codigo,:descripcion,:empleado,:fechainicio,:horainicio,:fechafin,:horafin)";
		$con->data = [':codigo'=>trim($_REQUEST['codigo']),':descripcion'=>trim($_REQUEST['descripcion']),':empleado'=>trim($_REQUEST['empleado']),':fechainicio'=>trim($_REQUEST['fechainicio']),':horainicio'=>trim($_REQUEST['horainicio']),':fechafin'=>trim($_REQUEST['fechafin']),':horafin'=>trim($_REQUEST['horafin'])];
	        $data=$con->query();
		//var_dump($data->errorInfo());
        echo json_encode($data);
    	break;

    case "mostrar_registro":	
		$con->sql='select Codigo,descripcion,Empleado,e.apellidoNombre,fecha_desde,fecha_hasta,hora_desde,hora_hasta from licencias left join empleado e on e.id=licencias.empleado';
		//echo "get";
		$con->data = [];
		$datos=$con->query();
		echo json_encode($datos->fetchAll());
		break;


    case "eliminar":
        $con->sql="delete from relojes where Codigo=:codigo";
		$con->data = [':codigo'=>$_REQUEST['id']];
        $data=$con->query();
	//var_dump($data->errorInfo());
        echo json_encode($data);
    	break;
    case "getEmpleados":
		$con->sql='select id,apellidoNombre from empleado order by apellidoNombre asc';
		//echo "get";
		$con->data = [];
		$datos=$con->query();
		echo json_encode($datos->fetchAll());
	break;




  }
?>
