<?php
$id = $_GET['id'];
$con_detalle = $link->query("SELECT * FROM `proveedores`
  LEFT JOIN ciudad on ciudad.id_ciudad = proveedores.ciudad_proveedor
  left join provincia on provincia.id_provincia = ciudad.provincia_id
  left join rubros on rubros.id_rubros = rubro_com_proveedor
  WHERE id_proveedor='$id'
  ORDER BY `proveedores`.`razon_com_proveedor` ASC  ");
$row = mysqli_fetch_array($con_detalle);
?>

<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Proveedor <?php echo $row['razon_com_proveedor'] . ' (' . $row['notas_proveedor'] . ')'; ?></h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=proveedores">Proveedor</a></li>
                <li class="breadcrumb-item"><a href="#">Detalle</a></li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block" method="get">
                <input type="hidden" name="pagina" value="clientes">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar...">
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-xlg-3 col-md-3">
            <div class="card"> <img class="card-img" src="/img/rubros/<?php if ($row['imagen_rubros'] != '') {echo $row['imagen_rubros'];} else {echo 'otros.jpg';} ?>" height="456" alt="Imagen del Rubro">
                <div class="card-img-overlay card-inverse text-white social-profile d-flex justify-content-center">
                    <div class="align-self-center"> <img src="img/comercios/<?php echo $row['foto_prov']; ?>" class="img-circle" width="100">
                        <br>
                        <h4 class="card-title" style="padding-top: 10px;"><?php echo $row['razon_com_proveedor']?></h4>
                        <h4 class="card-title">Balance Actual: <br />$<span id="balance2"></span></h4>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body"> <small class="text-muted">Correo </small>
                    <h6><?php echo $row['email_proveedor']; ?></h6>
                    <?php if ($row['telefono_com_proveedor'] != '') { ?> <small class="text-muted p-t-30 db">Telefono</small>
                        <h6><?php echo $row['telefono_com_proveedor'] . '</h6>';
                        } ?>
                        <?php if ($row['celular_com_proveedor'] != '') { ?><small class="text-muted p-t-30 db">Celular</small>
                            <h6><?php echo $row['celular_com_proveedor'] . '</h6>';
                            } ?>
                            <small class="text-muted p-t-30 db">Direccion</small>
                            <h6><?php echo $row['direccion_com_proveedor'] . ' ' . $row['dirnum_com_proveedor'] . '<br/>  ' . ucwords(strtolower($row['ciudad_nombre'])) . ', ' . $row['provincia_nombre']; ?></h6>
                            <div class="map-box">
                                <iframe src="https://maps.google.com/?q=<?php echo $row['lat_proveedor'] ?>,<?php echo $row['lon_proveedor'] ?>&output=embed" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen=""></iframe>
                            </div>

                </div>
            </div>
        </div>

        <div class="col-lg-9 col-xlg-12 col-md-9">
            <div class="card">
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#productos" role="tab">Productos</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home" role="tab">Ultimas Compras</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#notas" role="tab">Notas</a> </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane" id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline">
                                <?php
                                $lista = '';

                                $consulta_movimientos = $link->query("SELECT * FROM `facturas` JOIN tipo_comprobantes ON tipo_comprobantes.id_comprobantes = facturas.tipo
                                     WHERE facturas.id_proveedor = '$id'");

                                while ($row2 = mysqli_fetch_array($consulta_movimientos)) {
                                    $id_factura = $row2['id'];

                                    
                                    $consulta_movimientos2 = $link->query("SELECT * FROM `facturas_pagos`
                                        WHERE id_factura = '$id_factura'");
                                    
                                    
                                    while ($row3 = mysqli_fetch_array($consulta_movimientos2)) {
                                        $fecha_pago = $row3['fecha'];
                                        $observacion_pago = $row3['observaciones'];
                                        $monto_pago = $row3['monto'];

                                        $lista .= '<div class="sl-item">
                                            <div class="sl-left"> <button type="button" class="btn btn-success  btn-circle btn-lg"><i class="ti-money"></i> </button> </div>
                                            <div class="sl-right">
                                                <div>
                                                    <a href="javascript:void(0)" class="link">Pago</a> <span class="sl-date">'.date('d/m/Y', strtotime($fecha_pago)) .'</span>
                                                    <p class="m-t-10">Observación:'.$observacion_pago.'</p>
                                                </div>
                                                <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10"><b>Monto: $'. $monto_pago.'</b></a></div> 
                                            </div>
                                        </div>
                                        <hr>';
                                    }

                                    
                                    $nombre_comprobante = $row2['nombre_comprobantes'];
                                    $fecha = $row2['fecha'];
                                    $nro_comp = $row2['nro_factura'];
                                    $monto = $row2['monto'];
                                    $observacion = $row2['observaciones'];

                                   
                                    echo '<div class="sl-item">
                                        <div class="sl-left"> <button type="button" class="btn btn-warning  btn-circle btn-lg"><i class="ti-shopping-cart-full"></i> </button> </div>
                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link">Compra</a> <span class="sl-date">'.date('d/m/Y', strtotime($fecha)).'</span>
                                                <p class="m-t-10">Nro comprobante: '.$nro_comp.'</p>
                                                <p class="m-t-10">Observación: '.$observacion.'</p>
                                            </div>
                                            <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10"><b>Monto: $'.$monto.'</b></a></div> 
                                        </div>
                                    </div>
                                    <hr>';
                                    echo $lista;

                                    
                                    $lista = '';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane active" id="productos" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Detalle</th>
                                                    <th>Tipo</th>
                                                    <th>Monto</th>
                                                    <th>Saldo</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $filtro = "";
                                                $acumula_pagos = '0';
                                                $acumula_compras = '0';
                                                $saldo = '0';

                                                $consulta_corriente = $link->query("SELECT * FROM `facturas` JOIN tipo_comprobantes ON tipo_comprobantes.id_comprobantes = facturas.tipo
                                     WHERE facturas.id_proveedor = '$id' order by facturas.fecha DESC");
                                                
                                                while ($rowcc = mysqli_fetch_assoc($consulta_corriente)) {
                                                    $saldo = $rowcc['monto'];
                                                    $id_factura= $rowcc['id'];
                                                    $consulta2 = $link->query("SELECT * from facturas_pagos where id_factura='$id_factura'");
                                                    $arrayRow = array();
                                                    while ($rowFp = mysqli_fetch_assoc($consulta2)) {
                                                        $acumula_pagos = $acumula_pagos +$rowFp['monto'];
                                                        $saldo =$saldo-$rowFp['monto'];
                                                    }

                                                    $acumula_compras =  $acumula_compras + $rowcc['monto'];
                                                    
                                                    $rowcc['saldo'] = $saldo;
                                                    $mostrar=true;
                                                    

                                                    if($mostrar){
                                                    ?>
                                                    <tr>

                                                        <td>
                                                        <?php echo date('d/m/Y', strtotime($rowcc['fecha'])) ?>
                                                        </td>
                                                        <td><?php echo $rowcc['observaciones'] ?></td>
                                                        <td><?php echo $rowcc['nombre_comprobantes'] ?></a>             
                                                        <td class="font-weight-normal">$<?php echo $rowcc['monto'] ?></td>
                                                        <td class="font-weight-normal">$<?php echo $saldo ?></td>
                                                        <td class="font-weight-normal"><?php echo $id_factura ?></td>
                                                    </tr>
                                                <?php
                                            }
                                                }
                                                $balance_final = number_format($acumula_compras - $acumula_pagos, 0, '', '.');
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-4"><span class="text-success font-weight-normal">Total de Compras: $<?php echo number_format($acumula_compras, 0, '', '.') ?></span></div>
                                            <div class="col-md-4"><span class="text-danger font-weight-normal">Total de Pagos: $<?php echo number_format($acumula_pagos, 0, '', '.') ?></span></div>
                                            <div class="col-md-4"><span class="text-info font-weight-normal">Balance Total: $<?php echo $balance_final ?></span></div>
                                            <br />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="notas" role="tabpanel">
                        <div class="card-body">
                            <form action="procesos/crud.php" method="post" class="form-horizontal form-material">
                                <input type="hidden" name="accion" value="up_notas_prov">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    Notas sobre el cliente
                                    <div class="col-md-12">
                                        <?php
                                            $proveedor = $link->query("SELECT * FROM `proveedores` WHERE id_proveedor = '$id'");
                                            $rowp = mysqli_fetch_assoc($proveedor);
                                        ?>
                                        <textarea class="form-control" rows="8" name="notas"><?php echo trim($rowp['notas_proveedor']); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Actualizar Notas</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<script>
    $('#balance2').html('<?php echo $balance_final ?>');
</script>