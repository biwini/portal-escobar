<?php
  require_once("conn.class.php");
  $con=new Conexion();
  $action=$_REQUEST['action'];
  switch ($action) {
    case "get":
        $con->conectar();
        $tipo_doc=$con->format_string($_GET['tipo_doc']);
        $nro_doc=$con->format_string($_GET['nro_doc']);
        $sql="select nombre,id_beneficio,id_parentesco from todos where tipodoc='$tipo_doc' and nrodoc=$nro_doc ";
        $data=$con->array_result($con->query($sql));
        $con->close();
        //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
        echo json_encode($data);
    break;
    case "send":
        $con->conectar();
        $especialista=$con->format_string($_POST['especialista']);
        $date=$con->format_string($_POST['date']);
        $tipo_doc=$con->format_string($_POST['tipo_doc']);
        $nro_doc=$con->format_string($_POST['nro_doc']);
        $nro_beneficio=$con->format_string($_POST['nro_beneficio']);
        $nro_parentesco=$con->format_string($_POST['nro_parentesco']);
        $name=$con->format_string($_POST['name']);
        $practicas=$_POST['practicas'];
        $sql="insert into ambulatorio set c_profesional=$especialista, f_fecha_atencion='$date', tipo_doc='$tipo_doc', nro_doc=$nro_doc, id_beneficio='$nro_beneficio', id_parentesco='$nro_parentesco', apellidoynombre='$name'";
        $data=$con->query($sql);
        $last_id=$con->last_id();
        foreach($practicas as $val){
            $sql="insert into rel_practicasrealizadasxambulatorio set c_ambulatorio=$last_id, vch_codprestacion='$val[id_practica]', f_fecha_practica='$date', q_cantidad=$val[cant]";
            $con->query($sql);
        }
        $con->close();
        echo json_encode($data);
    break;
    case "search":
        $con->conectar();
        $date=$con->format_string($_GET['date']);
        $name=$con->format_string($_GET['name']);
        $sql="select f_fecha_atencion,apellidoynombre,count(select c_ambulatorio from rel_practicasrealizadasxambulatorio as r where r.c_ambulatorio=a.c_ambulatorio) as cant from ambulatorio as a where apellidoynombre like '$name' and f_fecha_atencion='$date' ";
        echo $sql;
        $con->close();
        echo json_encode($data);
    break;
  }
?>
