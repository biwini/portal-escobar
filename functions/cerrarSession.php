<?php
//Reanudo la session ya existente, la cierro y vuelvo a la pagina de registro.
	session_start();
if(isset($_SESSION)){
	session_destroy();
	session_unset();
	unset($_SESSION);
	header("location: ../login.php");
}
else{
	header("location: ../login.php");
}
?>