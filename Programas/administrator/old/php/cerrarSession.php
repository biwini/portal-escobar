<?php
//Reanudo la session ya existente, la cierro y vuelvo a la pagina 1.
	session_start();
if(isset($_SESSION)){
	session_destroy();
	session_unset();
	header("location: ../pagina1.php");
}
else{
	header("location: ../pagina1.php");
}
?>