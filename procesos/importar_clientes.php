<?php
require '../vendor/autoload.php';
require '../procesos/functions-online.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['excel_file']['tmp_name'];

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    foreach ($data as $index => $row) {
        if ($index == 0) continue;

        $apellido = $row[0];
        $nombre = $row[1];
        $tipodni = $row[2];
        $dni = $row[3];
        $celular = $row[4];
        $direccion = $row[5];
        $razon_social = $row[6];
        $ciudad = $row[7];
        $cp = $row[8];
        $condicion_iva = $row[9];
        $cuitcuil = $row[10];
        $estado = strtolower($row[11]) == 'activo' ? 1 : 2;

        try {
            // Asegúrate de que el número de columnas y valores coincida
            $stmt = $link->prepare("INSERT INTO clientes (apellido_clientes, nombre_clientes, tipodni_clientes, dni_clientes, celular_clientes, direccion_clientes, razon_com_clientes, ciudad_clientes, cp_cliente, condicioniva_com_clientes, cuitcuil_com_clientes, estado_clientes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE celular_clientes = VALUES(celular_clientes), direccion_clientes = VALUES(direccion_clientes), estado_clientes = VALUES(estado_clientes)");
            $stmt->bind_param("ssssssssssis", $apellido, $nombre, $tipodni, $dni, $celular, $direccion, $razon_social, $ciudad, $cp, $condicion_iva, $cuitcuil, $estado);
            $stmt->execute();
            header('Location: ../index.php?pagina=clientes&import=success');
            exit;
        } catch (\Throwable $th) {
            // Escapar mensaje de error para que no haya problemas de encabezado
            $error_message = urlencode($th->getMessage());
            header("Location: ../index.php?pagina=clientes&error=$error_message");
            exit;
        }
    }
} else {
    echo "Error al cargar el archivo.";
}
