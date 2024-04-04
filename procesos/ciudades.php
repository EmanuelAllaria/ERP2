<?php
include '../inc/conection.php';
date_default_timezone_set("America/Argentina/Buenos_Aires");

if ($_POST['p']){
  $provincia = $_POST['p'];
  $consul_ciudades = $link->query("SELECT * FROM ciudad where provincia_id = '$provincia' order by ciudad_nombre asc") or die(mysqli_error());
echo '<option value="" disabled="" selected="">Seleccione una Localidad</option>';
  while($ciudad= mysqli_fetch_array($consul_ciudades)){echo '<option value="'.$ciudad['id_ciudad'].'">'.$ciudad['ciudad_nombre'].'</option>';
  }
}
?>
