<?php
header("Access-Control-Allow-Origin: *");
ini_set('memory_limit', '-1');

date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");

try {
    $link = new mysqli('localhost', 'u598064194_sistemabig', 'CBV#*Bi0', 'u598064194_sistemabig');
    if ($link->connect_error) {
        throw new Exception("Connection failed: " . $link->connect_error);
    }
} catch (Exception $e) {
    $link = new mysqli('localhost', 'root', '', 'bpgestion');
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }
}

function scanInvoice($fileInput, $link)
{
    $cantidad_items_error = 0;
    $uploadUrl = 'https://api.totalum.app/api/v1/files/upload';
    $scanUrl = 'https://api.totalum.app/api/v1/files/scan-document';
    $apiKey = 'sk-eyJrZXkiOiJjZWRjZDZkMDNmYzA3NmExY2RlNGJhMDQiLCJuYW1lIjoiRGVmYXVsdCBBUEkgS2V5IGF1dG9nZW5lcmF0ZWQgbWxjNSIsIm9yZ2FuaXphdGlvbklkIjoibXktcHJveWVjdC10ZXN0In0_';

    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
        die('Error uploading file.');
    }

    $fileTmpPath = $_FILES[$fileInput]['tmp_name'];
    $fileName = $_FILES[$fileInput]['name'];
    $fileType = $_FILES[$fileInput]['type'];

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
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        echo 'Error: ' . curl_error($curl);
        curl_close($curl);
        return;
    }

    $uploadResponse = json_decode($response, true);
    $fileNameId = $uploadResponse['data'];

    $options = [
        'removeFileAfterScan' => false,
        'returnOcrFullResult' => false,
        'maxPages' => 10,
        'model' => 'scanum'
    ];

    $properties = [
        "id_factura" => ["type" => "string", "description" => "el numero de factura"],
        "tipo_factura" => ["type" => "string", "description" => "el tipo de la factura"],
        "total" => ["type" => "number", "description" => "el total de la factura"],
        "cuit_cliente" => ["type" => "number", "description" => "el cuit del cliente"],
        "cuit_proveedor" => ["type" => "number", "description" => "el cuit del proveedor"],
        "items" => ["type" => "array", "description" => "productos de la factura", "items" => [
            "type" => "object", "properties" => [
                "codigo" => ["type" => "number", "description" => "el codigo de cada producto"],
                "price_unity" => ["type" => "number", "description" => "el precio por unidad de cada producto"],
                "cantidad" => ["type" => "number", "description" => "la cantidad de productos de cada item"]
            ]
        ]]
    ];

    $postData = json_encode([
        'fileName' => $fileNameId,
        'properties' => $properties,
        'options' => $options
    ]);

    $headers = ['Content-Type: application/json', 'api-key: ' . $apiKey];
    curl_setopt_array($curl, [
        CURLOPT_URL => $scanUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $scanResponse = curl_exec($curl);
    if ($scanResponse === false) {
        echo 'Error: ' . curl_error($curl);
    } else {
        $scanData = json_decode($scanResponse, true);
        $codigo_vistos = [];
        $price_unity_vistos = [];
        $cantidad_vistos = [];
        $total_productos = 0;
        $id_factura = $scanData['data']['id_factura'];
        $tipo_factura = $scanData['data']['tipo_factura'] === 'A' ? 1 : 6;
        $total_factura = $scanData['data']['total'];
        $cuit_cliente = $scanData['data']['cuit_cliente'];
        $cuit_proveedor = $scanData['data']['cuit_proveedor'];
        $cantidad_items = count($scanData['data']['items']);

        foreach ($scanData['data']['items'] as $producto) {
            if (!in_array($producto['codigo'], $codigo_vistos)) {
                $codigo_vistos[] = $producto['codigo'];
                $price_unity_vistos[] = $producto['price_unity'];
                $cantidad_vistos[] = $producto['cantidad'];
                $total_productos += $producto['cantidad'];
            }
        }

        if ($total_productos >= $cantidad_items) {
            $nro_factura_in_bd = mysqli_fetch_assoc($link->query("SELECT nro_factura FROM facturas WHERE nro_factura = '$id_factura'"));
            if (!isset($nro_factura_in_bd) || count($nro_factura_in_bd) == 0) {
                $id_proveedor = mysqli_fetch_assoc($link->query("SELECT id_proveedor FROM proveedores WHERE cuitcuil_com_proveedor = '$cuit_proveedor'"));
                if (isset($id_proveedor) && count($id_proveedor) > 0) {
                    try {
                        $id_proveedor = $id_proveedor['id_proveedor'];
                        $fecha = date('Y-m-d H:i:s');
                        $fechaSinHorario = date('Y-m-d');
                        $link->query("INSERT INTO facturas (nro_factura, id_proveedor, fecha, tipo, monto) VALUES ('$id_factura', '$id_proveedor', '$fecha', '$tipo_factura', '$total_factura')");

                        $insert_compra_mercaderia = $link->query("INSERT INTO compra_mercaderia (prov_compram, fecha_compram, tipocom_compram, numcom_compram, ingresastock_compram, estado_compram, cuando_compram) VALUES ('$id_proveedor', '$fechaSinHorario', '$tipo_factura', '1', '1', '$fecha')");
                        $id_insert_compra_mercaderia = mysqli_insert_id($insert_compra_mercaderia);

                        $stmt = $link->prepare("INSERT INTO pruebas_escaneo (nro_factura, tipo_factura, cuit_cliente, cuit_proveedor, codigo_producto, cantidad_producto, precio_producto, total_factura) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        for ($i = 0; $i < $cantidad_items; $i++) {
                            if (isset($codigo_vistos[$i], $price_unity_vistos[$i], $cantidad_vistos[$i])) {
                                $codigo = $codigo_vistos[$i];
                                $price_unity = $price_unity_vistos[$i];
                                $cantidad = $cantidad_vistos[$i];
                                $id_producto = mysqli_fetch_assoc($link->query("SELECT id_producto FROM productos WHERE codigo_producto = '$codigo'"))['id_producto'];
                                $link->query("INSERT INTO productos_comprados (idCMercaderia, idProducto, cantidad) VALUES ('$id_insert_compra_mercaderia', '$id_producto', '$cantidad')");
                                $stmt->bind_param('sisissidd', $id_factura, $tipo_factura, $cuit_cliente, $cuit_proveedor, $codigo, $cantidad, $price_unity, $total_factura);
                                $stmt->execute();
                            } else {
                                $cantidad_items_error++;
                            }
                        }
                        $stmt->close();
                    } catch (\Exception $e) {
                        $cantidad_items_error = count($scanData['data']['items']);
                        echo "<script>console.error('Ha habido un error: " . $e->getMessage() . "');</script>";
                    }
                } else {
                    $cantidad_items_error = count($scanData['data']['items']);
                    echo "<script>alert('No existe ningun proveedor con ese cuil.');</script>";
                }
            }
        } else {
            $cantidad_items_error = count($scanData['data']['items']);
            echo "<script>alert('Ha habido un error al subir los datos, intentelo de nuevo.');</script>";
        }
    }

    curl_close($curl);
    $cantidad_items = count($scanData['data']['items']);
    return ['cantidad_items_error' => $cantidad_items_error, 'cantidad_items' => $cantidad_items];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = scanInvoice('fileInput', $link);
    $cantidad_items_error = $response['cantidad_items_error'];
    $cantidad_items = $response['cantidad_items'];

    if ($cantidad_items_error > 0) {
        if ($cantidad_items_error === $cantidad_items) {
            echo "<script>alert('No se pudieron subir los datos.');</script>";
        } else {
            echo "<script>alert('Se subieron los datos con exito. Pero hubieron $cantidad_items_error errores.');</script>";
        }
    } else {
        echo "<script>alert('Se subieron todos los datos con exito.');</script>";
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
        <input type="file" name="fileInput" id="fileInput" accept=".pdf, image/*">
        <button type="submit">Upload and Scan</button>
    </form>
    <br>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>N° Factura</th>
                <th>Tipo de Factura</th>
                <th>Total de la Factura</th>
                <th>Cuit Del Cliente</th>
                <th>Cuit Del Proveedor</th>
                <th>Codigo Del Producto</th>
                <th>Cantidad Del Producto</th>
                <th>Precio Unitario Del Producto</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $link->query('SELECT * FROM pruebas_escaneo');
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nro_factura']}</td>
                    <td>" . ($row['tipo_factura'] == 1 ? 'Factura A' : 'Factura B') . "</td>
                    <td>$" . number_format($row['total_factura'], 2, ',', '.') . "</td>
                    <td>{$row['cuit_cliente']}</td>
                    <td>{$row['cuit_proveedor']}</td>
                    <td>{$row['codigo_producto']}</td>
                    <td>{$row['cantidad_producto']}</td>
                    <td>$" . number_format($row['precio_producto'], 2, ',', '.') . "</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>