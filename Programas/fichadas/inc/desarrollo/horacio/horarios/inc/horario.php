<?php
  require_once("conn.class.php");
  $con=new Conexion();
  $action=$_REQUEST['action'];
  switch ($action) {
    case "get":
        $id=$_GET['id'];
        $sql="select vch_codprestacion as value,vch_descripprestacion as name from practica where vch_codprestacion=$id";
        $data=$con->array_result($con->query($sql));
        //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
        echo json_encode($data);
    break;
    case "todos":
        //sql microsoft
        $con->sql="select id_horario, nombre_horario from horarios ";
        $con->data = [];
        $datos=$con->query();
        echo json_encode($datos->fetchAll());

        //mysql
        //$sql="select id_horario, nombre_horario from horarios ";
        //$data=$con->array_result($con->query($sql)); // $data es el resulset
        //echo json_encode($data); //funcion que imprime en pantalla el resultado  en formato de json

    break;
    case "insertar":
        $descrip=$_POST['descrip'];
        $tol1=$_POST['tol1'];
        $tol2=$_POST['tol2'];
        $listhorarios=$_POST['listhorarios'];
        $usuarioalta=""; // usuario de alta
        $con->sql="insert into HORARIOS (NOMBRE_HORARIO,D_TOLERANCIA_ENTRADA,D_TOLERANCIA_SALIDA,N_USUARIOALTA) VALUES(:w2,:w3,:w4,:w5) ";
        $con->data=[':w2'=>trim($descrip),':w3'=>trim($tol1),':w4'=>trim($tol2),':w5'=>trim($usuarioalta)];
        $data=$con->query();
        if($data->errorInfo()[1]){
          echo json_encode(['status'=>"error",'output'=>$data->errorInfo()[2]]);
        }
        else{
          echo json_encode(['status'=>'ok']);
        }
         $horario=$con->last_id();
         foreach($listhorarios as $wvar){
           $con->sql="insert into HORARIOS_X_DIA (ID_HORARIO,ID_dia_semana,H_ENTRADA,H_SALIDA,H_HORA_egr_COMIDA,H_HORA_ing_COMIDA) VALUES(:w1,:w2,:w3,:w4,:w5,:w6) ";
           $con->data=[':w1'=>$horario,':w2'=>trim($wvar['dia']),':w3'=>trim($wvar['e1']),':w4'=>trim($wvar['s1']),':w5'=>trim($wvar['e2']),':w6'=>trim($wvar['s2'])];
           $data=$con->query();
           if($data->errorInfo()[1]){
             echo json_encode(['status'=>"error",'output'=>$data->errorInfo()[2]]);
           }
           else{
             echo json_encode(['status'=>'ok']);
           }

         }

    break;
    case "buscar":
        //sql microsoft
        $con->sql="select * from HORARIOS h left join HORARIOS_X_DIA hd on hd.ID_HORARIO=h.ID_HORARIO where h.ID_HORARIO=:wid";
        $con->data=[':wid'=>$_GET['id']];
        $datos=$con->query();
        echo json_encode($datos->fetchAll());

        //mysql
        //$sql="select id_horario, nombre_horario from horarios ";
        //$data=$con->array_result($con->query($sql)); // $data es el resulset
        //echo json_encode($data); //funcion que imprime en pantalla el resultado  en formato de json

    break;
  }
?>
