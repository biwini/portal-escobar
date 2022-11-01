<?php
if (!isset($_SESSION)) {
    require_once('sessionController.php');
    $Session = new session();
}

class Main
{
    protected $conn;
    protected $sql, $data;
    protected $errorMessage;
    public $fecha;

    function __construct()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        # $serverName = ".\SQLEXPRESS";
        $serverName = "192.168.122.17";
        $database = "Fichadas";
        $uid = "SA";
        # $pwd = '123';
        $pwd = 'Fu11@c3$*9739';

        $this->conn = new PDO("sqlsrv:server=$serverName;Database=$database", $uid, $pwd);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");

        $this->fecha = date('Y-m-d H:i:s');
        $this->data = array();
        $this->errorMessage = array('status' => 'error', 'output' => 'Ocurrio un error inesperado, no se realizo la acción');
    }

    protected function query()
    {
        $records = $this->conn->prepare($this->sql);
        $records->execute($this->data);
        return $records;
    }

    protected function searchRecords()
    {
        $records = $this->conn->prepare($this->sql);
        $records->execute($this->data);

        return $records->fetchColumn();
    }

    public function getActualDateTime()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        return date('Y-m-d H:i:s');
    }

    public function validateEmptyRequest($exeption)
    {
        $incomplete = false;
        if (!isset($_REQUEST)) {
            return false;
        }
        foreach ($_REQUEST as $key => $value) {
            $UpperKey = mb_strtoupper($key, 'UTF-8');
            if (!in_array($key, $exeption, true)) {
                if (empty(trim($value))) {
                    $incomplete = true;
                    break; //sale del bucle foreach
                }
            }
        }
        return $incomplete;
    }

    protected function is_valid_email($email)
    {
        $matches = null;
        return (1 === preg_match('/[^@\s]+@[^@\s]+\.[^@\s]+/', $email, $matches));
    }

    protected function validDate($date)
    {
        preg_match('/(\d{4})+(-)+(\d{2})+(-)+(\d{1,2})/', $date, $salida);
        if (count($salida) >= 1) {
            $salida = array_values(array_diff($salida, ['-']));
            if (!in_array($salida[1], range(1900, 2500))) {
                return false;
            }
            if (!in_array($salida[2], range(1, 12))) {
                return false;
            }
            if (!in_array($salida[3], range(1, cal_days_in_month(CAL_GREGORIAN, $salida[2], $salida[3])))) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    protected function last_id()
    {
        // return $this->con->insert_id;
        return $this->conn->lastInsertId();
    }

    public function format_string($string)
    {
        // $result=$this->con->real_escape_string($string); //mysql escape string no funciona en sql server
        $result = trim($string);

        $result = str_replace(
            array(
                "\\", "¨", "º", "~", "°", "¬",
                "#", "@", "|", "!", "\"", "`",
                "·", "$", "%",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                ".", "="
            ),
            '',
            $result
        );

        return mb_strtoupper($result, 'utf-8');
    }
}
