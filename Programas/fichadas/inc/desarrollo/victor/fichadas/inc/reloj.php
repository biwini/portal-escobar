<?php
  require_once("conn.class.php");
  $con=new Conexion();
  $action=$_REQUEST['action'];
  switch ($action) {
    case "get":
		$con->sql='select Codigo, Descripcion, Dependencia from relojes order by Codigo asc';
		//echo "get";
		$con->data = [];
		$datos=$con->query();
		echo json_encode($datos->fetchAll());
		break;
    case "send":
	$con->sql="Select Codigo from relojes where Codigo=:codigo";
	$con->data = [':codigo'=>$_REQUEST['codigo']];
	$data=$con->query();
   	 if($fila = $data->fetch()) { //encontro, entonces borra
     		$con->sql="delete from relojes where Codigo=:codigo";
		$con->data = [':codigo'=>$_REQUEST['codigo']];
        	$data=$con->query();
   	 }else{ //no encontro
	}
	$con->sql="insert into relojes(Codigo,Descripcion,Dependencia,Usuario,Clave,Ubicacion,Marca,Modelo,DireccionIP,Puerto) values(:codigo,:descripcion,:dependencia,:usuario,:clave,:ubicacion,:marca,:modelo,:direccionip,:puerto)";
	$con->data = [':codigo'=>trim($_REQUEST['codigo']),':descripcion'=>trim($_REQUEST['descripcion']),':dependencia'=>trim($_REQUEST['dependencia']),':usuario'=>trim($_REQUEST['usuario']),':clave'=>trim($_REQUEST['clave']),':ubicacion'=>trim($_REQUEST['ubicacion']),':marca'=>trim($_REQUEST['marca']),':modelo'=>trim($_REQUEST['modelo']),':direccionip'=>trim($_REQUEST['direccionip']),':puerto'=>trim($_REQUEST['puerto'])];
        $data=$con->query();
	//var_dump($data->errorInfo());
        echo json_encode($data);
        
    break;
    case "search":	
		$con->sql='select Codigo,Descripcion,Dependencia,Usuario,Clave,Ubicacion,Marca,Modelo,DireccionIP,Puerto,Usuario,Observaciones from relojes where Codigo=:Codigo order by Codigo asc';
		$con->data = [':Codigo'=>$_REQUEST['id']];
		$datos=$con->query();
		//var_dump($datos->errorInfo());
		echo json_encode($datos->fetchAll());
		break;
    break;
    case "eliminar":
        $con->sql="delete from relojes where Codigo=:codigo";
	$con->data = [':codigo'=>$_REQUEST['id']];
        $data=$con->query();
	//var_dump($data->errorInfo());
        echo json_encode($data);
    break;
    case "getLastID":
	$con->sql='select (max(Codigo)+1) as UltimoID from relojes';
		$con->data = [];
		$datos=$con->query();
		echo json_encode($datos->fetchAll());
		break;
    break;
  }
?>
