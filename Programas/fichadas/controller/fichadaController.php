<?php

    require 'globalController.php';

    class FichadaController extends Main {

        public function addFichada($listEmployee, $datetime ) {
            $formatedDatetime = date_format(date_create($datetime), 'Y-m-d H:i:s');

            $this->sql = "INSERT INTO fichadas_x_captura (idEmpleado, fecha) VALUES " . 
                implode(',', array_map(fn($dni) => "('$dni', '$formatedDatetime')", $listEmployee));

            $datos = $this->query();

            if($datos->errorInfo()[1]) {
                return json_encode([
                    'msg' => 'Error',
                ]);
            }

            return json_encode([
                'msg' => 'Fichadas generadas',
            ]);;
        }

    }
    
?>
