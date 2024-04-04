<?php
include '../inc/conection.php';
date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");
session_start();


if (!$link) {
	die('No se ha podido conectar a la base de datos');
}


$quien = $_SESSION['usuario'];
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

if (isset($_POST['a']) && $_POST['a'] == 'add') {
	$codigo = $_POST['codigo'];
	$nombre = $_POST['nombre'];
	$modelo = $_POST['modelo'];
	$presentacion = $_POST['presentacion'];
	$descripcion = $_POST['descripcion'];
	$fabricante = $_POST['fabricante'];
	if ($fabricante == '') {
		$fabricante = '0';
	}
	$proveedor = $_POST['proveedor'];
	if ($proveedor == '') {
		$proveedor = '0';
	}
	$categoria = $_POST['categoria'];
	if ($categoria == '') {
		$categoria = '0';
	}
	$estado = $_POST['estado'];
	$costo = $_POST['costo'];
	$utilidad = $_POST['utilidad'];

	$pventa1 = $_POST['pventa1'];
	$pventa2 = $_POST['pventa2'];
	$pventa3 = $_POST['pventa3'];
	$stock = $_POST['stock'];
	if ($stock == '') {
		$stock = '0';
	}
	$stockmin = $_POST['stockmin'];

	$fotoProducto = '';
	// Verificar si se ha enviado una imagen
	if (isset($_FILES['imagen'])) {
		$file_name = $_FILES['imagen']['name']; // Nombre del archivo
		$file_tmp = $_FILES['imagen']['tmp_name']; // Nombre temporal del archivo
		$file_type = $_FILES['imagen']['type']; // Tipo MIME del archivo
		$nombre_nuevo_de_archivo = $codigo . '_' . date('ymdHis');

		$upload_directory = '../img/prod/';

		// Mover el archivo temporal al directorio de destino
		if (move_uploaded_file($file_tmp, $upload_directory . $nombre_nuevo_de_archivo)) {
			$fotoProducto = " foto_producto='$nombre_nuevo_de_archivo', ";
		}
	}


	$insert_prod = "INSERT INTO productos SET codigo_producto='$codigo',	detalle_producto='$nombre',	precio_producto='$pventa1'	,	precio_producto2='$pventa2',	precio_producto3='$pventa3',
			categoria_producto='$categoria', marca_producto='$fabricante',	proveedor_producto='$proveedor', modelo_producto='$modelo',	
			presentacion_producto='$presentacion',	descripcion_producto='$descripcion',	costo_producto='$costo',	
			utilidad_producto='$utilidad',	estado_producto='$estado', $fotoProducto	quien_producto='$quien',	
			cuando_producto='$cuando',	stock_producto='$stock',	stockmin_producto='$stockmin' ";

	$add = $link->query($insert_prod);
	$ultimo_id = mysqli_insert_id($link);


	if ($add) {
		echo 'TRUE';
	} else {
		echo 'FALSE' . $insert_prod;
	}
}

if (isset($_POST['a']) && $_POST['a'] == 'actualiza') {
	$codigo = $_POST['codigo'];
	$nombre = $_POST['nombre'];
	$modelo = $_POST['modelo'];
	$presentacion = $_POST['presentacion'];
	$descripcion = $_POST['descripcion'];
	$fabricante = $_POST['fabricante'];
	if ($fabricante == '') {
		$fabricante = '0';
	}
	$proveedor = $_POST['proveedor'];
	if ($proveedor == '') {
		$proveedor = '0';
	}
	$categoria = $_POST['categoria'];
	if ($categoria == '') {
		$categoria = '0';
	}
	$estado = $_POST['estado'];
	$costo = $_POST['costo'];
	$utilidad = $_POST['utilidad'];
	$pventa1 = $_POST['pventa1'];
	$pventa2 = $_POST['pventa2'];
	$pventa3 = $_POST['pventa3'];
	$stock = $_POST['stock'];
	if ($stock == '') {
		$stock = '0';
	}
	$stockmin = $_POST['stockmin'];
	$id = $_POST['id'];

	$fotoProducto = '';
	// Verificar si se ha enviado una imagen
	if (isset($_FILES['imagen'])) {
		$file_name = $_FILES['imagen']['name']; // Nombre del archivo
		$file_tmp = $_FILES['imagen']['tmp_name']; // Nombre temporal del archivo
		$file_type = $_FILES['imagen']['type']; // Tipo MIME del archivo
		$nombre_nuevo_de_archivo = $id . '_' . date('ymdHis');

		$upload_directory = '../img/prod/';

		// Mover el archivo temporal al directorio de destino
		if (move_uploaded_file($file_tmp, $upload_directory . $nombre_nuevo_de_archivo)) {
			$fotoProducto = " foto_producto='$nombre_nuevo_de_archivo', ";
		} else {
			echo "Error al mover el archivo: " . $_FILES['imagen']['error'];
		}
	}


	$updatea = "UPDATE productos SET codigo_producto='$codigo',	detalle_producto='$nombre',	precio_producto='$pventa1',	precio_producto2='$pventa2',	precio_producto3='$pventa3',	categoria_producto='$categoria',
	marca_producto='$fabricante', proveedor_producto='$fabricante',	modelo_producto='$modelo',	$fotoProducto   presentacion_producto='$presentacion',	descripcion_producto='$descripcion',	costo_producto='$costo',	utilidad_producto='$utilidad',
	estado_producto='$estado',	quien_producto='$quien',	cuando_producto='$cuando',	stock_producto='$stock', stockmin_producto='$stockmin' WHERE id_producto='$id' ";

	$upg = $link->query($updatea);

	if ($upg > 0) {
		echo 'TRUE';
	} else {
		echo 'FALSE' . $updatea;
	}
}


