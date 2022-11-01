<?php
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued() && $_SESSION['ADMINISTRATOR'] == 1){

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
                        <li><a title="Active Directory" href="index"><span class="icon left icon-home2"></span><span class="menu-link">ACTIVE DIRECTORY</span></a></li>
                        <li><a title="Usuarios" href="usuarios"><span class="icon left icon-user"></span><span class="menu-link">USUARIOS</span></a></li>
                        <li><a title="Programas" href="programas"><span class="icon left icon-embed2"></span><span class="menu-link">PROGRAMAS</span></a></li>
                        <li><a title="Accesos" href="accesos"><span class="icon left icon-unlocked"></span><span class="menu-link">ACCESOS</span></a></li>
                        <li><a title="Localidades" href="localidades"><span class="icon left icon-location"></span><span class="menu-link">LOCALIDADES</span></a></li>
                        <li><a title="Secretarias" href="secretarias"><span class="icon left icon-library"></span><span class="menu-link">SECRETARIAS</span></a></li>
                        <li><a title="Dependencias" href="dependencias"><span class="icon left icon-office"></span><span class="menu-link">DEPENDENCIAS</span></a></li>
                        <li><a title="Equipos" href="equipos"><span class="icon left icon-display"></span><span class="menu-link">EQUIPOS</span></a></li>
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
        }
        else{
            header('location: login');
        }
    }
    else{
        header('location: login');
    }
?>