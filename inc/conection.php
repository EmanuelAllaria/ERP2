<?php

/*date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL,"es_ES");
$link= mysqli_connect('localhost', 'u598064194_matildebig', 'CBV#*Bi0');
$db='u598064194_matildebig';
$db_select = mysqli_select_db($link, $db);
if (!$db_select) {
    die("Database selection failed: " . mysqli_error());
}*/


date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL,"es_ES");
$link= mysqli_connect('localhost', 'root', '');
$db='u598064194_matildebig';
$db_select = mysqli_select_db($link, $db);
if (!$db_select) {
    die("Database selection failed: " . mysqli_error());
}
?>