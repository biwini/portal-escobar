<?php
    require_once('controller/sessionController.php');
    $Session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
        	if($_SESSION['UNREGISTRED']){
        		header("location: registro");
        	}
			if(isset($_SESSION["PAGINAS_PERMITIDAS"])){
				if(count($_SESSION["PAGINAS_PERMITIDAS"]) == 1){
					header("location:".$_SESSION["PAGINAS_PERMITIDAS"][0]."");
					die();
				}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Portal Escobar - Menu</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/app-main.css?v=<?php echo time(); ?>">
    <style type="text/css">
        #header {
            margin:auto;
            width:500px;
            font-family:Arial, Helvetica, sans-serif;
        }
        .wrap {text-align: center; margin: 15px auto;}
		.resp {
			display: inline-block;
			text-align: center;
			width: 21%;
			margin-right: 1%;
			margin-top: 1%;
		}
		.resp.fixed {height: 150px; width: 23rem; margin-right: 10px;}

		.resp {background-color: #2EFEC8}
		.hola {
			margin-top: 65px;
		}
		.wrap a{
			text-decoration: none !important;
			color: black;
			font: icon;
		}
    </style>
</head>
<body>
    <?php 
    require('header.php');
    require('menu.php');
    ?>
    <main class="app-main">
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-home2"></span> PANEL DE APLICACIONES</h1>
            </header>
            <section>
                <div class="card">
                    <div class="row">
                        <div class="message">
                            <span></span>
                        </div>
                        <div class="col-md-12">
                        	<div class="wrap">
                        		<?php foreach (array_combine($_SESSION["PAGINAS_PERMITIDAS"], $_SESSION["CANT_PERMISOS"]) as $Url=>$nombres){
									echo '<a href=\''.$Url.'\' title=\''.$nombres.'\' class=\'resp fixed\'><div class=\'hola\'>'.$nombres.'</div></a>';
								} ?>
							</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js?v=<?php echo time(); ?>"></script>
    <script src="js/sweetalert.min.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript">
    	</script>
    </body>
</html>
<!-- 			<!DOCTYPE html>
				<html>
				<head>
					<title>Portal Escobar - Menu</title>
					<meta name="theme-color" content="white"/>
				   	<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="Description" content="Portal Escobar - Menu">
					<meta name="theme-color" content="#fff"/>
				    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
					<link href="css/bootstrap.min.css" rel="stylesheet">
					<link href="css/bootstrap-theme.min.css" rel="stylesheet">
					<link rel="stylesheet" href="css/planilla.css?v=<?php echo time(); ?>">
					<link href="css/login.css" rel="stylesheet">
					<style type="text/css">
						.wrap {text-align: center; margin: 15px auto;}
						.resp {display: inline-block; text-align: left; width: 21%; margin-right: 1%;}
						.resp.fixed {width: 100px; margin-right: 10px;}
					</style>
				</head>
				<body style="background-color: lightgrey;">
					<div class="container">
						<div class="row text-center login-page" style="max-width: 500px;padding-top: 5%;">
							<div class="col-md-12 login-form" style="background-color: white;">
								<div class="row">
									<a href="functions/cerrarSession.php" class="pull-right" title="Cerrar sesion">Cerrar Sesion</a>
								</div>
								<div class="row">
									<img class=" img img-responsive" src="imagenes/logo-municipalidad-de-escobar.jpg" style="width: 50%; margin-left: auto; margin-right: auto; display: block;" alt="logo">
								</div>
								<div class="">
									<h3 class="pull-right modal-title">Bienvenido <?php echo $_SESSION["NOMBRE_USER"];?></h3><br>
									<h3  class="center-block">Lista de programas : </h3>
								</div>
								<ul class="nav navbar list-group">
									<?php 
										foreach (array_combine($_SESSION["PAGINAS_PERMITIDAS"], $_SESSION["CANT_PERMISOS"]) as $Url=>$nombres){
											echo "<li class='list-inline-item'><a href='".$Url."' title='".$nombres."'>".$nombres."</a></li>";
										}
									?>
								</ul>
							</div>
						</div>
					</div>
				</body>
			</html> -->
<?php 
			}else{
				if(isset($_SESSION["PAGINA_PERMITIDA"])){
					// $url = substr($_SESSION["PAGINA_PERMITIDA"],3)."";
					$url = $_SESSION["PAGINA_PERMITIDA"];
					header("location:".$url."");
				}
			}
		}else{
			header("location: login.php");
		}
	}else{
		header("location: login.php");
	}
?>