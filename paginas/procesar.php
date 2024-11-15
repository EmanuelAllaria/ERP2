<?php
header("Access-Control-Allow-Origin: *");

include 'scann-pdf-image.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = connectDatabase();
    $scanData = $_POST;

    $response = processInvoiceData($scanData, $link);

    $cantidad_items_error = $response['cantidad_items_error'];
    $cantidad_items = $response['cantidad_items'];

    unset($_SESSION['scanData']);

    if ($cantidad_items_error > 0 && isset($scanData['id_factura'])) {
        echo "<script>
                    alert('Hubo $cantidad_items_error errores al subir los datos.');
                </script>";
    } else {
        echo "<script>
                    alert('Se subieron todos los datos con éxito.');
                    window.location.href = 'scann-pdf-image.php';
                </script>";
    }
}
