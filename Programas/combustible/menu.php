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
                    <li><a title="Inicio" href="index"><span class="icon left icon-home2"></span><span class="menu-link">Inicio</span></a></li>
                    <li>
                        <span class="bar-active"></span>
                        <a title="Recepcion" href="recepcion"><span class="icon left icon-file-text"></span><span class="menu-link">Vales</span><span class="icon right caret down"></span></a>
                        <ul class="menu-sub-item">
                            <li><a href="crear-vale">Crear Vale</a></li>
                            <li><a href="consultar-vales">Consultar Vales</a></li>
                        </ul>
                    </li>
                    <li><a title="Orden de compra" href="orden-de-compra"><span class="icon left icon-list-numbered"></span><span class="menu-link">Orden de compra</span></a></li>
                    <li><a title="proveedores" href="proveedores"><span class="icon left icon-user-solid-circle"></span><span class="menu-link">Proveedores</span></a></li>
                    <li><a title="vehiculos" href="vehiculos"><span class="icon left icon-travel-taxi-cab"></span><span class="menu-link">Vehículos</span></a></li>
                    <!-- <li><a title="Talonarios" href="talonarios"><span class="icon left icon-briefcase"></span><span class="menu-link">Talonarios</span></a></li> -->
                    
                </ul>
                  </nav>
            </div>
            <ul class="menu">
                <li><a class="btn-block" href="http://192.168.122.180/portal-escobar/"><span class="icon left icon-home2"></span>Volver al menu</a></li>
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