<?php
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
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
                        <li><a title="Crear Liquidacion" href="crear-liquidacion"><span class="icon left icon-file-text"></span><span class="menu-link">Crear Liquidación</span></a></li>
                        <li><a title="Consultar Liquidacion" href="consultar-liquidaciones"><span class="icon left icon-search"></span><span class="menu-link">Consultar Liquidación</span></a></li>
                    </ul>
                </nav>
            </div>
            <ul class="menu">
                <li><a class="" href="http://192.168.122.180/portal-escobar/"><span class="icon left icon-home2"></span><span class="menu-link">Volver al menu</span></a></li>
            </ul>
            <footer class="aside-footer">
                  <button class="btn-block btn-light" id="btn-modo">
                         <span class="icon icon-moon-o"></span> <span class="menu-link">Modo</span>
                  </button>
            </footer>
      </div>
</aside>
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