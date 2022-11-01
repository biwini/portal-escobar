<?php
  require_once("conn.class.php");
  $con=new Conexion();
  $action=$_REQUEST['action'];
  switch ($action) {
    case "get":
        $con->conectar();
        $id=$_GET['id'];
        $sql="select vch_codprestacion as value,vch_descripprestacion as name from practica where vch_codprestacion=$id";
        $data=$con->array_result($con->query($sql));
        //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
        echo json_encode($data);
    break;
    case "search":
        $con->conectar();
        $q=$con->format_string($_GET['q']);
        $sql="select vch_codprestacion as value,vch_descripprestacion as name from practica where vch_descripprestacion like '%$q%'  ";
        $data=$con->array_result($con->query($sql));
        //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
        echo json_encode($data);
    break;
  }
?>
