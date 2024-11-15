<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

session_start();
header("Access-Control-Allow-Origin: *");
ini_set('memory_limit', '-1');
date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");

function connectDatabase()
{
    try {
	    $link = mysqli_connect('localhost', 'u598064194_sistemabig', 'CBV#*Bi0');
	    if (!$link) {
	        throw new Exception('Error al conectar a localhost');
	    }
	    $db = 'u598064194_sistemabig';
	    $db_select = mysqli_select_db($link, $db);
	    if (!$db_select) {
	        throw new Exception('Error al seleccionar la base de datos u598064194_sistemabig');
	    }
	} catch (Exception $e) {
	    $link = mysqli_connect('localhost', 'root', '');
	    $db = 'bpgestion';
	    $db_select = mysqli_select_db($link, $db);
	    if (!$db_select) {
	        die('Error al seleccionar la base de datos bpgestion: ' . mysqli_error($link));
	    }
	}
    return $link; 
}

$link = connectDatabase();


$id = $_GET['cliente'];
$hasta = date('Y-m-d', strtotime($link->real_escape_string($_GET['hasta']) . ' +1 day'));
$desde = $link->real_escape_string($_GET['desde']);


$consulta_corriente = $link->query("SELECT * FROM transaccion WHERE cliente ='$id' AND estado = 1 AND fecha >= '$desde'  AND fecha <= '$hasta' ORDER BY id ASC");

$acumula_pagos = '0';
$acumula_pedidos = '0';
$saldo = '0';
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'fecha');
$sheet->setCellValue('B1', 'detalle');
$sheet->setCellValue('C1', 'Tipo');
$sheet->setCellValue('D1', 'Monto');
$sheet->setCellValue('E1', 'Saldo');
$sheet->setCellValue('F1', '#');
$rowIndex = 2;
while ($cc = mysqli_fetch_array($consulta_corriente)) {
	
	$monto = 0;
	if ($cc['tipo'] == 'pago') {
	    $acumula_pagos = $acumula_pagos + $cc['monto2'];
	    $saldo = $saldo - $cc['monto2'];
	    $signo = '-$';
	    $monto = $cc['monto2'];
	}
	if ($cc['tipo'] == 'pedido') {
	    $acumula_pedidos = $acumula_pedidos + $cc['monto'];
	    $saldo = $saldo + $cc['monto'];
	    $signo = '$';
	    $monto = $cc['monto'];
	}
	if ($cc['tipo'] == 'cheque_rechazado') {
	    $acumula_pedidos = $acumula_pedidos + $cc['monto'];
	    $saldo = $saldo + $cc['monto'];
	    $signo = '$';
	    $monto = $cc['monto'];
	}

	$sheet->setCellValue('A' . $rowIndex, $cc['fecha']);
    $sheet->setCellValue('B' . $rowIndex, $cc['detalle']);
    $sheet->setCellValue('C' . $rowIndex, $cc['tipo']);
    $sheet->setCellValue('D' . $rowIndex, $monto);
    $sheet->setCellValue('E' . $rowIndex, $saldo);
    $sheet->setCellValue('F' . $rowIndex, $cc['id']);
    $rowIndex++;
}

$lastRow = $rowIndex - 1;
$tableRange = 'A1:F' . $lastRow;

$table = new Table($tableRange);
$sheet->addTable($table);

$tableStyle = new TableStyle();
$tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM2);
$table->setStyle($tableStyle);

foreach (range('A', 'F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$fileName = 'cuenta_corriente'.$desde.$hasta.'.xlsx';

    // Enviar archivo Excel al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$fileName\"");
$writer->save("php://output");
exit;

?>

