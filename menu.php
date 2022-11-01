<?php
require_once('controller/sessionController.php');
    $Session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
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
                      <li><a href="functions/cerrarSession.php"><span class="icon-exit"></span> Salir</a></li>
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
                        <li><a href="index.php"><span class="icon left icon-home2"></span>Inicio</a></li>
                    </ul>
                </nav>
            </div>
            <!-- <ul class="menu">
                <li><a class="btn-block" href="http://localhost:8080/ProyectoPortal-copia/index.php"><span class="icon left icon-home2"></span>Volver al menu</a></li>
            </ul> -->
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
  }
?>