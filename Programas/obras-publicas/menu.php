<?php
    if(!isset($_SESSION)){
        require_once ('controller/sessionController.php');
        $session = new session();
    }
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
                    <!-- <span class="bar-active"></span> -->
                        <li><a title="INICIO" href="./"><span class="icon left icon-home2"></span><span class="menu-link">INICIO</span></a></li>
                        <li><a title="MODALIDADES" href="modalidades"><span class="icon left icon-books"></span><span class="menu-link">MODALIDADES</span></a></li>
                        <li><a title="FUENTES" href="fuentes"><span class="icon left icon-newspaper"></span><span class="menu-link">FUENTES</span></a></li>
                        <li><a title="TIPO OBRA" href="tipo-obra"><span class="icon left icon-office"></span><span class="menu-link">TIPOS DE OBRA</span></a></li>
                        <li><a title="ESTADOS" href="estados"><span class="icon left icon-list"></span><span class="menu-link">ESTADOS</span></a></li>
                        <li><a title="OBJETO GASTO" href="objeto-gasto"><span class="icon left icon-copy"></span><span class="menu-link">OBJETOS DE GASTO</span></a></li>
                        <li><a title="UNIDAD EJECUTORA" href="unidad-ejecutora"><span class="icon left icon-file-text"></span><span class="menu-link">UNIDAD EJECUTORA</span></a></li>
                        <li><a title="PROYECTOS" href="proyectos"><span class="icon left icon-library"></span><span class="menu-link">PROYECTOS</span></a></li>
                        <li><a title="JURISDICCIONES" href="jurisdicciones"><span class="icon left icon-hammer2"></span><span class="menu-link">JURISDICCIONES</span></a></li>
                        <li><a title="PROVEEDORES" href="proveedores"><span class="icon left icon-profile"></span><span class="menu-link">PROVEEDORES</span></a></li>
                        <li><a title="AFECTACIONES" href="afectaciones"><span class="icon left icon-price-tags"></span><span class="menu-link">AFECTACIONES</span></a></li>
                        <!-- <li><a title="ORDENES DE PAGO" href="orden-pago"><span class="icon left icon-coin-dollar"></span><span class="menu-link">ORDENES DE PAGO</span></a></li> -->
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
        header('location: login');
    }
?>