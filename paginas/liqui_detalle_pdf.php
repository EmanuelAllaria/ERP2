<?php
include '../inc/conection.php';
require_once '../lib/pdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;


$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");
session_start();


if (!$link) {
	die('No se ha podido conectar a la base de datos');
}


if ($_SESSION['usuario'] != "") {

	$quien = $_SESSION['id'];
	$cuando = date("Y-m-d H:i:s");

	$personal = $_GET['u'];
	$periodo = $_GET['p'];

	

	$sql_liqui = "SELECT * FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and transaccion.quien='$personal' INNER JOIN liquidaciones on DATE(fecha_liquidacion) = DATE(fecha) and liquidaciones.vendedor_liquidacion=$personal WHERE DATE(fecha) LIKE '$periodo' AND `estado` = 1 and tipo = 'pedido' ORDER BY `transaccion`.`id` ASC";

	$transacciones = $link->query($sql_liqui);

	$sql_gastos = "SELECT * FROM `gastos` INNER JOIN tipo_gastos on tipo_gastos.id_tipogasto = gastos.tipo_gasto WHERE estado_gasto = 1 and DATE(fecha_gasto) LIKE '$periodo' and personal_gasto ='$personal' ";
	$gast = $link->query($sql_gastos);

	$idliqui = $link->query("SELECT id_liquidacion, CONCAT(nombre,', ',apellido) as personal, cuando_liquidacion FROM liquidaciones INNER JOIN personal on personal.id = vendedor_liquidacion WHERE DATE(fecha_liquidacion) = '$periodo' AND vendedor_liquidacion = $personal AND estado_liquidacion = 1 ");
	$liquid = mysqli_fetch_array($idliqui);

	$hora_liqui = $liquid['cuando_liquidacion'];
	$comp_int = '0001-' . str_pad($liquid['id_liquidacion'], 8, "0", STR_PAD_LEFT);
	$comp_fecha = date('d/m/Y', strtotime($periodo));

	$logo = '../lib/pdf/logo-liqui.png';
	$letter = 'X';

	$type = 'LIQUIDACION #';


	$filename = 'liqui_detalle_' . $comp_int . '.pdf';
	$plantilla = '';

	$plantilla .= '<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>'.$filename.'</title>
			</head>
			<body>';

	$plantilla .= '
	<div >
	    <table style="width:100%">
	    	<tr style="width:100%;">
	    		<td style="width: 100%;">
	                <img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($logo)) . '" alt="logo">
	            </td>
	    	</tr>
	    	<tr style="width:100%;">
	    		<td style="width: 50%;">
	                <span class="type_voucher header_margin">'.$type.'&nbsp; &nbsp;'.$comp_int.'</span><br>
	                <span style="width:50%;font-weight: bolder;" >Cierre de liquidacion: '.$hora_liqui.'</span><br>
	            </td>
	    	</tr>
	        <tr>
	            <td style="width: 100%;">
	                <span>Fecha:  ' . $comp_fecha . '</span><br>
	                <span style="width:100%;font-weight: bolder;">VENDEDOR: ' . $liquid['personal'] . '</span><br>
	            </td>
	        </tr>
	    </table>
	    <table border="1" cellspacing="1" style="width:100%; margin-left:10vw;">
	    <tr style="background-color:#edf1f5;">
	        <th class="center-text" style="width:25%;"> CLIENTE </th>
	        <th class="center-text" style="width:10%;"> UNIDAD </th>
	        <th class="center-text" style="width:45%;"> PRODUCTO </th>
	        <th class="center-text" style="width:15%;"> PRECIO U. </th>
	        <th class="center-text" style="width:25%;"> TOTAL. </th>
	    </tr>


';


	$xx = 0;
	$acuenta = [];
	while ($t = mysqli_fetch_array($transacciones)) {
		$id_t = $t['id'];
		$busco_items_trans = "SELECT * FROM items_pedidos INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE estado_itemsp = 1 and pedido_itemsp = '$id_t' ";
		$items = $link->query($busco_items_trans);
		$total = $t['monto'];
		while ($it = mysqli_fetch_array($items)) {

			$razon_cli = $t['razon_com_clientes'];
			$item_cant = $it['cantidad_itemsp'];
			$desc = $it['descripcion_producto'];
			$monto = number_format($it['monto_itemsp'], 2, ',', '.');
			$total_lista = number_format($it['monto_itemsp'] * $it['cantidad_itemsp'], 2, ',', '.') ;

			$plantilla .= '
			<tr>
			    <td class="center-text" style="width:25%;">' . $razon_cli . '</td>
			    <td class="center-text" style="width:15%;">' . $item_cant . '</td>
			    <td class="center-text" style="width:45%;">' . $desc . '</td>
			    <td class="center-text" style="width:15%;">$ ' . $monto . '</td>
			    <td class="center-text" style="width:25%;">$ ' .$total_lista . '</td>
			</tr>
			';
		}

	} 

	

	$plantilla .= '</table>	</div>';
	$plantilla .= '</body>
</html>';
	$html = $plantilla;


	
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4');
	$dompdf->render();

	$output = $dompdf->output();

	$pdfUbicacion = '../lib/pdf_archivados/' . $filename;

	file_put_contents($pdfUbicacion, $output);

	header("Location: ".$pdfUbicacion);
	exit;
	
}

	

///////

	


