<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'php-mailer/src/Exception.php';
	require 'php-mailer/src/PHPMailer.php';
	require 'php-mailer/src/SMTP.php';

	class mail {
		private $Host;
		private $Port;
		private $Username;
		private $Password;

		public $Subject;
		public $Body;
		public $Address;
		public $Name;

		function __construct(){
			$this->Host = 'smtp.office365.com';
			$this->Port = 587;
			$this->Username = 'no-responder@escobar.gob.ar';
			$this->Password = 'NoresponderMuni2019';
		}

		public function send(){

			$Mailer  = new PHPMailer();	
			
			$Mailer->IsSMTP(); // Indica que se usará SMTP para enviar el correo

			//$Mailer->SMTPDebug  = 1;	// Activar los mensajes de depuración, 
			// muy útil para saber el motivo si algo sale mal
			// 1 = errores y mensajes
			// 2 = solo mensajes entre el servidor u la clase PHPMailer

			$Mailer->SMTPAuth = true;// Activar autenticación segura a traves de SMTP, necesario para gmail
			$Mailer->SMTPSecure = "tls";// Indica que la conexión segura se realizará mediante TLS
			$Mailer->Host = $this->Host;// Asigna la dirección del servidor smtp de GMail
			$Mailer->Port = $this->Port;// Asigna el puerto usado por GMail para conexion con su servidor SMTP
			
			$Mailer->Username = $this->Username;  // Indica el usuario de gmail a traves del cual se enviará el correo
			$Mailer->Password = $this->Password;// GMAIL password
			$Mailer->SetFrom($this->Username, 'Municipalidad de Escobar'); //Asignar la dirección de correo y el nombre del contacto que aparecerá cuando llegue el correo
			
			$Mailer->Subject = $this->Subject; //Asignar el asunto del correo
			$Mailer->MsgHTML($this->Body); //Si deseas enviar un correo con formato HTML debes descomentar la linea anterior
			$Mailer->AddAddress($this->Address, $this->Name); //Indica aquí la dirección que recibirá el correo que será enviado

			$Mailer->CharSet = 'UTF-8';

			return ($Mailer->Send()) ? 'Mail Send': 'Mail Error: '. $Mailer->ErrorInfo ;
		}
	}

?>