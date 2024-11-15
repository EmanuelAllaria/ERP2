<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();
header("Access-Control-Allow-Origin: *");
ini_set('memory_limit', '-1');
date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");

function connectDatabase()
{
    try {
        $remoteDB = new mysqli('localhost', 'u598064194_sistemabig', 'CBV#*Bi0', 'u598064194_sistemabig');
    } catch (\Exception) {
        $remoteDB = new mysqli('localhost', 'root', '', 'bpgestion');
    }
    return $remoteDB;
}

function uploadFileToTotalum($fileTmpPath, $fileName, $fileType, $apiKey)
{
    $uploadUrl = 'https://api.totalum.app/api/v1/files/upload';
    $curl = curl_init();
    $headers = ['api-key: ' . $apiKey];
    $file = new CURLFile($fileTmpPath, $fileType, $fileName);
    $postData = ['file' => $file];

    curl_setopt_array($curl, [
        CURLOPT_URL => $uploadUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SAFE_UPLOAD => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        echo 'Error: ' . curl_error($curl);
        curl_close($curl);
        return false;
    }

    $uploadResponse = json_decode($response, true);
    curl_close($curl);
    return $uploadResponse['data'] ?? false;
}

function scanDocumentWithTotalum($tipoSubida, $fileNameId, $apiKey)
{
    $scanUrl = 'https://api.totalum.app/api/v1/files/scan-document';
    $options = [
        'removeFileAfterScan' => false,
        'returnOcrFullResult' => false,
        'maxPages' => 10,
        'model' => 'scanum'
    ];
    $properties = [];

    if ($tipoSubida === 'factura') {
        $properties = [
            "id_factura" => ["type" => "string", "description" => "Número de factura"],
            "tipo_factura" => ["type" => "string", "description" => "Tipo de factura 'A' o 'B'"],
            "total" => ["type" => "string", "description" => "Total de la factura"],
            "subtotal" => ["type" => "string", "description" => "Total del subtotal de la factura"],
            "iva" => ["type" => "string", "description" => "Total de la 'Percepción IVA' de la factura"],
            "ing_brutos" => ["type" => "string", "description" => "Total de los 'Percepción IIBB' de la factura"],
            "cuit_cliente" => ["type" => "string", "description" => "CUIT del cliente"],
            "cuit_proveedor" => ["type" => "string", "description" => "CUIT del proveedor"],
            "items" => ["type" => "array", "description" => "Productos de la factura", "items" => [
                "type" => "object",
                "properties" => [
                    "codigo" => ["type" => "string", "description" => "Código del producto"],
                    "price_unity" => ["type" => "string", "description" => "Precio unitario del producto en decimal, sin redondear"],
                    "cantidad" => ["type" => "number", "description" => "Cantidad de productos"]
                ]
            ]]
        ];
    } else {
        $properties = [
            "nombre_banco" => ["type" => "string", "description" => "Nombre del banco"],
            "lugar_cheque" => ["type" => "string", "description" => "Lugar de emisión del cheque"],
            "numero_cheque" => ["type" => "string", "description" => "Número del cheque"],
            "monto_cheque" => ["type" => "string", "description" => "Monto del cheque con los centimos"],
            "nombre_del_cheque" => ["type" => "string", "description" => "Nombre del emisor del cheque"],
            "fecha_emision_cheque" => ["type" => "string", "description" => "Fecha de emisión del cheque", "format" => "date"],
            "fecha_vencimiento_cheque" => ["type" => "string", "description" => "Fecha de vencimiento del cheque", "format" => "date"]
        ];
    }

    $postData = json_encode([
        'fileName' => $fileNameId,
        'properties' => $properties,
        'options' => $options
    ]);

    $curl = curl_init();
    $headers = ['Content-Type: application/json', 'api-key: ' . $apiKey];
    curl_setopt_array($curl, [
        CURLOPT_URL => $scanUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        echo 'Error: ' . curl_error($curl);
        curl_close($curl);
        return false;
    }

    $scanResponse = json_decode($response, true);
    curl_close($curl);
    $scanResponse['data']['tipo_subida'] = $tipoSubida;
    return $scanResponse['data'] ?? false;
}

function processInvoiceData($scanData, $link)
{
    $codigo_vistos = [];
    $price_unity_vistos = [];
    $cantidad_vistos = [];
    $total_productos = 0;
    $id_factura = $scanData['id_factura'] ?? null;
    $tipo_factura = isset($scanData['tipo_factura']) ? ($scanData['tipo_factura'] === 'A' ? 1 : 6) : null;
    $total_factura = $scanData['total'] ?? null;
    $cuit_cliente = $scanData['cuit_cliente'] ?? null;
    $cuit_proveedor = $scanData['cuit_proveedor'] ?? null;
    $cuit_str = (string)$cuit_proveedor;
    $cuit_formateado = substr($cuit_str, 0, 2) . '-' . substr($cuit_str, 2, -1) . '-' . substr($cuit_str, -1);
    $cantidad_items = isset($scanData['items']) ? count($scanData['items']) : null;
    $cantidad_items_error = 0;
    $tipo = $scanData['tipo_subida'];
    $nombre_banco = $scanData['nombre_banco'] ?? null;
    $lugar_cheque = $scanData['lugar_cheque'] ?? null;
    $numero_cheque = $scanData['numero_cheque'] ?? null;
    $monto_cheque = $scanData['monto_cheque'] ?? null;
    $nombre_del_cheque = $scanData['nombre_del_cheque'] ?? null;
    $fecha_emision_cheque = $scanData['fecha_emision_cheque'] ?? null;
    $fecha_vencimiento_cheque = $scanData['fecha_vencimiento_cheque'] ?? null;
    // $rechazado = $scanData['rechazado'] ?? null;

    if ($tipo === 'factura') {
        foreach ($scanData['items'] as $producto) {
            if (!in_array($producto['codigo'], $codigo_vistos)) {
                $price_unity = str_replace(',', '', $producto['price_unity']);
                $price_unity_float = (float)$price_unity;
                $codigo_vistos[] = $producto['codigo'];
                $price_unity_vistos[] = $price_unity_float;
                $cantidad_vistos[] = $producto['cantidad'];
                $total_productos += $producto['cantidad'];
            }
        }
    }

    if ($tipo === 'factura') {
        if ($total_productos >= $cantidad_items) {
            $nro_factura_in_bd = $link->query("SELECT nro_factura FROM facturas WHERE nro_factura = '$id_factura'")->fetch_assoc()['nro_factura'];
            if (!isset($nro_factura_in_bd)) {
                $id_proveedor = $link->query("SELECT id_proveedor FROM proveedores WHERE cuitcuil_com_proveedor = '$cuit_proveedor' OR cuitcuil_com_proveedor = '$cuit_formateado'")->fetch_assoc()['id_proveedor'];
                if (isset($id_proveedor)) {
                    try {
                        $id_proveedor = (int)$id_proveedor;
                        $fecha = date('Y-m-d H:i:s');
                        $fechaSinHorario = date('Y-m-d');

                        $link->query("INSERT INTO facturas (nro_factura, id_proveedor, fecha, tipo, monto) VALUES ('$id_factura', '$id_proveedor', '$fecha', '$tipo_factura', '$total_factura')");
                        $link->query("INSERT INTO compra_mercaderia (prov_compram, fecha_compram, tipocom_compram, numcom_compram, ingresastock_compram, estado_compram, cuando_compram) VALUES ('$id_proveedor', '$fechaSinHorario', '$tipo_factura', '$id_factura', '1', '1', '$fecha')");
                        $id_insert_compra_mercaderia = mysqli_insert_id($link);

                        for ($i = 0; $i < $cantidad_items; $i++) {
                            if (isset($codigo_vistos[$i], $price_unity_vistos[$i], $cantidad_vistos[$i])) {
                                $codigo = $codigo_vistos[$i];
                                $price_unity = $price_unity_vistos[$i];
                                $cantidad = $cantidad_vistos[$i];
                                $link->query("INSERT INTO pruebas_escaneo (nro_factura, tipo_factura, cuit_cliente, cuit_proveedor, codigo_producto, cantidad_producto, precio_producto, total_factura, tipo_documento) VALUES ('$id_factura', '$tipo_factura', '$cuit_cliente', '$cuit_proveedor', '$codigo', '$cantidad', '$price_unity', '$total_factura', 'factura')");
                                $id_producto = $link->query("SELECT id_producto FROM productos WHERE codigo_producto = '$codigo'")->fetch_assoc()['id_producto'];
                                $link->query("INSERT INTO productos_comprados (idCMercaderia, idProducto, cantidad) VALUES ('$id_insert_compra_mercaderia', '$id_producto', '$cantidad')");
                                $link->query("UPDATE productos SET stock_producto = stock_producto + $cantidad WHERE codigo_producto = $codigo;");
                            } else {
                                $cantidad_items_error++;
                            }
                        }
                    } catch (\Exception $e) {
                        $cantidad_items_error = count($scanData['items']);
                        echo "<script>console.error('Ha habido un error: " . $e->getMessage() . "');</script>";
                    }
                } else {
                    $cantidad_items_error = count($scanData['items']);
                    echo "<script>alert('No existe ningún proveedor con ese CUIT.');</script>";
                }
            } else {
                $cantidad_items_error = count($scanData['items']);
                echo "<script>alert('Ya existe una factura con el número de factura ingresado.');</script>";
            }
        } else {
            $cantidad_items_error = count($scanData['items']);
            echo "<script>alert('Ha habido un error al subir los datos, inténtelo de nuevo.');</script>";
        }
    } else {
        try {
            // if ($rechazado == 1)  {
            $link->query("INSERT INTO pruebas_escaneo (nombre_banco, lugar_cheque, numero_cheque, monto_cheque, nombre_del_cheque, fecha_emision_cheque, fecha_vencimiento_cheque, tipo_documento) VALUES ('$nombre_banco', '$lugar_cheque', '$numero_cheque', '$monto_cheque', '$nombre_del_cheque', '$fecha_emision_cheque', '$fecha_vencimiento_cheque', 'cheque')");
            // $link->query("INSERT INTO pruebas_escaneo (nombre_banco, lugar_cheque, numero_cheque, monto_cheque, nombre_del_cheque, fecha_emision_cheque, fecha_vencimiento_cheque, tipo_documento) VALUES ('$nombre_banco', '$lugar_cheque', '$numero_cheque', '$monto_cheque', '$nombre_del_cheque', '$fecha_emision_cheque', '$fecha_vencimiento_cheque', 'cheque')");
            // }
        } catch (Exception $e) {
            echo "<script>
                alert('Hubo un error al intentar subir los datos. Por favor, inténtalo de nuevo.');
                window.location.reload();
            </script>";
        }
    }

    $response = [];

    if ($tipo === 'factura') {
        $response = [
            'cantidad_items_error' => $cantidad_items_error,
            'cantidad_items' => $cantidad_items
        ];
    } else {
        $response = [
            'cheque subido' => $scanData
        ];
    }

    return $response;
}

if (isset($_POST['download_excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Cabeceras de las columnas
    $sheet->setCellValue('A1', '#');
    $sheet->setCellValue('B1', 'N° Factura');
    $sheet->setCellValue('C1', 'Tipo de Factura');
    $sheet->setCellValue('D1', 'Total de la Factura');
    $sheet->setCellValue('E1', 'Cuit Del Cliente');
    $sheet->setCellValue('F1', 'Cuit Del Proveedor');
    $sheet->setCellValue('G1', 'Codigo Del Producto');
    $sheet->setCellValue('H1', 'Cantidad Del Producto');
    $sheet->setCellValue('I1', 'Precio Unitario Del Producto');

    // Obtener datos de la base de datos
    $link = connectDatabase();
    $query = $link->query('SELECT * FROM pruebas_escaneo ORDER BY id DESC');
    $rowIndex = 2;

    while ($row = mysqli_fetch_assoc($query)) {
        $sheet->setCellValue('A' . $rowIndex, $row['id']);
        $sheet->setCellValue('B' . $rowIndex, $row['nro_factura']);
        $sheet->setCellValue('C' . $rowIndex, ($row['tipo_factura'] == 1 ? 'Factura A' : 'Factura B'));
        $sheet->setCellValue('D' . $rowIndex, $row['total_factura']);
        $sheet->setCellValue('E' . $rowIndex, $row['cuit_cliente']);
        $sheet->setCellValue('F' . $rowIndex, $row['cuit_proveedor']);
        $sheet->setCellValue('G' . $rowIndex, $row['codigo_producto']);
        $sheet->setCellValue('H' . $rowIndex, $row['cantidad_producto']);
        $sheet->setCellValue('I' . $rowIndex, $row['precio_producto']);
        $rowIndex++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'facturas.xlsx';

    // Enviar archivo Excel al navegador
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    $writer->save("php://output");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['PHP_SELF'], 'paginas/scann-pdf-image.php') !== false) {
    $link = connectDatabase();
    $apiKey = 'sk-eyJrZXkiOiJmMjAwODI5MzQ1ZTZjNDhhYTU3OTAzOWMiLCJuYW1lIjoiRGVmYXVsdCBBUEkgS2V5IGF1dG9nZW5lcmF0ZWQgeml2aSIsIm9yZ2FuaXphdGlvbklkIjoicHJveWVjdG8xMjM0NDM4MjAifQ__';

    if (!isset($_FILES['fileInput']) || $_FILES['fileInput']['error'] !== UPLOAD_ERR_OK) {
        die('Error uploading file.');
    }

    $tipoSubida = $_POST['fileType'];
    $fileTmpPath = $_FILES['fileInput']['tmp_name'];
    $fileName = $_FILES['fileInput']['name'];
    $fileType = $_FILES['fileInput']['type'];

    $fileNameId = uploadFileToTotalum($fileTmpPath, $fileName, $fileType, $apiKey);
    if ($fileNameId) {
        $scanData = scanDocumentWithTotalum($tipoSubida, $fileNameId, $apiKey);
        if (isset($scanData['id_factura']) || isset($scanData['numero_cheque'])) {
            $_SESSION['scanData'] = $scanData;
            $_SESSION['tipo_subida'] = $tipoSubida;
            header("Location: revisar.php");
            exit;
        } else {
            echo 'Error escaneando el documento';
        }
    } else {
        echo 'Error subiendo el archivo';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <style>
        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="fileType">Seleccione tipo de documento para subir:</label>
        <select name="fileType" id="fileType">
            <option value="factura">Factura</option>
            <option value="cheque">Cheque</option>
        </select>
        <br><br>
        <input type="file" name="fileInput" id="fileInput" accept=".pdf, image/*">
        <button type="submit">Subir y Escanear</button>
    </form>
    <br>
    <div style="display: flex; align-items: center; gap: 5px;">
        <form action="" method="get">
            <label for="filter">Filtrar por tipo de documento:</label>
            <select name="filter" id="filter">
                <option value="todos">Todos</option>
                <option value="factura">Factura</option>
                <option value="cheque">Cheque</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
        <?php if (isset($_GET['filter'])) { ?><button onclick="window.location.href = 'scann-pdf-image.php'">Quitar Filtros</button><?php } ?>
    </div>
    <br>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo de Documento</th>
                <th>N° Factura / N° Cheque</th>
                <th>Tipo de Factura / Banco</th>
                <th>Total Factura / Monto Cheque</th>
                <th>Cuit Cliente / Lugar Cheque</th>
                <th>Cuit Proveedor / Nombre del Cheque</th>
                <th>Fecha Emisión</th>
                <th>Fecha Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $link = connectDatabase();
            $filter = isset($_GET['filter']) ? $_GET['filter'] : 'todos';

            if ($filter == 'factura') {
                $query = $link->query("SELECT * FROM pruebas_escaneo WHERE tipo_documento = 'factura' ORDER BY id DESC");
            } elseif ($filter == 'cheque') {
                $query = $link->query("SELECT * FROM pruebas_escaneo WHERE tipo_documento = 'cheque' ORDER BY id DESC");
            } else {
                $query = $link->query("SELECT * FROM pruebas_escaneo ORDER BY id DESC");
            }

            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['tipo_documento']}</td>";
                if ($row['tipo_documento'] == 'factura') {
                    echo "<td>{$row['nro_factura']}</td>
                          <td>" . ($row['tipo_factura'] == 1 ? 'Factura A' : 'Factura B') . "</td>
                          <td>$" . number_format($row['total_factura'], 2, ',', '.') . "</td>
                          <td>{$row['cuit_cliente']}</td>
                          <td>{$row['cuit_proveedor']}</td>
                          <td>-</td>
                          <td>-</td>";
                } else {
                    echo "<td>{$row['numero_cheque']}</td>
                          <td>{$row['nombre_banco']}</td>
                          <td>$" . number_format($row['monto_cheque'], 2, ',', '.') . "</td>
                          <td>{$row['lugar_cheque']}</td>
                          <td>{$row['nombre_del_cheque']}</td>
                          <td>{$row['fecha_emision_cheque']}</td>
                          <td>{$row['fecha_vencimiento_cheque']}</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <form action="" method="post">
        <button type="submit" name="download_excel">Descargar Excel</button>
    </form>
</body>

</html>