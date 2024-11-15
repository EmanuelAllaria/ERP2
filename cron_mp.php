<?php

session_start();
include 'inc/conection.php';

// se deberia ver algo asi como APP_USR-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx con distintos caracteres en lugar de las x
$ACCESS_TOKEN="";

$url = 'https://api.mercadopago.com/v1/payments/search';
$today = new DateTime(date('Y-m-d'));
$yesterday = clone $today;
$yesterday->modify('-1 day');
$tomorrow = clone $today;
$tomorrow->modify('+1 day');

$begin_date = $yesterday->format('Y-m-d\TH:i:s\Z');
$end_date = $tomorrow->format('Y-m-d\TH:i:s\Z');


$query_params = http_build_query([
    'begin_date' => $begin_date,
    'end_date' => $end_date
]);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url . '?' . $query_params, 
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer '.$ACCESS_TOKEN
    ]
]);
$response = curl_exec($curl);
if (curl_errno($curl)) {
    echo 'Error al realizar la solicitud cURL: ' . curl_error($curl);
}
curl_close($curl);
$data = json_decode($response); 
//echo $response;
foreach($data->results as $pago){
    if (isset($pago->payer)) {
    //echo json_encode($pago->payer);
    
    $cuit=json_encode($pago->payer->identification->number);
    $importe = json_encode($pago->transaction_details->net_received_amount);
    $id_pago=json_encode($pago->id);
    // BUSCAR CLIENTE CON CUIT
    $cliente = $link->query("SELECT id_clientes FROM clientes where cuitcuil_com_clientes=$cuit");
    if($cliente && $cliente->num_rows>0 && $cliente->num_rows!=null){
        $cliente=$cliente->fetch_assoc();
        $id_cliente= $cliente['id_clientes']; 
        // CHEQUEAR QUE PAGO NO EXISTA EN TRANSACCION con id_mp
        $trans = $link->query("SELECT * FROM transaccion where id_pago_mp='$id_pago'");
        if($trans->num_rows==0){
            // insertar pago recibido
            $fecha=new DateTime();
            $fecha = $fecha->format('Y-m-d H:i:s');
            $query= "INSERT INTO `transaccion` (`cliente`, `fecha`, `detalle`, `observacion`, `monto`, `tipo`, `monto2`, `abonada`, `quien`, `estado`, `tipo_pedido`, `forma_pago`, `liquidacion`,`id_pago_mp`) VALUES
                ('$id_cliente', '$fecha', 'recibido por mercado pago', 'registrado automaticamente', NULL, 'pago', '$importe', '0', '2', 1, '0', 5, 0,'$id_pago')";
            $inserta = $link->query($query);
            if($inserta) echo "Exito guardando transaccion";
            else echo "error guardando transaccion";
        }else echo "Ya se registro!";
    } 
    }
}

?>



