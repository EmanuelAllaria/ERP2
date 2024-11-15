<?php if (isset($_GET['add']) && $_GET['add'] == '1') {
    include('clientes_add.php');
} else { ?>
    <?php
    $id = $_SESSION['id'];
    $autorizados = $link->query("SELECT * FROM carga_camion
                                INNER JOIN stock_depositos ON quien_stockd = '$id' AND carga_camion.personal_cargac = stock_depositos.idpersona_stockd AND stock_depositos.idcarga_stockd = carga_camion.id_cargac
                                WHERE autoriza_cargac LIKE '0000-00-00%'");
    $autorizado = mysqli_num_rows($autorizados);
    ?>
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-12">
                <h4 class="text-white">Listado de Cargas</h4>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item "><a href="#">Cargas</a></li>
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
                                        <p class="text-muted">Cargas de la Fecha</p>
                                    </div>
                                </a>
                                <div class="ml-auto">
                                    <h2 class="counter text-primary"><?php if (isset($cuento_nuevos)) echo $cuento_nuevos;
                                                                        else echo 0; ?></h2>
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
                                        <p class="text-muted">Mercaderia en Transito</p>
                                    </div>
                                </a>
                                <div class="ml-auto">
                                    <h2 class="counter text-purple">0</h2>
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
            <!-- Column -->
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <a href="index.php?pagina=pagos">
                                    <div>
                                        <h3><i class="icon-warning-sign"></i></h3>
                                        <p class="text-muted">Pendientes de Autorizacion</p>
                                    </div>
                                </a>
                                <div class="ml-auto">
                                    <h2 class="counter text-success"><?php echo $autorizado ?></h2>
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
                                <input class="form-control filtro" type="date" id="d" name="d" value="<?php if (isset($_GET['d']) && $_GET['d']) {
                                                                                                            echo $_GET['d'];
                                                                                                        } else {
                                                                                                            echo date('Y-m-01');
                                                                                                        } ?>">


                            </div>
                            <div class="col-md-2"><small class="form-control-feedback"> Hasta </small>
                                <input class="form-control filtro" type="date" id="h" name="h" value="<?php if (isset($_GET['h']) && $_GET['h']) {
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
                                        <th class="footable-sortable">Hora<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable">Personal<span class="footable-sort-indicator"></span></th>
                                        <!--  <th class="footable-sortable">Cliente<span class="footable-sort-indicator"></span></th> -->
                                        <!--   <th class="footable-sortable">Detalle<span class="footable-sort-indicator"></span></th> -->
                                        <th class="footable-sortable">Observacion<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable">Items<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable">Estado<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable" style="width: 110px;">Acciones<span class="footable-sort-indicator"></span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['d']) && $_GET['d']) {
                                        $desde = $_GET['d'];
                                    } else {
                                        $desde = date('Y-m-01');
                                    }
                                    if (isset($_GET['h']) && $_GET['h']) {
                                        $hasta = $_GET['h'];
                                    } else {
                                        $hasta = date('Y-m-d 23:59:59');
                                    }
                                    $acumula = 0;
                                    $con_pedidos = $link->query("SELECT *, sum(cantidad_stockd) as suma
                                        FROM `stock_depositos`
                                        INNER JOIN personal ON personal.id = stock_depositos.idpersona_stockd
                                        INNER JOIN carga_camion ON carga_camion.id_cargac = stock_depositos.idcarga_stockd
                                        WHERE `estado_stockd` != 0 AND `tipomov_stockd` LIKE 'carga'
                                        GROUP BY carga_camion.fecha_cargac
                                        ORDER BY carga_camion.fecha_cargac DESC;");
                                    while ($row = mysqli_fetch_array($con_pedidos)) {

                                    ?>
                                        <tr>

                                            <td class="font-weight-normal"><span class="footable-toggle"></span>
                                                <?php echo $row['idcarga_stockd']; ?>
                                            </td>
                                            <td class="font-weight-normal">
                                                <?php echo date('d/m/Y', strtotime($row['fecha_cargac'])) ?>
                                            </td>
                                            <td class="font-weight-normal">
                                                <?php echo date('H:i:s', strtotime($row['fecha_cargac'])) ?>
                                            </td>
                                            <td class="font-weight-normal"><?php echo $row['apellido'] . ', ' . $row['nombre'] ?></td>
                                            <td class="font-weight-normal"><?php echo $row['observacionadm_cargac'];
                                                                            if ($row['observacion_cargac'] != '') {
                                                                                echo ' || (Vendedor) ' . $row['observacion_cargac'];
                                                                            } ?></td>
                                            <td class="font-weight-normal"><?php echo $row['suma'] ?></td>
                                            <td class="font-weight-normal"><?php
                                                                            if ($row['autoriza_cargac'] == '0000-00-00 00:00:00' && $row['estado_stockd'] == '1') {
                                                                                echo '<span class="badge badge-pill badge-warning">Pendiente</span>';
                                                                            } else if ($row['autoriza_cargac'] != '0000-00-00 00:00:00' && $row['estado_stockd'] == '1') {
                                                                                echo '<span class="badge badge-pill badge-success">Autorizado</span>';
                                                                            } else if ($row['estado_cargac'] == '2') {
                                                                                echo '<span class="badge badge-pill badge-danger">Sin Autorizar</span>';
                                                                            } else if ($row['autoriza_cargac'] != '0000-00-00 00:00:00' && $row['estado_stockd'] == '2') {
                                                                                echo '<span class="badge badge-pill badge-info">Liquidada</span>';
                                                                            }

                                                                            ?></td>
                                            <td class="font-weight-normal">
                                                <a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=carga_ver&id=<?php echo $row['idcarga_stockd'] ?>"><i class="ti-eye" aria-hidden="true"></i></a>
                                                <?php if ($row['autoriza_cargac'] == '0000-00-00 00:00:00' && $row['estado_stockd'] == '1') {
                                                    echo '&nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=pedidos_edit&id=' . $row['idcarga_stockd'] . '" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                                                     &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#delc_' . $row['idcarga_stockd'] . '" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                                                     ';
                                                } ?>
                                            </td>

                                        </tr>
                                    <?php
                                        echo '
                                                  <div class="modal fade" id="delc_' . $row['idcarga_stockd'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <h4 ><center>Seguro que desea eliminar<br> la carga # "' . $row['idcarga_stockd'] . '" de ' . $row['apellido'] . ', ' . $row['nombre'] . ' con ' . $row['suma'] . ' items ?</center></h4>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button onclick="elimina_carga(' . $row['idcarga_stockd'] . ',1)" class="btn btn-primary">Si, Eliminar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
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
    </script>

<?php } ?>