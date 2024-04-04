<?php
include '../inc/conection.php';
date_default_timezone_set("America/Argentina/Buenos_Aires");

if ($_POST['dato']){

  $dato = $_POST['p'];
  $consul_razones = $link->query("SELECT * FROM clientes
    inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes
    where clientes_comercios.estado_comclientes ='1' and clientes_comercios.razon_comclientes like '%$dato%' order by clientes_comercios.razon_comclientes ASC") or die(mysqli_error());
echo '<option value="" disabled="" selected="">Seleccione una Localidad</option>';
  while($raz= mysqli_fetch_array($consul_razones)){echo '<option value="'.$raz['id_clientes'].'">'.$raz['razon_comclientes'].'</option>';
  }
}
?>
