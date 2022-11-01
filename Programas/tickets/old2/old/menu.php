<?php
require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"])){
?>
<aside class="app-aside show">
      <div class="aside-content">
            <header class="aside-header visible-xs">
                  <button class="btn-account dropdown " role="button" name="gato">
                        <span class="icon icon-user"></span>
                        <span class="account-summary">
                              <span class="account-name" id="account-name"><?php echo $_SESSION['NOMBRE_USER']." ".$_SESSION['APELLIDO_USER']; ?></span>
                              <!-- <span class="account-description" id="account-description"><?php echo $_SESSION['NOMBRE_ACCESO_USER']; ?></span> -->
                        </span>
                        <span class="account-icon">
                              <span class="caret "></span>
                        </span>
              </button>
              <div class="dropdown-item-collapse">
                  <ul class="dropdown-aside">
<!--                                <li><a href="#"><span class="icon-user"></span> Perfil</a></li> -->
                      <li><a href="../../functions/cerrarSession.php"><span class="icon-exit"></span> Salir</a></li>
                      <li role="separator" class="divider"></li>
<!--                                <li><a href="#"><span class="icon-cog"></span> Configurar</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="#"><span class="icon-bin"></span> Eliminar</a></li> -->
                </ul>
              </div>
            </header>
            <div class="aside-menu">
                  <nav class="stacked-menu" id="navegacion1">
                        <ul class="menu">
                            <li><a href="index.php"><span class="icon left icon-ticket"></span>Inicio</a></li>
                            <li><a href="consulta-tickets.php"><span class="icon left icon-search"></span>Consultar</a></li>
                            <li><a href="mis-tickets.php"><span class="icon left icon-folder-open"></span>Mis Tickets</a></li>
                            <?php if($_SESSION['TICKETS'] == 1){ ?><li><a href="motivos.php"><span class="icon left icon-list-numbered"></span>Motivos<!-- <span class="icon right icon-circle-down"></span> --></a></li> <?php } ?>
        <!--                    <ul class="menu-sub-item">
                                    <li><a href="#">Subitem 1</a></li>
                                    <li><a href="#">Subitem 2</a></li>
                                </ul> -->
                            </li>
                            <?php if($_SESSION['TICKETS'] == 1){ ?><li><a href="tickets.php"><span class="icon left icon-history"></span>Solicitudes</a></li> <?php } ?>
                            <?php if($_SESSION['TICKETS'] == 1){ ?><li><a href="informes.php"><span class="icon left icon-stats-bars"></span>Informes</a></li> <?php } ?>
                        </ul>
                  </nav>
            </div>
            <ul class="menu">
                <li><a class="btn-block" href="http://192.168.122.180/portal-escobar/index.php"><span class="icon left icon-home2"></span>Volver al menu</a></li>
            </ul>
            <footer class="aside-footer">
                    <button class="btn-block btn-light" id="btn-modo">
                        Modo Nocturno <span class="icon icon-IcoMoon"></span>
                    </button>
            </footer>
      </div>
</aside>
<?php
            }else{
                header("location: ../../index.php");
            }
        }else{
            header("location: ../../index.php");
        }
  }
?>