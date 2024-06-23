<?php
require_once '../procesos/functions-online.php';

if (isset($_GET['proveedor'], $_GET['id_pago'])) {
    $proveedor = $_GET['proveedor'];
    $id_pago = $_GET['id_pago'];
    $tipo_pago = isset($_GET['tipo_pago']) ? $_GET['tipo_pago'] : null;


    if ($tipo_pago !== 'cheque') {
        $all_pagos_query = $link->query("SELECT facturas_pagos.*, facturas_pagos.monto AS monto_factura, proveedores.razon_com_proveedor as proveedor
                                        FROM facturas_pagos
                                        INNER JOIN proveedores ON proveedores.id_proveedor = facturas_pagos.id_proveedor
                                        WHERE facturas_pagos.id_proveedor='$proveedor'
                                        AND facturas_pagos.id <= '$id_pago'
                                        ORDER BY facturas_pagos.id ASC");
    } else {
        $all_pagos_query = $link->query("SELECT facturas_cheques.*, facturas_cheques.monto AS monto_factura, facturas_pagos.*, proveedores.razon_com_proveedor as proveedor
                            FROM facturas_cheques
                            INNER JOIN facturas_pagos ON facturas_pagos.id = facturas_cheques.id_pago
                            INNER JOIN proveedores ON proveedores.id_proveedor = facturas_pagos.id_proveedor
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
    $total_factura = 0;
    $total_pago = 0;

    while ($row = mysqli_fetch_assoc($facturas_query)) {
        $total_factura += $row['monto'];
        $nombre_proveedor = $row['proveedor'];
    }

    while ($row2 = mysqli_fetch_assoc($all_pagos_query)) {
        $total_pago += $row2['monto'];
        if (intval($row2['id']) === intval($id_pago)) {
            $facturas_pagos[] = $row2;
        }
    }

    $fecha_de_pago = array_reduce($facturas_pagos, function ($max, $current) {
        return $current['id'] > $max['id'] ? $current : $max;
    }, $facturas_pagos[0]);
    $fecha_de_pago = $fecha_de_pago['fecha_emision'] ?: $fecha_de_pago['fecha'];
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
    <div class="container py-4">
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
                <h3><strong style="color: red;">Big Pollo.</strong></h3>
                <p><strong>CUIT:</strong> 30-71755545-3 <br>
                    PADSA BAHIA SA</p>
            </div>
            <div class="col-md-6 d-flex flex-column align-items-end">
                <p><strong>Fecha de pago:</strong> <?php echo date('d/m/Y', strtotime($fecha_de_pago)); ?></p>
                <br>
                <h3>Total debe a proveedor: $<?php echo number_format($total_factura, 2, ',', '.'); ?></h3>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr style="width: 100%;">
                            <th style="width: 12.5%;">Fecha</th>
                            <th style="width: 12.5%;">Numero de Pago</th>
                            <th style="width: 12.5%;">Tipo</th>
                            <th style="width: 12.5%;">Detalle</th>
                            <th style="width: 12.5%;">Total</th>
                            <th style="width: 12.5%;">Pago</th>
                            <th style="width: 12.5%;">Saldo</th>
                            <th style="width: 12.5%;">Observaciónes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $saldo = $total_factura;

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
                        ?>
                            <tr style="width: 100%;">
                                <td style="width: 12.5%;word-break: break-all;"><?php echo date('d/m/Y', strtotime($factura_pago['fecha'])); ?></td>
                                <td style="width: 12.5%;word-break: break-all;">#<?php echo $factura_pago['id']; ?></td>
                                <td style="width: 12.5%;word-break: break-all;">PAGO</td>
                                <td style="width: 12.5%;word-break: break-all;"><?php echo strtoupper($factura_pago['tipo_pago']); ?></td>
                                <td style="width: 12.5%;word-break: break-all;">$<?php echo number_format($total, 2, ',', '.'); ?></td>
                                <td style="width: 12.5%;word-break: break-all;">$<?php echo number_format($factura_pago['monto_factura'], 2, ',', '.'); ?></td>
                                <td style="width: 12.5%;word-break: break-all;">$<?php echo number_format($saldo, 2, ',', '.'); ?></td>
                                <td style="width: 12.5%;word-break: break-all;"><?php echo $factura_pago['observaciones']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 d-flex justify-content-end">
                <h3 class="border-top py-2"><strong>Total:</strong> $<?php echo number_format($saldo, 2, ',', '.'); ?></h3>
            </div>
        </div>
    </div>
</body>

</html>