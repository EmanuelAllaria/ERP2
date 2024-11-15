<?php
function inicio_fin_semana($fecha)
{

  $diaInicio = "Monday";
  $diaFin = "Sunday";

  $strFecha = strtotime($fecha);

  $fechaInicio = date('Y-m-d', strtotime('last ' . $diaInicio, $strFecha));
  $fechaFin = date('Y-m-d', strtotime('next ' . $diaFin, $strFecha));

  if (date("l", $strFecha) == $diaInicio) {
    $fechaInicio = date("Y-m-d", $strFecha);
  }
  if (date("l", $strFecha) == $diaFin) {
    $fechaFin = date("Y-m-d", $strFecha);
  }
  return array("fechaInicio" => $fechaInicio, "fechaFin" => $fechaFin);
}

$hoy = strtotime(date("Y-m-d"));
$desde = date("Y-m-d", strtotime('last Monday '));
$hasta = date("Y-m-d", strtotime('next Sunday '));
?>

<script>
  function menu_hamburguesa(argument) {
    var menu = document.getElementById("menu-colapsado");
    menu.classList.toggle("active");
  }
</script>
<header class="topbar">
  <nav class="navbar top-navbar navbar-expand-md navbar-dark contenedor-menu">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.html">
        <!-- Logo icon -->
        <b>
          <!-- Dark Logo icon -->
          <img src="./img/logo-icon.png" alt="homepage" class="dark-logo">
          <!-- Light Logo icon -->
          <a href="index.php"> <img src="./img/logo-light-icon.png" alt="homepage" class="light-logo">
          </a></b>
      </a>
    </div>

    <div class="button-menu-burguer" onclick="menu_hamburguesa();">
      <button title="Ver Menú"><i class="fa fa-bars"></i></button>
    </div>

    <div class="navbar-collapse" id="menu-colapsado">

      <ul class="navbar-nav mr-auto primer-lista">
        <li class="d-none d-md-block d-lg-block">
          <a href="index.php" class="p-l-15">
            <!--This is logo text-->
            <img src="./img/logo-light-text.png" alt="home" style="margin-top: 10px;" class="light-logo">
          </a>
        </li>



        <?php if ($_SESSION['tipo'] != 'Despacho' and $_SESSION['tipo'] != 'Deposito') { ?>
          <div class="btn-group" style="margin-left: 10px;">
            <button onclick="location.href='index.php?pagina=clientes'" type="button" class="btn btn-info d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="ti-user"></i><span style="font-size: 10px; margin-top: 4px">CLIENTES</span></button>
            <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu">
              <a href="index.php?pagina=clientes" class="dropdown-item">Listado de Clientes</a>
              <a href="index.php?pagina=clientes_mapa" class="dropdown-item">Mapa de Clientes</a>

              <?php if ($_SESSION['tipo'] != 'User') { ?> <a href="index.php?pagina=clientes_add" class="dropdown-item">Nuevo Cliente</a> <?php } ?>
            </div>
          </div>


          <div class="btn-group" style="margin-left: 10px;">
            <button type="button" onclick="location.href='index.php?pagina=pedidos'" class="btn btn-success d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="ti-shopping-cart-full"></i><span style="font-size: 10px; margin-top: 4px">VENTAS</span></button>
            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu">
              <a href="index.php?pagina=pedidos" class="dropdown-item">Listado de Ventas</a>
              <a href="index.php?pagina=pedidos_add_2" class="dropdown-item hide">Nuevo Venta</a>

            </div>
          </div>
          <div class="btn-group" style="margin-left: 10px;">
            <button type="button" onclick="location.href='index.php?pagina=pagos'" class="btn btn-danger d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="ti-money"></i><span style="font-size: 10px; margin-top: 4px">PAGOS</span></button>
            <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu">
              <li><a href="index.php?pagina=pagos" class="dropdown-item">Listado de Pagos</a></li>
              <?php if ($_SESSION['tipo'] != 'User') { ?> <li><a href="index.php?pagina=pagos_add" class="dropdown-item">Nuevo Pago</a></li> <?php } ?>
            </div>
          </div>
          <div class="btn-group" style="margin-left: 10px; margin-right: 30px;">
            <!-- <a href=""><button type="button" class="btn btn-warning d-flex flex-column justify-content-center align-items-center" style="font-size: 18px; width: 100px;"><i class="ti-direction-alt"></i><span style="font-size: 10px; margin-top: 4px">MOVIMIENTOS</span></button></a> -->
            <button type="button" onclick="location.href='index.php?pagina=transacciones'" class="btn btn-warning d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="ti-direction-alt"></i><span style="font-size: 10px; margin-top: 4px">MOVIMIENTOS</span></button>
            <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu">
              <a href="index.php?pagina=transacciones" class="dropdown-item">Transacciones</a>
              <a href="index.php?pagina=gastos" class="dropdown-item">Listado de Gastos</a>
            </div>
          </div>
        <?php } ?>
        <div class="btn-group" style="margin-left: 10px; margin-right: 30px;">
          <button type="button" onclick="location.href='index.php?pagina=estadocamion'" class="btn btn-success d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="ti-truck"></i><span style="font-size: 10px; margin-top: 4px">CARGAS</span></button>
          <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-angle-down"></i>
          </button>
          <div class="dropdown-menu">
            <a href="index.php?pagina=estadocamion" class="dropdown-item">Estado de Cargas</a>
            <a href="index.php?pagina=cargacamion" class="dropdown-item">Nuevo Carga</a>
            <a href="index.php?pagina=liquidaciones" class="dropdown-item">Liquidaciones</a>

          </div>
        </div>
        <div class="btn-group" style="margin-left: 10px;">
          <button onclick="location.href='index.php?pagina=fiscal'" type="button" class="btn btn-info d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="fa fa-file-invoice"></i><span style="font-size: 10px; margin-top: 4px">FISCAL</span></button>
          <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-angle-down"></i>
          </button>
          <div class="dropdown-menu">
            <a href="index.php?pagina=fiscal" class="dropdown-item">Fiscal Compras</a>
            <a href="index.php?pagina=fiscal_ventas" class="dropdown-item">Fiscal Ventas</a>
          </div>
        </div>



        <?php if ($_SESSION['tipo'] != 'Despacho' and $_SESSION['tipo'] != 'Deposito') { ?>
          <div class="btn-group" style="margin-left: 10px;">
            <button onclick="location.href='index.php?pagina=personal'" type="button" class="btn btn-primary d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="fa fa-user-o"></i><span style="font-size: 10px; margin-top: 4px">PERSONAL</span></button>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu">
              <a href="index.php?pagina=personal" class="dropdown-item">Listado del Personal</a>
              <a href="index.php?pagina=personal_add" class="dropdown-item">Nuevo Personal</a>
              <a href="index.php?pagina=adelanto" class="dropdown-item">Listado de adelantos</a>
              <a href="index.php?pagina=adelanto_add" class="dropdown-item">Nuevo Adelanto</a>
            </div>
          </div>
          <div class="btn-group" style="margin-left: 10px;">
            <button type="button" onclick="location.href='index.php?pagina=proveedores'" class="btn btn-info d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="fa fa-suitcase"></i><span style="font-size: 10px; margin-top: 4px">PROVEEDORES</span></button>
            <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu">
              <li><a href="index.php?pagina=proveedores" class="dropdown-item">Listado de Proveedores</a></li>
              <li><a href="index.php?pagina=proveedores_add" class="dropdown-item">Nuevo Proveedor</a></li>
              <li><a href="index.php?pagina=deudas" class="dropdown-item">Listado de Deudas a Proveedores</a></li>
              <li><a href="index.php?pagina=facturas" class="dropdown-item">Listado de Facturas</a></li>
              <li><a href="index.php?pagina=pagos_factura" class="dropdown-item">Listado de Pagos</a></li>
              <li><a href="index.php?pagina=cheques_rechazados" class="dropdown-item">Listado de Cheques Rechazados</a></li>
            </div>
          </div>
          <div class="btn-group" style="margin-left: 10px;">
            <button type="button" onclick="location.href='index.php?pagina=productos'" class="btn btn-primary d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="ti-package"></i><span style="font-size: 10px; margin-top: 4px">PRODUCTOS</span></button>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split d-flex flex-column justify-content-center align-items-center boton-icono" style="width: 20px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu">
              <li><a href="index.php?pagina=productos" class="dropdown-item">Productos</a></li>
              <?php if ($_SESSION['tipo'] != 'User') { ?> <li><a href="index.php?pagina=producto_add" class="dropdown-item">Nuevo Producto</a></li> <?php } ?>
              <li><a href="index.php?pagina=categorias" class="dropdown-item">Categorias</a></li>
              <?php if ($_SESSION['tipo'] != 'User') { ?> <li><a href="index.php?pagina=categoria_add" class="dropdown-item">Nueva Categoria</a></li> <?php } ?>
            </div>
          </div>
          <div class="btn-group" style="margin-left: 10px;">
            <button type="button" onclick="location.href='paginas/scann-pdf-image.php'" class="btn btn-primary d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;"><i class="fa fa-barcode"></i><span style="font-size: 10px; margin-top: 4px">SCANN</span></button>
          </div>
        <?php } ?>

        <div class="btn-group" style="margin-left: 10px;">
          <button type="button" onclick="location.href='index.php?pagina=dashboard'" class="btn btn-primary d-flex flex-column justify-content-center align-items-center boton-nombre" style="font-size: 18px; width: 80px;background: #d0070775 !important;"><i class="fa fa-chart-line"></i><span style="font-size: 10px; margin-top: 4px;">DATOS</span></button>
        </div>
      </ul>

      <ul class="navbar-nav my-lg-0">
        <li class="nav-item dropdown">
        <li class="nav-item dropdown u-pro">
          <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="index.html" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="./img/<?php echo $_SESSION['avatar'] ?>" alt="user" class=""> <span class="hidden-md-down"><?php echo $_SESSION['nombre'] ?> &nbsp;<i class="fa fa-angle-down"></i></span> </a>
          <div class="dropdown-menu dropdown-menu-right animated flipInY">
            <!-- text-->
            <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> Mi Perfil</a>
            <div class="dropdown-divider"></div>
            <!-- text-->
            <?php if ($_SESSION['tipo'] == 'Admin') {
              echo '<a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Configuracion</a>';
            } ?>
            <!-- text-->
            <div class="dropdown-divider"></div>
            <!-- text-->
            <a href="#" id="salir" class="dropdown-item"><i class="fa fa-power-off"></i> Salir</a>
            <!-- text-->
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>