<?php if (isset($_GET['add']) && $_GET['add'] == '1') {
  include('clientes_add.php');
} else { ?><div class="container-fluid">

    <div class="row page-titles">
      <div class="col-md-12">
        <h4 class="text-white">Listado de Liquidaciones</h4>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
          <li class="breadcrumb-item "><a href="#">Liquidaciones</a></li>
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
                    <h3><i class="icon-calendar"></i></h3>
                    <p class="text-muted">Recaudacion Diaria</p>
                  </div>
                </a>
                <div class="ml-auto">
                  <h2 id="recaudacion_module" class="counter text-primary"><?php echo $cuento_nuevos; ?></h2>
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

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="d-flex no-block align-items-center">
                <a href="index.php?pagina=facturas">
                  <div>
                    <h3><i class="ti-trucks"></i></h3>
                    <p class="text-muted">Liquidaciones del Día</p>
                  </div>
                </a>
                <div class="ml-auto">
                  <h2 id="liquidadas_module" class="counter text-purple">0</h2>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="progress">
                <div class="progress-bar bg-purple" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="d-flex no-block align-items-center">
                <a href="index.php?pagina=pagos">
                  <div>
                    <h3><i class="icon-warning-sign"></i></h3>
                    <p class="text-muted">Pendiente a liquidar</p>
                  </div>
                </a>
                <div class="ml-auto">
                  <h2 id="pendiente_module" class="counter text-success">0</h2>
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

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <h4 class="card-title">Listado</h4>
              </div>
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
              <div class="col-md-3" style="align-self: center;"><?php if (isset($_GET['d']) || isset($_GET['h'])) { ?><a href="index.php?pagina=estadocamion">Quitar Filtros</a><?php } ?></div>
              <!-- <div id="total_periodo">Total $</div> -->
            </div>
            <h6 class="card-subtitle"></h6>
            <div class="table-responsive">
              <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                <thead>
                  <tr>
                    <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable">Fecha<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable">Personal<span class="footable-sort-indicator"></span></th>
                    <!--  <th class="footable-sortable">Cliente<span class="footable-sort-indicator"></span></th> -->
                    <!--   <th class="footable-sortable">Detalle<span class="footable-sort-indicator"></span></th> -->
                    <th class="footable-sortable">Observacion<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable">Devoluciones<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable">Recaudacion<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable">Gastos<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable">Rinde<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable">Estado<span class="footable-sort-indicator"></span></th>
                    <th class="footable-sortable" style="width: 110px;">Acciones<span class="footable-sort-indicator"></span></th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($_GET['d'])) {
                    $desde = $_GET['d'];
                  } else {
                    $desde = date('Y-m-01');
                  }
                  if (isset($_GET['h'])) {
                    $hasta = $_GET['h'];
                  } else {
                    $hasta = date('Y-m-d 23:59:59');
                  }
                  $acumula = 0;
                  $acumula_pendiente = 0;
                  $acumula_liquidacion = 0;
                  $acumula_recaudacion = 0;
                  $con_liquida = $link->query("SELECT * FROM carga_camion
                                              INNER JOIN personal on personal.id = carga_camion.personal_cargac
                                              LEFT JOIN liquidaciones on carga_camion.id_cargac = liquidaciones.id_cargac and estado_liquidacion='1'
                                              AND liquidaciones.vendedor_liquidacion = carga_camion.personal_cargac
                                              LEFT JOIN tipo_estados on tipo_estados.id_estados = liquidaciones.estado_liquidacion
                                              WHERE estado_cargac='1' group by fecha_cargac , personal_cargac order by fecha_cargac DESC");
                  $acumula_liqui = '0';
                  $acumula_pendi = '0';
                  while ($row = mysqli_fetch_array($con_liquida)) {

                    $personal = $row['personal_cargac'];
                    $periodo = date('Y-m-d', strtotime($row['fecha_cargac']));

                    $ultima_liquidacion = $link->query("SELECT `cuando_liquidacion`  FROM `liquidaciones` WHERE `vendedor_liquidacion` = '$personal'
                                            ORDER BY `liquidaciones`.`id_liquidacion`  DESC LIMIT 1 ");
                    $ulti_liqui = " and transaccion.fecha > '0000-00-00 00:00:00' ";
                    if ($liqui = mysqli_fetch_array($ultima_liquidacion)) {
                      $ulti_liqui = " and transaccion.fecha > '" . $liqui['cuando_liquidacion'] . "' ";
                    }
                    $sql_liqui = "SELECT * FROM `transaccion`
                                                INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and transaccion.quien='$personal'
                                                WHERE DATE(fecha) LIKE '$periodo' AND `tipo` LIKE 'pago' AND `abonada` LIKE '0' /* $ulti_liqui */ AND `estado` = 1 ORDER BY transaccion.id DESC";
                    $cobros = $link->query($sql_liqui);
                    $sql_gastos = "SELECT * FROM `gastos` INNER JOIN tipo_gastos on tipo_gastos.id_tipogasto = gastos.tipo_gasto WHERE estado_gasto = 1 and DATE(fecha_gasto) LIKE '$periodo'  and personal_gasto = $personal";
                    $gast = $link->query($sql_gastos);

                    $acumula_cobros = 0;
                    $acumula_gastos = 0;
                  //  if($row['id_cargac']==27){  echo $sql_liqui;}
                    $movimientos = '<table class="table"><tr><th>#</th><th>Cliente</th><th>Monto</th></tr>';
                    $gastos = '<table class="table"><tr><th>#</th><th>Gastos</th><th>Monto</th></tr>';
                    while ($rowc = mysqli_fetch_array($cobros)) {
                      $acumula_cobros = $acumula_cobros + $rowc['monto2'];
                      $movimientos .= '<tr><td>' . $rowc['id'] . '</td><td>' . $rowc['razon_com_clientes'] . '</td><td>$' . number_format($rowc['monto2'], 2, ',', '.') . '</td></tr>';
                    }
                    while ($rowg = mysqli_fetch_array($gast)) {
                      $acumula_gastos = $acumula_gastos + $rowg['monto_gasto'];
                      $gastos .= '<tr><td>' . $rowg['id_gasto'] . '</td><td>' . $rowg['nombre_tipogasto'] . '</td><td>$' . number_format($rowg['monto_gasto'], 2, ',', '.') . '</td></tr>';
                    }
                    $movimientos .= '</table>';
                    $gastos .= '</table>';

                    $diferencia = ($acumula_cobros - $row['entrega_liquidacion']) - $acumula_gastos;

                    $buscastock = $link->query("SELECT * FROM stock_depositos WHERE idpersona_stockd='$personal' and DATE(fecha_stockd) like '$periodo' and estado_stockd='1'");
                    $carga = 0;
                    $venta = 0;
                    while ($calculo = mysqli_fetch_array($buscastock)) {
                      if (isset($calculo['tipomov_stockd']) && $calculo['tipomov_stockd'] == 'carga') {
                        $carga = $carga + $calculo['cantidad_stockd'];
                      }
                      if (isset($calculo['tipomov_stockd']) && ($calculo['tipomov_stockd'] == 'venta'  || $calculo['tipomov_stockd'] == 'devolucion')) {
                        $venta = $venta + $calculo['cantidad_stockd'];
                      }
                    }
                    $stock_final = $carga - $venta;
                    if ($row['entrega_liquidacion'] == null) {
                      $stock_final = 0;
                    }

                  ?>
                    <tr>

                      <td class="font-weight-normal"><span class="footable-toggle"></span>
                        <?php echo $row['id_cargac']; ?>
                      </td>
                      <td class="font-weight-normal">
                        <?php echo date('d/m/Y', strtotime($row['fecha_cargac'])) ?>
                      </td>
                      <td class="font-weight-normal"><?php echo $row['apellido'] . ', ' . $row['nombre'] ?></td>
                      <td class="font-weight-normal"><?php echo $row['observaciones_liquidacion']; ?></td>
                      <td class="font-weight-normal"><?php echo $stock_final; ?></td> <!-- devoluciones -->
                      <td class="font-weight-normal"><?php echo '$ ' . number_format($acumula_cobros, 2, ',', '.') ?> <span data-container="body" title="Detalle de Movimientos" data-toggle="popover" data-placement="top" data-html="true" data-content='<?php echo $movimientos; ?>'><i class="fa fa-th-list"></i></span></td>
                       <td class="font-weight-normal"><?php echo '$ ' . number_format($acumula_gastos, 2, ',', '.') ?> <span data-container="body" title="Detalle de Gastos" data-toggle="popover" data-placement="top" data-html="true" data-content='<?php echo $gastos; ?>'><i class="fa fa-th-list"></i></span></td>
                      <td class="font-weight-normal"><?php if (!isset($row['entrega_liquidacion']) && $row['entrega_liquidacion'] == null) {
                          echo '-';} else {
                          echo '$ ' . number_format($row['entrega_liquidacion'], 2, ',', '.');} ?></td>
                      <td class="font-weight-normal"><?php
                        if (isset($row['entrega_liquidacion']) && $row['entrega_liquidacion'] != null && $diferencia == '0') {

                            if (date('Y-m-d') == date('Y-m-d', strtotime($row['fecha_cargac']))) {
                              $acumula_liquidacion = $acumula_liquidacion + $row['entrega_liquidacion'];
                              $acumula_liqui++;
                            }

                          echo '<center><span class="badge badge-pill badge-success" style="100%">Liquidada</span></center>';
                        } else if ($diferencia != '0' && $row['entrega_liquidacion'] != null) {
                            if (date('Y-m-d') == date('Y-m-d', strtotime($row['fecha_cargac']))) {
                                $acumula_liquidacion = $acumula_liquidacion + $row['entrega_liquidacion'];
                                $acumula_liqui++;
                            }
                            echo '<center><span class="badge badge-pill badge-warning">Diferencias ($ ' . number_format($diferencia, 2, ',', '.') . ')</span></center>';
                          } else {
                              $acumula_pendiente = $acumula_pendiente + $acumula_cobros;
                            echo '<center><span class="badge badge-pill badge-danger">Pendiente</span></center>';
                              }
                              $acumula_recaudacion = $acumula_recaudacion + $acumula_cobros;
                            if (date('Y-m-d') == date('Y-m-d', strtotime($row['fecha_cargac']))) {
                                $acumula_pendi++;
                          }
                                                      ?></td>
                      <td class="font-weight-normal">
                        <?php
                        if (!isset($row['entrega_liquidacion']) && $row['entrega_liquidacion'] == null) {
                          echo '<a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#liquida_' . $row['id_cargac'] . '" data-toggle="tooltip" data-original-title="Cerrar Jornada"><i class="fa fa-money" aria-hidden="true"></i></a>';
                          
                        } else {
                          echo '<a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" target="_blank" href="paginas/liqui_pdf.php?p=' . date('Y-m-d', strtotime($row['fecha_cargac'])) . '&u=' . $personal . '" ><i class="fa fa-clipboard" aria-hidden="true"></i></a> <a class="btn-pure btn-outline-info info-row-btn btn-lg" style="padding:0px;" target="_blank" href="paginas/liqui_detalle_pdf.php?p=' . date('Y-m-d', strtotime($row['fecha_cargac'])) . '&u=' . $personal . '" ><i class="fa fa-file-text" aria-hidden="true"></i></a>';
                        }
                        ?>
                  

                      </td>

                    </tr>
                  <?php
                    echo '
                        <div class="modal fade" id="delc_' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                                      </button>
                        </div>
                        <div class="modal-body">
                        <h4 ><center>Seguro que desea eliminar<br> la carga # "' . $row['id'] . '" ?</center></h4>
                                </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button onclick="elimina_c(' . $row['id'] . ',1)" class="btn btn-primary">Si, Eliminar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';

                    if ($row['entrega_liquidacion'] == null) {
                      $modal_liqui = '
                        <div class="modal fade" id="liquida_' . $row['id_cargac'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                          <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                           <span aria-hidden="true">&times;</span>
                           </button>
                            </div>
                          <div class="modal-body">
                          <h4 ><center>Esta por realizar el cierre de la Jornada de la carga # "' . $row['id_cargac'] . '" (' . $row['fecha_cargac'] . ') de ' . $row['apellido'] . ', ' . $row['nombre'] . '</center></h4><br>
                            <div class="row"><div class="col-md-6">
                            + Recaudacion: $' . $acumula_cobros . '<br>
                            - Gastos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $' . $acumula_gastos . '<br>
                            --------------------------------<br>
                            A rendir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $' . ($acumula_cobros - $acumula_gastos) . '
                            </div>
                            <div class="col-md-6">El vendedor rinde un total de  $ <input type="number" id="total_rendido_' . $row['id_cargac'] . '" step="any" class="form-control" value="' . ($acumula_cobros - $acumula_gastos) . '"></div></div>
                              <input type="hidden" value="' . ($acumula_cobros - $acumula_gastos) . '" id="tot_a_cobrar_' . $row['id_cargac'] . '">
                              <input type="hidden" value="' . $personal . '" id="personal_' . $row['id_cargac'] . '">
                              <input type="hidden" value="' . $row['fecha_cargac'] . '" id="fecha_' . $row['id_cargac'] . '">
                                                          <hr/>
                          <p>Ademas se realiza de devolucion al stock general de:</p>
                          <div class="row">
                            <div class="col-md-9"><b>Poducto</b></div>
                          <div class="col-md-3"><b>Cantidad</b></div>
                          </div>';
                      $periodo_dia = date('Y-m-d', strtotime($periodo));
                     
                      $buscadevo = $link->query("
                        SELECT DISTINCT(`idproducto_stockd`), detalle_producto, presentacion_producto FROM stock_depositos
                          INNER JOIN productos on productos.id_producto = stock_depositos.idproducto_stockd
                              WHERE idpersona_stockd ='$personal' and DATE(fecha_stockd) like '$periodo_dia' and estado_stockd='1' ");

                      while ($devo = mysqli_fetch_array($buscadevo)) {

                        $id_dev = $devo['idproducto_stockd'];

                        $buscadev = $link->query("
                          SELECT * FROM stock_depositos
                              WHERE idpersona_stockd='$personal' and idproducto_stockd='$id_dev' and DATE(fecha_stockd) like '$periodo_dia' and estado_stockd='1'");
                        $cargad = 0;
                        $ventad = 0;
                        $stock_finald = 0;
                        $cant_item = 0;
                        while ($calculod = mysqli_fetch_array($buscadev)) {
                          if ($calculod['tipomov_stockd'] == 'carga') {
                            $cargad = $cargad + $calculod['cantidad_stockd'];
                            //    echo 'Cantidad Carga'.$id_dev.': '.$cargad.'<br>';
                          }
                          if ($calculod['tipomov_stockd'] == 'venta' || $calculod['tipomov_stockd'] == 'devolucion') {
                            $ventad = $ventad + $calculod['cantidad_stockd'];
                            //  echo 'Cantidad '.$id_dev.': '.$ventad.'<br>';
                          }
                          $stock_finald = $cargad - $ventad;
                          //  echo '----------------------------------<br>Final: '.$stock_finald.'------------';
                        }
                        if ($stock_finald != 0) {

                          $modal_liqui .= '
                            <div class="row">
                            <div class="col-md-9">' . $devo['detalle_producto'] . ' (' . $devo['presentacion_producto'] . ')</div>
                             <div class="col-md-3"><input value="' . $stock_finald . '" class="form-control cantidades" id="cantdevol_' . $cant_item . '"></div>
                            <input type="hidden" value="' . $id_dev . '" id="iddevol_' . $cant_item . '">
                              </div>

                                                              ';
                          $cant_item++;
                        }

                        $modal_liqui .= '  <input type="hidden" value="' . $stock_final . '" id="cantidadprod_' . $row['id_cargac'] . '"><input type="hidden" value="0" id="items_' . $row['id_cargac'] . '">';
                      }

                      $modal_liqui .= '<hr/>
                        <div class="row">
                          <div class="col-md-12">
                            <textarea placeholder="Ingrese una observacion" class="form-control" id="observacion_liq_' . $row['id_cargac'] . '"></textarea>
                          </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                          <button onclick="liquidacion(' . $row['id_cargac'] . ')" class="btn btn-primary">Si, Confirmar</button>
                        </div>
                        </div>
                        </div>
                        </div>';
                      echo $modal_liqui;
                    }
                  } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="2">
                      <a href="index.php?pagina=cargacamion" class="btn btn-info btn-rounded">Realizar Nueva Carga</a>
                    </td>

                    <td colspan="7">
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
    $('#total_periodo').html('<span class="btn btn-success pull-right"><b>TOTAL: $<?php echo number_format($acumula, 0, '', '.'); ?></b></span>')
    $(function() {
      // bind change event to select
      $('.filtro').on('change', function() {
        var datodesde = $('#d').val(); // get selected value
        var datohasta = $('#h').val(); // get selected value
        if (datodesde) { // require a URL
          window.location = 'index.php?pagina=pedidos&d=' + datodesde + '&h=' + datohasta; // redirect
        }
        return false;
      });
    });
    $('#recaudacion_module').html('<?php echo '$ ' . number_format($acumula_liquidacion, 2, ',', '.') ?>');
    $('#liquidadas_module').html('<?php echo '# ' . $acumula_liqui ?>');
    $('#pendiente_module').html('<?php echo '# ' . $acumula_pendi ?>');
  </script>

<?php } ?>
