<?php

    require 'globalController.php';

    class Secretary extends Main{
        public function getAllSecretary(){
            $this->sql = 'SELECT id,name from sectors';
            $this->data = [];
            return $this->query()->fetchAll();
        }
        public function getDependence(){
            $this->sql = 'SELECT id,name from dependencies';
            $this->data = [];
            return $this->query()->fetchAll();
        }     
    }

    
?>
