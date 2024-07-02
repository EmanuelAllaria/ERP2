<?php

function scanInvoice($fileInput)
{
    // API endpoint URLs
    $uploadUrl = 'https://api.totalum.app/api/v1/files/upload';
    $scanUrl = 'https://api.totalum.app/api/v1/files/scan-document';

    // Replace with your actual API key
    $apiKey = 'sk-eyJrZXkiOiI4MDM1NjRkODNkZGMxMmJhZjI4ZmNkMjQiLCJuYW1lIjoiRGVmYXVsdCBBUEkgS2V5IGF1dG9nZW5lcmF0ZWQgMG1sMSIsIm9yZ2FuaXphdGlvbklkIjoicHJ1ZWJhLXR0In0_';

    // Check if a file was uploaded
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
        die('Error uploading file.');
    }

    // File details
    $fileTmpPath = $_FILES[$fileInput]['tmp_name'];
    $fileName = $_FILES[$fileInput]['name'];
    $fileType = $_FILES[$fileInput]['type'];

    // Initialize cURL session
    $curl = curl_init();

    // Prepare headers for file upload
    $headers = [
        'api-key: ' . $apiKey
    ];

    // Initialize a cURL file handle for the file
    $file = new CURLFile($fileTmpPath, $fileType, $fileName);

    // Setup POST data for file upload
    $postData = [
        'file' => $file
    ];

    // Set cURL options for file upload
    curl_setopt_array($curl, [
        CURLOPT_URL => $uploadUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SAFE_UPLOAD => true, // Required for PHP 5.6+
        CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification (for debugging)
        CURLOPT_SSL_VERIFYHOST => false // Disable SSL verification (for debugging)
    ]);

    // Execute the POST request for file upload
    $response = curl_exec($curl);

    // Check for errors in file upload
    if ($response === false) {
        echo 'Error: ' . curl_error($curl);
        curl_close($curl);
        return;
    }

    // Decode JSON response
    $uploadResponse = json_decode($response, true);

    // Extract file ID from upload response
    $fileNameId = $uploadResponse['data'];

    // Specify scan options
    $options = [
        'removeFileAfterScan' => false,
        'returnOcrFullResult' => false,
        'maxPages' => 10,
        'model' => 'scanum'
    ];

    $properties = [
        "id_factura" => [
            "type" => "string",
            "description" => "el numero de factura"
        ],
        "total" => [
            "type" => "number",
            "description" => "el total de la factura"
        ],
        "cliente" => [
            "type" => "object",
            "properties" => [
                "nombre" => [
                    "type" => "string",
                    "description" => "el nombre del cliente"
                ],
                "cuit" => [
                    "type" => "number",
                    "description" => "el cuit del cliente"
                ],
                "telefono" => [
                    "type" => "number",
                    "description" => "el telefono del cliente"
                ],
                "direccion" => [
                    "type" => "string",
                    "description" => "la direccion del cliente"
                ]
            ]
        ],
        "proveedor" => [
            "type" => "object",
            "properties" => [
                "nombre" => [
                    "type" => "string",
                    "description" => "el nombre del proveedor"
                ],
                "cuit" => [
                    "type" => "number",
                    "description" => "el cuit del proveedor"
                ],
                "telefono" => [
                    "type" => "number",
                    "description" => "el telefono del proveedor"
                ],
                "direccion" => [
                    "type" => "string",
                    "description" => "la direccion del proveedor"
                ]
            ]
        ],
        "items" => [
            "type" => "array",
            "description" => "productos de la factura",
            "items" => [
                "type" => "object",
                "properties" => [
                    "cod_ean" => [
                        "type" => "number",
                        "description" => "el codigo ean de cada producto"
                    ],
                    "cod_int" => [
                        "type" => "number",
                        "description" => "el codigo int de cada producto"
                    ],
                    "price_unity" => [
                        "type" => "number",
                        "description" => "el precio por unidad de cada producto"
                    ],
                    "quantity" => [
                        "type" => "number",
                        "description" => "la cantidad de productos de cada item"
                    ]
                ]
            ]
        ]
    ];

    // Prepare data for scanning
    $postData = json_encode([
        'fileName' => $fileNameId,
        'properties' => $properties,
        'options' => $options
    ]);

    // Set headers for scanning request
    $headers = [
        'Content-Type: application/json',
        'api-key: ' . $apiKey
    ];

    // Set cURL options for scanning request
    curl_setopt_array($curl, [
        CURLOPT_URL => $scanUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification (for debugging)
        CURLOPT_SSL_VERIFYHOST => false // Disable SSL verification (for debugging)
    ]);

    // Execute the POST request for scanning
    $scanResponse = curl_exec($curl);

    // Check for errors in scanning request
    if ($scanResponse === false) {
        echo 'Error: ' . curl_error($curl);
    } else {
        // Decode JSON response for scanning
        $scanData = json_decode($scanResponse, true);
?>
        <h2 style="color:green;"><?php echo $scanData['data']['id_factura'] ?></h2>
        <br>
        <h3>Cliente:</h3>
        <p><b><?php echo $scanData['data']['cliente']['nombre'] ?></b></p>
        <p><?php echo $scanData['data']['cliente']['direccion'] ?></p>
        <p><?php echo $scanData['data']['cliente']['cuit'] ?></p>
        <p><?php echo $scanData['data']['cliente']['telefono'] ?></p>
        <br>
        <h3>Proveedor:</h3>
        <p><b><?php echo $scanData['data']['proveedor']['nombre'] ?></b></p>
        <p><?php echo $scanData['data']['proveedor']['direccion'] ?></p>
        <p><?php echo $scanData['data']['proveedor']['cuit'] ?></p>
        <p><?php echo $scanData['data']['proveedor']['telefono'] ?></p>
        <br>
        <p><b>Productos:</b></p>
        <table>
            <thead>
                <tr>
                    <th>Cod. Ean.</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scanData['data']['items'] as $producto) : ?>
                    <tr>
                        <td><?php echo $producto['cod_ean'] ?></td>
                        <td>$<?php echo $producto['price_unity'] ?></td>
                        <td><?php echo $producto['quantity'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <p>Total: $<?php echo number_format($scanData['data']['total'], 2, ',', '.') ?></p>
<?php
    }

    // Close cURL session
    curl_close($curl);
}

// Example usage: call the function with the input field name where the file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    scanInvoice('fileInput');
}

?>

<!DOCTYPE html>
<html lang="en">

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
</body>

</html>