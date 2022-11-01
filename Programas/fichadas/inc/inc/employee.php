<?php
    require_once("conn.class.php");
    $con=new Conexion();
    $action=$_REQUEST['action'];
    switch ($action) {
        case "getEmployee":
            if(!empty($_REQUEST['id'])){
              $con->sql='SELECT apellidoNombre,legajo,tipoDocumento,nroDocumento,fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,e.idSecretaria,e.idDependencia,e.idTipoEmpleado,cuit,s.nombre as nombreSecretaria,t.nombre as nombreTipoEmpleado,d.nombre as nombreDependencia FROM empleado AS e
              LEFT JOIN secretaria AS s ON s.id=e.idSecretaria
              LEFT JOIN tipoEmpleado AS t ON t.id=e.idTipoEmpleado
              LEFT JOIN dependencia AS d ON d.id=e.idDependencia
              WHERE e.id=:id ';
              $con->data = [':id'=>$_REQUEST['id']];
              $datos=$con->query();
              echo json_encode($datos->fetchAll());
            }
            break;
        case "getEmployeesPaginated":
            $search="";
            $listSearch=[];
            $c=0;
            $con->data = [':from'=>$_POST['from'],':to'=>$_POST['to']];
            if(!empty($_POST['search'])){
              $s=$_POST['search'];
              if(!empty($s['fullName'])){
                $listSearch[$c]=" apellidoNombre like :fullName ";
                $con->data[':fullName']="%".$s['fullName']."%";
                $c++;
              }
              if(!empty($s['secretaryId'])){
                $listSearch[$c]=" e.idSecretaria=:secretaryId ";
                $con->data[':secretaryId']=$s['secretaryId'];
                $c++;
              }
              if(!empty($s['dependenceId'])){
                $listSearch[$c]=" e.idDependencia=:dependenceId ";
                $con->data[':dependenceId']=$s['dependenceId'];
                $c++;
              }
              if(!empty($s['employeeTypeId'])){
                $listSearch[$c]=" e.idTipoEmpleado=:employeeTypeId ";
                $con->data[':employeeTypeId']=$s['employeeTypeId'];
                $c++;
              }
              $search=" and ".implode(" AND ", $listSearch);
            }
            $con->sql="SELECT * FROM (
              SELECT ROW_NUMBER() OVER(ORDER BY e.id desc) AS RowID,e.id,e.apellidoNombre,e.legajo,e.tipoDocumento,e.nroDocumento,e.fechaNacimiento,e.sexo,e.estadoCivil,e.direccion,e.telefono,e.email,s.nombre as nombreSecretaria,s.descripcion as descripcionSecretaria,d.nombre as nombreDependencia,d.descripcion as descripcionDependencia,t.nombre as nombreTipoEmpleado,t.descripcion as descripcionTipoEmpleado FROM empleado AS e
              LEFT JOIN secretaria AS s ON s.id=e.idSecretaria
              LEFT JOIN tipoEmpleado AS t ON t.id=e.idTipoEmpleado
              LEFT JOIN dependencia AS d ON d.id=e.idDependencia  where e.estado=0  ".$search."
            ) AS c WHERE c.RowID between :from AND :to ";
            //echo $con->sql;
            $datos=$con->query();
            echo json_encode($datos->fetchAll());
            break;
        case "getEmployeesAmount":
            $search="";
            $listSearch=[];
            $c=0;
            $con->data=[];
            
            if(!empty($_POST['search'])){
              $s=$_POST['search'];
              if(!empty($s['fullName'])){
                $listSearch[$c]=" apellidoNombre like :fullName ";
                $con->data[':fullName']="%".$s['fullName']."%";
                $c++;
              }
              if(!empty($s['secretaryId'])){
                $listSearch[$c]=" e.idSecretaria=:secretaryId ";
                $con->data[':secretaryId']=$s['secretaryId'];
                $c++;
              }
              if(!empty($s['dependenceId'])){
                $listSearch[$c]=" e.idDependencia=:dependenceId ";
                $con->data[':dependenceId']=$s['dependenceId'];
                $c++;
              }
              if(!empty($s['employeeTypeId'])){
                $listSearch[$c]=" e.idTipoEmpleado=:employeeTypeId ";
                $con->data[':employeeTypeId']=$s['employeeTypeId'];
                $c++;
              }
              $search=" and ".implode(" AND ", $listSearch);
            }
            $con->sql='SELECT count(*) as total from empleado as e  where estado=0  '.$search;
            //echo $con->sql;
            $datos=$con->query();
            echo json_encode($datos->fetchAll());
            break;
        case "getAllEmployeeTypes":
            $con->sql='SELECT * from tipoEmpleado order by nombre';
            $con->data = [];
            $datos=$con->query();
            echo json_encode($datos->fetchAll());
            break;
        case "insertEmployee":
            $con->sql='insert into empleado(
              apellidoNombre,legajo,tipoDocumento,nroDocumento,fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,idSecretaria,idDependencia,idTipoEmpleado,idUsuario,cuit)
            values(:fullname,:legajo,:typeDoc,:nroDoc,:birthDate,:gender,:stateCivil,:address,:phone,:email,:secretary,:dependence,:employeeTypes,99,:cuit)';
            $con->data = $_POST['values'];
            $datos=$con->query();
            if($datos->errorInfo()[1]){
              echo json_encode(['status'=>"error",'output'=>$datos->errorInfo()[2]]);
            }
            else{
              echo json_encode(['status'=>'ok']);
            }
            break;
        case "editEmployee":
          if(!empty($_REQUEST['id'])){
            $con->sql='update empleado set
              apellidoNombre=:fullname ,legajo=:legajo,tipoDocumento=:typeDoc,nroDocumento=:nroDoc,
              fechaNacimiento=:birthDate,sexo=:gender,estadoCivil=:stateCivil,direccion=:address,
              telefono=:phone,email=:email,idSecretaria=:secretary,idDependencia=:dependence,
              idTipoEmpleado=:employeeTypes,idUsuario=99,cuit=:cuit where id=:id';
            $con->data = $_POST['values'];
            $datos=$con->query();
            if($datos->errorInfo()[1]){
               echo json_encode(['status'=>"error",'output'=>$datos->errorInfo()[2]]);
            }
            else{
               echo json_encode(['status'=>'ok']);
            }
          }
          else{
            echo json_encode(['status'=>'error','output'=>"Faltan variables"]);
          }
          break;
        case "deleteEmployee":
            $con->sql='update empleado set estado=1 where id=:id';
            $con->data = $_POST['values'];
            $datos=$con->query();
            if($datos->errorInfo()[1]){
              echo json_encode(['status'=>"error",'output'=>$datos->errorInfo()[2]]);
            }
            else{
              echo json_encode(['status'=>'ok']);
            }
            break;
    }
    // Ver iamgenes guardadas en la base de datos
    //
    // $con->sql='SELECT top 50 image,mimetype from PersonImage ';
    // $con->data = [];
    // $datos=$con->query();
    // while($row=$datos->fetch()){
    //   echo '<img src="data:'.$row['mimetype'].';base64,' . base64_encode($row['image'])  . '" > <br>';
    // }  
    //s
?>
