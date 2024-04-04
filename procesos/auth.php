<?php

include '../inc/conection.php';

date_default_timezone_set("America/Argentina/Buenos_Aires");


if (isset($_POST['login'])) {

  $usuario = strtolower(htmlspecialchars(trim($_POST['usuario'])));

  $password = md5(htmlspecialchars(trim($_POST['password'])));

  $consulta = $link->query("SELECT * FROM `usuarios` WHERE `usuario` = '$usuario' and passuser = '$password' ") or die(mysqli_error());

  $login = mysqli_num_rows($consulta);

  if ($login != 0) {
    session_start();
    $users = mysqli_fetch_array($consulta);


    $_SESSION['usuario'] = $users['usuario'];
    $_SESSION['id'] = $users['id'];
    $_SESSION['nombre'] = $users['nombre'];
    $_SESSION['tipo'] = $users['tipo'];
    $_SESSION['avatar'] = $users['avatar'];
    $_SESSION['personal'] = $users['personal'];
    $_SESSION['email'] = $users['email'];

      header('location:../index.php');      
    


  } else {
    header('location:../login.html?msg=error');
  }
}
