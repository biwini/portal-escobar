<?php 
require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
          if(isset($_SESSION["TICKETS"])){
?>
<header class="app-header">
    <div class="navbar">
        <div class="navbar-header">
            <a href="index" class="navbar-brand">
                <img src="images/logo-escobar.png" class="logo" alt="Logo - GetOn">
            </a>
        </div>
        <div class="top-bar-list">
            <div class="top-bar-item top-bar-item-full visible-xs"></div> <!-- Espacio -->
            <div class="top-bar-item">
                <button type="button" class="btn-toggle" data-toggle="aside">
                    <span class="sr-only">Menu</span>
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div class="top-bar-item top-bar-item-full hidden-xs"></div> <!-- Espacio -->
            <div class="top-bar-item hidden-xs">
                <button class="dropdown form-control">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <span class="icon icon-user"></span>
                        <span class="account-summary">
                              <span class="account-name" id="account-name"><?php echo $_SESSION['NOMBRE_USER']." ".$_SESSION['APELLIDO_USER']; ?></span>
                              <!-- <span class="account-description" id="account-description"><?php echo $_SESSION['NOMBRE_ACCESO_USER']; ?></span> -->
                        </span>
                        <span class="account-icon">
                              <span class="caret "></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu burbuja">
                        <li><a href="../../functions/cerrarSession.php"><span class="icon-exit"></span> Salir</a></li>
                        <li role="separator" class="divider"></li>
                    </ul>
                </button>
                <div class="top-bar-item-separate"></div> <!-- Padding -->
            </div>
        </div>
    </div>
</header>

<?php
      }
      else{
        header("location: ../../index.php");
      }
    }
    else{
      header("location: ../../index.php");
    }
  }
?>