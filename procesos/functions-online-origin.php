<?php
header("Access-Control-Allow-Origin: *");

//Connect & Select Database
ini_set('memory_limit', '-1');

date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");
$link= mysqli_connect('localhost', 'u598064194_matildebig', 'CBV#*Bi0');
$db_select = mysqli_select_db($link, "u598064194_matildebig");
if (!$db_select) {
	die("Database selection failed: " . mysqli_error());
}

$ip = $_SERVER['REMOTE_ADDR'];
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


///////////// Funcion login de accesso /////////////////
if (isset($_POST['login'])) {
	// echo 'Enrta al login';
	$usuario = strtolower(trim($_POST['usuario']));
	$password = md5(trim($_POST['password']));

	$consulta = $link->query("SELECT * FROM `usuarios` WHERE email = '$usuario' and passuser = '$password' ") or die(mysqli_error());

	$login = mysqli_num_rows($consulta);
	if ($login != 0) {

		$data = array();
		$con = mysqli_fetch_assoc($consulta);
		$data['perfil'] = $con;
		$data['estado'] = "success";
		$data['fecha'] = date('Y-m-d H:i:s');
	} else {
		$data['estado'] = "failed";
	}


	header('Content-Type: application/json');


	$arreglo =  safe_json_encode($data);
	if ($arreglo) {
		echo $arreglo;
	} else {
		echo "failed";
	}
}
//------------------------------------------------------//

///////////// Funcion tranferir Billetera /////////////////

if (isset($_POST['transfiere'])) {
	$hoy = '%' . date('-m-d');

	$billetera = json_decode($_POST['b'], true);
	$cantjson = count($billetera);


	mysqli_autocommit($link, FALSE);
	$error = false;
	$error2 = '';
	for ($i = 0; $i < $cantjson; $i++) {


		$credito = $billetera[$i]['credito'];

		$fechaaux = str_replace('/', '-', $billetera[$i]['fecha']);
		$fecha = date('Y-m-d', strtotime($fechaaux));

		$hora = $billetera[$i]['hora'];
		$lat = $billetera[$i]['lat'];
		$lon = $billetera[$i]['lon'];
		$cobrador = $billetera[$i]['cobrador'];
		$akn = $billetera[$i]['akn'];

		$cantidad = $billetera[$i]['cant'];
		$valor = $billetera[$i]['valor'];
		$crc = '0';
		$codigo = $billetera[$i]['producto'];

		$gps = '0,0';





		if (mysqli_query($link, "INSERT INTO pagos SET creditos_pagos='$credito',	cantidad_pagos='$cantidad',	monto_pagos='$valor',	crc_pagos='$crc',	cuando_pagos='$cuando',	quien_pagos='$cobrador',	fecha_pagos='$fecha',hora_pagos='$hora',	codigo_pagos='$codigo',	gps_pagos='$gps', akn_pagos='$akn' ")) {
			$error2 .= 'FALSE';
		} else {
			$error2 .= 'TRUE';
		}
	}
	if (strpos($error2, 'TRUE') === false) {
		mysqli_commit($link);
	} else {
		$error = true;
	}

	if ($error) {
		mysqli_rollback($link);
		echo 'FALSE';
	} else {
		echo 'TRUE';
	}
}

//------------------------------------------------------//

