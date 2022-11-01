<?php
// require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"])){
?>
<aside class="app-aside show">
      <div class="aside-content">
            <header class="aside-header visible-xs">
              <button class="btn-account dropdown " role="button" name="gato">
                    <span class="icon icon-user-solid-circle"></span>
                    <span class="account-summary">
                          <span class="account-name" id="account-name"><?php echo $_SESSION['NOMBRE_USER']." ".$_SESSION['APELLIDO_USER']; ?></span>
                          <!-- -->
                    </span>
                    <span class="account-icon">
                          <span class="caret "></span>
                    </span>
              </button>
              <div class="dropdown-item-collapse">
                  <ul class="dropdown-aside">
                        <!-- <li><a href="#"><span class="icon-user"></span> Perfil</a></li> -->
                        <li><a href="http://192.168.122.180/portal-escobar/functions/cerrarSession.php"><span class="icon-exit"></span> Salir</a></li>
                        <!-- <li role="separator" class="divider"></li> -->
                        <!-- <li><a href="#"><span class="icon-cog"></span> Configurar</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#"><span class="icon-bin"></span> Eliminar</a></li> -->
                  </ul>
              </div>
            </header>
            <div class="aside-menu">
                <nav class="stacked-menu" id="navegacion1">
                    <ul class="menu">
                    <!-- <span class="bar-active"></span> -->
                    <!-- <span class="icon right icon-circle-down"></span> -->
                        <li><a href="index.php"><span class="icon left icon-ticket"></span><span class="menu-link">INICIO</span></a></li>
                        <li><a href="consulta-tickets.php"><span class="icon left icon-search"></span><span class="menu-link">CONSULTAR</span></a></li>
                        <li><a href="mis-tickets.php"><span class="icon left icon-folder-open"></span><span class="menu-link">MIS TICKETS</span></a></li>
                        <?php if($_SESSION['TICKETS'] == 1){ ?><li><a href="motivos.php"><span class="icon left icon-list-numbered"></span><span class="menu-link">MOTIVOS</span></a></li> <?php } ?>
                        <?php if($_SESSION['TICKETS'] == 1){ ?><li><a href="tickets.php"><span class="icon left icon-history"></span><span class="menu-link">SOLICITUDES</span></a></li> <?php } ?>
                        <?php if($_SESSION['TICKETS'] == 1){ ?><li><a href="informes.php"><span class="icon left icon-stats-bars"></span><span class="menu-link">INFORMES</span></a></li> <?php } ?>
                    </ul>
                </nav>
            </div>
            <div class="" style="position: relative;overflow: hidden;overflow-y: auto;">
                <ul class="menu">
                    <nav class="stacked-menu" id="navegacion2">
                        <li><a class="btn-block" href="http://192.168.122.180/portal-escobar/"><span class="icon left icon-home2"></span><span class="menu-link">Volver al menu</span></a></li>
                    </nav>
                </ul>
            </div>
            
            <footer class="aside-footer">
                  <button class="btn-block btn-light" id="btn-modo">
                         <span class="icon icon-moon-o"></span> <span class="menu-link">Modo</span>
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