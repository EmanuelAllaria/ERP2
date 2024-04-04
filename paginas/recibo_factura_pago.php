<?php
require_once '../procesos/functions-online.php';

if (isset($_GET['id_pago'], $_GET['id_factura'])) {
    $id_factura = $_GET['id_factura'];
    $id_pago = $_GET['id_pago'];
    $factura_pago = $link->query("SELECT facturas_pagos.*, facturas.id_proveedor, facturas.monto AS total, proveedores.razon_com_proveedor
                                    FROM facturas_pagos 
                                    INNER JOIN facturas ON facturas.id = facturas_pagos.id_factura 
                                    INNER JOIN proveedores ON proveedores.id_proveedor = facturas.id_proveedor
                                    WHERE facturas_pagos.id_factura='$id_factura' ORDER BY id ASC");
    $factura = null;
    $facturas = array();
    $total_factura = 0;

    while ($row = mysqli_fetch_assoc($factura_pago)) {
        $facturas[] = $row;
        if (intval($row['id']) === intval($id_pago)) {
            $factura = $row;
            $total_factura += $row['total'];
        }
    }

    if ($factura !== null) {
        $facturas_anteriores = array_filter($facturas, function ($f) use ($factura) {
            return intval($f['id']) < intval($factura['id']);
        });

        if (!empty($facturas_anteriores)) {
            foreach ($facturas_anteriores as $factura_anterior) {
                $total_factura -= $factura_anterior['monto'];
            }
        }
    }
} else {
    header('location: ../index.php');
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
                <h2 style="text-align: end;">#<?php echo $id_factura ?></h2>
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
                <h3><strong>Proveedor <?php echo $factura['razon_com_proveedor']; ?></strong></h3>
                <br>
                <p><strong>Fecha de pago:</strong> <?php echo date('d/m/Y', strtotime($factura['fecha_emision'])); ?></p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Numero de Factura</th>
                            <th>Tipo</th>
                            <th>Detalle</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Saldo</th>
                            <th>Observaciónes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($factura['fecha_emision'])); ?></td>
                            <td>#<?php echo $factura['id']; ?></td>
                            <td>PAGO</td>
                            <td>Cheque '<?php echo $factura['numero_cheque']; ?>'</td>
                            <td>$<?php echo number_format($factura['total'], 2, ',', '.'); ?></td>
                            <td>$<?php echo number_format($factura['monto'], 2, ',', '.'); ?></td>
                            <td>$<?php echo number_format(intval($total_factura) - intval($factura['monto']), 2, ',', '.'); ?></td>
                            <td>1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 d-flex justify-content-end">
                <h3 class="border-top py-2"><strong>Total:</strong> $<?php echo number_format(intval($total_factura) - intval($factura['monto']), 2, ',', '.'); ?></h3>
            </div>
        </div>
        <hr>
        <div class="col-md-12">
            <h3>Observaciónes</h3>
            <p>
                <?php echo $factura['observaciones'] ?: '-'; ?>
            </p>
        </div>
    </div>
</body>

</html>