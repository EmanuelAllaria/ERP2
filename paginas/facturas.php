<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Facturas</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Facturas</a></li>
        <?php if (isset($_GET['buscar'])) {
          echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . $_GET['buscar'] . ']</a></li>';
        } ?>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block" method="get">
        <input type="hidden" name="pagina" value="clientes">
        <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?php if (isset($_GET['buscar'])) {
            echo '<h4 class="card-title">Resulados de [' . $_GET['buscar'] . ']...</h4>';
          } else {
            echo '<h4 class="card-title">Listado</h4>';
          } ?>


          <h6 class="card-subtitle"></h6>
          <div class="table-responsive">
            <table id="facturas_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <td colspan="12">
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
                      <div class="col-md-2"><small class="form-control-feedback"> Saldo pendiente? </small><br>
                        <select class="form-control" id="saldosel">
                          <option value=''>Todos</option>
                          <option <?php echo isset($_GET['s']) && $_GET['s'] == 1 ? 'selected' : null ?> value="1">Pagadas por completo</option>
                          <option <?php echo isset($_GET['s']) && $_GET['s'] == 2 ? 'selected' : null ?> value="2">Pendientes</option>
                        </select>
                      </div>
                      <div class="col-md-2" style="align-self: center;">
                        <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                      </div>
                      <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p']) || isset($_GET['s'])) { ?>
                        <div class="col-md-2" style="align-self: center;"><a href="index.php?pagina=facturas">Quitar
                            Filtros</a></div><?php } ?>

                      <div class="col-md-2" style="align-self: center;">
                        <div id="total_periodo">Total $</div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <!--  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th> -->
                  <th>Nro Factura</th>
                  <th>Proveedor</th>
                  <th>Fecha</th>
                  <th>Tipo </th>
                  <th>Monto </th>
                  <th>Pago </th>
                  <th>Saldo Pendiente</th>
                  <th>Obs.</th>
                </tr>
              </thead>
              <tbody id="lista_facturas">
                <?php
                $busqueda = '';
                if (isset($_GET['buscar'])) {
                  $palabra = $_GET['buscar'];
                  $busqueda = "and (nro_factura like '%$palabra%' or tipo like '%$palabra%' )";
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
                  $busqueda = $busqueda . ' and proveedores.id_proveedor = ' . $_GET['p'];
                } else {
                  $vendedor = '';
                }

                $sqlDeuda = "SELECT facturas.*, proveedores.razon_com_proveedor AS proveedor, tipo_comprobantes.nombre_comprobantes
                             FROM facturas
                             LEFT JOIN proveedores ON facturas.id_proveedor = proveedores.id_proveedor
                             LEFT JOIN tipo_comprobantes ON facturas.tipo = tipo_comprobantes.id_comprobantes
                             LEFT JOIN facturas_pagos ON facturas.id = facturas_pagos.id_factura
                             WHERE facturas.id > 0
                             $busqueda
                             GROUP BY facturas.id
                             ORDER BY facturas.fecha ASC";
                if (isset($_GET['s']) && ($_GET['s'] == 1 || $_GET['s'] == 2)) {
                  $sqlDeuda = ($_GET['s'] == 1) ? "SELECT * FROM (
                    SELECT facturas.*, proveedores.razon_com_proveedor AS proveedor, tipo_comprobantes.nombre_comprobantes, 
                        ABS(facturas.monto) AS total_pagado,
                        GREATEST(0, CASE WHEN facturas.monto < 0 THEN 0 ELSE facturas.monto - COALESCE(SUM(facturas_pagos.monto), 0) END) AS saldo
                    FROM facturas
                    LEFT JOIN proveedores ON facturas.id_proveedor = proveedores.id_proveedor
                    LEFT JOIN tipo_comprobantes ON facturas.tipo = tipo_comprobantes.id_comprobantes
                    LEFT JOIN facturas_pagos ON facturas.id = facturas_pagos.id_factura
                    WHERE facturas.id > 0 AND (facturas.monto < 0 OR facturas.nro_factura IN (
                        SELECT nro_factura
                        FROM facturas as f
                        WHERE monto < 0
                        and f.id_proveedor = facturas.id_proveedor
                    ))
                    $busqueda
                    GROUP BY facturas.id
                    HAVING saldo <= 0 OR saldo IS NULL
                    UNION
                    SELECT facturas.*, proveedores.razon_com_proveedor AS proveedor, tipo_comprobantes.nombre_comprobantes, 
                        ABS(facturas.monto) AS total_pagado,
                        GREATEST(0, CASE WHEN facturas.monto < 0 THEN 0 ELSE facturas.monto - COALESCE(SUM(facturas_pagos.monto), 0) END) AS saldo
                    FROM facturas
                    LEFT JOIN proveedores ON facturas.id_proveedor = proveedores.id_proveedor
                    LEFT JOIN tipo_comprobantes ON facturas.tipo = tipo_comprobantes.id_comprobantes
                    LEFT JOIN facturas_pagos ON facturas.id = facturas_pagos.id_factura
                    WHERE facturas.id > 0 AND facturas.nro_factura IN (
                        SELECT nro_factura
                        FROM facturas as f
                        WHERE monto < 0
                        and f.id_proveedor = facturas.id_proveedor
                    )
                    $busqueda
                    GROUP BY facturas.id
                    HAVING saldo > 0
                ) AS subquery
                ORDER BY subquery.fecha ASC" : "SELECT facturas.*, proveedores.razon_com_proveedor AS proveedor, tipo_comprobantes.nombre_comprobantes, 
                            CASE 
                               WHEN facturas.monto < 0 THEN 0
                               ELSE CASE WHEN EXISTS (
                                           SELECT 1 
                                           FROM facturas AS f 
                                           WHERE f.nro_factura = facturas.nro_factura
                                           AND f.id_proveedor = facturas.id_proveedor
                                           AND f.id != facturas.id
                                       ) THEN 0
                                       ELSE (facturas.monto - COALESCE(SUM(facturas_pagos.monto), 0))
                                   END
                           END AS saldo,
                           CASE 
                               WHEN facturas.monto < 0 THEN
                                   facturas.monto * -1
                               ELSE 
                                   CASE 
                                       WHEN EXISTS (
                                           SELECT 1 
                                           FROM facturas AS f 
                                           WHERE f.nro_factura = facturas.nro_factura
                                           AND f.id_proveedor = facturas.id_proveedor
                                           AND f.id != facturas.id
                                       ) THEN facturas.monto
                                       ELSE COALESCE(SUM(facturas_pagos.monto), 0)
                                   END
                           END AS total_pagado
                           FROM facturas
                            LEFT JOIN proveedores ON facturas.id_proveedor = proveedores.id_proveedor
                            LEFT JOIN tipo_comprobantes ON facturas.tipo = tipo_comprobantes.id_comprobantes
                            LEFT JOIN facturas_pagos ON facturas.id = facturas_pagos.id_factura
                            WHERE facturas.id > 0
                            $busqueda
                            GROUP BY facturas.id
                            HAVING saldo > 0
                            ORDER BY facturas.fecha ASC";
                }
                $saldo_final = 0;

                $con_facturas = $link->query($sqlDeuda);

                while ($row = mysqli_fetch_assoc($con_facturas)) {
                  $saldo_final += $row['monto'];
                  $saldo = $row['monto'];
                  $id_factura = $row['id'];

                  $consulta2 = $link->query("SELECT * from facturas_pagos where id_factura='$id_factura' GROUP BY id ORDER BY id ASC");
                  $factura_pago = array();
                  while ($row2 = mysqli_fetch_assoc($consulta2)) {
                    $factura_pago[] = $row2;
                  }
                  $saldo = max($saldo, 0);
                ?>
                  <tr>
                    <td><?php echo $row['nro_factura'] ?></td>
                    <td><?php echo $row['proveedor'] ?></td>
                    <td><?php echo $row['fecha'] ?></td>
                    <td><?php echo $row['nombre_comprobantes'] ?></td>
                    <?php if (intval($row['monto']) > 0) { ?>
                      <td style="color:red;"><b>$<?php echo number_format($row['monto'], 2, ',', '.'); ?></b></td>
                    <?php } else { ?>
                      <td style="color:green;"><b>$<?php echo number_format($row['monto'], 2, ',', '.'); ?></b></td>
                    <?php } ?>
                    <td></td>
                    <td>$<?php echo number_format($saldo, 2, ',', '.'); ?></td>
                    <td><?php echo $row['observaciones'] ?></td>
                  </tr>
                  <?php

                  foreach ($factura_pago as $pago) {
                  ?>
                    <tr>
                      <td><?php echo $row['nro_factura'] ?></td>
                      <td></td>
                      <td><?php echo $pago['fecha_emision'] ?></td>
                      <td>PAGO</td>
                      <td></td>
                      <?php if (strpos($pago['observaciones'], 'Sobró del pago a la factura') !== false) { ?>
                        <td style="color:blue;"><b>$<?php echo number_format($pago['monto'], 2, ',', '.'); ?></b></td>
                      <?php } else { ?>
                        <td style="color:green;"><b>$<?php echo number_format($pago['monto'], 2, ',', '.'); ?></b></td>
                      <?php } ?>
                      <?php
                      $saldo_final -= intval($pago['monto']);
                      $saldo -= intval($pago['monto']);
                      ?>
                      <td>$<?php echo number_format($saldo, 2, ',', '.'); ?></td>
                      <td><a target="_blank" href="./paginas/recibo_factura_pago.php?id_factura=<?php echo $id_factura; ?>&id_pago=<?php echo $pago['id'] ?>"><i class="fa-solid fa-receipt"></i></a></td>
                    </tr>
                <?php
                  }
                }
                ?>
              </tbody>
              <tfoot>
                <tr>


                  <td colspan="9">
                    <div class="text-right">
                      <ul class="pagination">
                        <li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li>
                        <li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li>
                        <li class="footable-page active"><a data-page="0" href="#">1</a></li>
                        <li class="footable-page"><a data-page="1" href="#">2</a></li>
                        <li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li>
                        <li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Facturas A Favor</h4>

          <h6 class="card-subtitle"></h6>
          <div class="table-responsive">
            <table id="facturas_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Tipo </th>
                  <th>Pago </th>
                  <th>Obs.</th>
                </tr>
              </thead>
              <tbody id="lista_facturas">
                <?php
                $con_facturas = $link->query("SELECT *
                                              FROM facturas_pagos
                                              WHERE id_factura = '-1'
                                              GROUP BY facturas_pagos.id
                                              ORDER BY facturas_pagos.fecha ASC");

                while ($row = mysqli_fetch_assoc($con_facturas)) {
                  $saldo = $row['monto'];
                  $id_factura = $row['id'];

                  $consulta2 = $link->query("SELECT * from facturas_pagos where id_factura='$id_factura' GROUP BY id ORDER BY id ASC");
                  $factura_pago = array();
                  while ($row2 = mysqli_fetch_assoc($consulta2)) {
                    $factura_pago[] = $row2;
                  }
                  $saldo = max($saldo, 0);
                ?>
                  <tr>
                    <td><?php echo $row['fecha_emision'] ?></td>
                    <td>PAGO</td>
                    <?php if (strpos($row['observaciones'], 'Sobró del pago a la factura') !== false) { ?>
                      <td style="color:blue;"><b>$<?php echo number_format($row['monto'], 2, ',', '.'); ?></b></td>
                    <?php } else { ?>
                      <td style="color:green;"><b>$<?php echo number_format($row['monto'], 2, ',', '.'); ?></b></td>
                    <?php } ?>
                    <td><?php echo $row['observaciones'] ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <tfoot>
                <tr>


                  <td colspan="9">
                    <div class="text-right">
                      <ul class="pagination">
                        <li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li>
                        <li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li>
                        <li class="footable-page active"><a data-page="0" href="#">1</a></li>
                        <li class="footable-page"><a data-page="1" href="#">2</a></li>
                        <li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li>
                        <li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    var availableTags = [<?php
                          mysqli_data_seek($con_facturas, 0);
                          while ($com_sul = mysqli_fetch_array($con_facturas)) {
                            echo '"' . $com_sul['nro_factura'] . '",';
                          } ?>]
    $("#buscador").autocomplete({
      source: availableTags
    });
  });
</script>
<script>
  function filtrar_prov() {
    var datodesde = $('#d').val(); // get selected value
    var datohasta = $('#h').val(); // get selected value
    var datoproveedor = $('#proveedorsel option:selected').val(); // get selected value
    var datosaldo = $('#saldosel').val();
    console.log(datodesde);
    if (datodesde) { // require a URL
      location.search = '?pagina=facturas&d=' + datodesde + '&h=' + datohasta + '&p=' + datoproveedor + '&s=' + datosaldo; // redirect
    }
    return false;
  }
</script>
<script>
  $('#total_periodo').html('<span class="btn btn-success pull-right"><b>TOTAL: $<?php echo number_format($saldo_final, 0, '', '.'); ?></b></span>')
</script>