if (isset($_POST['clientes'])) {
	$hoy = '%' . date('-m-d');
	$usuario = $_POST['u'];
	$clientes = $link->query("SELECT * FROM clientes INNER JOIN clientes_comercios on clientes.id_clientes = clientes_comercios.cliente_comclientes INNER JOIN rubros on rubros.id_rubros = clientes_comercios.rubro_comclientes WHERE estado_clientes='1' and clientes_comercios.estado_comclientes='1' ") or die(mysqli_error());
	$c = '0';
	if (mysqli_num_rows($clientes) > 0) {
		while ($row = mysqli_fetch_array($clientes)) {

			$data['estado'] = 'true';
			$data['clientes'][$c]['id'] = $row['id_clientes'];
			$data['clientes'][$c]['nombre'] = trim($row['nombre_clientes']);
			$data['clientes'][$c]['apellido'] = trim($row['apellido_clientes']);
			$data['clientes'][$c]['telefono'] = trim($row['celular_clientes']);
			$data['clientes'][$c]['email'] = trim($row['email_clientes']);
			$data['clientes'][$c]['situacion'] = trim($row['situacion_comclientes']);
			$data['clientes'][$c]['latitud'] = trim($row['lat_comclientes']);
			$data['clientes'][$c]['longitud'] = trim($row['lon_comclientes']);
			$data['clientes'][$c]['vendedor'] = trim($row['vendedor_comclientes']);
			$data['clientes'][$c]['cumple'] = trim($row['fechacumple_clientes']);
			$data['clientes'][$c]['foto'] = trim($row['foto_comclientes']);
			$data['clientes'][$c]['dni'] = trim($row['dni_clientes']);
			$data['clientes'][$c]['direccion'] = trim($row['direccion_clientes']);
			$data['clientes'][$c]['dirnum'] = trim($row['dirnum_clientes']);
			$data['clientes'][$c]['direccion_com'] = trim($row['direccion_comclientes']);
			$data['clientes'][$c]['dirnum_com'] = trim($row['dirnum_comclientes']);
			$data['clientes'][$c]['razon'] = trim($row['razon_comclientes']);
			$data['clientes'][$c]['id_rubro'] = trim($row['rubro_comclientes']);
			$data['clientes'][$c]['rubro'] = trim($row['nombre_rubro']);
			$c++;
		}
	} else {
		$data['estado'] = 'false';
	}

	$arreglo =  safe_json_encode($data);
	if ($arreglo) {
		echo $arreglo;
	} else {
		echo "failed";
	}
}

//------------------------------------------------------//

if (isset($_POST['productos'])) {
	$hoy = date('Y-m-d');
	$usuario = $_POST['u'];
	$categoria = '';
	$marca = '';
	$busca = '';
	$filtro = ''; //marca - categorias -

	if (isset($_POST['c'])) {
		$c = $_POST['c'];
		$categoria = " and categoria_producto='$c' ";
	}
	if (isset($_POST['m'])) {
		$m = $_POST['m'];
		$marca = " and marca_producto='$m' ";
	}
	if (isset($_POST['b'])) {
		$b = $_POST['b'];
		$busca = " and (detalle_producto like '%$b%' or codigo_producto like '%$b%') ";
	}
	if (isset($_POST['f'])) {
		$f = $_POST['f'];
		if ($f == 'm') {
			$filtro = " group by marca_producto";
		}
		if ($f == 'c') {
			$filtro = " group by categoria_producto";
		}
	}

	$productos = $link->query("SELECT * FROM productos INNER JOIN categorias on categorias.id_categoria = productos.categoria_producto INNER JOIN marcas on marcas.id_marca = productos.marca_producto WHERE productos.estado_producto='1' $categoria $marca $busca $filtro") or die(mysqli_error());
	$p = '0';
	if (mysqli_num_rows($productos) > 0) {
		while ($row = mysqli_fetch_array($productos)) {
			$data['estado'] = 'true';
			$data['fecha'] = $hoy;
			$data['productos'][$p]['id'] = $row['id_producto'];
			$data['productos'][$p]['codigo'] = trim($row['codigo_producto']);
			$data['productos'][$p]['detalle'] = trim($row['detalle_producto']);
			$data['productos'][$p]['precio'] = trim($row['precio_producto']);
			$data['productos'][$p]['categoria_id'] = trim($row['categoria_producto']);
			$data['productos'][$p]['categoria'] = trim($row['titulo_categoria']);
			$data['productos'][$p]['marca_id'] = trim($row['marca_producto']);
			$data['productos'][$p]['marca'] = trim($row['titulo_marca']);
			$data['productos'][$p]['marca_logo'] = trim($row['logo_marca']);
			$data['productos'][$p]['foto'] = trim($row['foto_producto']);
			$data['productos'][$p]['stock'] = trim($row['stock_producto']);
			$p++;
		}
	} else {
		$data['estado'] = 'false';
	}

	$arreglo =  safe_json_encode($data);
	if ($arreglo) {
		echo $arreglo;
	} else {
		echo "failed";
	}
}

//------------------------------------------------------//

