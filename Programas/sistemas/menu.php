<?php
    require_once('functions/session.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
?>
<aside class="app-aside show">
      <div class="aside-content">
            <header class="aside-header visible-xs">
                  <button class="btn-account dropdown " role="button" name="gato">
                        <span class="icon icon-user"></span>
                        <span class="account-summary">
                              <span class="account-name" id="account-name"><?php echo $_SESSION['NOMBRE_USER']." ".$_SESSION['APELLIDO_USER']; ?></span>
                              <span class="account-description" id="account-description"><?php echo $_SESSION['NOMBRE_ACCESO_USER']; ?></span>
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
                    <li><a href="index"><span class="icon left icon-home2"></span>Home</a></li>
                    <li><a href="recepcion"><span class="icon left"><img class="ico" src="images/icons/ring.ico"></span>Recepcion</a></li>
                    <li><a href="usuarios"><span class="icon left icon-users"></span>Usuarios<!-- <span class="icon right icon-circle-down"></span> --></a>
<!--                          <ul class="menu-sub-item">
                              <li><a href="#">Subitem 1</a></li>
                              <li><a href="#">Subitem 2</a></li>
                        </ul> -->
                    </li>
                    <li><a href="habitaciones"><span class="icon left"><img class="ico" src="images/icons/room.ico"></span>Habitaciones<!-- <span class="icon right icon-circle-down"></span> --></a>
                        <!-- <ul class="menu-sub-item">
                              <li><a href="#">Subitem 1</a></li>
                              <li><a href="#">Subitem 2</a></li>
                        </ul> -->
                    </li>
                    <li><a href="hoteles"><span class="icon left icon-office"></span>Hoteles<!-- <span class="icon right icon-circle-down"></span> --></a>
                        <!-- <ul class="menu-sub-item">
                              <li><a href="#">Subitem 1</a></li>
                              <li><a href="#">Subitem 2</a></li>
                        </ul> -->
                    </li>
                    <li><a href="reservas"><span class="icon left icon-table2"></span>Reservas<!-- <span class="icon right icon-circle-down"></span> --></a>
                        <!-- <ul class="menu-sub-item">
                              <li><a href="#">Subitem 1</a></li>
                              <li><a href="#">Subitem 2</a></li>
                        </ul> -->
                    </li>
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
        }
        else{
            header('location: login');
        }
    }
    else{
        header('location: login');
    }
?>