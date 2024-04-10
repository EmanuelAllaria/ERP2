<?php
$estemes = date('Y-m');
$clientes_nuevos = $link->query("SELECT * FROM `clientes` WHERE `estado_clientes` LIKE '1' AND `cuando_clientes` LIKE '$estemes%' ");
$cuento_nuevos = mysqli_num_rows($clientes_nuevos);

if (isset($_GET['e']) && $_GET['e'] == 'ceok') {
    echo '<div class="callout callout-success">
    <h4>Correcto!</h4>

    <p>El ingreso de Caja fue realizado correctamente.</p>
  </div>';
}

if (isset($_GET['e']) && $_GET['e'] == 'csok') {
    echo '
  <div class="callout callout-success">
    <h4>Correcto!</h4>
    <p>La salida de Caja fue realizada correctamente.</p>
  </div>';
}
if (isset($_GET['e']) && $_GET['e'] == 'ccdok') {
    echo '
    <div class="callout callout-success">
      <h4>Correcto!</h4>
      <p>La Caja Diaria se cerró correctamente.</p>
    </div>';
}
if (isset($_GET['e']) && $_GET['e'] == 'crcok') {
    echo '
      <div class="callout callout-success">
        <h4>Correcto!</h4>
        <p>El retiro de Caja se realizo correctamente.</p>
      </div>';
}
?>
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Escritorio</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                <li class="breadcrumb-item "><a href="#">Escritorio</a></li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block">
                <input type="text" class="form-control" placeholder="Buscar...">
            </form>
        </div>
    </div>

    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <a href="index.php?pagina=clientes">
                                <div>
                                    <h3><i class="icon-screen-desktop"></i></h3>
                                    <p class="text-muted">Clientes Nuevos</p>
                                </div>
                            </a>
                            <div class="ml-auto">
                                <h2 class="counter text-primary"><?php echo $cuento_nuevos; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <a href="index.php?pagina=pedidos">
                                <div>
                                    <h3><i class="icon-note"></i></h3>
                                    <p class="text-muted">Pedidos de Hoy</p>
                                </div>
                            </a>
                            <div class="ml-auto">
                                <h2 class="counter text-cyan">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-cyan" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <a href="index.php?pagina=pagos">
                                <?php
                                    $hoy = date("Y-m-d");
                                    $total_gasto = 0;
                                      $gastosHoy = $link->query("SELECT * FROM gastos WHERE fecha_gasto='$hoy'");
                                      while ($gasto = mysqli_fetch_array($gastosHoy)) {
                                        if ($gasto['monto_gasto'] !== null) {
                                          $total_gasto = $total_gasto + intval($gasto['monto_gasto']);
                                        }
                                      }

                                        $total_formateado = number_format($total_gasto, 0, ',', '.');
                                ?>
                                <div>
                                    <h3><i class="icon-bag"></i></h3>
                                    <p class="text-muted">Gastos de Hoy</p>
                                </div>
                            </a>
                            <div class="ml-auto">
                                <h2 class="counter text-success">$<?=$total_formateado?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-start">
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Nueva Factura de Proveedor</h4>
                </div>
                <div class="card-body">

                    <form id="facturaadd" style="width:100%" name="facturaadd" action="procesos/caja.php" method="post">
                        <div class="row" id="form-factura">
                            <input name="accion" value="add" type="hidden">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Seleccione Proveedor</label>

                                    <select id="provee_factura" class="form-control">
                                        <option value='' selected disabled>Selecione Proveedor</option>
                                        <?php
                                        $con_prov = $link->query("SELECT * FROM proveedores WHERE estado_proveedor = '1' ORDER BY razon_com_proveedor ASC");
                                        while ($row = mysqli_fetch_array($con_prov)) {
                                            echo '<option value="' . $row['id_proveedor'] . '">' . utf8_encode($row['razon_com_proveedor']) . ' (' . utf8_encode($row['notas_proveedor']) . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nro_factura">Nº Factura</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-outdent"></i></span>
                                        </div>
                                        <input id="nro_factura" name="nro_factura" placeholder="Ingrese el Nº de factura" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese monto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-money"></i></span>
                                        </div>
                                        <input id="monto_factura" name="monto_factura" placeholder="Ingrese monto" class="form-control" step="any" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label">Tipo </label>
                                <select id="tipo_factura" class="form-control">
                                    <option selected>Selecione Tipo </option>
                                    <?php
                                    $con_tipocomp = $link->query("SELECT * FROM `tipo_comprobantes` WHERE `estado_comprobantes` = 1 ORDER BY `tipo_comprobantes`.`nombre_comprobantes` ASC");
                                    while ($row = mysqli_fetch_array($con_tipocomp)) {
                                        echo '<option value="' . $row['id_comprobantes'] . '">' . $row['nombre_comprobantes'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="detalle_factura" name="detalle_factura" rows="4" placeholder="Ingrese el detalle de la misma" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="button" onclick="add_factura()">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Cargar Pago a Proveedor</h4>
                </div>
                <div class="card-body">

                    <form id="pagofacturaadd" style="width:100%" name="pagofacturaadd" method="post">
                        <div class="row" id="form-pago">
                            <input name="accion" value="add" type="hidden">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Seleccione Proveedor</label>

                                    <select id="provee_pago" class="form-control" onchange="cambiafacturasprove()">
                                        <option value='' selected disabled>Selecione Proveedor</option>
                                        <?php
                                        $con_prov = $link->query("SELECT * FROM proveedores WHERE estado_proveedor = '1' ORDER BY razon_com_proveedor ASC");
                                        while ($row = mysqli_fetch_array($con_prov)) {
                                            echo '<option value="' . $row['id_proveedor'] . '">' . mb_convert_encoding($row['razon_com_proveedor'], 'UTF-8', 'ISO-8859-1') . ' (' . mb_convert_encoding($row['notas_proveedor'], 'UTF-8', 'ISO-8859-1') . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nro_factura">Nº Factura</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-outdent"></i></span>
                                        </div>
                                        <select id="nro_factura_pago" class="form-control" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese fecha</label>
                                    <div class="input-group mb-3">
                                        <input id="fecha_factura_pago" name="fecha" placeholder="Ingrese fecha" class="form-control" step="any" type="date" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese banco</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-bank"></i></span>
                                        </div>
                                        <input id="banco_factura_pago" name="banco" placeholder="Ingrese banco" class="form-control" step="any" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese numero de cheque</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-money-check"></i></span>
                                        </div>
                                        <input id="numero_cheque_factura_pago" name="numero_cheque" placeholder="Ingrese numero de cheque" class="form-control" step="any" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese fecha de emision</label>
                                    <div class="input-group mb-3">
                                        <input id="fecha_emision_factura_pago" name="fecha_emision" placeholder="Ingrese fecha de emision" class="form-control" step="any" type="date" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese fecha de cobro</label>
                                    <div class="input-group mb-3">
                                        <input id="fecha_cobro_factura_pago" name="fecha_cobro" placeholder="Ingrese fecha de cobro" class="form-control" step="any" type="date" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese titular</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input id="titular_factura_pago" name="titular" placeholder="Ingrese titular" class="form-control" step="any" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese cuit</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-address-card"></i></span>
                                        </div>
                                        <input id="cuit_factura_pago" name="cuit" placeholder="Ingrese cuit" class="form-control" step="any" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese monto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-money"></i></span>
                                        </div>
                                        <input id="monto_factura_pago" name="monto" placeholder="Ingrese monto" class="form-control" step="any" type="number">
                                        <input hidden id="total_factura_pago" name="total" type="number">
                                        <input hidden id="facturas_factura_pago" name="facturas" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese origen</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-earth-americas"></i></span>
                                        </div>
                                        <input id="origen_factura_pago" name="origen" placeholder="Ingrese origen" class="form-control" step="any" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="detalle_factura_pago" name="detalle" rows="4" placeholder="Ingrese el detalle del mismo" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="button" onclick="add_factura_pago()">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Ingreso de gastos</h4>
                </div>
                <div class="card-body">

                    <form id="precioadd" style="width:100%" name="precioadd" method="post">
                        <div class="row" id="form-alqui">
                            <input name="accion" value="add" type="hidden">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="movimiento">Tipo de Movimiento</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-exchange"></i></span>
                                        </div>
                                        <select class="form-control" id="movimiento" name="tipo" aria-describedby="basic-addon2">
                                            <option value="0">Salida</option>
                                            <option value="1">Sueldos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comprobante">Nº Comprobante</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-outdent"></i></span>
                                        </div>
                                        <input id="comprobante" name="comprobante" id="comprobante" placeholder="Ingrese el Nº de comprobante" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese monto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-money"></i></span>
                                        </div>
                                        <input id="monto" name="monto" placeholder="Ingrese monto" class="form-control" step="any" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="detalle-alqui" name="detalle" rows="4" placeholder="Ingrese el detalle del mismo" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="button" id="agregarGasto">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card ">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white"> Ingreso de Comprobantes</h4>
                </div>
                <div class="card-body">

                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Seleccione Proveedor</label>

                                    <select id="provee_card" onchange="llenaprod();" class="form-control">
                                        <option value='' selected disabled>Selecione Proveedor</option>
                                        <?php
                                        $con_prov = $link->query("SELECT * FROM proveedores WHERE estado_proveedor = '1' ORDER BY razon_com_proveedor ASC");
                                        while ($row = mysqli_fetch_array($con_prov)) {
                                            $id_proveedor = $row['id_proveedor'];
                                            echo '<option value="' . $row['id_proveedor'] . '">' . utf8_encode($row['razon_com_proveedor']) . ' (' . utf8_encode($row['notas_proveedor']) . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Fecha de Comprobante</label>
                                    <input type="date" id="fecha_card" value="<?php echo date('Y-m-d') ?>" class="form-control ">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Tipo Comprobante</label>
                                    <select id="tipocompro_card" class="form-control">
                                        <option selected>Selecione Tipo de Comprobante</option>
                                        <?php
                                        $con_tipocomp = $link->query("SELECT * FROM `tipo_comprobantes` WHERE `estado_comprobantes` = 1 ORDER BY `tipo_comprobantes`.`nombre_comprobantes` ASC");
                                        while ($row = mysqli_fetch_array($con_tipocomp)) {
                                            echo '<option value="' . $row['id_comprobantes'] . '">' . $row['nombre_comprobantes'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Nº Comprobante</label>
                                    <input type="number" id="comprobante_num_card" class="form-control " placeholder="Ingrese el Nº de comprobante">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Producto</label>
                                    <select id="producto_card" name="producto_card" class="form-control producto" required="" disabled>
                                        <option disabled selected>Seleccione un Producto </option>
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Cantidad</label>
                                    <input type="number" id="cantproducto_card" class="form-control " value="1" min="1">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" style="margin-top: 30px;">
                                    <label class="control-label"></label>
                                    <button onclick="llena_canasta_compra_stock()" class="btn btn-success"> +</button>
                                </div>
                            </div>
                            <div id="list_prod_card" style="width:100%"></div>
                            <!--/span-->
                        </div>
                        <div class="modal" id="myModal">
                          <div class="modal-dialog">
                            <div class="modal-content">

                              <!-- Encabezado del modal -->
                              <div class="modal-header">
                                <h4 class="modal-title" id="tituloModal">Editar Producto - </h4>
                                <h4 class="modal-title" id="idCanasta"></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <!-- Contenido del modal -->
                              <div class="modal-body">
                                <div class="row">
                                    <h4 class="col-md-12">Producto: </h4>
                                    <h4 class="col-md-12" id="producto"></h4>
                                </div>
                                <div class="row">
                                    <h4 class="col-md-12">Cantidad: </h4>
                                    <input type="number" id="ncantproducto_card" class="form-control" min="1">
                                </div>
                                
                              </div>

                              <!-- Pie del modal -->
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="edita_canasta_compra_stock();">Guardar cambios</button>
                              </div>

                            </div>
                          </div>
                        </div>
                        <!--/row-->
                        <hr>
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Ingresa a Stock</label>
                                    <select id="stock_card" class="form-control">
                                        <option selected value="1">SI</option>
                                        <option value="0">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Ingreso con Vencimiento</label>
                                    <input type="date" id="vencimiento_card" class="form-control ">
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="form-actions">
                        <button id="ingresar_compra" class="btn btn-success"> <i class="fa fa-check"></i> Ingresar</button>
                        <a href="index.php" class="btn btn-inverse">Cancelar</a>
                    </div>

                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-2 col-md-12">
            <div class="row">
                <!-- Column -->
                <div class="col-md-12">
                    <div class="card bg-cyan text-white">
                        <div class="card-body ">
                            <div class="row weather">
                                <div class="col-6 ">
                                    <div class="display-4" id="temperatura" style="float:left"></div>°C<div class="clearfix"></div>
                                    <p class="text-white">Bahia Blanca, Buenos Aires</p>
                                </div>
                                <div class="col-6 text-right">
                                    <center>
                                        <h1 class="m-b-" id="icono" style="max-width: 70px; margin: 0px; padding: 0px;fill: white;"></h1>
                                    </center>
                                    <b class="text-white" id="estado_clima"></b>
                                    <p class="op-5">
                                        <script>
                                            var mydate = new Date();
                                            var year = mydate.getYear();
                                            if (year < 1000);
                                            year += 1900;
                                            var day = mydate.getDay();
                                            var month = mydate.getMonth();
                                            var daym = mydate.getDate();
                                            if (daym < 10)
                                                daym = "0" + daym;
                                            var dayarray = new Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
                                            var montharray = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                            document.write(dayarray[day] + "  " + daym + " de " + montharray[month]);
                                        </script>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <h5 class="card-title">Alertas de Stock</h5>
                            <div class="steamline m-t-40">
                                <div class="sl-item">
                                    <div class="sl-left"> <img class="img-circle" alt="user" src="../assets/images/users/3.jpg" onerror="this.src='img/product.png'"> </div>
                                    <div class="sl-right">
                                        <div><a href="javascript:void(0)">COD: CODIGO</a> <span class="sl-date">Unidades</span></div>
                                        <div class="desc">Titulo de Producto
                                            <br><a href="javascript:void(0)" class="btn m-t-10 m-r-5 btn-rounded btn-outline-success">Cargar Stock</a>
                                            <a href="javascript:void(0)" class="btn m-t-10 btn-rounded btn-outline-danger">Desactivar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

</div>