<?php
require_once '../procesos/functions-online.php';

if (isset($_GET['proveedor'], $_GET['id_pago'])) {
    $proveedor = $_GET['proveedor'];
    $id_pago = (int) $_GET['id_pago'];
    $tipo_pago = isset($_GET['tipo_pago']) ? $_GET['tipo_pago'] : null;


    if ($tipo_pago !== 'cheque') {
        $all_pagos_query = $link->query("SELECT
                                            facturas_pagos.*,
                                            facturas_pagos.id AS id_pagos,
                                            facturas_pagos.monto AS monto_factura,
                                            proveedores.razon_com_proveedor AS proveedor,
                                            CASE WHEN facturas_pagos.origen IS NOT NULL AND facturas_pagos.origen != 0 THEN clientes.nombre_clientes ELSE NULL
                                        END AS nombre_clientes,
                                        CASE WHEN facturas_pagos.origen IS NOT NULL AND facturas_pagos.origen != 0 THEN clientes.apellido_clientes ELSE NULL
                                        END AS apellido_clientes
                                        FROM
                                            facturas_pagos
                                        INNER JOIN proveedores ON proveedores.id_proveedor = facturas_pagos.id_proveedor
                                        LEFT JOIN clientes ON facturas_pagos.origen = clientes.id_clientes
                                        WHERE
                                            facturas_pagos.id_proveedor = '$proveedor' AND facturas_pagos.id <= '$id_pago'
                                        ORDER BY
                                            facturas_pagos.id ASC;");
    } else {
        $all_pagos_query = $link->query("SELECT facturas_cheques.*, facturas_cheques.monto AS monto_factura, facturas_pagos.id AS id_pagos, facturas_pagos.id_proveedor, facturas_pagos.tipo_pago, proveedores.razon_com_proveedor as proveedor, clientes.nombre_clientes, clientes.apellido_clientes
                            FROM facturas_cheques
                            INNER JOIN facturas_pagos ON facturas_pagos.id = facturas_cheques.id_pago
                            INNER JOIN proveedores ON proveedores.id_proveedor = facturas_pagos.id_proveedor
                            INNER JOIN clientes ON facturas_cheques.origen = clientes.id_clientes or facturas_pagos.origen = clientes.id_clientes
                            WHERE facturas_pagos.id_proveedor='$proveedor'
                            AND facturas_cheques.id_pago='$id_pago'
                            ORDER BY facturas_cheques.id ASC");
    }

    $facturas_query = $link->query("SELECT facturas.*, proveedores.razon_com_proveedor as proveedor
    FROM facturas
    INNER JOIN proveedores ON proveedores.id_proveedor = facturas.id_proveedor
    WHERE facturas.id_proveedor='$proveedor'
    ORDER BY fecha ASC");

    $facturas_pagos = array();
    $nombre_proveedor = '';
    $nombre_titular = '';
    $cuit_titular = 0;
    $total_factura = 0;
    $total_pago = 0;

    while ($row = mysqli_fetch_assoc($facturas_query)) {
        $total_factura += $row['monto'];
        $nombre_proveedor = $row['proveedor'];
    }

    while ($row2 = mysqli_fetch_assoc($all_pagos_query)) {
        $nombre_proveedor = $row2['proveedor'];
        $nombre_titular = $row2['titular'];
        $total_pago += $row2['monto'];
        if (intval($row2['id_pagos']) === intval($id_pago)) {
            $facturas_pagos[] = $row2;
        }
    }

    $fecha_de_pago = array_reduce($facturas_pagos, function ($max, $current) {
        return $current['id_pagos'] > $max['id_pagos'] ? $current : $max;
    }, $facturas_pagos[0]);
    $fecha_de_pago = $fecha_de_pago['fecha'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="p-4">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="col-md">
                <h2>Recibo de Pago</h2>
            </div>
            <div class="col-md">
                <h2 style="text-align: end;">#<?php echo $id_pago ?></h2>
            </div>
        </div>
        <hr>
        <div class="row mt-4">
            <div class="col-md-6">
                <h3><strong style="color: red;"><?php echo $nombre_titular ?></strong></h3>
                <p><strong>CUIT:</strong> <?php echo $nombre_titular === 'PADSA BAHIA SA' ? '30-71755545-3' : ($nombre_titular === 'DPA GROUP SA' ? '30-71755562-3' : null) ?></p>
            </div>
            <div class="col-md-6 d-flex flex-column align-items-end">
                <p><strong>Fecha de pago:</strong> <?php echo date('d/m/Y', strtotime($fecha_de_pago)); ?></p>
                <h3>Proveedor: <b><?php echo $nombre_proveedor; ?></b></h3>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12" style="overflow-y:auto;width:100%;">
                <table class="table table-bordered">
                    <thead>
                        <tr style="width: 100%;">
                            <?php echo $tipo_pago === 'cheque' ? '<th>#</th>' : '' ?>
                            <?php echo $tipo_pago === 'cheque' ? '<th>N° de Cheque</th>' : '' ?>
                            <?php echo $tipo_pago === 'cheque' ? '<th>Fecha Emisión</th>' : '' ?>
                            <?php echo $tipo_pago === 'cheque' ? '<th>Fecha Cobro</th>' : '' ?>
                            <th>Titular</th>
                            <?php echo $tipo_pago === 'mp' ? '<th>N° de Comprobante</th>' : '' ?>
                            <th>Banco</th>
                            <th>Cuit</th>
                            <th>Tipo Pago</th>
                            <th>Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $saldo = $total_factura;
                        $montoTotal = 0;

                        foreach ($facturas_pagos as $key => $factura_pago) {
                            $total = $saldo;
                            if (intval($factura_pago['monto_factura']) > 0) {
                                $saldo -= $factura_pago['monto_factura'];
                            } else {
                                if ($saldo > 0) {
                                    $saldo += $factura_pago['monto_factura'];
                                } else {
                                    $saldo -= +$factura_pago['monto_factura'];
                                }
                            }
                            $montoTotal += $factura_pago['monto_factura'];
                        ?>
                            <tr style="width: 100%;">
                                <?php echo $tipo_pago === 'cheque' ? '<td>' . $factura_pago['id'] . '</td>' : '' ?>
                                <?php echo $tipo_pago === 'cheque' ? '<td>' . $factura_pago["numero_cheque"] . '</td>' : '' ?>
                                <?php echo $tipo_pago === 'cheque' ? '<td>' . ($factura_pago['tipo_pago'] === 'cheque' && !is_null($factura_pago['fecha_emision']) && $factura_pago['fecha_emision'] !== '0000-00-00'
                                    ? date('d/m/Y', strtotime($factura_pago['fecha_emision']))
                                    : '00/00/0000') . '</td>' : '' ?>
                                <?php echo $tipo_pago === 'cheque' ? '<td>' . ($factura_pago['tipo_pago'] === 'cheque' && !is_null($factura_pago['fecha_cobro']) && $factura_pago['fecha_cobro'] !== '0000-00-00'
                                    ? date('d/m/Y', strtotime($factura_pago['fecha_cobro']))
                                    : '00/00/0000') . '</td>' : '' ?>
                                <td><?php echo $factura_pago['titular']; ?></td>
                                <td><?php echo $tipo_pago === 'mp' ? $factura_pago['nro_comprobante'] : '' ?></td>
                                <td><?php echo $factura_pago['banco']; ?></td>
                                <td><?php echo $factura_pago['cuit']; ?></td>
                                <td><?php echo $factura_pago['tipo_pago']; ?></td>
                                <td>$<?php echo number_format($factura_pago['monto_factura'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <p style="text-align:end;font-weight:bold;font-size:xx-large;">Total: $ <?php echo number_format($montoTotal, 2, ',', '.') ?></p>
            </div>
        </div>
    </div>
</body>

</html>