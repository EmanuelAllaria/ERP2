<?php
// Verifica si el parámetro id está presente
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verifica la conexión a la base de datos
    if ($link) {
        // Utiliza consultas preparadas para evitar inyecciones SQL
        $stmt = $link->prepare("
            SELECT * FROM `proveedores`
            LEFT JOIN ciudad on ciudad.id_ciudad = proveedores.ciudad_proveedor
            LEFT JOIN provincia on provincia.id_provincia = ciudad.provincia_id
            LEFT JOIN rubros on rubros.id_rubros = rubro_com_proveedor
            WHERE id_proveedor=?
            ORDER BY `proveedores`.`razon_com_proveedor` ASC
        ");

        // Verifica si la preparación de la consulta fue exitosa
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        } else {
            die("Error al preparar la consulta: " . $link->error);
        }
    } else {
        die("Error de conexión a la base de datos");
    }
} else {
    die("El parámetro id no está presente en la solicitud");
}
?>

<div class="container-fluid">
    <!-- Código HTML para mostrar la información del proveedor y las pestañas -->

    <div class="row">
        <div class="col-lg-3 col-xlg-3 col-md-3">
            <div class="card">
                <img class="card-img" src="/img/rubros/<?php echo $row['imagen_rubros'] != '' ? $row['imagen_rubros'] : 'otros.jpg'; ?>" height="456" alt="Imagen del Rubro">
                <div class="card-img-overlay card-inverse text-white social-profile d-flex justify-content-center">
                    <div class="align-self-center">
                        <img src="img/comercios/<?php echo $row['foto_prov']; ?>" class="img-circle" width="100">
                        <br>
                        <h4 class="card-title" style="padding-top: 10px;"><?php echo $row['razon_com_proveedor'] ?></h4>
                        <h4 class="card-title">Balance Actual: <br />$<span id="balance2"></span></h4>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Correo </small>
                    <h6><?php echo $row['email_proveedor']; ?></h6>
                    <?php if ($row['telefono_com_proveedor'] != '') { ?>
                        <small class="text-muted p-t-30 db">Teléfono</small>
                        <h6><?php echo $row['telefono_com_proveedor']; ?></h6>
                    <?php } ?>
                    <?php if ($row['celular_com_proveedor'] != '') { ?>
                        <small class="text-muted p-t-30 db">Celular</small>
                        <h6><?php echo $row['celular_com_proveedor']; ?></h6>
                    <?php } ?>
                    <small class="text-muted p-t-30 db">Dirección</small>
                    <h6><?php echo $row['direccion_com_proveedor'] . ' ' . $row['dirnum_com_proveedor'] . '<br/> ' . ucwords(strtolower($row['ciudad_nombre'])) . ', ' . $row['provincia_nombre']; ?></h6>
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
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home" role="tab">Últimas Compras</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#notas" role="tab">Notas</a> </li>
                </ul>

                <div class="tab-content">

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
                                                $acumula_pagos = 0;
                                                $acumula_compras = 0;
                                                $saldo = 0;

                                                $consulta_corriente = $link->prepare("SELECT * FROM `facturas` JOIN tipo_comprobantes ON tipo_comprobantes.id_comprobantes = facturas.tipo WHERE facturas.id_proveedor = ? ORDER BY facturas.fecha DESC");
                                                $consulta_corriente->bind_param("i", $id);
                                                $consulta_corriente->execute();
                                                $result_corriente = $consulta_corriente->get_result();

                                                while ($rowcc = $result_corriente->fetch_assoc()) {
                                                    $saldo = $rowcc['monto'];
                                                    $id_proveedor = $rowcc['id_proveedor'];

                                                    $consulta2 = $link->prepare("SELECT * FROM facturas_pagos WHERE id_proveedor = ?");
                                                    $consulta2->bind_param("i", $id_proveedor);
                                                    $consulta2->execute();
                                                    $result2 = $consulta2->get_result();

                                                    while ($rowFp = $result2->fetch_assoc()) {
                                                        $acumula_pagos += $rowFp['monto'];
                                                        $saldo -= $rowFp['monto'];
                                                    }

                                                    $acumula_compras += $rowcc['monto'];
                                                    $rowcc['saldo'] = $saldo;
                                                    $mostrar = true;

                                                    if ($mostrar) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo date('d/m/Y', strtotime($rowcc['fecha'])) ?></td>
                                                            <td><?php echo $rowcc['observaciones'] ?></td>
                                                            <td><?php echo $rowcc['nombre_comprobantes'] ?></td>
                                                            <td class="font-weight-normal">$<?php echo $rowcc['monto'] ?></td>
                                                            <td class="font-weight-normal">$<?php echo $saldo ?></td>
                                                            <td class="font-weight-normal"><?php echo $rowcc['id'] ?></td>
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

                    <div class="tab-pane" id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline">
                                <?php
                                $lista = '';

                                $consulta_movimientos = $link->prepare("SELECT * FROM `facturas` JOIN tipo_comprobantes ON tipo_comprobantes.id_comprobantes = facturas.tipo WHERE facturas.id_proveedor = ?");
                                $consulta_movimientos->bind_param("i", $id);
                                $consulta_movimientos->execute();
                                $result_movimientos = $consulta_movimientos->get_result();

                                while ($row2 = $result_movimientos->fetch_assoc()) {
                                    $id_factura = $row2['id'];

                                    $consulta_movimientos2 = $link->prepare("SELECT * FROM `facturas_pagos` WHERE id_factura = ?");
                                    $consulta_movimientos2->bind_param("i", $id_factura);
                                    $consulta_movimientos2->execute();
                                    $result_movimientos2 = $consulta_movimientos2->get_result();

                                    while ($row3 = $result_movimientos2->fetch_assoc()) {
                                        $fecha_pago = $row3['fecha'];
                                        $observacion_pago = $row3['observaciones'];
                                        $monto_pago = $row3['monto'];

                                        $lista .= '<div class="sl-item">
                                            <div class="sl-left"> <button type="button" class="btn btn-success btn-circle btn-lg"><i class="ti-money"></i> </button> </div>
                                            <div class="sl-right">
                                                <div>
                                                    <a href="javascript:void(0)" class="link">Pago</a> <span class="sl-date">' . date('d/m/Y', strtotime($fecha_pago)) . '</span>
                                                    <p class="m-t-10">Observación: ' . $observacion_pago . '</p>
                                                </div>
                                                <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10"><b>Monto: $' . $monto_pago . '</b></a></div> 
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
                                        <div class="sl-left"> <button type="button" class="btn btn-warning btn-circle btn-lg"><i class="ti-shopping-cart-full"></i> </button> </div>
                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link">Compra</a> <span class="sl-date">' . date('d/m/Y', strtotime($fecha)) . '</span>
                                                <p class="m-t-10">Nro comprobante: ' . $nro_comp . '</p>
                                                <p class="m-t-10">Observación: ' . $observacion . '</p>
                                            </div>
                                            <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10"><b>Monto: $' . $monto . '</b></a></div> 
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

                    <div class="tab-pane" id="notas" role="tabpanel">
                        <div class="card-body">
                            <form action="procesos/crud.php" method="post" class="form-horizontal form-material">
                                <input type="hidden" name="accion" value="up_notas_prov">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    Notas sobre el cliente
                                    <div class="col-md-12">
                                        <?php
                                        $proveedor = $link->prepare("SELECT * FROM `proveedores` WHERE id_proveedor = ?");
                                        $proveedor->bind_param("i", $id);
                                        $proveedor->execute();
                                        $result_proveedor = $proveedor->get_result();
                                        $rowp = $result_proveedor->fetch_assoc();
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
    document.getElementById('balance2').innerText = '<?php echo $balance_final ?>';
</script>