<?php
  class Conexion{
    public $conn;
		public $sql,$data;

		function __construct(){
      date_default_timezone_set('America/Argentina/Buenos_Aires');

      $serverName = "192.168.122.17";
      $database = "Fichadas";
      $uid = "sa";
      $pwd = 'Fu11@c3$*9739';

      $this->conn = new PDO("sqlsrv:server=$serverName;Database=$database",$uid,$pwd);
      $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
      $this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
      
      $this->data = array();

      //Defino el permiso administrador segun su permiso en el sistema y segun su dependencia particular
      //$this->Admin = ($_SESSION['TICKETS_DE_COMBUSTIBLE'] == 1 && $_SESSION['DEPENDENCIA'] == 265) ? true : false; // Id de la dependencia de hacienda '265'
    }
    

    public function query(){  //Funcion parra ejecutar la query. devuelve la respuesta del sql
      $records = $this->conn->prepare($this->sql);
      $records->execute($this->data);
      return $records;
    }

    public function last_id(){ //obtene ultimo id generado
      return $this->conn->lastInsertId();
    }
  }
?>
