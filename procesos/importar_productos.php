<?php
require '../vendor/autoload.php';
require '../procesos/functions-online.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['archivo_excel'])) {
    $archivo = $_FILES['archivo_excel']['tmp_name'];
    $documento = IOFactory::load($archivo);
    $hojaActual = $documento->getActiveSheet();

    foreach ($hojaActual->getRowIterator(2) as $fila) {
        $cod = $hojaActual->getCell("A" . $fila->getRowIndex())->getValue();
        $producto = $hojaActual->getCell("B" . $fila->getRowIndex())->getValue();
        $proveedor = $hojaActual->getCell("C" . $fila->getRowIndex())->getValue();
        $precioCosto = $hojaActual->getCell("D" . $fila->getRowIndex())->getValue();
        $precio = $hojaActual->getCell("E" . $fila->getRowIndex())->getValue();
        $precio2 = $hojaActual->getCell("F" . $fila->getRowIndex())->getValue();
        $precio3 = $hojaActual->getCell("G" . $fila->getRowIndex())->getValue();
        $categoria = $hojaActual->getCell("H" . $fila->getRowIndex())->getValue();
        $stock = $hojaActual->getCell("I" . $fila->getRowIndex())->getValue();

        $link->query("INSERT INTO productos (codigo_producto, detalle_producto, proveedor_producto, costo_producto, precio_producto, precio_producto2, precio_producto3, categoria_producto, stock_producto)
                      VALUES ('$cod', '$producto', '$proveedor', '$precioCosto', '$precio', '$precio2', '$precio3', '$categoria', '$stock')
                      ON DUPLICATE KEY UPDATE detalle_producto='$producto', proveedor_producto='$proveedor', costo_producto='$precioCosto', precio_producto='$precio', precio_producto2='$precio2', precio_producto3='$precio3', categoria_producto='$categoria', stock_producto='$stock'");
    }
    echo "Importación exitosa.";
}