if (isset($_POST['sincroniza'])) {

	$hoy = date('Y-m-d H:i:s');
	$usuario = $_POST['u'];
	$data['estado'] = 'true';
	$data['fecha'] = $hoy;

	$perfil = $link->query("SELECT * FROM usuarios WHERE id = $usuario and estado_usuarios='1' ") or die(mysqli_error());
	$clientes = $link->query("SELECT * FROM clientes INNER JOIN clientes_comercios on clientes.id_clientes = clientes_comercios.cliente_comclientes INNER JOIN rubros on rubros.id_rubros = clientes_comercios.rubro_comclientes WHERE estado_clientes='1' and clientes_comercios.estado_comclientes='1' ") or die(mysqli_error());
	$i = '0';

	$c = '0';
	while ($row = mysqli_fetch_array($clientes)) {
		$data['clientes'][$c]['id'] = $row['id_clientes'];
		$data['clientes'][$c]['nombre'] = trim($row['nombre_clientes']);
		$data['clientes'][$c]['apellido'] = trim($row['apellido_clientes']);
		$data['clientes'][$c]['telefono'] = trim($row['celular_clientes']);
		$data['clientes'][$c]['email'] = trim($row['email_clientes']);
		$data['clientes'][$c]['situacion'] = trim($row['situacion_comclientes']);
		$data['clientes'][$c]['latitud'] = trim($row['lat_comclientes']);
		$data['clientes'][$c]['longitud'] = trim($row['lon_comclientes']);
		$data['clientes'][$c]['vendedor'] = trim($row['vendedor_comclientes']);
		$data['clientes'][$c]['cumple'] = trim($row['fechacumple_clientes']);
		$data['clientes'][$c]['foto'] = trim($row['foto_comclientes']);
		$data['clientes'][$c]['dni'] = trim($row['dni_clientes']);
		$data['clientes'][$c]['direccion'] = trim($row['direccion_clientes']);
		$data['clientes'][$c]['dirnum'] = trim($row['dirnum_clientes']);
		$data['clientes'][$c]['direccion_com'] = trim($row['direccion_comclientes']);
		$data['clientes'][$c]['dirnum_com'] = trim($row['dirnum_comclientes']);
		$data['clientes'][$c]['razon'] = trim($row['razon_comclientes']);
		$data['clientes'][$c]['id_rubro'] = trim($row['rubro_comclientes']);
		$data['clientes'][$c]['rubro'] = trim($row['nombre_rubro']);
		$c++;
	}

	$marcas = $link->query("SELECT * FROM  marcas  WHERE estado_marca='1' ") or die(mysqli_error());
	$m = '0';

	while ($row = mysqli_fetch_array($marcas)) {
		$data['marcas'][$m]['id'] = $row['id_marca'];
		$data['marcas'][$m]['titulo'] = trim($row['titulo_marca']);
		$data['marcas'][$m]['logo'] = trim($row['logo_marca']);
		$m++;
	}

	$productos = $link->query("SELECT * FROM productos INNER JOIN categorias on categorias.id_categoria = productos.categoria_producto INNER JOIN marcas on marcas.id_marca = productos.marca_producto WHERE productos.estado_producto='1' ") or die(mysqli_error());
	$p = '0';

	while ($row = mysqli_fetch_array($productos)) {
		$data['productos'][$p]['id'] = $row['id_producto'];
		$data['productos'][$p]['codigo'] = trim($row['codigo_producto']);
		$data['productos'][$p]['detalle'] = trim($row['detalle_producto']);
		$data['productos'][$p]['precio'] = trim($row['precio_producto']);
		$data['productos'][$p]['categoria_id'] = trim($row['categoria_producto']);
		$data['productos'][$p]['categoria'] = trim($row['titulo_categoria']);
		$data['productos'][$p]['marca_id'] = trim($row['marca_producto']);
		$data['productos'][$p]['marca'] = trim($row['titulo_marca']);
		$data['productos'][$p]['marca_logo'] = trim($row['logo_marca']);
		$data['productos'][$p]['foto'] = trim($row['foto_producto']);
		$data['productos'][$p]['stock'] = trim($row['stock_producto']);
		$p++;
	}



	if ($row = mysqli_fetch_array($perfil)) {

		$data['perfil']['id'] = $row['id'];
		$data['perfil']['nombre'] = $row['nombre'];
		$data['perfil']['apellido'] = $row['apellido'];
		$data['perfil']['avatar'] = $row['avatar'];
		//$data['perfil']['cumple']=$row['fechacumple_persucursal'];
		$data['perfil']['mail'] = $row['email'];
		$data['perfil']['telefono'] = $row['tel'];
	}

	$arreglo =  safe_json_encode($data);
	if ($arreglo) {
		echo $arreglo;
	} else {
		echo "failed";
	}
}
