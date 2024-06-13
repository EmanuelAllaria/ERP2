<?php
session_start();
include '../inc/conection.php';
if ($_SESSION['usuario'] != '') {

  date_default_timezone_set("America/Argentina/Buenos_Aires");
  $quien = $_SESSION['usuario'];
  $cuando = date("Y-m-d H:i:s");
  $api_key = 'AIzaSyCzlFav95MEH_UoIvMStdOEeUovVJO2mqQ';


  //notificaciones
  $email_from = "dario.velaco@gmail.com";
  $fromname = "Notificaciones BIG POLLO";
  $headers = "MIME-Version: 1.0\n";
  $headers .= "Content-type: text/html; charset=utf8\n";
  $headers .= "X-Priority: 3\n";
  $headers .= "X-MSMail-Priority: Normal\n";
  $headers .= "X-Mailer: php\n";
  $headers .= "From: \"" . $fromname . "\" <" . $email_from . ">\n";


  //echo 'afuera';
  if (isset($_POST['accion']) && $_POST['accion'] == 'up_notas') {
    $id = $_POST['id'];
    $notas = $_POST['notas'];

    $inserta = $link->query("UPDATE clientes SET notas_clientes='$notas' where id_clientes='$id'");

    header('Location: ../index.php?pagina=clientes_view&id=' . $id);
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'up_notas_prov') {
    $id = $_POST['id'];
    $notas = $_POST['notas'];

    $inserta = $link->query("UPDATE proveedores SET notas_proveedor='$notas' where id_proveedor='$id'");

    header('Location: ../index.php?pagina=prov_view&id=' . $id);
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_cate') {
    $nombre = $_POST['nombre'];
    $color = $_POST['color'];
    $iconito = $_POST['iconito'];
    $foto = $_POST['foto'];

    $inserta = $link->query("INSERT INTO categorias set titulo_categoria='$nombre', color_categoria='$color', icono_categoria='$iconito', imagen_categoria ='$foto', quien_categoria='$quien', cuando_categoria='$cuando', estado_categoria='1' ");

    if ($inserta) {
      header('Location: ../index.php?pagina=categorias');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'edit_cate') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $color = $_POST['color'];
    $iconito = $_POST['iconito'];
    $foto = $_POST['foto'];

    $inserta = $link->query("UPDATE categorias set titulo_categoria='$nombre', color_categoria='$color', icono_categoria='$iconito', imagen_categoria ='$foto', quien_categoria='$quien', cuando_categoria='$cuando', estado_categoria='1' WHERE id_categoria ='$id' ");

    if ($inserta) {
      header('Location: ../index.php?pagina=categorias');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_GET['accion']) && $_GET['accion'] == 'delcat') {
    $id = $_GET['id'];
    $update = $link->query("UPDATE categorias set estado_categoria='0' WHERE id_categoria='$id'");
    if ($update) {
      header('Location: ../index.php?pagina=categorias');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_GET['accion']) && $_GET['accion'] == 'delrub') {
    $id = $_GET['id'];
    $update = $link->query("UPDATE rubros set estado_rubros='0' WHERE id_rubros='$id'");
    if ($update) {
      header('Location: ../index.php?pagina=rubros');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_clientes') {
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['dni'];
    $cumple = $_POST['cumple'];
    if ($cumple == '') {
      $cumple = '0000-00-00';
    }
    $email = $_POST['email'];
    $email2 = $_POST['email2'];
    $telfijo = $_POST['telfijo'];
    $celular = $_POST['celular'];
    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $notas = $_POST['notas'];
    //  $direccion = $_POST['direccion'];
    //  $numero = $_POST['numero'];
    //  $piso = $_POST['piso'];
    //  $depto = $_POST['depto'];
    $razon = addslashes(htmlentities($_POST['razon']));
    /*$cuitcuil=$_POST['cuit'];*/
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    //  $telfijo_com = $_POST['telfijo_com'];
    //  $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion'];
    $numero_com = $_POST['numero'];
    $piso_com = $_POST['piso'];
    $depto_com = $_POST['depto'];
    $upload = $_POST['upload'];
    $asignado = $_POST['asignado'];
    if ($asignado == '') {
      $asignado = '0';
    }
    $financia = $_POST['financia'];
    if ($financia == '' || $financia == 'null') {
      $financia = '0';
    }
    $limite = $_POST['limite'];
    $listap = $_POST['listap'];
    $dias_financia = $_POST['dias_financia'];


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

    // dir comercios

    $direccion_geo_com = $direccion_com . ' ' . $numero_com . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo_com = str_replace(' ', '+', $direccion_geo_com);
    $call_com = $url . urlencode($direccion_geo_com);
    $response_com = json_decode(file_get_contents($call_com), true);
    $latitud_com = $response_com['results'][0]['geometry']['location']['lat'];
    $longitud_com = $response_com['results'][0]['geometry']['location']['lng'];

    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }
    if ($latitud_com != '' || $latitud_com != '0') {
      $latitud_com = str_replace(",", '.', $latitud_com);
      $longitud_com = str_replace(",", '.', $longitud_com);
    }
    // fin geoencode
    $inserto_cliente = "INSERT INTO clientes SET
    apellido_clientes = '$apellido',
    nombre_clientes = '$nombre',
    tipodni_clientes = '$tipodni',
    dni_clientes = '$dni',
    fechacumple_clientes = '$cumple',
    email_clientes = '$email',
    email2_clientes = '',
    celular_clientes = '$celular',
    telefono_clientes = '$telfijo',
    provincia_clientes = '$provincia',
    ciudad_clientes = '$ciudad',
    cp_cliente = 0,
    direccion_clientes = '$direccion_com',
    dirnum_clientes = '$numero_com',
    piso_clientes = '$piso_com',
    depto_clientes = '$depto_com',
    estadocivil_clientes = '',
    foto_clientes = '$upload',
    sexo_clientes = '',
    facebook_clientes = '',
    notas_clientes = '$notas',
    estado_clientes = 1,
    lat_clientes = '$latitud',
    lng_clientes = '$longitud',
    quien_clientes = '$quien',
    lon_com_clientes = '$longitud_com',
    lat_com_clientes = '$latitud_com',
    telefono_com_clientes = '',
    dirnum_com_clientes = '',
    rubro_com_clientes = '$rubro',
    condicioniva_com_clientes = '$condicioniva',
    cuitcuil_com_clientes = '',
    razon_com_clientes = '$razon',
    asignado_clientes = '$asignado',
    financiacion_com_clientes = '$financia',
    topefinancia_com_clientes = '$limite'
    ";
    $inserta = $link->query($inserto_cliente);
    $id_clie_ulti = mysqli_insert_id($link);
    //  $inserta = $link->query("insert INTO clientes_comercios set situacion_comclientes='activo', cliente_comclientes='$id_clie_ulti', estado_comclientes='1',  quien_comclientes='$quien', cuando_comclientes='$cuando'");

    if ($inserta) {
      echo $id_clie_ulti . '@' . $razon . '@' . $provincia . '@' . $ciudad . '@' . $direccion_com . '@' . $numero_com;
    } else {
      $accion = "Error al Insertar cliente  (" . $inserto_cliente . ")";
      echo 'FALSE';
    }
  }

  /*comienza insercion adelantos*/
  if (isset($_POST['accion']) && $_POST['accion'] == 'add_ade') {
    $movimiento = $_POST['mov'];
    $nroComp = $_POST['nroComp'];
    $monto = $_POST['monto'];
    $observaciones = $_POST['observaciones'];
    $fecha_actual = date('Y-m-d');
    $usuario = $_SESSION['usuario'];
    $personal = $_POST['personal'];
    $quien_select = $link->query("SELECT id FROM usuarios  WHERE usuario='$usuario'");
    $row = mysqli_fetch_array($quien_select);
    $id_user = $row['id'];



    $insert = $link->query("INSERT INTO adelantos SET 
      personal_adelanto ='$personal',
      fecha_adelanto = '$fecha_actual',
      tipo_adelanto = '$movimiento',
      observacion_ad = '$observaciones',
      monto_adelanto = '$monto',
      relacion_adelanto = '',
      liquidado_ad = '',
      quien_ad = '$id_user',
      estado_ad = '1'");


    if ($insert) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }
  /*termina insercion adelanto*/


  //--------------------------------------//

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_comercio') {

    $cliente = $_POST['cliente'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['cuit'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $tel = $_POST['telefono'];
    $filial = $_POST['ciudad'];
    $dir = ucwords(strtolower(addslashes(htmlentities($_POST['direccion']))));
    $numdir = $_POST['dirnum'];
    $perifact = $_POST['perifact'];
    $vendedor = $_POST['vendedor'];
    $situacion = $_POST['situacion'];
    if ($situacion == '') {
      $situacion = 'activo';
    }
    $foto = $_FILES['files']['name'];
    if ($foto != '') {
      $foton = "foto_comclientes='" . $foto . "',";
    } else {
      $foton = '';
    }
    if ($filial != '') {
      $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$filial'");
      $row = mysqli_fetch_array($consulto_datos);

      $direccion_geo = $dir . ' ' . $numdir . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
      $direccion_geo = str_replace(' ', '+', $direccion_geo);

      $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address="; //AIzaSyDNFykTVx2b0z3Fu9T9AFidLY-dRmFXKAU
      $call = $url . urlencode($direccion_geo);
      $response = json_decode(file_get_contents($call), true);
      $latitud = $response['results'][0]['geometry']['location']['lat'];
      $longitud = $response['results'][0]['geometry']['location']['lng'];
    }
    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }



    $inserta = $link->query("INSERT INTO clientes_comercios SET cliente_comclientes='$cliente',perifact_comclientes='$perifact', razon_comclientes='$razon', $foton cuitcuil_comclientes='$cuitcuil', condicioniva_comclientes='$condicioniva', rubro_comclientes='$rubro', ciudad_comclientes='$filial', direccion_comclientes='$dir', dirnum_comclientes='$numdir', telefono_comclientes='$tel', cuando_comclientes='$cuando', lat_comclientes='$latitud', lon_comclientes='$longitud', quien_comclientes='$quien', estado_comclientes='1', vendedor_comclientes='$vendedor', situacion_comclientes='$situacion'");

    if ($inserta) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_compras') {
    /*guardo en variables los datos traidos desde funciones.js*/
    $prov = $_POST['proveedor_id'];
    $proveedor_nombre = $_POST['prov_nombre'];
    $fecha_compra = $_POST['fecha'];
    $tipo_comp = $_POST['tipo'];
    $nro_comp = $_POST['numero_comprobante'];
    $en_stock = $_POST['en_stock'];
    $vencimiento = $_POST['vencimiento'];
    $estado = 1;

    $items = $_POST['items'];

    $itemDecodificados = json_decode($items, true);

    /*inserto los datos en compra mercaderia*/

    $inserta = $link->query("INSERT INTO compra_mercaderia SET
      prov_compram = '$prov',
      fecha_compram = '$fecha_compra',
      tipocom_compram = '$tipo_comp',
      numcom_compram = '$nro_comp',
      ingresastock_compram = '$en_stock',
      vencimiento_compram = '$vencimiento',
      estado_compram = '$estado'
    ");

    /*creo un for para tomar las cantidades de cada productos y poder actualizar el stock*/

    for ($i = 0; $i < count($itemDecodificados); $i++) {
      $id_producto = $itemDecodificados[$i]['id'];
      $cantidad = intval($itemDecodificados[$i]['cant']);
      $consulto_datos = $link->query("SELECT stock_producto from productos where id_producto ='$id_producto'");
      $row = mysqli_fetch_array($consulto_datos);

      $stockActual = intval($row[0]);

      $stockFinal = $stockActual + $cantidad;

      $inserta = $link->query("UPDATE productos SET stock_producto='$stockFinal' where id_producto='$id_producto' ");
    }


    if ($inserta == true) {
      $id_compra = $link->query("SELECT id_compram FROM compra_mercaderia ORDER BY id_compram DESC LIMIT 1");
      $rowm = mysqli_fetch_array($id_compra);
      $id_ult_compra = $rowm['id_compram'];

      /*inserto las cantidades y el id de los productos a una productos comprados*/
      for ($i = 0; $i < count($itemDecodificados); $i++) {
        $id_producto = $itemDecodificados[$i]['id'];
        $cantidad = intval($itemDecodificados[$i]['cant']);
        $inserta_compra = $link->query("INSERT INTO productos_comprados SET idCMercaderia = '$id_ult_compra', idProducto = '$id_producto', cantidad = '$cantidad'");

        if ($inserta_compra) {
          echo 'TRUE';
        } else {
          echo 'FALSE';
        }
      }
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_pedido') {
    $monto = $_POST['monto'];
    $detalle = $_POST['detalle'];
    $comercio = $_POST['comercio'];
    $tipo_p = $_POST['tipo_pedido'];
    $fecha = $_POST['fechapedido'];

    $inserta = $link->query("INSERT INTO transaccion SET cliente='$comercio', fecha='$fecha', tipo_pedido='$tipo_p', detalle='$detalle', monto='$monto', tipo='pedido', abonada='0', quien='$quien', estado='1'");

    if ($inserta) {
      header('Location: ../index.php?pagina=pedidos');
    } else {
      header('Location: ../index.php?pagina=pedidos&error=si');
    }
  }


  if (isset($_POST['accion']) && $_POST['accion'] == 'add_pago') {
    $monto = $_POST['monto_pago'];
    $detalle = $_POST['detalle_pago'];
    $comercio = $_POST['comercio_pago'];
    $tipo_p = $_POST['tipo_pedido'];
    $fecha = $_POST['fecha_pago'];
    $inserta = $link->query("INSERT INTO transaccion SET cliente='$comercio', fecha='$fecha', tipo_pedido='$tipo_p', detalle='$detalle', monto2='$monto', tipo='pago', abonada='0', quien='$quien', estado='1'");
    if ($inserta) {

      header('Location: ../index.php?pagina=pagos');
    } //cierra if inserta
    else {
      header('Location: ../index.php?pagina=pagos&error=si');
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'edit_transaccion') {
    $id = $_POST['id_trans'];
    $monto = $_POST['monto_trans'];
    $tipo = $_POST['tipo_trans'];
    $detalle = $_POST['detalle_trans'];
    $comercio = $_POST['comercio_trans'];
    $tipo_p = $_POST['tipo_pedido'];
    $fecha = $_POST['fecha_trans'];
    if ($_POST['tipo_trans'] == 'pago') {
      $trasac = "monto2='" . $monto . "'";
    } else {
      $trasac = "monto='" . $monto . "'";
    }
    $inserta = $link->query("UPDATE transaccion SET cliente='$comercio', fecha='$fecha', tipo_pedido='$tipo_p', detalle='$detalle', $trasac, tipo='$tipo', abonada='0', estado='1' where id='$id' ");
    if ($inserta) {
      header('Location: ../index.php?pagina=' . $tipo . 's');
    } else {
      header('Location: ../index.php?pagina=' . $tipo . 's&error=si');
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'editar_clientes') {
    //echo 'entro';
    $id = $_POST['id'];
    $apellido = '';
    $nombre = '';
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['dni'];

    $cumple = $_POST['cumple'];
    if ($cumple == '') {
      $cumple = '0000-00-00';
    }
    $email = $_POST['email'];
    $telfijo = $_POST['telfijo'];
    $celular = $_POST['celular'];
    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $notas = '';
    if (isset($_POST['notas']))
      $notas = $_POST['notas'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['dni'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $telfijo_com = $_POST['telfijo'];
    $celular_com = $_POST['celular'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];
    $asignado = $_POST['asignado'];
    $financia = $_POST['financia'];
    $limite = $_POST['limite'];
    if ($asignado == '') {
      $asignado = '0';
    }
    $upload = '';
    if (isset($_POST['upload']))
      $upload = $_POST['upload'];
    $dias_financia = $_POST['dias_financia'];
    $listap = $_POST['listap'];
    // inicio geoencode
    $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$ciudad'");
    $row = mysqli_fetch_array($consulto_datos);
    $direccion_geo = $direccion_com . ' ' . $numero_com . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
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
    $sql_update_client = "UPDATE clientes SET tipodni_clientes='$tipodni',  dni_clientes='$dni',  fechacumple_clientes='$cumple', email_clientes='$email',  celular_clientes='$celular',  telefono_clientes='$telfijo', provincia_clientes='$provincia',  ciudad_clientes='$ciudad',  direccion_clientes='$direccion_com',  dirnum_clientes='$numero_com',
          piso_clientes='$piso_com', depto_clientes='$depto_com', foto_clientes='$upload',  notas_clientes='$notas',  estado_clientes='1',  lat_clientes='$latitud',  lng_clientes='$longitud',  razon_com_clientes='$razon', cuitcuil_com_clientes='$cuitcuil', condicioniva_com_clientes='$condicioniva', rubro_com_clientes='$rubro', direccion_com_clientes='$direccion_com',
           quien_clientes='$quien', cuando_clientes='$cuando', asignado_clientes='$asignado', financiacion_com_clientes='$financia', topefinancia_com_clientes='$limite',
           lista_precio='$listap', dias_financiacion='$dias_financia'  where id_clientes = '$id' ";

    $inserta = $link->query($sql_update_client);

    if ($inserta > 0) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
      $accion = "Error al Actualizar cliente  (" . $sql_update_client . ")";
      mail('alerozasdennis@gmail.com', 'Error en Actualizador de cliente', $accion, $headers);
    }

    // echo $sql_update_client;
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'edita_comercio') {
    $comercio = $_POST['id'];
    $cliente = $_POST['cliente'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['cuit'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $tel = $_POST['telefono'];
    $filial = $_POST['ciudad'];
    $dir = ucwords(strtolower(addslashes(htmlentities($_POST['direccion']))));
    $numdir = $_POST['dirnum'];
    $vendedor = $_POST['vendedor'];
    $perifact = $_POST['perifact'];

    $situacion = $_POST['situacion'];
    if ($situacion == '') {
      $situacion = 'activo';
    }
    $foto = $_FILES['files']['name'];


    $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$filial'");
    $row = mysqli_fetch_array($consulto_datos);

    $direccion_geo = $dir . ' ' . $numdir . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo = str_replace(' ', '+', $direccion_geo);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address="; //AIzaSyDNFykTVx2b0z3Fu9T9AFidLY-dRmFXKAU
    $call = $url . urlencode($direccion_geo);
    $response = json_decode(file_get_contents($call), true);
    $latitud = $response['results'][0]['geometry']['location']['lat'];
    $longitud = $response['results'][0]['geometry']['location']['lng'];


    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }
    if ($latitud_com != '' || $latitud_com != '0') {
      $latitud_com = str_replace(",", '.', $latitud_com);
      $longitud_com = str_replace(",", '.', $longitud_com);
    }

    if ($latitud_com == '') {
      $latitud_com = '0';
    }
    if ($longitud_com == '') {
      $longitud_com = '0';
    }
    if ($latitud == '') {
      $latitud = '0';
    }
    if ($longitud == '') {
      $longitud = '0';
    }

    $inserta = $link->query("UPDATE clientes_comercios SET cliente_comclientes='$cliente', perifact_comclientes='$perifact', razon_comclientes='$razon', foto_comclientes='frente_sinfoto.jpg', cuitcuil_comclientes='$cuitcuil', condicioniva_comclientes='$condicioniva', rubro_comclientes='$rubro', ciudad_comclientes='$filial', direccion_comclientes='$dir', dirnum_comclientes='$numdir', telefono_comclientes='$tel', cuando_comclientes='$cuando', lat_comclientes='$latitud', lon_comclientes='$longitud', quien_comclientes='$quien', estado_comclientes='1', vendedor_comclientes='$vendedor' where id_comclientes ='$comercio'");

    if ($inserta) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_clientes') {
    $id = $_POST['id'];

    $update2 = $link->query("UPDATE clientes SET estado_clientes='0' where id_clientes ='$id' ");
    if ($update2) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_proveedor') {
    $id = $_POST['id'];

    $update2 = $link->query("UPDATE proveedores SET estado_proveedor='0' where id_proveedor ='$id' ");
    if ($update2) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_carga') {
    $id = $_POST['id'];
    $update = $link->query("UPDATE stock_depositos SET estado_stockd='0' where `estado_stockd` = 1 AND `idcarga_stockd` ='$id' ");
    if ($update > 0) {
      echo 'TRUE';
      $link->query("UPDATE `carga_camion` SET `estado_cargac` = '0' WHERE `carga_camion`.`id_cargac` = '$id' ");
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_transaccion') {
    $id = $_POST['id'];
    $update1 = $link->query("UPDATE transaccion SET estado='0', quien='$quien' where id ='$id' ");

    if ($update1) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  // ------------------------------Proveedores ------------------------------//

  //************ Proceso Edita Proveedor **************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'editar_proveedor') {
    $id = $_POST['id'];
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['dni'];
    $sexoh = $_POST['sexoh'];
    $sexom = $_POST['sexom'];
    $ecivil = $_POST['ecivil'];
    $cumple_d = $_POST['cumple_d'];
    $cumple_m = $_POST['cumple_m'];
    $cumple_a = $_POST['cumple_a'];
    $cumple = $cumple_a . '-' . $cumple_m . '-' . $cumple_d;

    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $numero = $_POST['numero'];
    $piso = $_POST['piso'];
    $depto = $_POST['depto'];

    $razon = addslashes(htmlentities($_POST['razon']));
    $rubro = $_POST['rubro'];
    $condicioniva = $_POST['condicioniva'];
    $cuitcuil = $_POST['cuit'];
    $email = $_POST['email'];
    $email2 = $_POST['email2'];
    $celular = $_POST['celular'];
    $celular2 = $_POST['celular2'];
    $telfijo = $_POST['telfijo'];
    $notas = $_POST['notas'];

    $banco = $_POST['banco'];
    $nroCuenta = $_POST['nroCuenta'];
    $cbu = $_POST['cbu'];


    $telfijo_com = $_POST['telfijo_com'];
    $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];


    $telfijo_com = $_POST['telfijo_com'];
    $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];
    $upload = $_POST['upload'];
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
    // fin geoencode

    $inserta = $link->query("UPDATE proveedores SET email_proveedor='$email',
      emailDos_proveedor='$email2',
      provincia_proveedor='$provincia',
      ciudad_proveedor='$ciudad',
      notas_proveedor='$notas',
      estado_proveedor='1',
      telefono_com_proveedor = '$telfijo',
      celular_com_proveedor = '$celular',
      celularDos_com_proveedor = '$celular2',
      dirnum_com_proveedor = '$numero',
      direccion_com_proveedor='$direccion',
      rubro_com_proveedor='$rubro',
      condicioniva_com_proveedor = '$condicioniva',
      cuitcuil_com_proveedor = '$cuitcuil',
      razon_com_proveedor = '$razon',
      piso_com_proveedor = '$piso',
      depto_com_proveedor = '$depto',
      banco = '$banco',
      nroCuenta = '$nroCuenta',
      cbu = '$cbu',
      quien_proveedor='$quien',
      cuando_proveedor='$cuando' WHERE id_proveedor = '$id' ");


    if ($inserta) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  //************ Proceso Carga Proveedor **************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'add_proveedores') {
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['dni'];
    $sexoh = $_POST['sexoh'];
    $sexom = $_POST['sexom'];
    $ecivil = $_POST['ecivil'];
    $cumple = $_POST['cumple'];

    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $numero = $_POST['numero'];
    $piso = $_POST['piso'];
    $depto = $_POST['depto'];

    $razon = addslashes(htmlentities($_POST['razon']));
    $rubro = $_POST['rubro'];
    $condicioniva = $_POST['condicioniva'];
    $cuitcuil = $_POST['cuit'];
    $email = $_POST['email'];
    $email2 = $_POST['email2'];
    $celular = $_POST['celular'];
    $celular2 = $_POST['celular2'];
    $telfijo = $_POST['telfijo'];
    $notas = $_POST['notas'];

    $banco = $_POST['banco'];
    $nroCuenta = $_POST['nroCuenta'];
    $cbu = $_POST['cbu'];


    $telfijo_com = $_POST['telfijo_com'];
    $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];
    $upload = $_POST['upload'];

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

    // dir comercios

    $direccion_geo_com = $direccion_com . ' ' . $numero_com . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo_com = str_replace(' ', '+', $direccion_geo_com);
    $call_com = $url . urlencode($direccion_geo_com);
    $response_com = json_decode(file_get_contents($call_com), true);
    $latitud_com = $response_com['results'][0]['geometry']['location']['lat'];
    $longitud_com = $response_com['results'][0]['geometry']['location']['lng'];

    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }
    if ($latitud_com != '' || $latitud_com != '0') {
      $latitud_com = str_replace(",", '.', $latitud_com);
      $longitud_com = str_replace(",", '.', $longitud_com);
    }
    // fin geoencode

    $inserta = $link->query("INSERT INTO proveedores SET email_proveedor='$email',
      emailDos_proveedor='$email2',
      provincia_proveedor='$provincia',
      ciudad_proveedor='$ciudad',
      notas_proveedor='$notas',
      estado_proveedor='1',
      telefono_com_proveedor = '$telfijo',
      celular_com_proveedor = '$celular',
      celularDos_com_proveedor = '$celular2',
      dirnum_com_proveedor = '$numero',
      direccion_com_proveedor='$direccion',
      rubro_com_proveedor='$rubro',
      condicioniva_com_proveedor = '$condicioniva',
      cuitcuil_com_proveedor = '$cuitcuil',
      razon_com_proveedor = '$razon',
      piso_com_proveedor = '$piso',
      depto_com_proveedor = '$depto',
      banco = '$banco',
      nroCuenta = '$nroCuenta',
      cbu = '$cbu',
      quien_proveedor='$quien',
      cuando_proveedor='$cuando'");
    $id_clie_ulti = mysqli_insert_id($link);

    if ($inserta) {
      echo $id_clie_ulti . '@' . $apellido . ', ' . $nombre . '@' . $provincia . '@' . $ciudad . '@' . $direccion . '@' . $numero;
    } else {
      echo 'FALSE';
    }
  }

  //************ Cambiar estado cliente **************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'desactiva_cliente') {
    $id = $_POST['id'];

    $update = $link->query("UPDATE clientes SET estado_clientes='2' where id_clientes ='$id' ");
    if ($update) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }
  if (isset($_POST['accion']) && $_POST['accion'] == 'activa_cliente') {
    $id = $_POST['id'];

    $update = $link->query("UPDATE clientes SET estado_clientes='1' where id_clientes ='$id' ");
    if ($update) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  //************ Proceso Carga Facturas de Proveedor **************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'add_facturas') {
    $proveedor = $_POST['proveedor'];
    $nro_factura = $_POST['nro_factura'];
    $tipo = $_POST['tipo'];
    $monto = $_POST['monto'];
    $obs = $_POST['obs'];
    $sqlHayPagosAFavor = $link->query("SELECT * FROM facturas_pagos WHERE id_factura='-1' ORDER BY monto DESC LIMIT 1");
    $pagoAFavor = array();
    if ($sqlHayPagosAFavor->num_rows > 0) {
      $pagoAFavor = $sqlHayPagosAFavor->fetch_assoc();
    }

    $parseTipo = intval($tipo);
    if ($parseTipo === 7 || $parseTipo === 8 || $parseTipo === 9 || $parseTipo === 10) {
      $sqlHayFacturaErronea = $link->query("SELECT * FROM facturas WHERE id_proveedor='$proveedor' AND nro_factura='$nro_factura' OR monto='$monto'");
      if (mysqli_num_rows($sqlHayFacturaErronea) === 0) {
        echo 'ERROR NO EXISTE FACTURA';
        exit;
      }
      if (intval(mysqli_fetch_ASSOC($sqlHayFacturaErronea)['monto']) !== abs(intval($monto))) {
        echo 'ERROR EL MONTO NO COINCIDE';
        exit;
      }
    }

    if ($parseTipo === 7 || $parseTipo === 8 || $parseTipo === 9 || $parseTipo === 10) {
      $inserta = $link->query("INSERT INTO facturas SET nro_factura='$nro_factura', id_proveedor='$proveedor',  tipo='$tipo', monto='-$monto', observaciones='$obs'");
    } else {
      $inserta = $link->query("INSERT INTO facturas SET nro_factura='$nro_factura', id_proveedor='$proveedor',  tipo='$tipo', monto='$monto', observaciones='$obs'");
    }
    $id = mysqli_insert_id($link);
    if ($pagoAFavor && ($parseTipo !== 7 || $parseTipo !== 8 || $parseTipo !== 9 || $parseTipo !== 10)) {
      $idPagoAFavor = $pagoAFavor['id'];
      $tipo_pago = $pagoAFavor['tipo_pago'];
      $fecha = $pagoAFavor['fecha'];
      $banco = $pagoAFavor['banco'];
      $numero_cheque = $pagoAFavor['numero_cheque'];
      $fecha_emision = $pagoAFavor['fecha_emision'];
      $fecha_cobro = $pagoAFavor['fecha_cobro'];
      $cuit = $pagoAFavor['cuit'];
      $titular = $pagoAFavor['titular'];
      $montoFactura = intval($pagoAFavor['monto']) - intval($monto);
      $origen = $pagoAFavor['origen'];
      if (intval($pagoAFavor['monto']) > intval($monto)) {
        $link->query("UPDATE facturas_pagos SET id_factura='$id', monto='$monto' WHERE id=$idPagoAFavor and id_proveedor='$proveedor';");
        if ($link->affected_rows > 0) {
          $razon_com_proveedor = $link->query("SELECT razon_com_proveedor FROM proveedores WHERE id_proveedor='$proveedor'")->fetch_assoc()['razon_com_proveedor'];
          $link->query("INSERT INTO facturas_pagos SET id_factura='-1', id_proveedor='$proveedor', tipo_pago='$tipo_pago', fecha='$fecha', banco='$banco', numero_cheque='$numero_cheque', fecha_emision='$fecha_emision', fecha_cobro='$fecha_cobro', titular='$titular', cuit='$cuit', monto='$montoFactura', origen='$origen', observaciones='Pago a favor a $razon_com_proveedor'");
        }
      } else {
        $link->query("UPDATE facturas_pagos SET id_factura='$id' WHERE id=$idPagoAFavor and id_proveedor='$proveedor';");
      }
    }

    if ($inserta) {
      echo $id . '@' . $nro_factura . ', ' . $tipo . '@' . $monto;
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'get_facturas') {
    $proveedor = $_POST['proveedor'];

    $consulta = $link->query("SELECT * FROM facturas f 
                              WHERE f.id_proveedor='$proveedor' 
                              AND f.tipo NOT IN (7, 8, 9, 10)
                              AND NOT EXISTS (
                                  SELECT 1 FROM facturas f2 
                                  WHERE f2.nro_factura = f.nro_factura 
                                  AND f2.tipo IN (7, 8, 9, 10)
                                  AND f2.id_proveedor='$proveedor'
                              )");
    $rows = array(); // Inicializa un array para almacenar todas las filas

    while ($fila = mysqli_fetch_assoc($consulta)) {
      $saldo = $fila['monto'];
      $id_factura = $fila['id'];
      $consulta2 = $link->query("SELECT * from facturas_pagos where id_proveedor='$proveedor'");
      $rows2 = array(); // Inicializa un array para almacenar todas las filas
      while ($fila2 = mysqli_fetch_assoc($consulta2)) {
        $saldo = $saldo - $fila2['monto'];
      }
      if ($saldo > 0) {
        $fila['saldo'] = $saldo;
        $rows[] = $fila; // Agrega cada fila al array
      }
    }

    if ($rows) {
      echo json_encode($rows);
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_gasto') {
    $movimiento = $_POST['mov'];
    $nroComp = $_POST['nroComp'];
    $monto = $_POST['monto'];
    $observaciones = $_POST['observaciones'];
    $fecha_actual = date('Y-m-d');
    $usuario = $_SESSION['usuario'];
    $quien_select = $link->query("SELECT id FROM usuarios  WHERE usuario='$usuario'");
    $row = mysqli_fetch_array($quien_select);
    $id_user = $row['id'];



    $insert = $link->query("INSERT INTO gastos SET 
      personal_gasto ='1',
      fecha_gasto = '$fecha_actual',
      tipo_gasto = '$movimiento',
      observacion_gasto = '$observaciones',
      monto_gasto = '$monto',
      relacion_gasto = '',
      liquidado_gasto = '',
      quien_gasto = '$id_user',
      estado_gasto = '1'");


    if ($insert) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  /*comienza insercion adelantos*/
  if (isset($_POST['accion']) && $_POST['accion'] == 'add_ade') {
    $movimiento = $_POST['mov'];
    $nroComp = $_POST['nroComp'];
    $monto = $_POST['monto'];
    $observaciones = $_POST['observaciones'];
    $fecha_actual = date('Y-m-d');
    $usuario = $_SESSION['usuario'];
    $quien_select = $link->query("SELECT id FROM usuarios  WHERE usuario='$usuario'");
    $row = mysqli_fetch_array($quien_select);
    $id_user = $row['id'];



    $insert = $link->query("INSERT INTO adelantos SET 
      personal_adelanto ='1',
      fecha_adelanto = '$fecha_actual',
      tipo_adelanto = '$movimiento',
      observacion_ad = '$observaciones',
      monto_adelanto = '$monto',
      relacion_adelanto = '',
      liquidado_ad = '',
      quien_ad = '$id_user',
      estado_ad = '1'");


    if ($insert) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }
  /*termina insercion adelanto*/

  if (isset($_POST['accion']) && $_POST['accion'] == 'remove_cheque') {
    $id_pago = $_POST['id_pago'];
    $remove_cheque = $link->query("DELETE FROM facturas_cheques WHERE id='$id_pago'");

    if ($remove_cheque) {
      echo $id_pago;
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_facturas_pago') {
    $proveedor = $_POST['proveedor'];
    $total = $_POST['total'];
    $fecha = $_POST['fecha'];
    $tipo_pago = $_POST['tipo_pago'];
    $banco = $_POST['banco'];
    $numero_cheque = $_POST['numero_cheque'];
    $fecha_emision = $_POST['fecha_emision'];
    $fecha_cobro = $_POST['fecha_cobro'];
    $titular = $_POST['titular'];
    $cuit = $_POST['cuit'];
    $monto = $_POST['monto'];
    $origen = $_POST['origen'];
    $ids_cheques = $_POST['tipo_pago'] === 'cheque' ? explode(', ', $_POST['ids_cheques']) : null;
    $inserta = null;

    if ($_POST['tipo_pago'] !== 'cheque') {
      $inserta = $link->query("INSERT INTO facturas_pagos SET id_proveedor='$proveedor', tipo_pago='$tipo_pago', fecha='$fecha', banco='$banco', fecha_emision='$fecha_emision', fecha_cobro='$fecha_cobro', titular='$titular', cuit='$cuit', monto='$monto', origen='$origen'");
      $id = mysqli_insert_id($link);
    } else {
      $inserta = $link->query("INSERT INTO facturas_pagos SET id_proveedor='$proveedor', tipo_pago='$tipo_pago', fecha='$fecha', monto='$monto'");
      $id = mysqli_insert_id($link);
      foreach ($ids_cheques as $key => $id_cheque) {
        $link->query("UPDATE facturas_cheques SET id_pago='$id' WHERE id='$id_cheque'");
      }
    }

    if ($inserta) {
      echo $id . '@' . $monto;
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_facturas_cheque') {
    $proveedor = $_POST['proveedor'];
    $total = $_POST['total'];
    $banco = $_POST['banco'];
    $numero_cheque = $_POST['numero_cheque'];
    $fecha_emision = $_POST['fecha_emision'];
    $fecha_cobro = $_POST['fecha_cobro'];
    $titular = $_POST['titular'];
    $cuit = $_POST['cuit'];
    $monto = $_POST['monto'];
    $origen = $_POST['origen'];
    $inserta = null;

    $inserta = $link->query("INSERT INTO facturas_cheques SET banco='$banco', numero_cheque='$numero_cheque', fecha_emision='$fecha_emision', fecha_cobro='$fecha_cobro', titular='$titular', cuit='$cuit', monto='$monto', origen='$origen'");

    $id = mysqli_insert_id($link);

    if ($inserta) {
      $nombreProveedor = $link->query("select razon_com_proveedor from proveedores where id_proveedor = '$proveedor'")->fetch_assoc()['razon_com_proveedor'];
      $selectPagoGuardado = $link->query("SELECT * FROM facturas_cheques WHERE id='$id'")->fetch_assoc();
      $selectPagoGuardado['nombre_proveedor'] = $nombreProveedor;
      $selectPagoGuardado['tipo_pago'] = 'cheque';
      if ($selectPagoGuardado) {
        echo json_encode($selectPagoGuardado, JSON_PRETTY_PRINT);
      }
    } else {
      echo 'FALSE';
    }
  }

  //************ Proceso vER PRODUCTOS **************//
  if (isset($_POST['productos']) && $_POST['productos'] == "GET") {


    $productos = $link->query("SELECT * FROM productos  WHERE estado_producto='1'") or die(mysqli_error());
    $data_prods = array();
    if (mysqli_num_rows($productos) > 0) {
      while ($row = mysqli_fetch_ASSOC($productos)) {
        $data_prods[] = $row;
      }
      $data['productos'] = $data_prods;
      $data['estado'] = 'true';
    } else {
      $data['estado'] = 'false';
    }

    $arreglo = json_encode($data);
    if ($arreglo) {
      echo $arreglo;
    } else {
      echo "FALSE";
    }
  }
  //**********************************************************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'changePassWord') {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $pass = md5($_POST['newpass']);
    if ($tipo == 'Admin') {
      $update1 = $link->query("UPDATE usuarios SET passuser='$pass', quien='$quien' where id ='$id' ");
    } else {
      $update1 = $link->query("UPDATE personal SET pass='$pass', quien='$quien' where id ='$id' ");
    }


    if ($update1) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  //**********************************************************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'altaPersonal') {
    $area = $_POST['area'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $pass = md5($_POST['password']);
    $pin = md5($_POST['pin']);

    $insert = $link->query("INSERT INTO personal SET user='$email', pass='$pass', nombre='$nombre', apellido='$apellido',
      email_per='$email', celular='$telefono', direccion='$direccion', area='$area', pin='$pin', estado = 1 ");
    if ($area == 'Admin') {
      $insert = $link->query("INSERT INTO usuarios SET nombre='$nombre', email='$email', usuario='$email', passuser='$pass', tipo='$area', tel='$telefono', quien='$quien', avatar='1.jpg', estado_usuarios=1, apellido='$apellido', personal='0'");
    }


    if ($insert) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'select_t') {
    $valores = $_POST['valoresCheckboxes'];
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $vendedor = $_POST['vendedor'];
    $datos = explode(",", $valores);

    for ($i = 0; $i < count($datos); $i++) {
      $consul_id = $link->query("SELECT * FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente WHERE transaccion.estado='1' and transaccion.id='$datos[$i]'");

      while ($rowid = mysqli_fetch_array($consul_id)) {
        $num = '1';
        $acumula = 0;
        $td = '';
        $consul_items = $link->query("SELECT * FROM items_pedidos INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE pedido_itemsp='$datos[$i]' and estado_itemsp='1'");

        while ($row = mysqli_fetch_array($consul_items)) {
          $total = $row['monto_itemsp'] * $row['cantidad_itemsp'];
          $cantidadItem = $row['cantidad_itemsp'];
          $bonificacion = $row['bonifica_itemsp'];
          $codigo = strtoupper($row['codigo_producto']);
          $detalle_producto = $row['detalle_producto'];
          $modelo_producto = $row['modelo_producto'];
          $presentacion_producto = $row['presentacion_producto'];
          $precio_unitario = number_format($row['monto_itemsp'], 2, ',', '.');
          $subtotal = number_format($total, 2, ',', '.');

          $td .= '<tr>
                    <td class="text-right">' . $cantidadItem . '</td>
                    <td class="text-right">' . $bonificacion . '</td>
                    <td>' . $codigo . '</td>
                    <td>' . $detalle_producto . ' ' . $modelo_producto . ' ' . $presentacion_producto . '</td>
                    <td class="text-right">$ ' . $precio_unitario . '</td>
                    <td class="text-right">$ ' . $subtotal . '</td>
                    <td class="text-center">' . $num . '</td>
                  </tr>';
          $acumula = $acumula + $total;
          $num++;

          $total_final = number_format($acumula, 2, ',', '.');
        }

        echo '
          <div style="page-break-before: always">
        <h3>
          <b>PEDIDO</b> 
          <span class="pull-right">#' . $rowid['id'] . '</span>
        </h3>
        <div class="row">
          <div class="col-md-12">
            <div class="pull-left">
              <address>
                <h3> &nbsp;<b class="text-danger">Big Pollo.</b></h3>
                <p class="text-muted m-l-5">CUIT: 00-00000000-0
                <br/> José Hernández 935,
                <br/> Bahia Blanca - 8000</p>
              </address>
            </div>

            <div class="pull-right text-right">
              <address>
                <h3>Cliente:</h3>
                <h4 class="font-bold">' . $rowid['apellido_clientes'] . ', ' . $rowid['nombre_clientes'] . '</h4>
                <p class="text-muted m-l-30">Comercio:' . $rowid['razon_com_clientes'] . ',
                <br/>' . $rowid['direccion_com_clientes'] . ' ' . $rowid['dirnum_com_clientes'] . '
                <br/> Tel:' . $rowid['telefono_com_clientes'] . ',
                <p class="m-t-30"><b>Fecha de Pedido :</b> <i class="fa fa-calendar"></i>' . date('d/m/Y', strtotime($rowid['fecha'])) . '</p>
              </address>
            </div>
          </div>
          <div class="col-md-12">
            <div class="table-responsive m-t-40" style="clear: both;">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th class="text-right">Cantidad</th>
                        <th class="text-right">Bonificacion</th>
                        <th>Codigo</th>
                        <th>Detalle</th>
                        <th class="text-right">P. Unitario</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $td . '
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-12">
            <div class="pull-right m-t-30 text-right">
              <h3><b>Total: </b> $' . $total_final . '</h3>
            </div>
            <div class="clearfix"></div>
            <hr>
            <h3><b>Observaciones:</b></h3>
            <p>' . $rowid['observacion'] . '</p>
            <hr>
          </div
        </div>

        <hr>
        </div>
        ';
      }
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'buscarTrans') {
    $id = $_POST['id'];
    $consul_id = $link->query("SELECT * FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente WHERE estado='1' and id='$id' ");
    $rowid = mysqli_fetch_array($consul_id);
    $modal = '';

    $modal .= '<div class="row">
        <div class="col-md-12">
            <div class="white-box printableArea">
                <h3><b>PEDIDO</b> <span class="pull-right">#' . $id . '</span></h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <address>
                                <h3> &nbsp;<b class="text-danger">Big Pollo.</b></h3>
                                <p class="text-muted m-l-5">CUIT: 00-00000000-0
                                    <br/> José Hernández 935,
                                    <br/> Bahia Blanca - 8000</p>
                            </address>
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <h3>Cliente:</h3>
                                <h4 class="font-bold">' . $rowid['apellido_clientes'] . ', ' . $rowid['nombre_clientes'] . '</h4>
                                <p class="text-muted m-l-30">Comercio: ' . $rowid['razon_com_clientes'] . ',
                                    <br/>' . $rowid['direccion_com_clientes'] . ' ' . $rowid['dirnum_com_clientes'] . '
                                    <br/> Tel: ' . $rowid['telefono_com_clientes'] . ',

                                <p class="m-t-30"><b>Fecha de Pedido :</b> <i class="fa fa-calendar"></i>' . date('d/m/Y', strtotime($rowid['fecha'])) . '</p>

                            </address>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Bonificacion</th>
                                        <th>Codigo</th>
                                        <th>Detalle</th>
                                        <th class="text-right">P. Unitario</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>';
    $num = '1';
    $acumula = 0;
    $consul_items = $link->query("SELECT * FROM items_pedidos INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE pedido_itemsp='$id' and estado_itemsp='1'");
    while ($row = mysqli_fetch_array($consul_items)) {
      $total = $row['monto_itemsp'] * $row['cantidad_itemsp'];

      $modal .= '<tr>
                                        <td class="text-right">' . $row['cantidad_itemsp'] . '</td>
                                        <td class="text-right">' . $row['bonifica_itemsp'] . '</td>
                                        <td>' . strtoupper($row['codigo_producto']) . '</td>
                                        <td>' . $row['detalle_producto'] . ' ' . $row['modelo_producto'] . ' ' . $row['presentacion_producto'] . '</td>
                                        <td class="text-right">$ ' . number_format($row['monto_itemsp'], 2, ',', '.') . '</td>
                                        <td class="text-right">$ ' . number_format($total, 2, ',', '.') . '</td>
                                        <td class="text-center">' . $num . '</td>
                                    </tr>';
      $acumula = $acumula + $total;
      $num++;
    }

    $modal .= '</tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <p style="display:none">SubTotal: $' . number_format($acumula, 2, ',', '.') . '</p>
                            <p style="display:none">IVA (21%): $' . number_format($acumula * 0.21, 2, ',', '.') . '</p>
                            <hr>
                            <h3><b>Total: </b> $' . number_format($acumula, 2, ',', '.') . '</h3>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <h3><b>Observaciones:</b></h3>
                        <p>' . $rowid['observacion'] . '</p>
                        <hr>

                    </div>
                </div>
            </div>
            <div class="text-right botones">
            
                <button class="btn btn-success" id="btnImprimir" onclick="impresion();">Imprimir</button>
                <button class="btn btn-primary" id="btnImprimir" onclick="cerrar();">Cerrar</button>
            </div>
        </div>
    </div>';

    echo $modal;
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'select_transaccion') {
    $valores = $_POST['valoresCheckboxes'];
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $vendedor = $_POST['vendedor'];
    $datos = explode(",", $valores);

    for ($i = 0; $i < count($datos); $i++) {
      $con_pedidos = $link->query("SELECT *, transaccion.id as ide FROM `transaccion`
              inner join clientes on transaccion.cliente = clientes.id_clientes
              inner join personal on transaccion.quien = personal.id
              left join formas_pagos on transaccion.forma_pago = formas_pagos.id_formapago
                WHERE transaccion.estado='1' and transaccion.tipo ='pedido' and transaccion.id = '$datos[$i]' and transaccion.fecha >= '$desde' and transaccion.fecha <= '$hasta' $vendedor order by transaccion.fecha DESC, razon_com_clientes ASC");

      while ($row = mysqli_fetch_array($con_pedidos)) {
        echo '<tr>
              <td class="font-weight-normal"><span class="footable-toggle"></span>' . date('d/m/Y', strtotime($row['fecha'])) . '</td>
              <td class="font-weight-normal"><span class="footable-toggle"></span>' . mb_strtoupper($row['razon_com_clientes']) . '
              </td>
              <td class="font-weight-normal">' . $row['nombre'] . ', ' . $row['apellido'] . '</td>
              <td class="font-weight-normal">' . $row['detalle'] . '</td>
              <td class="font-weight-normal">' . $row['observacion'] . '</td>
              <td class="font-weight-normal">' . $row['detalle_formapago'] . '</td>
              <td class="font-weight-normal">$' . number_format($row['monto'], 0, '', '.') . '</td>
              <td class="font-weight-normal"><span class="footable-toggle"></span>' . $row['ide'] . '</td>
            </tr>
      ';
      }
    }
  }
}
