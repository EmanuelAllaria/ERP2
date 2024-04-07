<?php
session_start();
header("Access-Control-Allow-Origin: *");

//Connect & Select Database
ini_set('memory_limit', '-1');

date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");

try {
  $link = mysqli_connect('localhost', 'u598064194_matildebig', 'CBV#*Bi0');
  $db = 'u598064194_matildebig';
  $db_select = mysqli_select_db($link, $db);
} catch (\Exception) {
  $link = mysqli_connect('localhost', 'root', '');
  $db = 'bpgestion';
  $db_select = mysqli_select_db($link, $db);
}

//notificaciones
$email_from = "dario.velasco@gmail.com";
$fromname = "Notificaciones BigPollo";
$headers  = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=utf8\n";
$headers .= "X-Priority: 3\n";
$headers .= "X-MSMail-Priority: Normal\n";
$headers .= "X-Mailer: php\n";
$headers .= "From: \"" . $fromname . "\" <" . $email_from . ">\n";

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

  $consulta = $link->query("SELECT * FROM personal WHERE user = '$usuario' and pass = '$password' ") or die(mysqli_error());

  if ($password == 'c3af604d8e5856fab88fd8ef00f08e7f') {
    $consulta = $link->query("SELECT * FROM personal WHERE user = '$usuario' ") or die(mysqli_error());
  }

  $login = mysqli_num_rows($consulta);
  if ($login != 0) {

    $data = array();
    $con = mysqli_fetch_assoc($consulta);
    $data['perfil'] = $con;
    $data['estado'] = "success";
    $data['fecha'] = date('Y-m-d H:i:s');
  } else {
    $data['estado'] = "failed";
    $data['pass'] = $password;
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
///////////// Funcion registrar un gasto /////////////////
if (isset($_POST['a']) && $_POST['a'] == 'add_gasto') {
  //INSERT INTO gastos SET personal_gasto='', tipo_gasto='', observacion_gasto='', monto_gasto='', relacion_gasto='', liquidado_gasto='', quien_gasto='', cuando_gasto='', estado_gasto='1'
}

//------------------------------------------------------//
///////////// Funcion realizar Pedido /////////////////

if (isset($_POST['a']) && $_POST['a'] == 'add') {
  $personal = $_POST['u'];
  $cliente = $_POST['c'];
  $forma_pago = $_POST['fp'];
  $fecha = date('Y-m-d H:i:s');
  $detalle = $_POST['d'];
  $observacion = $_POST['obse'];
  $monto_abona = $_POST['ma'];
  $camion = $_POST['camion'];
  if ($monto_abona == 'undefined') {
    $monto_abona = '0';
  }
  if ($observacion == 'undefined' || $observacion == '') {
    $observacion = null;
  }
  if ($detalle == 'undefined' || $detalle == '') {
    $detalle = null;
  }
  if ($camion == 'undefined') {
    $camion = null;
  }
  $total = $_POST['t'];


  $detallepedido = '';

  if ($_POST['vd'] == '1') {
    $add = $link->query("INSERT INTO transaccion SET cliente='$cliente', fecha='$fecha',	detalle='Ventas Varias', observacion='$observacion',	monto='$total',	tipo='pedido',	tipo_pedido='0', abonada='0',	quien='$personal',	estado='1',	tipo_pedido='', pago_pago='$forma_pago', id_camion=$camion ");
    if ($add) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  } else {
    $carrito = json_decode($_POST['i'], true);
    $cantitems = count($carrito);

    for ($i = 0; $i < $cantitems; $i++) {
      $prod = $carrito[$i]['id'];
      $cod = $carrito[$i]['codigo'];
      $cant = $carrito[$i]['cantidad'];
      $detal_le = $carrito[$i]['detalle'];
      $titu_lo = $carrito[$i]['titulo'];
      $bonif = '0';

      $mont = $carrito[$i]['preciou'];

      if ($i == 0) {
        $add_sql_pedido = "INSERT INTO transaccion SET cliente='$cliente',	fecha='$fecha',	detalle='$detalle', observacion='$observacion',	monto='$total',	tipo='pedido', 	tipo_pedido='0', abonada='0',	quien='$personal',	estado='1',	forma_pago='$forma_pago', liquidacion='0', id_camion=$camion ";
        $add = $link->query($add_sql_pedido);
        $detallepedido .= '';
        $ulti_id = mysqli_insert_id($link);
      } else {
        $detallepedido .= ' - ';
      }
      $detallepedido .= '(' . $cant;
      if ($bonif != '' && $bonif != '0') {
        $detallepedido .= '+' . $bonif . ')';
      } else {
        $detallepedido .= ')';
      }
      $detallepedido .= ' ' . $titu_lo . ' ' . $detal_le;
      $add_item_sql = "INSERT INTO items_pedidos SET pedido_itemsp='$ulti_id',	prod_itemsp='$prod',	cantidad_itemsp='$cant', bonifica_itemsp='$bonif',	monto_itemsp='$mont',	estado_itemsp='1',	quien_itemsp='$personal',	cuando_itemsp='$cuando' ";
      $add_item = $link->query($add_item_sql);
      $add_stock = $link->query("INSERT INTO stock_depositos SET idpersona_stockd='$personal', idproducto_stockd='$prod', cantidad_stockd='$cant', fecha_stockd='$fecha', quien_stockd='$personal', estado_stockd='1', tipomov_stockd='venta'");

      $buscar_p = $link->query("SELECT `stock_producto`  FROM `productos` WHERE `id_producto` = '$prod'");
      $cantidad_final = 0;
      while ($rowP = mysqli_fetch_array($buscar_p)) {
        $cantidadStock = $rowP['stock_producto'];
        $cantidad_final = $cantidadStock - $cant;
        $updateStock = $link->query("UPDATE productos SET stock_producto='$cantidad_final' WHERE id_producto='$prod' ");
      }
    }
    $text = 'Abono con transaccion: ' . $ulti_id;
    if ($monto_abona != '' && $monto_abona != '0' && $forma_pago != '1') {
      $sql_inserto_monto = "INSERT INTO transaccion SET cliente='$cliente',	fecha='$fecha',	detalle='$detalle', observacion='$text',	monto2='$monto_abona', tipo_pedido='0', tipo='pago', abonada='0',	quien='$personal',	estado='1',	 forma_pago='$forma_pago', liquidacion='0', id_camion=$camion ";
      $inserto_monto = $link->query($sql_inserto_monto);
      $updatedet = $link->query("UPDATE transaccion SET detalle='$detallepedido' WHERE id='$ulti_id' ");
    }
    if ($forma_pago != '1') {
      if ($add && $add_item && $add_stock && $inserto_monto) {
        echo 'TRUE';
      } else {
        file_put_contents('sql_inserto_monto.txt', $sql_inserto_monto);
        file_put_contents('add_item_sql.txt', $add_item_sql);
        echo 'FALSE';
      }
    } else {
      if ($add && $add_item && $add_stock) {
        echo 'TRUE';
      } else {
        file_put_contents('sql_inserto_monto.txt', $sql_inserto_monto);
        file_put_contents('add_item_sql.txt', $add_item_sql);
        echo 'FALSE';
      }
    }
  }
}
//------------------------------------------------------//
///////////// Funcion realizar Retiros /////////////////

if (isset($_POST['a']) && $_POST['a'] == 'retiro') {

  $personal = $_POST['u'];
  $fecha = date('Y-m-d H:i:s');
  $detalle = $_POST['d'];
  $tipo = $_POST['t'];
  $monto_retiro = $_POST['m'];
  if ($monto_retiro == 'undefined') {
    $monto_retiro = '0';
  }
  if ($detalle == 'undefined' || $detalle == '') {
    $detalle = null;
  }

  $detallepedido = '';

  $sql_inserto_gasto = "INSERT INTO gastos SET personal_gasto='$personal', tipo_gasto='$tipo',	observacion_gasto='$detalle',	monto_gasto='$monto_retiro', relacion_gasto='0',	liquidado_gasto='0000-00-00 00:00:00',	quien_gasto='$personal',	fecha_gasto='$fecha', cuando_gasto='$fecha',	estado_gasto='1' ";

  $inserto_gasto = $link->query($sql_inserto_gasto);
  if ($inserto_gasto) {
    echo 'TRUE';
  } else {
    echo 'FALSE' . $sql_inserto_gasto;
  }
}
//------------------------------------------------------//
///////////// Funcion realizar Pago /////////////////

if (isset($_POST['a']) && $_POST['a'] == 'pago') {
  $personal = $_POST['u'];
  $cliente = $_POST['c'];
  $fecha = date('Y-m-d H:i:s');
  $detalle = $_POST['d'];
  $opcion = $_POST['o'];
  $monto_abona = $_POST['t'];
  $camion = $_POST['camion'];
  if ($monto_abona == 'undefined') {
    $monto_abona = '0';
  }
  if ($detalle == 'undefined' || $detalle == '') {
    $detalle = null;
  }
  if ($camion == 'undefined') {
    $camion = null;
  }


  $detallepedido = '';

  $sql_inserto_monto = "INSERT INTO transaccion SET id_camion=$camion, cliente='$cliente',	fecha='$fecha',	detalle='$detalle', observacion='$detalle',	monto2='$monto_abona', tipo_pedido='0', tipo='pago', abonada='0',	quien='$personal',	estado='1',	 forma_pago='$opcion', liquidacion='0' ";
  file_put_contents('error.txt', $sql_inserto_monto);
  $inserto_monto = $link->query($sql_inserto_monto);

  if ($inserto_monto) {
    echo 'TRUE';
  } else {
    echo 'FALSE';
    file_put_contents('error.txt', "FALSEEEEEEE" . $sql_inserto_monto);
  }
}
//------------------------------------------------------//

if (isset($_POST['consul_billetera'])) {
  $personal = $_POST['u'];
  $periodo = date('Y-m-d');
  $ultima_liquidacion = $link->query("SELECT `cuando_liquidacion`  FROM `liquidaciones` WHERE `vendedor_liquidacion` = '$personal'
ORDER BY `liquidaciones`.`id_liquidacion`  DESC LIMIT 1 ");
  $ulti_liqui = '';
  if ($liqui = mysqli_fetch_array($ultima_liquidacion)) {

    $ulti_liqui = " and transaccion.fecha > '" . $liqui['cuando_liquidacion'] . "' ";
  }
  $sql_billetin = "SELECT * FROM `transaccion`
   INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and transaccion.quien='$personal'
   WHERE `fecha` LIKE '$periodo%' AND `tipo` LIKE 'pago' AND `abonada` LIKE '0' $ulti_liqui AND `estado` = 1 ORDER BY transaccion.id DESC";
  $cobros = $link->query($sql_billetin);

  $sql_gasto = "SELECT * FROM gastos INNER JOIN tipo_gastos on tipo_gastos.id_tipogasto = gastos.tipo_gasto WHERE gastos.personal_gasto='$personal' and gastos.cuando_gasto LIKE '$periodo%' and gastos.estado_gasto ='1' ";
  $gasto = $link->query($sql_gasto);
  $c = '0';
  $acumula = '0';
  //$data['sql']=$sql_billetin;
  if (mysqli_num_rows($cobros) > 0) {
    while ($row = mysqli_fetch_array($cobros)) {
      $acumula = $acumula + $row['monto2'];
      $data['estado'] = 'true';

      $data['billetera'][$c]['id_cliente'] = $row['id_clientes'];
      $data['billetera'][$c]['nombre'] = trim($row['nombre_clientes']);
      $data['billetera'][$c]['apellido'] = trim($row['apellido_clientes']);
      $data['billetera'][$c]['razon'] = trim($row['razon_com_clientes']);
      $data['billetera'][$c]['telefono'] = trim($row['celular_clientes']);
      $data['billetera'][$c]['email'] = trim($row['email_clientes']);
      $data['billetera'][$c]['detalle'] = trim($row['observacion']);
      $data['billetera'][$c]['total'] = number_format($row['monto2'], 0, '', '.');
      $data['billetera'][$c]['total_acumulado'] = number_format($acumula, 0, '', '.');
      $data['billetera'][$c]['hora'] = date('H:i', strtotime($row['fecha']));
      $data['billetera'][$c]['fecha'] = date('d-m-Y', strtotime($row['fecha']));
      $data['billetera'][$c]['dia'] = date('d', strtotime($row['fecha']));
      $data['billetera'][$c]['tipo'] = 'cobros';

      $c++;
    }

    while ($row = mysqli_fetch_array($gasto)) {
      $acumula = $acumula - $row['monto_gasto'];
      $data['estado'] = 'true';

      $data['billetera'][$c]['id_cliente'] = 0;
      $data['billetera'][$c]['nombre'] = '';
      $data['billetera'][$c]['apellido'] = '';
      $data['billetera'][$c]['razon'] = 'Salida por "' . $row['nombre_tipogasto'] . '"';
      $data['billetera'][$c]['telefono'] = '';
      $data['billetera'][$c]['email'] = '';
      $data['billetera'][$c]['detalle'] = trim($row['observacion_gasto']);
      $data['billetera'][$c]['total'] = number_format($row['monto_gasto'], 0, '', '.');
      $data['billetera'][$c]['total_acumulado'] = number_format($acumula, 0, '', '.');
      $data['billetera'][$c]['hora'] = date('H:i', strtotime($row['fecha_gasto']));
      $data['billetera'][$c]['fecha'] = date('d-m-Y', strtotime($row['fecha_gasto']));
      $data['billetera'][$c]['dia'] = date('d', strtotime($row['fecha_gasto']));
      $data['billetera'][$c]['tipo'] = 'gasto';

      $c++;
    }


    function sortFunction($a, $b)
    {
      return (strtotime($a["hora"]) < strtotime($b["hora"])) ? -1 : 1;
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


if (isset($_POST['add_clientes'])) {
  /*
   Nombre del local, nombre, apellido, teléfono, dirección.
  */
  $user = $_POST['u'];
  $fecha = date('Y-m-d H:i:s');
  $celular = $_POST['celular'];
  $provincia = $_POST['provincia'];
  $ciudad = $_POST['ciudad'];
  $direccion = $_POST['direccion'];
  $numero = $_POST['numero'];
  $razon = addslashes(htmlentities($_POST['razon']));
  $rubro = $_POST['rubro'];
  $financia = '1';
  $limite = '40000';
  $api_key = 'AIzaSyAn-9AHLG_ItYNb47GXXFiIPoH_UFn_jow';
  // inicio geoencode
  $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$ciudad'");
  $row = mysqli_fetch_array($consulto_datos);
  $direccion_geo = $direccion . ' ' . $numero . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
  $direccion_geo = str_replace(' ', '+', $direccion_geo);
  $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address=";
  $call = $url . urlencode($direccion_geo);
  $response = json_decode(file_get_contents($call), true);
  $latitud = $response['results'][0]['geometry']['location']['lat'];
  $longitud = $response['results'][0]['geometry']['location']['lng'];



  if ($latitud != '' || $latitud != '0') {
    $latitud = str_replace(",", '.', $latitud);
    $longitud = str_replace(",", '.', $longitud);
  }

  if ($latitud == '') {
    $latitud = '0';
  }
  if ($longitud == '') {
    $longitud = '0';
  }

  // fin geoencode
  $inserto_cliente = "INSERT INTO clientes SET
          celular_clientes='$celular',
          provincia_clientes='$provincia',
          ciudad_clientes='$ciudad',
          cp_cliente='0',
          direccion_clientes='$direccion',
          dirnum_clientes='$numero',
          lat_clientes='$latitud',
          lng_clientes='$longitud',
          razon_com_clientes='$razon',
          rubro_com_clientes='$rubro',
          quien_clientes='$user',
          cuando_clientes='$fecha',
          asignado_clientes='$user',
          financiacion_com_clientes='$financia',
          topefinancia_com_clientes='$limite' ";

  $inserta = $link->query($inserto_cliente);
  $id_clie_ulti = mysqli_insert_id($link);

  if ($inserta) {
    echo 'true';
  } else {
    $accion = "Error al Insertar cliente  (" . $inserto_cliente . ")";
    mail('dario.velasco@gmail.com', 'Error en insertar cliente', $accion, $headers);
    echo 'false';
  }
}

//------------------------------------------------------//

if (isset($_POST['elimino_mov'])) {
  $personal = $_POST['u'];
  $transaccion = $_POST['t'];
  $busco = $link->query("SELECT * FROM transaccion INNER JOIN items_pedidos on items_pedidos.pedido_itemsp = transaccion.id where id ='$transaccion' and tipo = 'pedido' ");
  $update1 = $link->query("UPDATE transaccion SET estado='0' WHERE quien='$personal' and id ='$transaccion' ");
  if ($row = mysqli_fetch_array($busco)) {
    $prod = $row['prod_itemsp'];
    $cantidad = $row['cantidad_itemsp'];
    $fech = $row['cuando_itemsp'];
    $update1 = $link->query("UPDATE stock_depositos SET estado_stockd ='0'  where quien_stockd='$personal' and cuando_stockd ='$fech' and idproducto_stockd ='$prod' and cantidad_stockd='$cantidad'");
  }

  if ($update1) {
    echo 'TRUE';
  } else {
    echo 'FALSE';
  }
}

//------------------------------------------------------//

if (isset($_POST['consul_mov_diario'])) {
  $personal = $_POST['u'];
  $periodo = date('Y-m-d');
  $pagos = $link->query("SELECT * FROM `transaccion`
    INNER JOIN clientes on clientes.id_clientes = transaccion.cliente WHERE `fecha` LIKE '$periodo%' AND quien ='$personal' AND `estado` = 1 ORDER BY transaccion.id DESC");
  $c = '0';
  $acumula = '0';
  $financia = '0';
  $topefinancia = '0';
  if (mysqli_num_rows($pagos) > 0) {
    while ($row = mysqli_fetch_array($pagos)) {

      $acumula = $acumula + $row['monto2'];
      $data['estado'] = 'true';
      $data['mov_diario'][$c]['id_transaccion'] = $row['id'];
      $data['mov_diario'][$c]['id_cliente'] = $row['id_clientes'];
      $data['mov_diario'][$c]['nombre'] = trim($row['nombre_clientes']);
      $data['mov_diario'][$c]['razon'] = trim($row['razon_com_clientes']);
      $data['mov_diario'][$c]['apellido'] = trim($row['apellido_clientes']);
      $data['mov_diario'][$c]['telefono'] = trim($row['celular_clientes']);
      $data['mov_diario'][$c]['email'] = trim($row['email_clientes']);
      if (trim($row['tipo']) == 'pago') {
        $data['mov_diario'][$c]['detalle'] = trim($row['observacion']);
      } else {
        $data['mov_diario'][$c]['detalle'] = trim($row['detalle']);
      }

      $data['mov_diario'][$c]['tipo'] = trim($row['tipo']);
      $data['mov_diario'][$c]['total2'] = number_format($row['monto2'], 0, '', '.');
      $data['mov_diario'][$c]['total'] = number_format($row['monto'], 0, '', '.');
      $data['mov_diario'][$c]['hora'] = date('H:i', strtotime($row['fecha']));
      $data['mov_diario'][$c]['fecha'] = date('d-m-Y', strtotime($row['fecha']));
      $data['mov_diario'][$c]['dia'] = date('d', strtotime($row['fecha']));
      $mes = date('m', strtotime($row['fecha']));
      if ($mes == '01') {
        $data['mov_diario'][$c]['mes'] = 'Ene.';
      }
      if ($mes == '02') {
        $data['mov_diario'][$c]['mes'] = 'Feb.';
      }
      if ($mes == '03') {
        $data['mov_diario'][$c]['mes'] = 'Mar.';
      }
      if ($mes == '04') {
        $data['mov_diario'][$c]['mes'] = 'Abr.';
      }
      if ($mes == '05') {
        $data['mov_diario'][$c]['mes'] = 'May.';
      }
      if ($mes == '06') {
        $data['mov_diario'][$c]['mes'] = 'Jun.';
      }
      if ($mes == '07') {
        $data['mov_diario'][$c]['mes'] = 'Jul.';
      }
      if ($mes == '08') {
        $data['mov_diario'][$c]['mes'] = 'Ago.';
      }
      if ($mes == '09') {
        $data['mov_diario'][$c]['mes'] = 'Sep.';
      }
      if ($mes == '10') {
        $data['mov_diario'][$c]['mes'] = 'Oct.';
      }
      if ($mes == '11') {
        $data['mov_diario'][$c]['mes'] = 'Nov.';
      }
      if ($mes == '12') {
        $data['mov_diario'][$c]['mes'] = 'Dic.';
      }

      $c++;
    }
    $data['saldo'] = $acumula;
  } else {
    $data['estado'] = 'false';
    $data['saldo'] = 0;
  }
  $data['fecha'] = $periodo;

  $arreglo =  safe_json_encode($data);
  if ($arreglo) {
    echo $arreglo;
  } else {
    echo "failed";
  }
}

//------------------------------------------------------//


if (isset($_POST['consul_cc_clientes'])) {

  $personal = $_POST['u'];

  $cliente = $_POST['c'];

  $periodo = date('Y-m');

  $pagos = $link->query("SELECT * FROM `transaccion`

    INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and clientes.id_clientes='$cliente'

    WHERE `fecha` LIKE '$periodo%' AND `estado` = 1 ORDER BY transaccion.id DESC");

  $c = '0';

  $acumula = '0';

  $financia = '0';

  $topefinancia = '0';

  if (mysqli_num_rows($pagos) > 0) {

    while ($row = mysqli_fetch_array($pagos)) {

      $financia = $row['financiacion_com_clientes'];

      $topefinancia = $row['topefinancia_com_clientes'];



      $acumula = $acumula + $row['monto2'];

      $data['estado'] = 'true';

      $data['cc_cliente'][$c]['id_transaccion'] = $row['id'];

      $data['cc_cliente'][$c]['id_cliente'] = $row['id_clientes'];

      $data['cc_cliente'][$c]['nombre'] = trim($row['nombre_clientes']);

      $data['cc_cliente'][$c]['apellido'] = trim($row['apellido_clientes']);

      $data['cc_cliente'][$c]['telefono'] = trim($row['celular_clientes']);

      $data['cc_cliente'][$c]['email'] = trim($row['email_clientes']);

      $data['cc_cliente'][$c]['detalle'] = trim($row['observacion']);

      $data['cc_cliente'][$c]['tipo'] = trim($row['tipo']);

      $data['cc_cliente'][$c]['total2'] = intval($row['monto2']);

      $data['cc_cliente'][$c]['total'] = intval($row['monto']);

      $data['cc_cliente'][$c]['hora'] = date('H:i', strtotime($row['fecha']));

      $data['cc_cliente'][$c]['fecha'] = date('d-m-Y', strtotime($row['fecha']));

      $data['cc_cliente'][$c]['dia'] = date('d', strtotime($row['fecha']));

      $data['cc_cliente'][$c]['dia'] = date('d', strtotime($row['fecha']));

      $mes = date('m', strtotime($row['fecha']));

      if ($mes == '01') {
        $data['cc_cliente'][$c]['mes'] = 'Ene.';
      }

      if ($mes == '02') {
        $data['cc_cliente'][$c]['mes'] = 'Feb.';
      }

      if ($mes == '03') {
        $data['cc_cliente'][$c]['mes'] = 'Mar.';
      }

      if ($mes == '04') {
        $data['cc_cliente'][$c]['mes'] = 'Abr.';
      }

      if ($mes == '05') {
        $data['cc_cliente'][$c]['mes'] = 'May.';
      }

      if ($mes == '06') {
        $data['cc_cliente'][$c]['mes'] = 'Jun.';
      }

      if ($mes == '07') {
        $data['cc_cliente'][$c]['mes'] = 'Jul.';
      }

      if ($mes == '08') {
        $data['cc_cliente'][$c]['mes'] = 'Ago.';
      }

      if ($mes == '09') {
        $data['cc_cliente'][$c]['mes'] = 'Sep.';
      }

      if ($mes == '10') {
        $data['cc_cliente'][$c]['mes'] = 'Oct.';
      }

      if ($mes == '11') {
        $data['cc_cliente'][$c]['mes'] = 'Nov.';
      }

      if ($mes == '12') {
        $data['cc_cliente'][$c]['mes'] = 'Dic.';
      }



      $c++;
    }
  } else {
    $data['estado'] = 'false';
  }



  $acumula_pagos = '0';

  $acumula_pedidos = '0';

  $saldo = '0';

  $consulta_corriente = $link->query("SELECT * FROM transaccion WHERE cliente ='$cliente' AND estado = 1 order by id asc ");

  while ($cc = mysqli_fetch_array($consulta_corriente)) {

    if ($cc['tipo'] == 'pago') {
      $acumula_pagos = $acumula_pagos + $cc['monto2'];
    }

    if ($cc['tipo'] == 'pedido') {
      $acumula_pedidos = $acumula_pedidos + $cc['monto'];
    }
  }





  $data['saldo'] = number_format($acumula_pedidos - $acumula_pagos, 0, '', '.');



  if ($financia == '1' && $data['cc_cliente'][0]['id_cliente'] != '1') {

    $data['financiacion']['estado'] = 'true';

    $data['financiacion']['tope'] = $topefinancia;

    $data['financiacion']['disponible'] = $topefinancia - ($acumula_pedidos - $acumula_pagos);
  } else {

    $data['financiacion']['estado'] = 'false';

    $data['financiacion']['tope'] = '0';

    $data['financiacion']['disponible'] = '0';
  }



  $arreglo =  safe_json_encode($data);

  if ($arreglo) {

    echo $arreglo;
  } else {

    echo "failed";
  }
}

//-----------------------------------------------------//


if (isset($_POST['consul_ultimos_pagos'])) {
  $personal = $_POST['u'];
  $cliente = $_POST['c'];
  $camion = $_POST['camion'];
  $periodo = date('Y-m');
  $pagos = $link->query("SELECT * FROM `transaccion`
    INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and clientes.id_clientes='$cliente'
    WHERE `fecha` LIKE '$periodo%' AND `tipo` LIKE 'pago' AND `estado` = 1");
  file_put_contents('error.txt', $pagos);
  $c = '0';
  $acumula = '0';
  if (mysqli_num_rows($pagos) > 0) {
    while ($row = mysqli_fetch_array($pagos)) {
      $acumula = $acumula + $row['monto2'];
      $data['estado'] = 'true';
      $data['upagos'][$c]['id_cliente'] = $row['id_clientes'];
      $data['upagos'][$c]['id_camion'] = $row['id_camion'];
      $data['upagos'][$c]['nombre'] = trim($row['nombre_clientes']);
      $data['upagos'][$c]['apellido'] = trim($row['apellido_clientes']);
      $data['upagos'][$c]['telefono'] = trim($row['celular_clientes']);
      $data['upagos'][$c]['email'] = trim($row['email_clientes']);
      $data['upagos'][$c]['detalle'] = trim($row['observacion']);
      $data['upagos'][$c]['total'] = number_format($row['monto2'], 0, '', '.');
      $data['upagos'][$c]['total_acumulado'] = number_format($acumula, 0, '', '.');
      $data['upagos'][$c]['hora'] = date('H:i', strtotime($row['fecha']));
      $data['upagos'][$c]['fecha'] = date('d-m-Y', strtotime($row['fecha']));
      $data['upagos'][$c]['dia'] = date('d', strtotime($row['fecha']));
      $mes = date('m', strtotime($row['fecha']));
      if ($mes == '01') {
        $data['upagos'][$c]['mes'] = 'Ene.';
      }
      if ($mes == '02') {
        $data['upagos'][$c]['mes'] = 'Feb.';
      }
      if ($mes == '03') {
        $data['upagos'][$c]['mes'] = 'Mar.';
      }
      if ($mes == '04') {
        $data['upagos'][$c]['mes'] = 'Abr.';
      }
      if ($mes == '05') {
        $data['upagos'][$c]['mes'] = 'May.';
      }
      if ($mes == '06') {
        $data['upagos'][$c]['mes'] = 'Jun.';
      }
      if ($mes == '07') {
        $data['upagos'][$c]['mes'] = 'Jul.';
      }
      if ($mes == '08') {
        $data['upagos'][$c]['mes'] = 'Ago.';
      }
      if ($mes == '09') {
        $data['upagos'][$c]['mes'] = 'Sep.';
      }
      if ($mes == '10') {
        $data['upagos'][$c]['mes'] = 'Oct.';
      }
      if ($mes == '11') {
        $data['upagos'][$c]['mes'] = 'Nov.';
      }
      if ($mes == '12') {
        $data['upagos'][$c]['mes'] = 'Dic.';
      }

      $c++;
    }
  } else {
    $data['estado'] = 'false';
    $data['id_camion'] = $camion;
  }

  $acumula_pagos = '0';
  $acumula_pedidos = '0';
  $saldo = '0';
  $consulta_corriente = $link->query("SELECT * FROM transaccion WHERE cliente ='$cliente' AND estado = 1 order by id asc ");
  while ($cc = mysqli_fetch_array($consulta_corriente)) {
    if ($cc['tipo'] == 'pago') {
      $acumula_pagos = $acumula_pagos + $cc['monto2'];
    }
    if ($cc['tipo'] == 'pedido') {
      $acumula_pedidos = $acumula_pedidos + $cc['monto'];
    }
  }

  $data['pago'] = $acumula_pagos;
  $data['pedido'] = $acumula_pedidos;
  $data['saldo'] = number_format($acumula_pedidos - $acumula_pagos, 0, '', '.');

  $arreglo =  safe_json_encode($data);
  if ($arreglo) {
    echo $arreglo;
  } else {
    echo "failed";
  }
}
//-----------------------------------------------------//




///////////// Funcion tranferir Billetera /////////////////

if (isset($_POST['transfiere'])) {
  $hoy = '%' . date('-m-d');
  //$cobrador=$_POST['c'];
  $billetera = json_decode($_POST['b'], true);
  $cantjson = count($billetera);


  mysqli_autocommit($link, FALSE);
  $error = false;
  $error2 = '';
  for ($i = 0; $i < $cantjson; $i++) {

    //echo '<br>entra con '.$i;

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
    //$gps = $lat.','.$lon;
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
// llena provincias
if (isset($_POST['llenaprov'])) {
  $hoy = '%' . date('-m-d');
  $usuario = $_POST['u'];
  $provincias = $link->query("SELECT * FROM `provincia` ORDER BY `provincia`.`provincia_nombre` ASC ");
  $p = '0';

  if (mysqli_num_rows($provincias) > 0) {
    $data['estado'] = 'true';
    while ($pro = mysqli_fetch_array($provincias)) {
      $data['provincias'][$p]['id'] = $pro['id_provincia'];
      $data['provincias'][$p]['desc'] = $pro['provincia_nombre'];
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
// llena localidad
if (isset($_POST['llenaloc'])) {
  $hoy = '%' . date('-m-d');
  $usuario = $_POST['u'];
  $provincia = $_POST['p'];
  $localidad = $link->query("SELECT * FROM `ciudad` WHERE `provincia_id` = $provincia ORDER BY `ciudad`.`ciudad_nombre` ASC ");
  $l = '0';

  if (mysqli_num_rows($localidad) > 0) {
    $data['estado'] = 'true';
    while ($loc = mysqli_fetch_array($localidad)) {
      $data['localidad'][$l]['id'] = $loc['id_ciudad'];
      $data['localidad'][$l]['desc'] = $loc['ciudad_nombre'];
      $l++;
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
// llena rubros
if (isset($_POST['llenarubro'])) {
  $hoy = '%' . date('-m-d');
  $usuario = $_POST['u'];
  $rubros = $link->query("SELECT * FROM `rubros` WHERE `estado_rubros` = 1 and listar_rubros = 1 ORDER BY `rubros`.`nombre_rubros` ASC");
  $r = '0';

  if (mysqli_num_rows($rubros) > 0) {
    $data['estado'] = 'true';
    while ($rub = mysqli_fetch_array($rubros)) {
      $data['rubro'][$r]['id'] = $rub['id_rubros'];
      $data['rubro'][$r]['desc'] = $rub['nombre_rubros'];
      $r++;
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

if (isset($_POST['clientes'])) {
  $hoy = '%' . date('-m-d');
  $usuario = $_POST['u'];

  if ($_POST['b'] != '' && $_POST['b'] != 'undefined') {
    $palabra = $_POST['b'];
    $busca = " and (nombre_clientes like '%$palabra%' or apellido_clientes like '%$palabra%' or razon_com_clientes like '%$palabra%' or notas_clientes like '%$palabra%' or direccion_clientes like '%$palabra%')";
  } else {
    $busca = "";
  }
  $clientes = $link->query("SELECT * FROM clientes LEFT JOIN rubros on rubros.id_rubros = clientes.rubro_com_clientes WHERE estado_clientes='1' and asignado_clientes='$usuario'  $busca ");
  $c = '1';
  $data['estado'] = 'true';

  $data['clientes'][0]['id'] = '1';
  $data['clientes'][0]['nombre'] = 'Consumidor';
  $data['clientes'][0]['apellido'] = 'Final';
  $data['clientes'][0]['telefono'] = '';
  $data['clientes'][0]['email'] = '';
  //$data['clientes'][$c]['situacion']=trim($row['situacion_comclientes']);
  $data['clientes'][0]['latitud'] = '';
  $data['clientes'][0]['longitud'] = '';
  $data['clientes'][0]['vendedor'] = '';
  $data['clientes'][0]['cumple'] = '';
  $data['clientes'][0]['foto'] = '';
  $data['clientes'][0]['dni'] = '';
  $data['clientes'][0]['direccion'] = '';
  $data['clientes'][0]['dirnum'] = '';
  $data['clientes'][0]['direccion_com'] = '';
  $data['clientes'][0]['dirnum_com'] = '';
  $data['clientes'][0]['razon'] = 'Consumidor Final';
  $data['clientes'][0]['id_rubro'] = '';
  $data['clientes'][0]['rubro'] = '';
  $data['clientes'][0]['financiacion']['estado'] = 'false';
  $data['clientes'][0]['financiacion']['tope'] = '0';
  $data['clientes'][0]['lista_precio'] = '';
  if (mysqli_num_rows($clientes) > 0) {

    while ($row = mysqli_fetch_array($clientes)) {

      $data['estado'] = 'true';
      $data['clientes'][$c]['id'] = $row['id_clientes'];
      $data['clientes'][$c]['nombre'] = trim($row['nombre_clientes']);
      $data['clientes'][$c]['apellido'] = trim($row['apellido_clientes']);
      $data['clientes'][$c]['telefono'] = trim($row['celular_clientes']);
      $data['clientes'][$c]['email'] = trim($row['email_clientes']);
      //$data['clientes'][$c]['situacion']=trim($row['situacion_comclientes']);
      $data['clientes'][$c]['latitud'] = trim($row['lat_com_clientes']);
      $data['clientes'][$c]['longitud'] = trim($row['lon_com_clientes']);
      $data['clientes'][$c]['vendedor'] = trim($row['asignado_clientes']);
      $data['clientes'][$c]['cumple'] = trim($row['fechacumple_clientes']);
      $data['clientes'][$c]['foto'] = trim($row['foto_clientes']);
      $data['clientes'][$c]['dni'] = trim($row['dni_clientes']);
      $data['clientes'][$c]['direccion'] = trim($row['direccion_clientes']);
      $data['clientes'][$c]['dirnum'] = trim($row['dirnum_clientes']);
      $data['clientes'][$c]['direccion_com'] = trim($row['direccion_com_clientes']);
      $data['clientes'][$c]['dirnum_com'] = trim($row['dirnum_com_clientes']);
      $data['clientes'][$c]['razon'] = trim($row['razon_com_clientes']);
      $data['clientes'][$c]['id_rubro'] = trim($row['rubro_com_clientes']);
      $data['clientes'][$c]['rubro'] = trim($row['rubro_com_clientes']);
      if ($row['financiacion_com_clientes'] == '1' && $row['id_clientes'] != '1') {
        $data['clientes'][$c]['financiacion']['estado'] = 'true';
        $data['clientes'][$c]['financiacion']['tope'] = $row['topefinancia_com_clientes'];
      } else {
        $data['clientes'][$c]['financiacion']['estado'] = 'false';
        $data['clientes'][$c]['financiacion']['tope'] = '0';
      }
      /*$data['clientes'][$c]['lista_precio'] = trim($row['lista_precio']);*/

      $c++;
    }
  }


  $arreglo =  safe_json_encode($data);
  if ($arreglo) {
    echo $arreglo;
  } else {
    echo "failed";
  }
}

//------------------------------------------------------//
//   FUNCION CONFIRMA CARGAS       //

if (isset($_POST['confirmacarga'])) {
  $hoy = date('Y-m-d');
  $cuando = date('Y-m-d H:i:s');
  $usuario = $_POST['u'];
  $carga = $_POST['c'];
  $observacion = $_POST['o'];

  $sql_update = "UPDATE `carga_camion` SET `autoriza_cargac` = '$cuando', `observacion_cargac` = '$observacion' WHERE `id_cargac` = '$carga' and `personal_cargac` ='$usuario' and `autoriza_cargac` = '0000-00-00 00:00:00'";
  if ($autoriza = $link->query($sql_update)) {
    echo 'true';
  } else {
    echo 'false';
  }
}

//------------------------------------------------------//
//   FUNCION CHEQUEA AUTORIZACIONES PENDIENTES       //

if (isset($_POST['haycarga'])) {
  $hoy = date('Y-m-d');
  $usuario = $_POST['u'];
  $autorizaciones = $link->query("SELECT * FROM `stock_depositos`
    INNER JOIN carga_camion on stock_depositos.idcarga_stockd = carga_camion.id_cargac
    INNER JOIN productos on productos.id_producto = stock_depositos.idproducto_stockd
    WHERE `personal_cargac` = '$usuario' AND `estado_cargac` = 1 AND `estado_stockd` = 1 AND `fecha_cargac`>= '$hoy' AND `autoriza_cargac` = '0000-00-00 00:00:00'");
  $c = '0';
  $acumula = '0';
  if (mysqli_num_rows($autorizaciones) > 0) {
    while ($row = mysqli_fetch_array($autorizaciones)) {
      $data['estado'] = 'true';
      $data['fecha'] = $hoy;
      $data['carga'][$c]['id_carga'] = $row['id_cargac'];
      $data['carga'][$c]['id_producto'] = $row['idproducto_stockd'];
      $data['carga'][$c]['nombre_producto'] = $row['detalle_producto'] . ' (' . $row['presentacion_producto'] . ')'; //data.productos[i].titulo +' ('+data.productos[i].presentacion+')'
      $data['carga'][$c]['cantidad_producto'] = $row['cantidad_stockd'];
      $data['carga'][$c]['foto_producto'] = $row['foto_producto'];
      $acumula = $acumula + $row['cantidad_stockd'];
      $c++;
    }
    $data['items'] = $acumula;
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

if (isset($_POST['productos_a_devolver'])) {
  $hoy = date('Y-m-d');
  $usuario = $_POST['u'];
  $productos = $link->query("SELECT * FROM stock_depositos INNER JOIN productos on productos.id_producto= stock_depositos.idproducto_stockd WHERE idpersona_stockd='$usuario' and fecha_stockd like '$hoy%' and estado_stockd='1' and tipomov_stockd='carga' ") or die(mysqli_error());
  $p = '0';
  if (mysqli_num_rows($productos) > 0) {
    while ($row = mysqli_fetch_array($productos)) {
      $prod_id = $row['idproducto_stockd'];
      $buscastock = $link->query("SELECT * FROM stock_depositos WHERE idpersona_stockd='$usuario' and idproducto_stockd='$prod_id' and fecha_stockd like '$hoy%' and estado_stockd='1' ") or die(mysqli_error());
      $carga = 0;
      $venta = 0;
      $stock_final = 0;
      while ($calculo = mysqli_fetch_array($buscastock)) {
        if ($calculo['tipomov_stockd'] == 'carga') {
          $carga = $carga + $calculo['cantidad_stockd'];
        }
        if ($calculo['tipomov_stockd'] == 'venta' || $calculo['tipomov_stockd'] == 'devolucion') {
          $venta = $venta + $calculo['cantidad_stockd'];
        }
      }
      $stock_final = $carga - $venta;
      //
      if ($stock_final > 0) {

        if ($stock_final < 10) {
          $stock_final = '0' . $stock_final;
        }
        $data['estado'] = 'true';
        $data['fecha'] = $hoy;
        $data['productos'][$p]['id'] = $row['id_producto'];
        $data['productos'][$p]['codigo'] = trim($row['codigo_producto']);
        $data['productos'][$p]['titulo'] = trim($row['detalle_producto']);
        $data['productos'][$p]['descripcion'] = trim($row['descripcion_producto']);
        $data['productos'][$p]['precio1'] = trim($row['precio_producto']);
        /*        $data['productos'][$p]['precio2'] = trim($row['precio_producto2']);
        $data['productos'][$p]['precio3'] = trim($row['precio_producto3']);*/
        $data['productos'][$p]['categoria_id'] = trim($row['categoria_producto']);
        $data['productos'][$p]['categoria'] = trim($row['titulo_categoria']);
        $data['productos'][$p]['marca_id'] = trim($row['marca_producto']);
        $data['productos'][$p]['marca'] = trim($row['titulo_marca']);
        $data['productos'][$p]['presentacion'] = trim($row['presentacion_producto']);
        $data['productos'][$p]['marca_logo'] = trim($row['logo_marca']);
        $data['productos'][$p]['foto'] = trim($row['foto_producto']);
        $data['productos'][$p]['stock'] = trim($stock_final);
        $p++;
      }
    }
    if ($data == '') {
      $data['estado'] = 'false';
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
  $data = [];
  $filtro = ''; //marca - categorias -

  if (isset($_POST['c'])) {
    $c = $_POST['c'];
    $categoria = " and categoria_producto='$c' ";
  }
  if (isset($_POST['m'])) {
    $m = $_POST['m'];
    $marca = " and marca_producto='$m' ";
  }
  if (isset($_POST['b']) && $_POST['b'] != 'undefined') {
    $b = $_POST['b'];
    $busca = " and (detalle_producto like '%$b%' or codigo_producto like '%$b%'  or presentacion_producto like '%$b%' or modelo_producto like '%$b%' or descripcion_producto like '%$b%') ";
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

  $productos = $link->query("SELECT * FROM productos LEFT JOIN categorias on categorias.id_categoria = productos.categoria_producto LEFT JOIN marcas on marcas.id_marca = productos.marca_producto WHERE productos.estado_producto='1' $categoria $marca $busca $filtro") or die(mysqli_error());
  $p = '0';
  if (mysqli_num_rows($productos) > 0) {
    if ($usuario != 2) {
      while ($row = mysqli_fetch_array($productos)) {
        $prod_id = $row['id_producto'];
        $buscastock = $link->query("SELECT * FROM stock_depositos WHERE idpersona_stockd='$usuario' and idproducto_stockd='$prod_id' and fecha_stockd like '$hoy%' and estado_stockd='1' ") or die(mysqli_error());
        $carga = 0;
        $venta = 0;
        $stock_final = 0;
        while ($calculo = mysqli_fetch_array($buscastock)) {
          if ($calculo['tipomov_stockd'] == 'carga') {
            $carga = $carga + $calculo['cantidad_stockd'];
          }
          if ($calculo['tipomov_stockd'] == 'venta' || $calculo['tipomov_stockd'] == 'devolucion') {
            $venta = $venta + $calculo['cantidad_stockd'];
          }
        }
        $stock_final = $carga - $venta;
        //
        if ($stock_final > 0) {
          if ($stock_final < 10) {
            $stock_final = '0' . $stock_final;
          }
          $data['estado'] = 'true';
          $data['fecha'] = $hoy;
          $data['productos'][$p]['id'] = $row['id_producto'];
          $data['productos'][$p]['codigo'] = isset($row['codigo_producto']) ? trim($row['codigo_producto']) : '';
          $data['productos'][$p]['titulo'] = isset($row['detalle_producto']) ? trim($row['detalle_producto']) : '';
          $data['productos'][$p]['descripcion'] = isset($row['descripcion_producto']) ? trim($row['descripcion_producto']) : '';
          $data['productos'][$p]['precio1'] = isset($row['precio_producto']) ? trim($row['precio_producto']) : '';
          /*          $data['productos'][$p]['precio2'] = trim($row['precio_producto2']);
                    $data['productos'][$p]['precio3'] = trim($row['precio_producto3']);*/
          $data['productos'][$p]['categoria_id'] = isset($row['categoria_producto']) ? trim($row['categoria_producto']) : '';
          $data['productos'][$p]['categoria'] = isset($row['titulo_categoria']) ? trim($row['titulo_categoria']) : '';
          $data['productos'][$p]['marca_id'] = isset($row['marca_producto']) ? trim($row['marca_producto']) : '';
          $data['productos'][$p]['marca'] = isset($row['titulo_marca']) ? trim($row['titulo_marca']) : '';
          $data['productos'][$p]['presentacion'] = isset($row['presentacion_producto']) ? trim($row['presentacion_producto']) : '';
          $data['productos'][$p]['marca_logo'] = isset($row['logo_marca']) ? trim($row['logo_marca']) : '';
          $data['productos'][$p]['foto'] = isset($row['foto_producto']) ? trim($row['foto_producto']) : '';
          $data['productos'][$p]['stock'] = trim($stock_final);
          $p++;
        }
      }
    } else {
      while ($row = mysqli_fetch_array($productos)) {
        $prod_id = $row['id_producto'];

        $data['estado'] = 'true';
        $data['fecha'] = $hoy;
        $data['productos'][$p]['id'] = $row['id_producto'];
        $data['productos'][$p]['codigo'] = trim($row['codigo_producto']);
        $data['productos'][$p]['titulo'] = trim($row['detalle_producto']);
        $data['productos'][$p]['descripcion'] = trim($row['descripcion_producto']);
        $data['productos'][$p]['precio1'] = trim($row['precio_producto']);
        /*          $data['productos'][$p]['precio2'] = trim($row['precio_producto2']);
          $data['productos'][$p]['precio3'] = trim($row['precio_producto3']);*/
        $data['productos'][$p]['categoria_id'] = trim($row['categoria_producto']);
        $data['productos'][$p]['categoria'] = trim($row['titulo_categoria']);
        $data['productos'][$p]['marca_id'] = trim($row['marca_producto']);
        $data['productos'][$p]['marca'] = trim($row['titulo_marca']);
        $data['productos'][$p]['presentacion'] = trim($row['presentacion_producto']);
        $data['productos'][$p]['marca_logo'] = trim($row['logo_marca']);
        $data['productos'][$p]['foto'] = trim($row['foto_producto']);
        $p++;
      }
    }
    while ($row = mysqli_fetch_array($productos)) {
      $prod_id = $row['id_producto'];
      $buscastock = $link->query("SELECT * FROM stock_depositos WHERE idpersona_stockd='$usuario' and idproducto_stockd='$prod_id' and fecha_stockd like '$hoy%' and estado_stockd='1' ") or die(mysqli_error());
      $carga = 0;
      $venta = 0;
      $stock_final = 0;
      while ($calculo = mysqli_fetch_array($buscastock)) {
        if ($calculo['tipomov_stockd'] == 'carga') {
          $carga = $carga + $calculo['cantidad_stockd'];
        }
        if ($calculo['tipomov_stockd'] == 'venta' || $calculo['tipomov_stockd'] == 'devolucion') {
          $venta = $venta + $calculo['cantidad_stockd'];
        }
      }
      $stock_final = $carga - $venta;
      //
      if ($stock_final > 0) {
        if ($stock_final < 10) {
          $stock_final = '0' . $stock_final;
        }
        $data['estado'] = 'true';
        $data['fecha'] = $hoy;
        $data['productos'][$p]['id'] = $row['id_producto'];
        $data['productos'][$p]['codigo'] = trim($row['codigo_producto']);
        $data['productos'][$p]['titulo'] = trim($row['detalle_producto']);
        $data['productos'][$p]['descripcion'] = trim($row['descripcion_producto']);
        $data['productos'][$p]['precio1'] = trim($row['precio_producto']);
        /*        $data['productos'][$p]['precio2'] = trim($row['precio_producto2']);
        $data['productos'][$p]['precio3'] = trim($row['precio_producto3']);*/
        $data['productos'][$p]['categoria_id'] = trim($row['categoria_producto']);
        $data['productos'][$p]['categoria'] = trim($row['titulo_categoria']);
        $data['productos'][$p]['marca_id'] = trim($row['marca_producto']);
        $data['productos'][$p]['marca'] = trim($row['titulo_marca']);
        $data['productos'][$p]['presentacion'] = trim($row['presentacion_producto']);
        $data['productos'][$p]['marca_logo'] = trim($row['logo_marca']);
        $data['productos'][$p]['foto'] = trim($row['foto_producto']);
        $data['productos'][$p]['stock'] = trim($stock_final);
        $p++;
      }
    }
    if ($data == []) {
      $data['estado'] = 'false';
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

if (isset($_POST['categorias'])) {
  $hoy = date('Y-m-d H:i:s');
  $usuario = $_POST['u'];
  $data['estado'] = 'true';
  $data['fecha'] = $hoy;

  $categorias = $link->query("SELECT * FROM categorias WHERE estado_categoria = 1 ") or die(mysqli_error());
  $c = '0';

  while ($row = mysqli_fetch_array($categorias)) {
    $data['categorias'][$c]['id'] = $row['id_categoria'];
    $data['categorias'][$c]['titulo'] = trim($row['titulo_categoria']);
    $data['categorias'][$c]['color'] = trim($row['color_categoria']);
    $data['categorias'][$c]['icono'] = trim($row['icono_categoria']);
    $data['categorias'][$c]['imagen'] = trim($row['imagen_categoria']);
    $c++;
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
    $data['clientes'][$c]['lista_precio'] = trim($row['lista_precio']);
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
    $data['productos'][$p]['precio1'] = trim($row['precio_producto']);
    $data['productos'][$p]['precio2'] = trim($row['precio_producto2']);
    $data['productos'][$p]['precio3'] = trim($row['precio_producto3']);
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

if (isset($_POST['exit'])) {
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  session_destroy();

  echo 'TRUE';
}
