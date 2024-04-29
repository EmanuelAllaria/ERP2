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

	$sql_entrega = "SELECT entrega_liquidacion as total FROM liquidaciones WHERE DATE(liquidaciones.fecha_liquidacion)='$periodo' and estado_liquidacion=1 and vendedor_liquidacion = $personal";
	$total_entr = $link->query($sql_entrega);
	$total_entrega = mysqli_fetch_array($total_entr);

	$sql_pagos = "SELECT monto2, razon_com_clientes FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and transaccion.quien='$personal' WHERE DATE(fecha) LIKE '$periodo' AND `estado` = 1 and tipo = 'pago' and observacion not like 'Abono con transaccion:%' ";
	$pago_gral = $link->query($sql_pagos);

	$idliqui = $link->query("SELECT id_liquidacion, CONCAT(nombre,', ',apellido) as personal, cuando_liquidacion FROM liquidaciones INNER JOIN personal on personal.id = vendedor_liquidacion WHERE DATE(fecha_liquidacion) = '$periodo' AND vendedor_liquidacion = $personal AND estado_liquidacion = 1 ");
	$liquid = mysqli_fetch_array($idliqui);
	$hora_liqui = $liquid['cuando_liquidacion'];
	$comp_int = '0001-' . str_pad($liquid['id_liquidacion'], 8, "0", STR_PAD_LEFT);
	$comp_fecha = date('d/m/Y', strtotime($periodo));
	$logo = '../lib/pdf/logo-liqui.png';
	$letter = 'X';
	$type = 'LIQUIDACION #';

	$filename = 'liqui_detalle' . $comp_int . '.pdf';
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
	    </table></div>';

	$plantilla .= '
		<div >
			<table style="width:100%" border="1"  bordercolor="#CCCCCC" cellspacing="0">
				<tr>
					<th style="width:29%;"> CLIENTE </th>
					<th style="width:9%;"> POLLO </th>
					<th style="width:9%;"> SUP </th>
					<th style="width:9%;"> PM </th>
					<th style="width:9%;"> AL </th>
					<th style="width:9%;"> CONG </th>
					<th style="width:9%;"> HUE </th>
					<th style="width:9%;"> OTROS </th>
					<th style="width:15%;"> TOTAL. </th>
					<th style="width:15%;"> CONTADO </th>
				</tr>';

	$par = 0;
	$contado = 0;
	$cc = 0;
	$total = 0;
	$total_grl = 0;

	$pollo_grl = 0;
	$sup_grl = 0;
	$pm_grl = 0;
	$al_grl = 0;
	$cong_grl = '0';
	$hue_grl = 0;
	$otro_grl = 0;
	$entrega_grl = 0;
	$xx = 0;
	$acuenta = [];
	$recorre_blucle = 0;
	$salto_obligado = 0;
	$tope_bucle = 0;

	if (mysqli_num_rows($pago_gral) > 7) {
		$tope_bucle = mysqli_num_rows($transacciones);
		$salto_obligado = 1;
	} else {
		$tope_bucle = 25;
	}

	while ($t = mysqli_fetch_array($transacciones)) {
		$id_t = $t['id'];
		$razon = $t['razon_com_clientes'];
		$pollo = 0;
		$sup = 0;
		$pm = 0;
		$al = 0;
		$cong = '0';
		$hue = 0;
		$otro = 0;
		$entrega = 0;
		$sum_pollo = 0;
		$sum_sup = 0;
		$sum_pm = 0;
		$sum_al = 0;
		$sum_cong = '0';
		$sum_hue = 0;
		$sum_otro = 0;


		$total = $t['monto'];
		$total_grl = $total_grl + $total;

		$sql_ft = "SELECT monto2 FROM `transaccion` WHERE DATE(fecha) LIKE '$periodo' AND `estado` = 1 and tipo = 'pago'  and observacion = 'Abono con transaccion: $id_t'";
		$pagotrans = $link->query($sql_ft);
		if ($can_pago = mysqli_fetch_array($pagotrans)) {
			$entrega = $can_pago['monto2'];
			$entrega_grl = $entrega_grl + $entrega;
		}
		$cantidades_items = $link->query("SELECT cantidad_itemsp, categoria_producto, monto_itemsp FROM `items_pedidos` INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE `pedido_itemsp` = $id_t and estado_itemsp = 1 and quien_itemsp=$personal");
		while ($can_item = mysqli_fetch_array($cantidades_items)) {
			if ($can_item['categoria_producto'] == 1) {
				$pollo = $pollo + $can_item['cantidad_itemsp'];
				$sum_pollo = $sum_pollo + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 2) {
				$sup = $sup + $can_item['cantidad_itemsp'];
				$sum_sup = $sum_sup + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 3) {
				$pm = $pm + $can_item['cantidad_itemsp'];
				$sum_pm = $sum_pm + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 6) {
				$al = $al + $can_item['cantidad_itemsp'];
				$sum_al = $sum_al + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 7) {
				$cong = $cong + $can_item['cantidad_itemsp'];
				$sum_cong = $sum_cong + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 9) {
				$hue = $hue + $can_item['cantidad_itemsp'];
				$sum_hue = $sum_hue + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 10) {
				$otro = $otro + $can_item['cantidad_itemsp'];
				$sum_otro = $sum_otro + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
		}
	
	$plantilla .= '<tr>';

	$pollo_grl = $pollo_grl + $pollo;
	$sup_grl = $sup_grl + $sup;
	$pm_grl = $pm_grl + $pm;
	$al_grl = $al_grl + $al;
	$cong_grl = $cong_grl + $cong;
	$hue_grl = $hue_grl + $hue;
	$otro_grl = $otro_grl + $otro;

	if ($pollo == 0) {
		$pollo = '';
	}
	if ($sup == 0) {
		$sup = '';
	}
	if ($pm == 0) {
		$pm = '';
	}
	if ($al == 0) {
		$al = '';
	}
	if ($cong == 0) {
		$cong = '';
	}
	if ($hue == 0) {
		$hue = '';
	}
	if ($otro == 0) {
		$otro = '';
	}

	$plantilla .= '
		<td rowspan="2" style="width=29%;">' . $razon . '</td>
					<td style="width:9%;"></td>
					<td style="width:9%;"></td>
					<td style="width:9%;"></td>
					<td style="width:9%;"></td>
					<td style="width:9%;"></td>
					<td style="width:9%;"></td>
					<td style="width:9%;"></td>
					<td style="width:15%;">$ ' . number_format($total, 2, ',', '.') . '</td>
					<td style="width:15%;">$ ' . number_format($entrega, 2, ',', '.') . '</td>
				</tr>';

	$calculo_pollo = '';
	$calculo_sup = '';
	$calculo_pm = '';
	$calculo_al = '';
	$calculo_cong = '';
	$calculo_hue = '';
	$calculo_otro = '';

	if ($pollo != '') {
		$calculo_pollo = "$ " . number_format(($sum_pollo / $pollo), 0, ',', '.');
	}
	if ($sup != '') {
		$calculo_sup = "$ " . number_format(($sum_sup / $sup), 0, ',', '.');
	}
	if ($pm != '') {
		$calculo_pm = "$ " . number_format(($sum_pm / $pm), 0, ',', '.');
	}
	if ($al != '') {
		$calculo_al = "$ " . number_format(($sum_al / $al), 0, ',', '.');
	}
	if ($cong != '') {
		$calculo_cong = "$ " . number_format(($sum_cong / $cong), 0, ',', '.');
	}
	if ($hue != '') {
		$calculo_hue = "$ " . number_format(($sum_hue / $hue), 0, ',', '.');
	}
	if ($otro != '') {
		$calculo_otro = "$ " . number_format(($sum_otro / $otro), 0, ',', '.');
	}

	$plantilla .= '
		<tr >
					<td style="width:9%;">' . $calculo_pollo .'</td>
					<td style="width:9%;">' . $calculo_sup . '</td>
					<td style="width:9%;">' . $calculo_pm . '</td>
					<td style="width:9%;">' . $calculo_al . '</td>
					<td style="width:9%;">' . $calculo_cong . '</td>
					<td style="width:9%;">' . $calculo_hue . '</td>
					<td style="width:9%;">' . $calculo_otro . '</td>
					<td colspan="2" style="width:15%;"></td>
				</tr>';


	if ($par == 1) {
		$par = 0;
	} else {
		$par++;
	}

	if ($recorre_blucle == $tope_bucle && $salto_obligado != 1) {
			$plantilla .= '
				</table>
			</div>
			<div style="margin-top: 30px;">
			<table style="width:100%" border="1"  bordercolor="#CCCCCC" cellspacing="0">
				<tr>
					<th style="width:29%;"></th>
					<th style="width:9%;"> POLLO </th>
					<th style="width:9%;"> SUP </th>
					<th style="width:9%;"> PM </th>
					<th style="width:9%;"> AL </th>
					<th style="width:9%;"> CONG </th>
					<th style="width:9%;"> HUE </th>
					<th style="width:9%;"> OTROS </th>
					<th style="width:15%;"> TOTAL. </th>
					<th style="width:15%;"> CONTADO </th>
					<th style="width:15%;"> CTA CTE </th>
				</tr>';
			$recorre_blucle = 0;
		} else {
			$recorre_blucle++;
		}

	}	

	$plantilla .= '
				<tr>
					<th style="width:29%;">TOTALES</th>
					<th style="width:9%;"></th>
					<th style="width:9%;"></th>
					<th style="width:9%;"></th>
					<th style="width:9%;"></th>
					<th style="width:9%;"></th>
					<th style="width:9%;"></th>
					<th style="width:9%;"></th>
					<th style="width:15%;">$ ' . number_format($total_grl, 2, ',', '.') . '<span style="right:0px"></span></th>
					<th style="width:15%;">$' . number_format($entrega_grl, 2, ',', '.') . ' </th>
				</tr>
			</table>
			</div>';

		$plantilla .= '
			<div style="margin-top: 30px;">
			<table border="1" style="width: 100%;">
				<tr>
					<th colspan="2" style="width: 100%;">Gastos</th>
				</tr>
				<tr>
					<td>CLIENTE</td>
					<td>IMPORTE</td>
				</tr>';

		$total_gasto = 0;
		while ($g = mysqli_fetch_array($gast)) {
			$total_gasto = $total_gasto + $g['monto_gasto'];
			$plantilla .= '
				<tr>
					<td>' . $g['nombre_tipogasto'] . '</td>
					<td>$' . $g['monto_gasto'] . '</td>
				</tr>';
		}

		$plantilla .= '
			<tr>
					<td>Total: </td>
					<td>$' . number_format($total_gasto, 2, ',', '.') . '</td>
			</tr>
		</table>
		<div style="margin-top:30px;">
		<table border="1" style="width: 100%;" margin-top:30px;>
				<tr>
					<th colspan="2">Cobros Cuentas Corrientes</th>
				</tr>
				<tr>
					<td>CLIENTE</td>
					<td>IMPORTE</td>
				</tr>';
	$acumula_acuenta = 0;
	//for ($i=0; $i < count($acuenta) ; $i++) {


	while ($pgral = mysqli_fetch_array($pago_gral)) {
		//	$acumula_acuenta=$acumula_acuenta+$acuenta[$i]['importe'];
		$acumula_acuenta = $acumula_acuenta + $pgral['monto2'];

		$plantilla .= '
			<tr>
				<td>' . $pgral['razon_com_clientes'] . '</td>
				<td>$' . number_format($pgral['monto2'], 2, ',', '.') . '</td>
			</tr>';
	}

	$cajaFinal = $acumula_acuenta-$total_gasto;

	$plantilla .= '
		<tr>
			<td>Total: </td>
			<td>$' . number_format($acumula_acuenta, 2, ',', '.') . '</td>
		</tr>
	</table>
	</div>
		</div>
	<div style="margin-top:30px;width: 100%;">
				<table border="1" style="width: 100%;">
				<tr>
					<th>Ingresos</th>
					<th>Gastos</th>
					<th>Caja Final</th>
				</tr>
				<tr>

					<td>$' . number_format($acumula_acuenta, 2, ',', '.') . '</td>
					<td>$'.number_format($total_gasto, 2, ',', '.').'</td>
					<td>$'.number_format($cajaFinal, 2, ',', '.').'</td>
				</tr>
			</table>
		</div>';

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