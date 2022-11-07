<?php

  class Conexion{
  public $conn;
  public $sql,$data;
  
  function __construct(){
      date_default_timezone_set('America/Argentina/Buenos_Aires');
      $serverName = "127.0.0.1";
      $database = "Fichadas";
      $uid = "SA";
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
  
  



  class ConexionMYSQL{
    public $con;
    public function conectar(){
      //Agregar los parametros
      $this->con=new mysqli('localhost','root','usbw','fichadas');
     // $this->con=new mysqli("","","","efectores_ambulatorio");
      $this->con->set_charset("utf8");
      $this->con->query("BEGIN;");
    }
    public function query($sql){
      $rego=$this->con->query($sql);
      if($this->con->errno!=0){
        $error="Error: ".$this->con->error." - (".$sql.")";
        $this->con->query("ROLLBACK;");
        $this->con->close();
        exit($error);
      }
      return $rego;
    }
    public function array_result($result){
      $datos=array();
      $cont=0;
      $info_campo=$result->fetch_fields();
      while($reg=$result->fetch_array()){
        foreach ($info_campo as $valor) {
          $datos[$cont][$valor->name]=$reg[$valor->name];
        }
        $cont++;
      }
      return $datos;
    }
    public function format_string($string){
      $result=$this->con->real_escape_string($string);
      return $result;
    }
    public function last_id(){
      return $this->con->insert_id;
    }
    public function error_struct($error){
      header("HTTP/1.0 404 Not Found");
      header("Status: 404 Not Found");
      $this->con->query("ROLLBACK;");
      $this->con->close();
      exit($error);
    }
    public function close(){
      $this->con->query("COMMIT;");
      $this->con->close();
    }
  }
?>
