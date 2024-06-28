<?php
$provedores_id = [];
?>

<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Listado de Deudas a Proveedores</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=deudas">Deudas a Proveedores</a></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Listado</h4>
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
                                                        $provedores_id[] = intval($row['id']);
                                                        echo '<option value="' . $row['id'] . '"';
                                                        if (isset($_GET['p']) && $_GET['p'] == $row['id']) {
                                                            echo ' selected ';
                                                        }
                                                        echo '>' . $row['nombre'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="align-self: center;">
                                                <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                                            </div>
                                            <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p'])) { ?>
                                                <div class="col-md-2" style="align-self: center;"><a href="index.php?pagina=deudas">Quitar Filtros</a></div>
                                            <?php } ?>

                                            <div id="total_periodo">Total $</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="width: 100%;">
                                    <th style="width: 14.2857142857142%;">Proveedor</th>
                                    <th style="width: 14.2857142857142%;">Fecha</th>
                                    <th style="width: 14.2857142857142%;">Debe</th>
                                    <th style="width: 14.2857142857142%;">Haber</th>
                                    <th style="width: 14.2857142857142%;">Saldo</th>
                                    <th style="width: 14.2857142857142%;">Observaciones</th>
                                    <th style="width: 14.2857142857142%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista_facturas">
                                <?php
                                $busqueda = '';
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
                                if (isset($_GET['p'], $_GET['proveedor']) && ($_GET['p'] != '' || $_GET['proveedor'] != '')) {
                                    $busqueda = $busqueda . ' and facturas.id_proveedor = ' . $_GET['p'] ?: $_GET['proveedor'];
                                } else {
                                    $vendedor = '';
                                }

                                $sqlDeuda = "SELECT facturas.*, proveedores.razon_com_proveedor AS proveedor, tipo_comprobantes.nombre_comprobantes, SUM(facturas.monto) AS total_factura
                                            FROM facturas
                                            LEFT JOIN proveedores ON facturas.id_proveedor = proveedores.id_proveedor
                                            LEFT JOIN tipo_comprobantes ON facturas.tipo = tipo_comprobantes.id_comprobantes
                                            WHERE facturas.id > 0
                                            $busqueda
                                            GROUP BY facturas.id_proveedor
                                            ORDER BY facturas.fecha ASC";

                                $saldo_final = 0;
                                $debe_total = 0;
                                $haber_total = 0;

                                $con_facturas = $link->query($sqlDeuda);
                                $ids_proveedores_buscados = [];

                                while ($row = mysqli_fetch_assoc($con_facturas)) {
                                    $ids_proveedores_buscados[] = intval($row['id_proveedor']);
                                    $consulta_pagos = $link->query("SELECT
                                                                    id, id_proveedor, tipo_pago, fecha, fecha_emision, observaciones, SUM(monto) as monto
                                                                    FROM facturas_pagos
                                                                    WHERE id_proveedor='{$row['id_proveedor']}'
                                                                    GROUP BY id_proveedor
                                                                    ORDER BY fecha_emision ASC");
                                    $saldo_factura = $row['total_factura'];
                                    $debe_total += ($row['total_factura'] > 0) ? $row['total_factura'] : 0;
                                    $haber_total += ($row['total_factura'] < 0) ? abs($row['total_factura']) : 0;

                                    echo "<tr>";
                                    echo "<td style='width: 14.2857142857142%;word-break: break-all;'>{$row['proveedor']}</td>";
                                    echo "<td style='width: 14.2857142857142%;word-break: break-all;'>{$row['fecha']}</td>";
                                    echo "<td style='color: red;width: 14.2857142857142%;word-break: break-all;'>$" . number_format(($row['total_factura'] > 0) ? $row['total_factura'] : 0, 2, ',', '.') . "</td>";
                                    echo "<td style='color: green;width: 14.2857142857142%;word-break: break-all;'>$" . number_format(($row['total_factura'] < 0) ? abs($row['total_factura']) : 0, 2, ',', '.') . "</td>";
                                    echo "<td style='width: 14.2857142857142%;word-break: break-all;'>$" . number_format($saldo_factura, 2, ',', '.') . "</td>";
                                    echo "<td style='width: 14.2857142857142%;word-break: break-all;'>{$row['observaciones']}</td>";
                                    echo "<td style='width: 14.2857142857142%;word-break: break-all;'><a href='index.php?pagina=facturas&proveedor={$row['id_proveedor']}' class='btn btn-info btn-lg' role='button'>Ver Facturas</a></td>";
                                    echo "</tr>";

                                    while ($pago = mysqli_fetch_assoc($consulta_pagos)) {
                                        $haber_total += $pago['monto'];
                                        $saldo_factura -= $pago['monto'];
                                        echo "<tr>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;'>{$row['proveedor']}</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;'>{$pago['fecha_emision']}</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;'></td>";
                                        echo "<td style='color: green;width: 14.2857142857142%;word-break: break-all;'>$" . number_format($haber_total, 2, ',', '.') . "</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;'>$" . number_format($saldo_factura, 2, ',', '.') . "</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;'>{$pago['observaciones']}</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;'><a href='index.php?pagina=pagos&proveedor={$row['id_proveedor']}' class='btn btn-info btn-lg' role='button'>Ver Pagos</a></td>";
                                        echo "</tr>";
                                    }
                                    $saldo_final += $saldo_factura;
                                }
                                $proveedores_faltantes = array_diff($provedores_id, $ids_proveedores_buscados);
                                if ((!isset($busqueda) || $busqueda === '') && isset($proveedores_faltantes[0])) {
                                    $consulta_pagos_a_favor = $link->query("SELECT facturas_pagos.id, facturas_pagos.id_proveedor, facturas_pagos.tipo_pago, facturas_pagos.fecha, facturas_pagos.fecha_emision, facturas_pagos.observaciones, SUM(facturas_pagos.monto) as monto, proveedores.razon_com_proveedor AS proveedor
                                                                    FROM facturas_pagos
                                                                    LEFT JOIN proveedores ON facturas_pagos.id_proveedor = proveedores.id_proveedor
                                                                    WHERE facturas_pagos.id_proveedor IN (" . implode(', ', $proveedores_faltantes) . ")
                                                                    GROUP BY facturas_pagos.id_proveedor
                                                                    ORDER BY facturas_pagos.fecha_emision ASC");

                                    while ($pago_a_favor = mysqli_fetch_assoc($consulta_pagos_a_favor)) {
                                        $haber_total += $pago_a_favor['monto'];
                                        $saldo_factura -= $pago_a_favor['monto'];
                                        echo "<tr>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;color:green;'>{$pago_a_favor['proveedor']}</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;color:green;'>{$pago_a_favor['fecha_emision']}</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;color:green;'></td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;color:green;'>$" . number_format($pago_a_favor['monto'], 2, ',', '.') . "</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;color:green;'>$" . number_format(0, 2, ',', '.') . "</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;color:green;'>Pago a Favor a: {$pago_a_favor['proveedor']}</td>";
                                        echo "<td style='width: 14.2857142857142%;word-break: break-all;color:green;'><a href='index.php?pagina=pagos&proveedor={$pago_a_favor['id_proveedor']}' class='btn btn-info btn-lg' role='button'>Ver Pagos</a></td>";
                                        echo "</tr>";
                                    }
                                    $saldo_final -= $saldo_factura;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Totales</strong></td>
                                    <td><strong style='color: red;'>$<?php echo number_format($debe_total, 2, ',', '.'); ?></strong></td>
                                    <td><strong style='color: green;'>$<?php echo number_format($haber_total, 2, ',', '.'); ?></strong></td>
                                    <td><strong>$<?php echo number_format($saldo_final, 2, ',', '.'); ?></strong></td>
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
    function filtrar_prov() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('pagina', 'deudas');

        const desde = document.getElementById('d').value;
        const hasta = document.getElementById('h').value;
        const prov = document.getElementById('proveedorsel').value;

        if (desde) urlParams.set('d', desde);
        if (hasta) urlParams.set('h', hasta);
        if (prov) urlParams.set('p', prov);

        window.location.search = urlParams.toString();
    }

    $('#total_periodo').html('<span class="btn <?php echo $saldo_final > 0 ? 'btn-danger' : 'btn-success' ?> pull-right"><b>TOTAL: $<?php echo number_format($saldo_final, 0, '', '.'); ?></b></span>')
</script>