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


	if ($_POST['a'] == 'add_carga') {

		$cliente = $_POST['c'];
		$fecha = $_POST['f'];
		$detalle = $_POST['d'];
		$observacion = $_POST['d'];
		if (!isset($observacion)) {
			$observacion = '';
		}
		$total = $_POST['t'];

		$detallepedido = '';


		$carrito = json_decode($_POST['i'], true);
		$cantitems = count($carrito);

		$cargo = $link->query("INSERT INTO carga_camion SET personal_cargac='$cliente', observacionadm_cargac='$observacion' , fecha_cargac='$fecha', estado_cargac='1' ") or die(mysqli_error());
		$ultimoid = mysqli_insert_id($link);
		if ($cargo) {
			for ($i = 0; $i < $cantitems; $i++) {
				$prod = $carrito[$i]['id'];
				$cod = $carrito[$i]['codigo'];
				$cant = $carrito[$i]['cantidad'];

				$add = $link->query("INSERT INTO stock_depositos SET idcarga_stockd='$ultimoid', idpersona_stockd='$cliente', idcamion_stockd='$cliente', idproducto_stockd='$prod', cantidad_stockd='$cant', fecha_stockd='$fecha', quien_stockd='$quien', estado_stockd='1', tipomov_stockd='carga', cuando_stockd='$cuando' ") or die(mysqli_error());
			}
		}
		if ($add) {
			echo 'TRUE';
		} else {
			echo 'FALSE';
		}
	}

	//////////////////////////////////////////////////////

	/*if ($_POST['a'] == 'liquida_stock') {

		$idcarga = $_POST['idcarga'];
		$vendedor = $_POST['vendedor'];
		$camion = $_POST['camion'];
		$observaciones = $_POST['observaciones'];
		$json_completo = addslashes(safe_json_encode($_POST));

		$periodo = $_POST['fecha'];
		$sql_item = "UPDATE stock_depositos SET	estado_stockd='2' WHERE idpersona_stockd='$vendedor' and DATE(fecha_stockd) like '$periodo' and estado_stockd='1'";
		$add_it = $link->query($sql_item);
		if ($add_it > 0) {
			$sql_item = "UPDATE carga_camion SET autoriza_cargac='0001-02-15' WHERE id_cargac='$idcarga'";
			$add_it = $link->query($sql_item);
		
				echo 'true';
		} else {

			echo 'false update stock' . $sql_item;
		}
	}
	if ($_POST['a'] == 'liquida_plata') {
		$idcarga = $_POST['idcarga'];
		$vendedor = $_POST['vendedor'];
		$devoluciones = $_POST['devoluciones'];
		$montototal = $_POST['montototal'];
		$entrega = $_POST['entrega'];
		$observaciones = $_POST['observaciones'];
		$json_completo = addslashes(safe_json_encode($_POST));
		$periodo = $_POST['fecha'];
		$sql_add = "INSERT INTO liquidaciones SET devoluciones_liquidacion='" . $devoluciones . "',	montototal_liquidacion='" . $montototal . "',	entrega_liquidacion='" . $entrega . "',	observaciones_liquidacion='" . $observaciones . "',	vendedor_liquidacion='" . $vendedor . "',	cuando_liquidacion='$cuando',	fecha_liquidacion='$periodo',quien_liquidacion='$quien',	json_liquidacion='$json_completo',	estado_liquidacion='1' ";
		$add = $link->query($sql_add);
		if ($add) {
			$sql_item = "UPDATE carga_camion SET autoriza_cargac='$periodo' WHERE id_cargac='$idcarga'";
			$add_it = $link->query($sql_item);
			echo 'true';
		} else {
			echo 'false liquida' . $sql_add;
		}
		
	}*/

	if ($_POST['a'] == 'liquida') {

		$idcarga = $_POST['idcarga'];
		$vendedor = $_POST['vendedor'];
		$camion = $_POST['camion'];
		$devoluciones = $_POST['devoluciones'];
		$montototal = $_POST['montototal'];
		$entrega = $_POST['entrega'];
		$observaciones = $_POST['observaciones'];
		$tipo = $_POST['tipo'];
		//$json = json_decode($_POST['items'], true);
		//$cantitems = count($json);
		$json_completo = addslashes(safe_json_encode($_POST));

		$periodo = $_POST['fecha'];
		$sql_item = "UPDATE stock_depositos SET	estado_stockd='2' WHERE idpersona_stockd='$vendedor' and idcarga_stockd like '$idcarga' and estado_stockd='1'";
		$add_it = $link->query($sql_item);
		$sql_add = null;
		if ($tipo) {
			$get_liquidacion_result = $link->query("SELECT id_liquidacion FROM liquidaciones WHERE id_cargac='$idcarga'");
			$get_liquidacion = $get_liquidacion_result->fetch_assoc();
			if ($tipo === 'deposito') {
				if ($get_liquidacion) {
					$sql_add = "UPDATE liquidaciones SET deposito=1, devoluciones_liquidacion='$devoluciones'";
				} else {
					$sql_add = "INSERT INTO liquidaciones SET id_cargac='$idcarga', devoluciones_liquidacion='$devoluciones', entrega_liquidacion='$entrega', observaciones_liquidacion='$observaciones', vendedor_liquidacion='$vendedor', cuando_liquidacion='$cuando', fecha_liquidacion='$periodo', quien_liquidacion='$quien', json_liquidacion='$json_completo', estado_liquidacion='1', deposito=1";
				}
			} else if ($tipo === 'venta') {
				if ($get_liquidacion) {
					$sql_add = "UPDATE liquidaciones SET venta=1, montototal_liquidacion='$montototal', entrega_liquidacion='$entrega'";
				} else {
					$sql_add = "INSERT INTO liquidaciones SET id_cargac='$idcarga', montototal_liquidacion='$montototal', entrega_liquidacion='$entrega', observaciones_liquidacion='$observaciones', vendedor_liquidacion='$vendedor', cuando_liquidacion='$cuando', fecha_liquidacion='$periodo', quien_liquidacion='$quien', json_liquidacion='$json_completo', estado_liquidacion='1', venta=1";
				}
			}
		} else {
			$sql_add = "INSERT INTO liquidaciones SET id_cargac='" . $idcarga . "', devoluciones_liquidacion='" . $devoluciones . "',	montototal_liquidacion='" . $montototal . "',	entrega_liquidacion='" . $entrega . "',	observaciones_liquidacion='" . $observaciones . "',	vendedor_liquidacion='" . $vendedor . "',	cuando_liquidacion='$cuando',	fecha_liquidacion='$periodo',quien_liquidacion='$quien',	json_liquidacion='$json_completo',	estado_liquidacion='1'";
		}
		if ($add_it > 0) {
			$add = $link->query($sql_add);
			if ($add) {
				echo 'true';
			} else {
				echo 'false liquida' . $sql_add;
			}
		} else {

			echo 'false update stock' . $sql_item;
		}
	}

	//////////////////////////////////////////////////////

	if ($_POST['a'] == 'edit') {

		$carrito = json_decode($_POST['i'], true);
		$cantitems = count($carrito);
		$id = $_POST['id'];
		$cliente = $_POST['c'];
		$fecha = $_POST['f'];
		$detalle = $_POST['d'];
		$observacion = $_POST['d'];
		if (!isset($observacion)) {
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


	//////////////////////////////////////////////////////
	if ($_POST['a'] == 'existe_carga') {
		$personal = $_POST['p'];
		$busca = $link->query("SELECT * FROM carga_camion INNER JOIN personal on personal.id = carga_camion.personal_cargac LEFT JOIN liquidaciones on DATE(carga_camion.autoriza_cargac) = DATE(liquidaciones.cuando_liquidacion) and estado_liquidacion='1' AND liquidaciones.vendedor_liquidacion = carga_camion.personal_cargac LEFT JOIN tipo_estados on tipo_estados.id_estados = liquidaciones.estado_liquidacion WHERE estado_cargac='1' and personal_cargac='$personal' and cuando_liquidacion is null group by fecha_cargac , personal_cargac order by fecha_cargac DESC");

		if (mysqli_fetch_array($busca) > 0) {
			echo 'TRUE';
		} else {
			echo 'FALSE';
		}
	}

	//////////////////////////////////////////////////////

}
