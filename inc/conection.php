<?php

date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");
try {
    $link = mysqli_connect('localhost', 'u598064194_sistemabig', 'CBV#*Bi0');
    $db = 'u598064194_sistemabig';
    $db_select = mysqli_select_db($link, $db);
} catch (\Exception) {
    $link = mysqli_connect('localhost', 'root', '');
    $db = 'bpgestion';
    $db_select = mysqli_select_db($link, $db);
}


