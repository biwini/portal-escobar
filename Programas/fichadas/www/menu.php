<?php
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued() && isset($_SESSION['FICHADAS'])){

            $p = $_SESSION['FICHADAS'];

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
        <?php if($p == 1){ ?><li><a title="Fichadas" href="index"><span class="icon left icon-home2"></span><span class="menu-link">INCIO</span></a></li> <?php } ?>
        <?php if($p == 1){ ?><li><a title="Horarios" href="horarios"><span class="icon left icon-clock"></span><span class="menu-link">HORARIOS</span></a></li> <?php } ?>
        <?php if($p == 1 || $p == 2){ ?><li><a title="Empleados" href="empleados"><span class="icon left icon-user"></span><span class="menu-link">EMPLEADOS</span></a></li> <?php } ?>
        <?php if($p == 1){ ?><li><a title="Relojes" href="relojes"><span class="icon left icon-stopwatch"></span><span class="menu-link">RELOJES</span></a></li> <?php } ?>
        <?php if($p == 1 || $p == 2){ ?><li><a title="Licencias" href="licencias"><span class="icon left icon-calendar"></span><span class="menu-link">LICENCIAS</span></a></li> <?php } ?>
        <?php if($p == 1 || $p == 2){ ?><li><a title="Fichadas" href="Fichadas"><span class="icon left icon-stopwatch"></span><span class="menu-link">FICHADAS</span></a></li> <?php } ?>
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
<div class="message">
    <span></span>
</div>
<?php
        }
        else{
            header('location: login');
        }
    }
    else{
        header('location: login');
    }
?>