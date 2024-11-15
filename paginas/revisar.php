<?php
session_start();
if (!isset($_SESSION['scanData'])) {
    die("No hay datos escaneados para mostrar.");
}

$scanData = $_SESSION['scanData'];
$tipoSubida = $_SESSION['tipo_subida'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar y Editar Datos Escaneados</title>
    <style>
        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body class="container">
    <form action="procesar.php" method="post">
        <table>
            <thead>
                <tr>
                    <?php if ($tipoSubida === 'factura'): ?>
                        <th>N° Factura</th>
                        <th>Tipo de Factura</th>
                        <th>Total de la Factura</th>
                        <th>SubTotal de la Factura</th>
                        <th>Percepción IVA de la Factura</th>
                        <th>Ingresos Brutos de la Factura</th>
                        <th>CUIT Cliente</th>
                        <th>CUIT Proveedor</th>
                        <th>Código Producto</th>
                        <th>Cantidad Producto</th>
                        <th>Precio Unitario</th>
                    <?php else: ?>
                        <th>Nombre del Banco</th>
                        <th>Lugar del Cheque</th>
                        <th>Número del Cheque</th>
                        <th>Monto del Cheque</th>
                        <th>Nombre del Emisor</th>
                        <th>Fecha de Emisión</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Está Rechazado?</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($tipoSubida === 'factura') {
                    foreach ($scanData['items'] as $index => $item) {
                        die(var_dump($scanData));
                        echo "<tr>
                            <td><input type='text' name='id_factura' value='{$scanData['id_factura']}'></td>
                            <td><input type='text' name='tipo_factura' value='{$scanData['tipo_factura']}'></td>
                            <td><input type='text' name='total' value='{$scanData['total']}'></td>
                            <td><input type='text' name='subtotal' value='{$scanData['subtotal']}'></td>
                            <td><input type='text' name='iva' value='{$scanData['iva']}'></td>
                            <td><input type='text' name='ing_brutos' value='{$scanData['ing_brutos']}'></td>
                            <td><input type='text' name='cuit_cliente' value='{$scanData['cuit_cliente']}'></td>
                            <td><input type='text' name='cuit_proveedor' value='{$scanData['cuit_proveedor']}'></td>
                            <td><input type='text' name='items[$index][codigo]' value='{$item['codigo']}'></td>
                            <td><input type='text' name='items[$index][cantidad]' value='{$item['cantidad']}'></td>
                            <td><input type='text' name='items[$index][price_unity]' value='{$item['price_unity']}'></td>
                        </tr>";
                    }
                } else {
                    $montoCheque = str_replace(['$', '.', ' '], '', $scanData['monto_cheque']);
                    $montoCheque = str_replace(',', '.', $montoCheque);
                    echo "<tr>
                        <td><input type='text' name='nombre_banco' value='{$scanData['nombre_banco']}'></td>
                        <td><input type='text' name='lugar_cheque' value='{$scanData['lugar_cheque']}'></td>
                        <td><input type='text' name='numero_cheque' value='{$scanData['numero_cheque']}'></td>
                        <td><input type='text' name='monto_cheque' value='{$montoCheque}'></td>
                        <td><input type='text' name='nombre_del_cheque' value='{$scanData['nombre_del_cheque']}'></td>
                        <td><input type='text' name='fecha_emision_cheque' value='{$scanData['fecha_emision_cheque']}'></td>
                        <td><input type='text' name='fecha_vencimiento_cheque' value='{$scanData['fecha_vencimiento_cheque']}'></td>
                        <td><select name='rechazado'><option value='1'>Si</option><option selected value='0'>No</option></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <button type="submit">Confirmar y Subir Datos</button>
    </form>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>