<?php
  class Conexion{
    public $con;
    public function conectar(){
      //Agregar los parametros
      $this->con=new mysqli('localhost','root','usbw','efectores_ambulatorio');
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
