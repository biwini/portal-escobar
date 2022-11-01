<?php

require_once("globalController.php");

class horario extends Main{

        private $id;
        private $search;

        function __construct(){
            parent::__construct();

            $this->conectar();
        }

        public function getHorario(){
            $this->id = $this->format_string($_GET['id']);

            $this->sql = 'SELECT vch_codprestacion AS value, vch_descripprestacion AS name FROM practica WHERE vch_codprestacion = :id';
            $this->data = [':id' => $this->id];

            return $this->array_result($this->query());
        }

        public function searchHorario(){
            $this->search = $this->format_string($_GET['q']);

            $this->sql = 'SELECT vch_codprestacion AS value,vch_descripprestacion AS name FROM practica WHERE vch_descripprestacion LIKE :search';
            $this->data = [':search' => $this->id];

            return $this->array_result($this->query());
        }
}

//   $con = new Conexion();
//   $action = $_REQUEST['action'];
//   switch ($action) {
//     case "get":
//         $con->conectar();
//         $id = $con->format_string($_GET['id']);
//         $sql = "SELECT vch_codprestacion as value,vch_descripprestacion as name from practica where vch_codprestacion=$id";
//         $data = $con->array_result($con->query($sql));
//         //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
//         echo json_encode($data);
//     break;
//     case "search":
//         $con->conectar();
//         $q = $con->format_string($_GET['q']);
//         $sql = "SELECT vch_codprestacion as value,vch_descripprestacion as name from practica where vch_descripprestacion like '%$q%'  ";
//         $data = $con->array_result($con->query($sql));
//         //$data=array(array("c_profesional"=>"12","d_apellidoynombre"=>"alexis"),array("c_profesional"=>"13","d_apellidoynombre"=>"victor"));
//         echo json_encode($data);
//     break;
//   }
?>
