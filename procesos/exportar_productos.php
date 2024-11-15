<?php
require '../vendor/autoload.php';
require '../procesos/functions-online.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Productos");

$sheet->setCellValue('A1', 'Cod.');
$sheet->setCellValue('B1', 'Producto');
$sheet->setCellValue('C1', 'Proveedor');
$sheet->setCellValue('D1', 'PrecioCosto');
$sheet->setCellValue('E1', 'Precio');
$sheet->setCellValue('F1', 'Precio2');
$sheet->setCellValue('G1', 'Precio3');
$sheet->setCellValue('H1', 'Categoria');
$sheet->setCellValue('I1', 'Stock');

$con_productos = $link->query("SELECT * FROM productos LEFT JOIN categorias ON categorias.id_categoria = productos.categoria_producto LEFT JOIN proveedores ON proveedores.id_proveedor = productos.proveedor_producto WHERE estado_producto ='1' ORDER BY codigo_producto ASC");

$fila = 2;
while ($row = mysqli_fetch_array($con_productos)) {
    $sheet->setCellValue('A' . $fila, $row['codigo_producto']);
    $sheet->setCellValue('B' . $fila, $row['detalle_producto'] . ' ' . $row['modelo_producto']);
    $sheet->setCellValue('C' . $fila, $row['razon_com_proveedor']);
    $sheet->setCellValue('D' . $fila, $row['costo_producto']);
    $sheet->setCellValue('E' . $fila, $row['precio_producto']);
    $sheet->setCellValue('F' . $fila, $row['precio_producto2']);
    $sheet->setCellValue('G' . $fila, $row['precio_producto3']);
    $sheet->setCellValue('H' . $fila, $row['titulo_categoria']);
    $sheet->setCellValue('I' . $fila, $row['stock_producto']);
    $fila++;
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="productos.xlsx"');
$writer->save("php://output");
