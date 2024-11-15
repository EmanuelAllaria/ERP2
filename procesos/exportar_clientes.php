<?php
require '../vendor/autoload.php';
require '../procesos/functions-online.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Apellido');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'Tipo DNI');
$sheet->setCellValue('D1', 'DNI');
$sheet->setCellValue('E1', 'Celular');
$sheet->setCellValue('F1', 'Dirección');
$sheet->setCellValue('G1', 'Razon Social');
$sheet->setCellValue('H1', 'Ciudad');
$sheet->setCellValue('I1', 'Código Postal');
$sheet->setCellValue('J1', 'Condición IVA');
$sheet->setCellValue('K1', 'CUIT/CUIL');
$sheet->setCellValue('L1', 'Estado');

$query = $link->query("SELECT * FROM clientes");

$rowIndex = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowIndex, $row['apellido_clientes']);
    $sheet->setCellValue('B' . $rowIndex, $row['nombre_clientes']);
    $sheet->setCellValue('C' . $rowIndex, $row['tipodni_clientes']);
    $sheet->setCellValue('D' . $rowIndex, $row['dni_clientes']);
    $sheet->setCellValue('E' . $rowIndex, $row['celular_clientes']);
    $sheet->setCellValue('F' . $rowIndex, $row['direccion_clientes']);
    $sheet->setCellValue('G' . $rowIndex, $row['razon_com_clientes']);
    $sheet->setCellValue('H' . $rowIndex, $row['ciudad_clientes']);
    $sheet->setCellValue('I' . $rowIndex, $row['cp_cliente']);
    $sheet->setCellValue('J' . $rowIndex, $row['condicioniva_com_clientes']);
    $sheet->setCellValue('K' . $rowIndex, $row['cuitcuil_com_clientes']);
    $sheet->setCellValue('L' . $rowIndex, $row['estado_clientes'] == '1' ? 'Activo' : 'Inactivo');
    $rowIndex++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'clientes.xlsx';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
