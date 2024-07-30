<?php
$id=$_GET['id'];
$consul_id = $link->query("SELECT * FROM `compra_mercaderia`
                        left join proveedores on proveedores.id_proveedor = compra_mercaderia.prov_compram
                        left join tipo_comprobantes on compra_mercaderia.tipocom_compram = tipo_comprobantes.id_comprobantes
                             WHERE compra_mercaderia.id_compram = '$id'");
  $rowid= mysqli_fetch_array($consul_id);
?>
<div class="container-fluid">
    <!-- .row -->
    <div class="row page-titles">
                    <div class="col-md-12">
                        <h4 class="text-white">Detalle de Compra</h4>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item "><a href="index.php?pagina=comprobantes">Comprobantes</a></li>
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
                <h3><b>COMPRA</b> <span class="pull-right">#<?php echo $id ?></span></h3>
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
                                <h3>Proveedor:</h3>
                                <h4 class="font-bold"><?php echo $rowid['razon_com_proveedor']?></h4>
                                    <br/> Direccion: <?php echo $rowid['direccion_com_proveedor'] .' '. $rowid['dirnum_com_proveedor'] ?>
                                    <br/> Tel: <?php echo $rowid['telefono_com_proveedor']?>,

                                <p class="m-t-30"><b>Fecha de Compra :</b> <i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($rowid['fecha_compram']))?></p>

                            </address>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Codigo</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $num='1';
                                  $subtotal = 0;
                                    $consul_items = $link->query("
                                        SELECT * FROM `productos_comprados`
                                        left join productos on productos.id_producto = productos_comprados.idProducto
                             WHERE productos_comprados.idCMercaderia = '$id'");
                                    while ($row= mysqli_fetch_array($consul_items)){
                                    $subtotal += $row['costo_producto'] * $row['cantidad'];
                                    echo '<tr>
                                        <td>'.$row['cantidad'].'</td>
                                        <td>'.$row['detalle_producto'].'</td>
                                        <td>$'.$row['costo_producto'].'</td>
                                        <td>'.strtoupper($row['codigo_producto']).'</td>
                                        <td class="text-center">'.$num.'</td>
                                    </tr>';
                                    $num++;
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="clearfix"></div>
                        <hr>
                        <h3><b>Tipo y Número de Comprobante:</b></h3>
                        <p><?php echo $rowid['nombre_comprobantes'].':  NRO '.$rowid['numcom_compram']?></p>
                        <hr>

                    </div>
                    <div class="col-md-8">
                        <div class="clearfix"></div>
                        <hr>
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Subtotal</th>
                                    <th>I.V.A</th>
                                    <th>Percepción Ingresos brutos</th>
                                    <th>Percepción I.V.A</th>
                                    <th>Impuestos Internos</th>
                                    <th>Tasas</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $impuestos = $link->query("
                                        SELECT * FROM `impuesto_compra_mercaderia`
                                    WHERE id_compra_mercaderia = '$id'");
                                    $row2= mysqli_fetch_array($impuestos);
                                ?>
                                <tr>
                                    <td>$<?=$subtotal?></td>
                                    <td>$<?=$row2['iva']?></td>
                                    <td>$<?=$row2['iibb']?></td>
                                    <td>$<?=$row2['per_iva']?></td>
                                    <td>$<?=$row2['imp_interno']?></td>
                                    <td>$<?=$row2['tasa']?></td>
                                    <td>$<?=$rowid['total_compra']?></td>
                                </tr>
                            </tbody>
                        </table>
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