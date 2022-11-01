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
                      <li><a href="functions/cerrarSesion"><span class="icon-exit"></span> Salir</a></li>
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
                            <li><a class="pointer"><span class="icon left icon-users"></span>USUARIOS</a></li>
                            <li><a class="pointer"><span class="icon left icon-folder"></span>PROGRAMAS<!-- <span class="icon right icon-circle-down"></span> --></a>
        <!--                    <ul class="menu-sub-item">
                                    <li><a href="#">Subitem 1</a></li>
                                    <li><a href="#">Subitem 2</a></li>
                                </ul> -->
                            </li>
                            <li><a class="pointer"><span class="icon left icon-location"></span>LOCALIDADES</a></li>
                            <li><a class="pointer"><span class="icon left icon-home2"></span>DEPENDENCIAS</a></li>
                            <li><a class="pointer"><span class="icon left icon-home2"></span>SECRETARIAS</a></li>
                            <li><a class="pointer"><span class="icon left icon-star-full"></span>ACCESOS</a></li>
                            <li><a class="pointer"><span class="icon left icon-list"></span>AUDITORIAS</a></li>
                            <li><a class="pointer"><span class="icon left icon-list"></span>COMPARTIDAS</a></li>
                            <li><a class="pointer"><span class="icon left icon-list"></span>EQUIPOS</a></li>
                        </ul>
                  </nav>
            </div>
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