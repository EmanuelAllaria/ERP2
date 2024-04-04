<?php
$id=$_GET['id'];
  $consul_id = $link->query("SELECT * FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente WHERE estado='1' and id='$id' ");
  $rowid= mysqli_fetch_array($consul_id);
?><div class="container-fluid">
    <!-- .row -->
    <div class="row page-titles">
                    <div class="col-md-12">
                        <h4 class="text-white">Detalle de Pedido</h4>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Pedidos</a></li>
                            <li class="breadcrumb-item "><a href="#">Detalle</a></li>
                        </ol>
                    </div>
                    <div class="col-md-6 text-right">
                        <form class="app-search d-none d-md-block d-lg-block">
                            <input type="text" class="form-control" placeholder="Buscar...">
                        </form>
                    </div>
                </div>
    <!-- /.row -->
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12">
            <div class="white-box printableArea">
                <h3><b>PEDIDO</b> <span class="pull-right">#<?php echo $id ?></span></h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <address>
                                <h3> &nbsp;<b class="text-danger">Big Pollo.</b></h3>
                                <p class="text-muted m-l-5">CUIT: 00-00000000-0
                                    <br/> José Hernández 935,
                                    <br/> Bahia Blanca - 8000</p>
                            </address>
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <h3>Cliente:</h3>
                                <h4 class="font-bold"><?php echo $rowid['apellido_clientes'].', '.$rowid['nombre_clientes'] ?></h4>
                                <p class="text-muted m-l-30">Comercio: <?php echo $rowid['razon_com_clientes'] ?>,
                                    <br/> <?php echo $rowid['direccion_com_clientes'] .' '. $rowid['dirnum_com_clientes'] ?>
                                    <br/> Tel: <?php echo $rowid['telefono_com_clientes']?>,

                                <p class="m-t-30"><b>Fecha de Pedido :</b> <i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($rowid['fecha']))?></p>

                            </address>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Bonificacion</th>
                                        <th>Codigo</th>
                                        <th>Detalle</th>
                                        <th class="text-right">P. Unitario</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $num='1';
                                  $acumula=0;
                                    $consul_items = $link->query("SELECT * FROM items_pedidos INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE pedido_itemsp='$id' and estado_itemsp='1'");
                                    while ($row= mysqli_fetch_array($consul_items)){
                                      $total = $row['monto_itemsp'] * $row['cantidad_itemsp'];

                                    echo '<tr>
                                        <td class="text-right">'.$row['cantidad_itemsp'].'</td>
                                        <td class="text-right">'.$row['bonifica_itemsp'].'</td>
                                        <td>'.strtoupper($row['codigo_producto']).'</td>
                                        <td>'.$row['detalle_producto'].' '.$row['modelo_producto'].' '.$row['presentacion_producto'].'</td>
                                        <td class="text-right">$ '.number_format($row['monto_itemsp'], 2, ',', '.').'</td>
                                        <td class="text-right">$ '.number_format($total, 2, ',', '.').'</td>
                                        <td class="text-center">'.$num.'</td>
                                    </tr>';
                                    $acumula = $acumula+$total;
                                    $num++;
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <p style="display:none">SubTotal: $ <?php echo number_format($acumula, 2, ',', '.') ?></p>
                            <p style="display:none">IVA (21%): $ <?php echo number_format($acumula * 0.21, 2, ',', '.') ?></p>
                            <hr>
                            <h3><b>Total: </b> $ <?php echo number_format($acumula, 2, ',', '.') ?></h3>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <h3><b>Observaciones:</b></h3>
                        <p><?php echo $rowid['observacion']?></p>
                        <hr>

                    </div>
                </div>
            </div>
            <div class="text-right">
            
                <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Imprimir</span> </button>
            </div>
        </div>
    </div>
  
</div>

<script src="js/jquery.PrintArea.js" type="text/JavaScript"></script>
<script>
$(document).ready(function() {
    $("#print").click(function() {
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close
        };
        $("div.printableArea").printArea(options);
    });
});
</script>
