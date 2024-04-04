<?php
include '../inc/conection.php';
date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");
session_start();


if (!$link) {
	die('No se ha podido conectar a la base de datos');
}

if ($_SESSION['usuario'] != "") {

	$quien = $_SESSION['id'];
	$cuando = date("Y-m-d H:i:s");

	function safe_json_encode($value, $options = 0, $depth = 512)
	{
		$encoded = json_encode($value, $options, $depth);
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = utf8ize($value);
				return safe_json_encode($clean, $options, $depth);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()
		}
	}

	function utf8ize($mixed)
	{
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = utf8ize($value);
			}
		} else if (is_string($mixed)) {
			return utf8_encode($mixed);
		}
		return $mixed;
	}


	if ($_POST['a'] == 'add_comprobante') {

		$cliente = $_POST['c'];
		$fecha = $_POST['f'];
		$detalle = $_POST['d'];
		$observacion = $_POST['d'];
		if ($observacion == undefined) {
			$observacion = '';
		}
		$total = $_POST['t'];
		$detallepedido = '';


		$carrito = json_decode($_POST['i'], true);
		$cantitems = count($carrito);
		$cargo = $link->query("INSERT INTO carga_camion SET personal_cargac='$cliente', fecha_cargac='$fecha', estado_cargac='1' ") or die(mysqli_error());
		$ultimoid = mysqli_insert_id($link);
		if ($cargo) {
			for ($i = 0; $i < $cantitems; $i++) {
				$prod = $carrito[$i]['id'];
				$cod = $carrito[$i]['codigo'];
				$cant = $carrito[$i]['cantidad'];
				$mont = $carrito[$i]['precio'];

				$add = $link->query("INSERT INTO stock_depositos SET idcarga_stockd='$ultimoid', idpersona_stockd='$cliente', idcamion_stockd='$cliente', idproducto_stockd='$prod', cantidad_stockd='$cant', fecha_stockd='$fecha', quien_stockd='$quien', estado_stockd='1', tipomov_stockd='carga', cuando_stockd='$cuando' ") or die(mysqli_error());
			}
		}
		if ($add) {
			echo 'TRUE';
		} else {
			echo 'FALSE';
		}
	}

	if ($_POST['a'] == 'edit') {

		$carrito = json_decode($_POST['i'], true);
		$cantitems = count($carrito);
		$id = $_POST['id'];
		$cliente = $_POST['c'];
		$fecha = $_POST['f'];
		$detalle = $_POST['d'];
		$observacion = $_POST['d'];
		if ($observacion == undefined) {
			$observacion = '';
		}
		$total = $_POST['t'];
		$detallepedido = '';
		for ($i = 0; $i < $cantitems; $i++) {
			$prod = $carrito[$i]['id'];
			$cod = $carrito[$i]['codigo'];
			$cant = $carrito[$i]['cantidad'];
			$bonif = $carrito[$i]['bonifica'];

			$mont = $carrito[$i]['precio'];

			if ($i == 0) {
				$limpio = $link->query("UPDATE items_pedidos SET estado_itemsp='0' WHERE pedido_itemsp='$id' ") or die(mysqli_error());
				$actualizo = $link->query("UPDATE transaccion SET cliente='$cliente',	fecha='$fecha',	detalle='$detalle', observacion='$observacion',	monto='$total',	quien='$quien' WHERE id='$id' ") or die(mysqli_error());
				$detallepedido .= '';
			} else {
				$detallepedido .= ' - ';
			}
			$detallepedido .= '(' . $cant;
			if ($bonif != '' && $bonif != '0') {
				$detallepedido .= '+' . $bonif . ')';
			} else {
				$detallepedido .= ')';
			}
			$detallepedido .= ' ' . $cod;
			$add_item = $link->query("INSERT INTO items_pedidos SET pedido_itemsp='$id',	prod_itemsp='$prod',	cantidad_itemsp='$cant', bonifica_itemsp='$bonif',	monto_itemsp='$mont',	estado_itemsp='1',	quien_itemsp='$quien',	cuando_itemsp='$cuando' ") or die(mysqli_error());
		}
		$updatedet = $link->query("UPDATE transaccion SET detalle='$detallepedido' WHERE id='$id' ") or die(mysqli_error());

		if ($actualizo && $add_item && $updatedet) {
			echo 'TRUE';
		} else {
			echo 'FALSE';
		}
	}
}
