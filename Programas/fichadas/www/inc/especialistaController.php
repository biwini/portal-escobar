<?php

  require_once("globalController.php");

  class especialista extends Main{

    function __construct(){

      parent::__construct();
    }

    public function getEspecialistas(){
      $this->sql = 'SELECT * FROM profesional';
      $this->data = [];
      // $result = $this->query();

      return $this->array_result($this->query());
    }
  }

  // switch ($action) {
  //   case "get":
  //     $con->conectar();
  //     $sql = "SELECT * FROM profesional";
  //     $data = $con->array_result($con->query($sql));
  //     //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
  //     echo json_encode($data);
  //     break;
  // }
?>
