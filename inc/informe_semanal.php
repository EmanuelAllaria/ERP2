<?php
//Base de datos
include 'conection.php';
$desde = $_GET['d'];
$hasta = $_GET['h'];

// Procesa info

$consulta=$link->query("SELECT * FROM clientes inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes
WHERE clientes.estado_clientes='1' ORDER BY clientes_comercios.vendedor_comclientes DESC, clientes_comercios.razon_comclientes ASC");



$fecha = date("d-m-Y");

echo '<table width="100%" border="1"  style="border-spacing: 0px;">
        <tr>
          <td colspan="7" style="text-align:center;"><h2>RESUMEN DESDE '.date('d/m/Y', strtotime($desde)).' HASTA '.date('d/m/Y', strtotime($hasta)).'</h2></td>
        </tr>
        <tr style="font-weight: bold;background-color:black;color:white;text-align:center">
          <td>VENDEDOR</td>
          <td>CLIENTE</td>
          <td>PEDIDO</td>
          <td>IMPORTE</td>
          <td>SALDO ANTERIOR</td>
          <td>ENTREGA</td>
          <td>SALDO</td>
        </tr>';

$acumula_pagos="0";
$acumula_importe='0';
$acumula_saldoa='0';
$acumula_saldo='0';
#,nombre_completo,numeracion,numerito,codificacion \n";
 while ($row = mysqli_fetch_array($consulta)) {
   $vendedor = $row['vendedor_comclientes'];
   $consulto_vendedor=$link->query("SELECT * FROM usuarios where id='$vendedor' and estado_usuarios='1' ");
   $vende = mysqli_fetch_array($consulto_vendedor);
$cliente = $row['cliente_comclientes'];
//datos CC
$consulta_corriente= $link->query("SELECT (sum(monto)-sum(monto2)) as total_balance FROM transaccion WHERE cliente ='$cliente' and fecha <= '$hasta' AND estado = 1 group by cliente order by id asc ");
$cc = mysqli_fetch_array($consulta_corriente);

$consulta_pago = $link->query("SELECT sum(monto2) as total_pagos FROM transaccion where (fecha BETWEEN '$desde' AND '$hasta') and cliente='$cliente' and tipo='pago' order by id desc");
$cp = mysqli_fetch_array($consulta_pago);

$ultimo_pedido = $link->query("SELECT *, sum(monto) as total_pedidos FROM transaccion where (fecha BETWEEN '$desde' AND '$hasta') and cliente='$cliente' and fecha !='0000-00-00 00:00:00' and tipo='pedido' order by id desc");
$up = mysqli_fetch_array($ultimo_pedido);
$id_ultimo=$up['id'];
$saldito= $link->query("SELECT (sum(monto)-sum(monto2)) as sub_balance FROM transaccion WHERE cliente ='$cliente' and id < '$id_ultimo' AND estado = 1 group by cliente order by id desc ");
$cs = mysqli_fetch_array($saldito);

$saldo_anterior=$cs['sub_balance'];
$total = ($up['monto']+$saldo_anterior)-$cp['total_pagos'];

$acumula_pagos = $acumula_pagos+$cp['total_pagos'];
$acumula_importe = $acumula_importe+$up['total_pedidos'];
$acumula_saldoa =$acumula_saldoa+$saldo_anterior;
$acumula_saldo =$acumula_saldo+$total;
//if ($saldo_anterior < 0 ){$saldo_anterior= $saldo_anterior*-1;}
echo '
<tr >
  <td>'.$vende['nombre'].'</td>
  <td>'.$row['razon_comclientes'].'</td>
  <td style="text-align:center">';if($up['fecha']!=''){echo date('d/m/Y',strtotime($up['fecha']));}else{echo'-';} echo'</td>
  <td style="text-align:right">'.number_format($up['total_pedidos'],0,'','.').'</td>
  <td style="text-align:right">'.number_format($saldo_anterior,0,'','.').'</td>
  <td style="text-align:right">'.number_format($cp['total_pagos'],0,'','.').'</td>
  <td style="text-align:right">'.number_format($total,0,'','.').'</td>
</tr>';
}

echo '
<tr height="10px"><td colspan="7"></td></tr>
<tr style="font-weight: bold;background-color:black;color:white;">
        <td>TOTAL</td>
        <td></td>
        <td></td>
        <td style="text-align:right">'.number_format($acumula_importe,0,'','.').'</td>
        <td style="text-align:right">'.number_format($acumula_saldoa,0,'','.').'</td>
        <td style="text-align:right">'.number_format($acumula_pagos,0,'','.').'</td>
        <td style="text-align:right">'.number_format($acumula_saldo,0,'','.').'</td>
      </tr>
      <tr style="background-color:yellow">
        <td colspan="7" height="10px"></td>
      </tr>
    </table>';

?>
