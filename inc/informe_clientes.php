<?php
//Base de datos
 echo '
<style>
    .tbl{
     width: 100%
     /* border: 1px solid #000;
     bac
     */
    }
</style>

' ;

include 'conection.php';
$desde = $_GET['d'];
$hasta = $_GET['h'];
$cliente = $_GET['c'];

$consulta=$link->query("SELECT * FROM clientes inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes
WHERE clientes.estado_clientes='1' AND cliente_comclientes='$cliente' ORDER BY clientes_comercios.vendedor_comclientes DESC, clientes_comercios.razon_comclientes ASC");

if($row = mysqli_fetch_array($consulta)){
  
  $consulta_mov=$link->query("SELECT * FROM transaccion
INNER JOIN clientes on clientes.id_clientes = transaccion.cliente
inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes
WHERE (fecha BETWEEN '$desde' AND '$hasta') and clientes.estado_clientes='1' and transaccion.estado='1' and clientes_comercios.cliente_comclientes='$cliente'
ORDER BY clientes_comercios.vendedor_comclientes DESC, clientes_comercios.razon_comclientes ASC");

$consulta_pedidos = $link->query("SELECT sum(monto) FROM transaccion WHERE fecha < '$desde' and transaccion.estado='1' and transaccion.cliente='$cliente' and transaccion.tipo = 'pedido'");
$pedido_total = mysqli_fetch_array($consulta_pedidos);
$pedido= $pedido_total[0];

$consulta_pagos = $link->query("SELECT sum(monto2) FROM transaccion WHERE fecha < '$desde' and transaccion.estado='1' and transaccion.cliente='$cliente' and transaccion.tipo = 'pago'");
$pagos_total = mysqli_fetch_array($consulta_pagos);
$pagos= $pagos_total[0];

    

$fecha = date("d-m-Y");

echo '<table class="tbl" border="1" CELLSPACING="0" cellpadding="2" width="800">
        <tr>
        
          <td colspan="7" style="text-align:center;" width="100%">
          <h2>RESUMEN DESDE '.date('d/m/Y', strtotime($desde)).' HASTA '.date('d/m/Y', strtotime($hasta)).' DEL CLIENTE - '.$row['razon_comclientes'].'</h2></td>
        </tr>
        <tr style="font-weight: bold;background-color:black;color:white;text-align:center">
          <td width="10%">FECHA</td>
          <td width="200">DETALLE</td>
          <td width="10%">TIPO</td>
          <td width="20%">MONTO</td>
          <td width="20%">SALDO</td>
        </tr>';


$saldo_anterior=($pagos-$pedido);
$suma_pedidos='0';
$suma_pagos='0';
echo'
<tr >
  <td width="10%"> < '.date('d/m/Y',strtotime($_GET['d'])).'</td>
  <td width="200">Saldo Anterior</td>
  <td width="10%" style="text-align:center">-</td>
  <td width="20%" style="text-align:right">-</td>
  <td width="20%" style="text-align:right">$ '.number_format($saldo_anterior,0,'','.').'</td>
</tr>';
while ($row = mysqli_fetch_array($consulta_mov)){
//if ($saldo_anterior < 0 ){$saldo_anterior= $saldo_anterior*-1;}
echo '
<tr >
  <td width="10%">'.date('d/m/Y',strtotime($row['fecha'])).'</td>
  <td width="200">'.$row['detalle'].'</td>
  <td width="10%" style="text-align:center">';
  if($row['tipo']=='pedido'){
  $monto=$row['monto'];
  $signo='+';
  $saldo_anterior=($saldo_anterior-$monto);
  $suma_pedidos= $suma_pedidos+$row['monto'];
  echo 'PEDIDO';}else{
  $signo='-';      
  $monto='-'.$row['monto2'];
  $saldo_anterior=($saldo_anterior-$monto);
  $suma_pagos= $suma_pagos+$row['monto2'];
  echo'PAGO';}
  
  echo'</td>
  <td width="20%" style="text-align:right">$ '.number_format($monto,0,'','.').'</td>
  <td width="20%" style="text-align:right">$ '.number_format($saldo_anterior,0,'','.').'</td>

</tr>';
}

echo '
<tr height="10px"><td colspan="7"></td></tr>
<tr style="font-weight: bold;background-color:black;color:white;">
        <td width="10%">TOTAL</td>
        <td width="40%"></td>
         <td width="10%" style="text-align:center">Total Pedidos: <br/>$'.number_format($suma_pedidos,0,'','.').'</td>
        <td width="20%" style="text-align:center">Total Pagos: <br/>$'.number_format($suma_pagos,0,'','.').'</td>
        <td width="20%" style="text-align:center">Balance Total: <br/>$'.number_format(($saldo_anterior),0,'','.').'</td>
      </tr>
      <tr style="background-color:yellow">
        <td colspan="7" width="100%" height="10px"></td>
      </tr>
    </table>';
}else{echo 'No se encontro el cliente';}
?>
