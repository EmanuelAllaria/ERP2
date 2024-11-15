<?php
$id=$_GET['id'];
  $consul_id = $link->query("SELECT * FROM `carga_camion`
    INNER JOIN personal on personal.id = carga_camion.personal_cargac
    INNER JOIN stock_depositos on carga_camion.id_cargac = stock_depositos.idcarga_stockd
    WHERE estado_cargac='1' and id_cargac='$id' ");
  $rowid= mysqli_fetch_array($consul_id);
?><div class="container-fluid">
    <!-- .row -->
    <div class="row page-titles">
                    <div class="col-md-12">
                        <h4 class="text-white">Detalle de Carga</h4>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Cargas</a></li>
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
                <h3><b>Carga </b> <span class="pull-right">#<?php echo $id ?></span> || Estado: <?php
                if($rowid['autoriza_cargac']=='0000-00-00 00:00:00' && $row['estado_stockd']=='1'){echo '<span class="badge badge-pill badge-warning">Pendiente</span>';}
                else if($rowid['autoriza_cargac']!='0000-00-00 00:00:00' && $rowid['estado_stockd']=='1'){echo '<span class="badge badge-pill badge-success">Autorizado</span>';}
                else if($rowid['estado_cargac']=='2'){echo '<span class="badge badge-pill badge-danger">Sin Autorizar</span>';}
                else if($rowid['autoriza_cargac']!='0000-00-00 00:00:00' && $rowid['estado_stockd']=='2'){echo '<span class="badge badge-pill badge-info">Liquidada</span>';}

                ?></h3>
                <hr>
                <div class="row">

                    <div class="col-md-12">
                        <div class="pull-left">
                  <!--          <address>
                                <h3> &nbsp;<b class="text-danger">Big Pollo.</b></h3>
                                <p class="text-muted m-l-5">CUIT: 00-00000000-0
                                    <br/> José Hernández 935,
                                    <br/> Bahia Blanca - 8000</p>
                            </address>
                  -->      </div>

                        <div class="pull-right text-right">
                            <address>
                                <h3>Personal:</h3>
                                <h4 class="font-bold"><?php echo $rowid['apellido'].', '.$rowid['nombre'] ?></h4>
                                <!-- <p class="text-muted m-l-30">Comercio: <?php echo $rowid['razon_com_clientes'] ?>,
                                    <br/> <?php echo $rowid['direccion_com_clientes'] .' '. $rowid['dirnum_com_clientes'] ?>
                                    <br/> Tel: <?php echo $rowid['telefono_com_clientes']?>,
                                 -->
                                <p class="m-t-30"><b>Fecha de Carga :</b> <i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($rowid['fecha_cargac']))?></p>

                            </address>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th class="text-center">#</th>
                                        <th class="text-right">Cantidad</th>
                                        <th>Codigo</th>
                                        <th>Detalle</th>
                                        <th class="text-right">presentacion</th>

                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $num='1';
                                  $acumula=0;
                                    $consul_items = $link->query("SELECT * FROM `stock_depositos` INNER JOIN productos on productos.id_producto = stock_depositos.idproducto_stockd WHERE `estado_stockd` != 0 AND `tipomov_stockd` LIKE 'carga' AND `idcarga_stockd` = $id");
                                    while ($row= mysqli_fetch_array($consul_items)){
                                      $total = $row['cantidad_itemsp'];

                                    echo '<tr>
                                        <td class="text-center">'.$num.'</td>
                                        <td class="text-right">'.$row['cantidad_stockd'].'</td>
                                        <td>'.strtoupper($row['codigo_producto']).'</td>
                                        <td>'.$row['detalle_producto'].' '.$row['modelo_producto'].'</td>
                                        <td class="text-right">'.$row['presentacion_producto'].'</td>

                                    </tr>';
                                    $acumula = $acumula+$row['cantidad_stockd'];
                                    $num++;
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <hr>
                            <h3><b>Items: </b>  <?php echo $acumula ?></h3>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <h3><b>Observaciones:</b></h3>
                        <p><?php echo $rowid['observacion_cargac']?></p>
                        <hr>

                    </div>
                </div>
            </div>
            <div class="text-right">

                <button id="print" class="btn btn-info btn-outline" type="button"> <span><i class="fa fa-print"></i> Imprimir</span> </button>
            </div>
        </div>
    </div>
    <!-- .row -->
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
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
