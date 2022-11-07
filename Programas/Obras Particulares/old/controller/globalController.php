<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }

	class globalController{
		protected $conn,$filterDesde = '',$filterHasta = '';
        public $WHERE = '';
		public $query,$data,$fecha;

		function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
            $serverName = "127.0.0.1"; //serverName\instanceName
            $database = "ObraParticular";
            $UID = 'SA';
            $PWD = 'Fu11@c3$*9739';
			$this->conn = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,[
		       PDO::ATTR_EMULATE_PREPARES => false,
		       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		    ]);
			$this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$this->data = array();
            $this->fecha = date('Y-m-d H:i:s', time());

            if(isset($_POST['fechaDesde']) && isset($_POST['fechaHasta'])){
                $this->WHERE .= 'AND ';
                if(!empty(trim($_POST['fechaDesde']))){
                    $this->WHERE .= ' l.fecha_liquidacion >= \''.$this->cleanString($_POST['fechaDesde']).' 00:00:00\' AND';
                }
                if(!empty(trim($_POST['fechaHasta']))){
                    $this->WHERE .= ' l.fecha_liquidacion <= \''.$this->cleanString($_POST['fechaHasta']).' 23:59:59\' AND';   
                }
                $this->WHERE = substr($this->WHERE,0,-3).'';
            }
		}

		public function searchRecords(){
			$records = $this->conn->prepare($this->query);
			$records->execute($this->data);

			return $records->fetchColumn();
		}
		public function executeQuery(){
			$records = $this->conn->prepare($this->query);
			$records->execute($this->data);

			return $records;
		}
        public function validateEmptyPost(){
            $incomplete = false;
            foreach ($_POST as $key => $value) {
                if(empty(trim($value))){
                    $incomplete = true;
                    break; //sale del bucle foreach
                }
            }
            return $incomplete;
        }
		public function cleanString($string){
             
            $string = trim($string);
         
            $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $string
            );
         
            $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $string
            );
         
            $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $string
            );
         
            $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','õ'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','o'),
                $string
            );
         
            $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $string
            );
         
            $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C',),
                $string
            );
         
            $string = str_replace(
                array("\\", "¨", "º", "~","°","¬",
                     "#", "@", "|", "!", "\"","`",
                     "·", "$", "%", "/",
                     "(", ")", "?", "'", "¡",
                     "¿", "[", "^", "<code>", "]",
                     "+", "}", "{", "¨", "´",
                     ">", "< ", ";", ",", ":",
                     ".","="),
                ' ',
                $string
            );
           	return $string;
        }
	}
?>