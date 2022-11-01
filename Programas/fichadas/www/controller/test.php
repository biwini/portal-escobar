<?php
    require 'globalController.php';

    class test extends Main{
        // public function hola(){
        //     $personas = $this->getPersonas();
        //     echo 'hola';
        //     var_dump($personas);

        //     while($row = $personas->fetch()){
        //         $this->sql = 'INSERT INTO empleado (nombre,apellido,legajo,tipoDocumento,nroDocumento,cuit,fechaNacimiento,sexo,estadoCivil,direccion,telefono,email,idSecretaria,idDependencia,idTipoEmpleado,estado,idUsuario,createdAt,updatedAt,idPersona,idSector,idDependency) 
        //             VALUES (:nombre,:apellido,:legajo,:tipoDocumento,:nroDocumento,:cuit,:fechaNacimiento,:sexo,:estadoCivil,:direccion,:telefono,:email,:idSecretaria,:idDependencia,:idTipoEmpleado,:estado,:idUsuario,:createdAt,:updatedAt,:idPersona,:idSector,:idDependency)';
        //         $this->data = [
        //             ':nombre' => $row['Nombre'],
        //             ':apellido' => $row['Apellido'],
        //             ':legajo' => $row['FileNumber'],
        //             ':tipoDocumento' => 'DNI',
        //             ':nroDocumento' => $row['Legajo'],
        //             ':cuit' => $row['Win'],
        //             ':fechaNacimiento' => $row['BirthDate'],
        //             ':sexo' => NULL,
        //             ':estadoCivil' => NULL,
        //             ':direccion' => $row['Address'],
        //             ':telefono' => NULL,
        //             ':email' => $row['Email'],
        //             ':idSecretaria' => NULL,
        //             ':idDependencia' => NULL,
        //             ':idTipoEmpleado' => NULL,
        //             ':estado' => 0,
        //             ':idUsuario' => NULL,
        //             ':createdAt' => NULL,
        //             ':updatedAt' => NULL,
        //             ':idPersona' => $row['ID_Persona'],
        //             ':idSector' => $row['SectorId'],
        //             ':idDependency' => $row['DependencyId'],
        //         ];

        //         echo ($this->quer()) ? 'hola' : 'nop';
        //     }
        // }

        public function getDependenceNameById($id){
            $this->sql = 'SELECT Name FROM Dependencies WHERE idDependencia = :id';
            $this->data = [':id' => $id];

            return $this->query()->fetchColumn(0);
        }

        public function getDependenceId($id){
            $this->sql = 'SELECT Id FROM Dependencies WHERE idDependencia = :id';
            $this->data = [':id' => $id];

            return $this->query()->fetchColumn(0);
        }
        public function getDependence2NameById($id){
            $this->sql = 'SELECT cNombre FROM Dependencies WHERE idDependencia = :id';
            $this->data = [':id' => $id];

            return $this->query()->fetchColumn(0);
        }

        public function getRepeatedId(){
            $this->sql = 'SELECT idDependencia, count(*) AS repeated FROM Dependencies 
                GROUP BY idDependencia
                HAVING COUNT(*) > 1';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getSectors(){
            $this->sql = 'SELECT * FROM Sectors';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getDependences(){
            $this->sql = 'SELECT * FROM Dependencies ORDER BY Name';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getNotRelatedDependences(){
            $this->sql = 'SELECT * FROM Dependencies WHERE idDependencia IS NULL ORDER BY Name';
            $this->data = [];

            return $this->query()->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getPersonas(){
            $this->sql = 'SELECT * FROM Personas';
            $this->data = [];

            // return $this->query()->fetchAll(PDO::FETCH_ASSOC);
            return $this->query();
        }
    }


    $Test = new test();

    $sector = $Test->getSectors();
    $dependences = $Test->getDependences();
    $notRelatedDependences = $Test->getNotRelatedDependences();
    $repeatedDependences = $Test->getRepeatedId();
    
    $SecretaryList = $_SESSION['SECRETARIAS'];

    $optionSecretarias = '';
    $optionDependences = '';
    $optionDependences2 = '';

    foreach ($dependences as $key => $value) {
        $optionDependences2 .= '<option value=\' - '.$value['Name'].'\'>';
    }

    foreach ($SecretaryList as $key => $value) {
        $optionSecretarias .= '<option value=\''.$value['Id'].'\'>'.$value['Id'].' | '.$value['Secretary'].'</option>';
        foreach ($value['Dependences'] as $k => $v) {
            $optionDependences .= '<option value=\''.$v['Id'].' - '.$v['Dependence'].' - '.$value['Id'].'\'>';
        }
    }

    function getDependenceName($id){
        $d = '';
        foreach ($_SESSION['SECRETARIAS'] as $key => $value) {
            foreach ($value['Dependences'] as $k => $v) {
                if($v['Id'] == $id){
                    $d = $v['Dependence'];
                    break;
                }                
            }
        }

        return $d;
    }

    function getDependenceId($id){
        $d = '';
        foreach ($_SESSION['SECRETARIAS'] as $key => $value) {
            foreach ($value['Dependences'] as $k => $v) {
                if($v['Id'] == $id){
                    $d = $v['Dependence'];
                    break;
                }                
            }
        }

        return $d;
    }
?>

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    const sec = <?php echo json_encode($SecretaryList); ?>;
</script>
</head>
<body>
    <!-- <select name="dep" id="dep">
        <?php 
            // foreach ($dependences as $key => $value) {
            //     echo '<option value=\''.$value['Id'].'\'>'.$value['Name'].'</option>';
            // }  
        ?>
    </select> -->
    <select name="dep2" id="dep2">
        <?php 
            foreach ($repeatedDependences as $key => $value) {
                echo '<option value=\''.$Test->getDependenceId($value['idDependencia']).'\'>'.$Test->getDependenceNameById($value['idDependencia']).' - '.$value['repeated'].' - '.getDependenceName($value['idDependencia']).'</option>';
            }  
        ?>
    </select>
    <select name="dep" id="dep">
        <?php 
            foreach ($dependences as $key => $value) {
                echo '<option value=\''.$value['Id'].'\'>'.$value['Name'].' - '.getDependenceName($value['idDependencia']).'</option>';
            }  
        ?>
    </select>
    <input list="fichadas" id="hola2" style="width: 50%;">
    <datalist id="fichadas">
        <?php echo $optionDependences2; ?>
    </datalist>

<input list="dependencias" id="hola" style="width: 50%;">
<datalist id="dependencias">
  <?php echo $optionDependences; ?>
</datalist>
<button type="button" id="reemplazar">Reemplazar</button>
<hr>
<div>
    <h3>DEPENDENCIAS SIN RELACIONAR <?php echo count($notRelatedDependences); ?></h3>
        <?php 
            foreach ($notRelatedDependences as $key => $value) {
                echo $value['Name'].'<br>';
            }  
        ?>
</div>
<div>
    <label for="">MIGRAR PERSONAS A EMPLEADOS</label>
    <button id="migrar_personas">MIGRAR PERSONAS</button>
</div>
<hr>
<div>
    <label for="">MIGRAR EmployeeTypes A TipoEmpleado</label>
    <button id="migrar_tipoempleado">MIGRAR EMPLOYEE TYPES</button>
</div>
<hr>
<div>
    <label for="">MIGRAR SHIFT A HORARIOS</label>
    <button id="migrar_shift">MIGRAR SHIFT</button>
</div>
<hr>
<div>
    <label for="">MIGRAR DAYSHIFT A HORARIOS_X_FICAHDAS</label>
    <button id="migrar_dayshift">MIGRAR DAYSHIFT</button>
</div>

<hr>
<div>
    <label for="">MIGRAR HOLIDAY A FERIADOS</label>
    <button id="migrar_holiday">MIGRAR HOLiDAY</button>
</div>

<hr>
<div>
    <label for="">MIGRAR ASSIGNED SHIFT TO HORARIOS EMPLEADO</label>
    <button id="migrar_assignedshift">MIGRAR ASSIGNED SHIFT</button>
</div>

<hr>
<div>
    <label for="">MIGRAR LICENCIAS</label>
    <button id="migrar_licencias">MIGRAR LICENCIAS</button>
</div>

<script>
    $('#reemplazar').click(function(e){
        let dependencia = $('#hola').val().split('-')[0];

        console.log(dependencia.split('-')[0]);

        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'id='+dependencia+'&dep='+$('#dep').val()+'&hola=1',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            if(data.status == 'success'){
                console.log('bien');
            }else{
                console.log('mal');
            }
            console.log(data)
        });
    });
    let holas = [];

    $('#migrar_personas').click(function(){
        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'hola=2',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            holas = data;
            if(data.status == 'ok'){
                console.log('bien');
                console.log(data);
            }else{
                console.log('mal');
            }
            console.log(holas)
        });
    });

    $('#migrar_tipoempleado').click(function(){
        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'hola=3',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            if(data.status == 'success'){
                console.log('bien');
                console.log(data);
            }else{
                console.log('mal');
            }
            console.log(data)
        });
    });

    $('#migrar_shift').click(function(){
        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'hola=4',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            if(data.status == 'success'){
                console.log('bien');
                console.log(data);
            }else{
                console.log('mal');
            }
            console.log(data)
        });
    });

    $('#migrar_dayshift').click(function(){
        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'hola=5',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            if(data.status == 'success'){
                console.log('bien');
                console.log(data);
            }else{
                console.log('mal');
            }
            console.log(data)
        });
    });

    $('#migrar_holiday').click(function(){
        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'hola=6',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            if(data.status == 'success'){
                console.log('bien');
                console.log(data);
            }else{
                console.log('mal');
            }
            console.log(data)
        });
    });

    $('#migrar_assignedshift').click(function(){
        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'hola=7',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            if(data.status == 'success'){
                console.log('bien');
                console.log(data);
            }else{
                console.log('mal');
            }
            console.log(data)
        });
    });

    $('#migrar_licencias').click(function(){
        $.ajax({
            type: "POST",
            url: "test2.php",
            data: 'hola=8',
            dataType: "json",
        })
        .fail(function(data){
            console.log('aprende a programar');
        })
        .done(function(data){
            if(data.status == 'success'){
                console.log('bien');
                console.log(data);
            }else{
                console.log('mal');
            }
            console.log(data)
        });
    });
</script>
</body>
</html>