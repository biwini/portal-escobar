<?php
  require_once("conn.class.php");
  $con=new Conexion();
  $action=$_REQUEST['action'];
  switch ($action) {
    case "getAll":
        $con->sql='SELECT * from dependencia order by nombre';
        $con->data = [];
        $datos=$con->query();
        echo json_encode($datos->fetchAll());
        
        break;
  }
?>
