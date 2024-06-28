<?php if (isset($_GET['add']) && $_GET['add'] == '1') {
  include('clientes_add.php');
} else { ?><div class="container-fluid">

    <div class="row page-titles">
      <div class="col-md-12">
        <h4 class="text-white">Listado de Pagos</h4>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
          <li class="breadcrumb-item"><a href="#">Pagos</a></li>
        </ol>
      </div>
      <div class="col-md-6 text-right">
        <form class="app-search d-none d-md-block d-lg-block" method="get">
          <input type="hidden" name="pagina" value="pagos">
          <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar...">
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Listado</h4>
            <div class="row">
              <div class="col-md-2"><small class="form-control-feedback"> Desde </small>
                <input class="form-control filtro" type="date" id="d" name="d" value="<?php if (isset($_GET['d'])) {
                                                                                        echo $_GET['d'];
                                                                                      } else {
                                                                                        echo date('Y-m-01');
                                                                                      } ?>">
              </div>
              <div class="col-md-2"><small class="form-control-feedback"> Hasta </small>
                <input class="form-control filtro" type="date" id="h" name="h" value="<?php if (isset($_GET['h'])) {
                                                                                        echo $_GET['h'];
                                                                                      } else {
                                                                                        echo date('Y-m-d');
                                                                                      } ?>">
              </div>
              <div class="col-md-2"><small class="form-control-feedback"> Proveedor </small><br>
                <select class="form-control" id="proveedorsel">
                  <option value='' selected>Todos</option>
                  <?php
                  $busca_prov = $link->query("SELECT razon_com_proveedor as nombre, id_proveedor as id FROM `proveedores` WHERE `estado_proveedor` LIKE '1'");
                  while ($row = mysqli_fetch_array($busca_prov)) {
                    echo '<option value="' . $row['id'] . '"';
                    if (isset($_GET['p']) && $_GET['p'] == $row['id']) {
                      echo ' selected ';
                    }
                    echo '>' . $row['nombre'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-2"><small class="form-control-feedback">Forma pago </small><br>
                <select class="form-control" id="formasel">
                  <option value='' selected>Todas</option>
                  <?php
                  $busca_f = $link->query("SELECT * FROM `formas_pagos` WHERE `estado_formapago` LIKE '1'");
                  while ($row = mysqli_fetch_array($busca_f)) {

                    echo '<option value="' . (strtolower($row['detalle_formapago']) === 'mercado pago' ? 'mp' : strtolower($row['detalle_formapago'])) . '"';
                    if (isset($_GET['f']) && $_GET['f'] == strtolower($row['detalle_formapago'])) {
                      echo ' selected ';
                    }
                    echo '>' . $row['detalle_formapago'] . '</option>';
                  }
                  ?>
                </select>

              </div>
              <div class="col-md-2" style="margin-top:17px">
                <a href="#" onclick="filtrar_vende()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['buscar'])) { ?><a href="index.php?pagina=pagos">Quitar Filtros</a><?php } ?>
              </div>

              <div class="col-md-2" style="align-self: center;">
                <div id="totales_formas">
                  <?php
                  $busqueda = '';
                  $busquedaProveedor = '';
                  if (isset($_GET['buscar'])) {
                    $palabra = $_GET['buscar'];
                    $busqueda = " and facturas_cheques.numero_cheque = '$palabra'";
                  }
                  if (isset($_GET['d']) && $_GET['d'] != '') {
                    $desde = $_GET['d'];
                    $busqueda = $busqueda . ' and facturas.fecha >= "' . $desde . '"';
                  } else {
                    $desde = date('Y-m-01');
                  }
                  if (isset($_GET['h']) && $_GET['h'] != '') {
                    $hasta = date('Y-m-d', strtotime($_GET['h'] . ' +1 day'));;
                    $busqueda = $busqueda . ' and facturas.fecha <= "' . $hasta . '"';
                  } else {
                    $hasta = date('Y-m-d 23:59:59');
                  }
                  if (isset($_GET['p']) && $_GET['p'] != '') {
                    $busqueda = $busqueda . " and facturas_pagos.id_proveedor = '" . $_GET['p'] . "'";
                  } else {
                    $vendedor = '';
                  }
                  if (isset($_GET['proveedor']) && $_GET['proveedor'] != '') {
                    $busquedaProveedor = " and facturas_pagos.id_proveedor = '" . $_GET['proveedor'] . "'";
                  } else {
                    $vendedor = '';
                  }
                  if (isset($_GET['f']) && $_GET['f'] != '') {
                    $busqueda = ' and facturas_pagos.tipo_pago = ' . $_GET['f'];
                  } else {
                    $vendedor = '';
                  }
                  ?>
                </div>

                <div id="total_periodo">Total $</div>
              </div>

            </div>
            <h6 class="card-subtitle"></h6>
            <div class="table-responsive">
              <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                <thead>
                  <tr style="width: 100%;">
                    <th>#</th>
                    <th>Número de Cheque</th>
                    <th>Proveedor</th>
                    <th>Titular</th>
                    <th>Banco</th>
                    <th>Cuit</th>
                    <th>Tipo de Pago</th>
                    <th>Fecha Pago</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Cobro</th>
                    <th>Monto</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $sqlDeuda = "SELECT facturas_pagos.*, proveedores.razon_com_proveedor AS proveedor, facturas_cheques.monto AS monto_rechazado,
                                CASE 
                                    WHEN (SELECT COUNT(*) FROM facturas WHERE facturas.id_proveedor = facturas_pagos.id_proveedor) = 0 
                                    THEN 1 
                                    ELSE 0 
                                END AS pago_favor
                              FROM facturas_pagos
                              LEFT JOIN proveedores ON facturas_pagos.id_proveedor = proveedores.id_proveedor
                              LEFT JOIN facturas_cheques ON facturas_pagos.id = facturas_cheques.id_pago
                              WHERE facturas_pagos.id > 0
                              GROUP BY facturas_pagos.id
                              ORDER BY facturas_pagos.fecha ASC;";

                  $saldo_final = 0;

                  $con_facturas = $link->query($sqlDeuda);

                  while ($row = mysqli_fetch_assoc($con_facturas)) {
                    $saldo_final += $row['monto'];
                    $id_pago = $row['id'];
                    echo "<tr style='width: 100%;" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>{$id_pago}</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'></td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>{$row['proveedor']}</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>{$row['titular']}</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>{$row['banco']}</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>{$row['cuit']}</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>" . strtoupper($row['tipo_pago']) . "</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>" . date('d/m/Y', strtotime($row['fecha'])) . "</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>" . ($row['tipo_pago'] === 'cheque' && !is_null($row['fecha_emision']) ? date('d/m/Y', strtotime($row['fecha_emision'])) : '') . "</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>" . ($row['tipo_pago'] === 'cheque' && !is_null($row['fecha_cobro']) ? date('d/m/Y', strtotime($row['fecha_cobro'])) : '') . "</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>$" . number_format($row['monto'], 2, ',', '.') . "</td>";
                    echo "<td style='" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>" . ($row['pago_favor'] == 1 ? 'Factura a favor' : $row['observaciones']) . "</td>";
                    echo "<td class='d-flex align-items-center' style='gap: 1em;" . ($row['pago_favor'] == 1 ? 'color:green;' : '') . "'>" . ($row['tipo_pago'] === 'cheque' ? '<button onclick="mostrar_tr_cheques(`tr_cheques_' . $row['id'] . '`)" class="btn btn-info btn-lg">Ver Cheques</button>' : '') . " <a target='_blank' href='paginas/recibo_factura_pago.php?id_pago={$id_pago}&proveedor={$row['id_proveedor']}&tipo_pago={$row['tipo_pago']}'><i class='fa-solid fa-receipt'></i></a></td>";
                    echo "</tr>";

                    if ($row['tipo_pago'] === 'cheque') {
                      $con_cheque = $link->query("SELECT *
                                                  FROM facturas_cheques
                                                  WHERE id > 0
                                                  AND id_pago = '$id_pago'
                                                  ORDER BY id ASC");

                      while ($row_cheque = mysqli_fetch_assoc($con_cheque)) {
                        echo "<tr style='width:100%;display:none;' class='tr_cheques_$id_pago'>";
                        echo "<td>{$row_cheque['id_pago']}</td>";
                        echo "<td>{$row_cheque['numero_cheque']}</td>";
                        echo "<td>{$row['proveedor']}</td>";
                        echo "<td>{$row_cheque['titular']}</td>";
                        echo "<td>{$row_cheque['banco']}</td>";
                        echo "<td>{$row_cheque['cuit']}</td>";
                        echo "<td>" . strtoupper($row['tipo_pago']) . "</td>";
                        echo "<td></td>";
                        echo "<td>" . ($row['tipo_pago'] === 'cheque' && !is_null($row_cheque['fecha_emision']) ? date('d/m/Y', strtotime($row_cheque['fecha_emision'])) : '') . "</td>";
                        echo "<td>" . ($row['tipo_pago'] === 'cheque' && !is_null($row_cheque['fecha_cobro']) ? date('d/m/Y', strtotime($row_cheque['fecha_cobro'])) : '') . "</td>";
                        echo "<td>$" . number_format($row_cheque['monto'], 2, ',', '.') . "</td>";
                        echo "<td>{$row_cheque['observaciones']}</td>";
                        echo "<td>" .
                          (intval($row_cheque['cheque_rechazado']) === 1
                            ? '<i title="Rechazado" class="fa-solid fa-circle" style="color:red;"></i>'
                            : '<i title="Aceptado" class="fa-solid fa-circle" style="color:green;"></i>') .
                          "</td>";
                        echo "</tr>";
                      }
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>


      </div>
    </div>

  </div>

  <script>
    $('#total_periodo').html('<span class="btn <?php echo $saldo_final < 0 ? 'btn-danger' : 'btn-success' ?> pull-right"><b>TOTAL: $<?php echo number_format($saldo_final, 0, '', '.'); ?></b></span>')

    function filtrar_vende() {
      var datodesde = $('#d').val(); // get selected value
      var datohasta = $('#h').val(); // get selected value
      var datovendedor = $('#vendedorsel option:selected').val(); // get selected value
      var datoforma = $('#formasel option:selected').val(); // get selected valu
      if (datodesde) { // require a URL
        window.location = 'index.php?pagina=pagos&d=' + datodesde + '&h=' + datohasta + '&v=' + datovendedor + '&f=' + datoforma; // redirect
      }
      return false;
    };
  </script>
  <script>
    var click = 0;

    function mostrar_tr_cheques(id_tr) {
      click++;
      if (click % 2 === 1) {
        $('.' + id_tr).show();
      } else {
        $('.' + id_tr).hide();
      }
    }
  </script>
<?php } ?>