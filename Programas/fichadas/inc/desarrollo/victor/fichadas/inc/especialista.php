<?php
  require_once("conn.class.php");
  $con=new Conexion();
  $action=$_REQUEST['action'];
  switch ($action) {
    case "get":
      $con->conectar();
      $sql="select * from profesional";
      $data=$con->array_result($con->query($sql));
      //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
      echo json_encode($data);
      break;
  }
?>
