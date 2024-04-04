<?php if (isset($_GET['add']) && $_GET['add'] == '1') {
    include('clientes_add.php');
} else { ?><div class="container-fluid">

        <div class="row page-titles">
            <div class="col-md-12">
                <h4 class="text-white">Listado de Transacciones</h4>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Transacciones</a></li>
                </ol>
            </div>
            <div class="col-md-6 text-right">
                <form class="app-search d-none d-md-block d-lg-block">
                    <input type="text" class="form-control" placeholder="Buscar...">
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <h4 class="card-title">Listado</h4>
                            </div>
                            <div class="col-md-4"><small class="form-control-feedback"> Desde </small>
                                <input class="form-control filtro" type="date" id="d" name="d" value="<?php if (isset($_GET['d'])) {
                                                                                                            echo $_GET['d'];
                                                                                                        } else {
                                                                                                            echo date('Y-m-01');
                                                                                                        } ?>">


                            </div>
                            <div class="col-md-4"><small class="form-control-feedback"> Hasta </small>
                                <input class="form-control filtro" type="date" id="h" name="h" value="<?php if (isset($_GET['h'])) {
                                                                                                            echo $_GET['h'];
                                                                                                        } else {
                                                                                                            echo date('Y-m-d');
                                                                                                        } ?>">


                            </div>
                            <div class="col-md-2" style="align-self: center;"><?php if (isset($_GET['d']) || isset($_GET['h'])) { ?><a href="index.php?pagina=pedidos">Quitar Filtros</a><?php } ?></div>
                        </div>
                        <h6 class="card-subtitle"></h6>
                        <div class="table-responsive">
                            <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                                <thead>
                                    <tr>
                                        <th class="footable-sortable">Comercio<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable">Fecha<span class="footable-sort-indicator"></span></th>
                                        <!-- <th class="footable-sortable">Cliente<span class="footable-sort-indicator"></span></th> -->
                                        <th class="footable-sortable">Detalle<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable">Pedido<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable">Pago<span class="footable-sort-indicator"></span></th>


                                        <!--  <th class="footable-sortable">Estado<span class="footable-sort-indicator"></span></th> -->
                                        <th class="footable-sortable">Acciones<span class="footable-sort-indicator"></span></th>
                                        <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th>
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
                                        $hasta = $_GET['h'] . ' ' . date('H:i:s');
                                    } else {
                                        $hasta = date('Y-m-d H:i:s');
                                    }
                                    $con_pedidos = $link->query("SELECT * FROM `transaccion`
                                            inner join clientes on transaccion.cliente = clientes.id_clientes
                                            WHERE transaccion.estado='1' and transaccion.fecha >= '$desde' and transaccion.fecha <= '$hasta' order by transaccion.fecha DESC, razon_com_clientes ASC ");
                                    while ($row = mysqli_fetch_array($con_pedidos)) {
                                    ?>
                                        <tr>
                                            <td class="font-weight-normal">
                                                <a href="index.php?pagina=clientes_view&id=<?php echo $row['id_clientes'] ?>" style="color:#262626;" class="font-weight-normal"><!--<img src="img/comercios/<?php echo $row['foto_comclientes'] ?>" alt="user" width="40" style="margin-right: 5px;" class="img-circle"> --> <?php echo $row['nombre_clientes'] . ' ' . $row['apellido_clientes'] ?></a>
                                            </td>
                                            <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                            <!--    <td class="font-weight-normal"><?php echo $row['apellido_clientes'] . ', ' . $row['nombre_clientes'] ?></td> -->
                                            <td class="font-weight-normal"><?php
                                                                            if ($row['tipo'] == 'pedido') {
                                                                                echo $row['detalle'];
                                                                            } else {
                                                                                echo $row['observacion'];
                                                                            }

                                                                            ?></td>
                                            <td class="font-weight-normal"><?php if ($row['monto'] != '') {
                                                                                echo '$' . number_format($row['monto'], 0, '', '.');
                                                                            } ?></td>
                                            <td class="font-weight-normal"><?php if ($row['monto2'] != '') {
                                                                                echo '$' . number_format($row['monto2'], 0, '', '.');
                                                                            } ?></td>


                                            <!--  <td class="font-weight-normal"><?php if ($row['abonada'] == '0') {
                                                                                        echo '<span class="label label-danger">SIN FACTURAR</span>';
                                                                                    } else {
                                                                                        echo '<span class="label label-success">FACTURADA</span>';
                                                                                    } ?></td> -->

                                            <td>
                                                <?php if ($_SESSION['tipo'] == 'sdsd') { ?> <a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                                                    &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#delt_<?php echo $row['id'] ?>" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                                                <?php } ?> </td>
                                            <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['id'] ?></td>
                                        </tr>
                                    <?php
                                        echo '
                                                  <div class="modal fade" id="delt_' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <h4 ><center>Seguro que desea eliminar<br> la transaccion # "' . $row['id'] . '" ?</center></h4>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button onclick="elimina_t(' . $row['id'] . ',2)" class="btn btn-primary">Si, Eliminar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <a href="index.php?pagina=pagos_add" class="btn btn-info btn-rounded">Realizar Nuevo Pago</a>
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
        $(function() {

            $('.filtro').on('change', function() {
                var datodesde = $('#d').val(); // get selected value
                var datohasta = $('#h').val(); // get selected value
                if (datodesde) { // require a URL
                    window.location = 'index.php?pagina=transacciones&d=' + datodesde + '&h=' + datohasta; // redirect
                }
                return false;
            });
        });
    </script>

<?php } ?>