if (isset($_POST['a']) && $_POST['a'] == 'updateprecio') {
	$res = 'FALSE';
	$prod = $_POST['id'];
	$precio = $_POST['pre'];
	$cual_pre = $_POST['cual_pre'];
	if (isset($cual_pre)) {
		if ($cual_pre == '1') {
			$sql_update = "UPDATE productos SET precio_producto='$precio' WHERE id_producto='$prod' ";
		} else if ($cual_pre == '2') {
			$sql_update = "UPDATE productos SET precio_producto2='$precio' WHERE id_producto='$prod' ";
		} else if ($cual_pre == '3') {
			$sql_update = "UPDATE productos SET precio_producto3='$precio' WHERE id_producto='$prod' ";
		}
	}
	$consul = $link->query($sql_update) or die(mysqli_error());
	if ($consul > 0) {
		$res = 'TRUE';
	}
	echo $res;
}

if (isset($_POST['accion']) && $_POST['accion'] == 'catelist') {
	$consul_cate = $link->query("SELECT id_categoria as id, titulo_categoria as nombre  FROM categorias WHERE estado_categoria = 1 ORDER BY titulo_categoria ASC ") or die(mysqli_error());
	echo '<option value="" disabled selected>Seleccione una Categoria </option>';
	while ($row = mysqli_fetch_array($consul_cate)) {
		echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
	}
}

if (isset($_POST['accion']) && $_POST['accion'] == 'fabrilist') {
	echo '<option value="" disabled selected>Seleccione un Fabricante </option>';
	$consul_fab = $link->query("SELECT id_marca as id, titulo_marca as nombre FROM marcas WHERE estado_marca = 1 ORDER BY titulo_marca ASC ") or die(mysqli_error());
	while ($row = mysqli_fetch_array($consul_fab)) {
		echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
	}
}

if (isset($_POST['accion']) && $_POST['accion'] == 'provlist') {
	echo '<option value="" disabled selected>Seleccione un Proveedor </option>';
	$consul_prov = $link->query("SELECT id_proveedor as id, razon_com_proveedor as nombre FROM proveedores WHERE estado_proveedor = 1 ORDER BY razon_com_proveedor ASC ") or die(mysqli_error());
	while ($row = mysqli_fetch_array($consul_prov)) {
		echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
	}
}


if (isset($_POST['accion']) && $_POST['accion'] == 'product_list') {
	if ($_POST['prov'] != '') {
		$id_prov = $_POST['prov'];
		$prov = " and proveedor_producto='$id_prov' ";
	} else {
		$prov = '';
	}
	echo '<option value="" disabled selected>Seleccione un Producto </option>';
	$consul_prod = $link->query("SELECT * FROM `productos` WHERE `estado_producto` = 1 $prov order by codigo_producto ASC ") or die(mysqli_error());
	while ($row = mysqli_fetch_array($consul_prod)) {
		echo '<option value="' . $row['id_producto'] . '">' . $row['codigo_producto'] . ' - ' . $row['detalle_producto'] . ' (' . $row['presentacion_producto'] . ')</option>';
	}
}



if (isset($_POST['accion']) && $_POST['accion'] == 'datosprod') {

	$prod = $_POST['id'];
	$consul_prod = $link->query("SELECT * FROM productos WHERE id_producto ='$prod' ") or die(mysqli_error());

	$data = [];

	if ($row = mysqli_fetch_array($consul_prod)) {

		$data['data']['codigo'] = trim($row['codigo_producto']);
		$data['data']['nombre'] = trim($row['detalle_producto']);
		$data['data']['modelo'] = trim($row['modelo_producto']);
		$data['data']['presentacion'] = trim($row['presentacion_producto']);
		$data['data']['descripcion'] = trim($row['descripcion_producto']);
		$data['data']['fabricante'] = trim($row['proveedor_producto']);
		$data['data']['categoria'] = trim($row['categoria_producto']);
		$data['data']['estado'] = trim($row['estado_producto']);
		$data['data']['costo'] = trim($row['costo_producto']);
		$data['data']['utilidad'] = trim($row['utilidad_producto']);
		$data['data']['precio1'] = trim($row['precio_producto']);
		$data['data']['precio2'] = trim($row['precio_producto2']);
		$data['data']['precio3'] = trim($row['precio_producto3']);
		$data['data']['stock'] = trim($row['stock_producto']);
		$data['data']['stock_min'] = trim($row['stockmin_producto']);
		$data['data']['foto'] = trim($row['foto_producto']);
	}
	$arreglo =  safe_json_encode($data);
	if ($arreglo) {
		echo $arreglo;
	} else {
		echo json_last_error_msg();
	}
} //cierra accion datosprod


if (isset($_POST['a']) && $_POST['a'] == 'delp') {
	//echo 'entra ok';
	$id = $_POST['id'];
	$delsql = $link->query("UPDATE productos SET estado_producto='0' WHERE id_producto = '$id' ") or die(mysqli_error());
	if ($delsql) {
		echo 'TRUE';
	} else {
		echo 'FALSE';
	}
}
