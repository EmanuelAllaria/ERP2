<?php
session_start();
$hoy = date('d-m-Y hh:mm:ss');
date_default_timezone_set("America/Argentina/Buenos_Aires");

	ob_start();
if($_GET['c']){include("informe_clientes.php");}
else{
  include("informe_semanal.php");}

    $content = ob_get_clean();

    //Se obtiene la librería
    require_once(dirname(__FILE__).'/html2pdf.class.php');

    try
    {
        $html2pdf = new HTML2PDF('L', 'A4', 'es', true, 'UTF-8', 3); //Configura la hoja
        $html2pdf->pdf->SetDisplayMode('fullpage'); //Ver otros parámetros para SetDisplaMode
        $html2pdf->writeHTML($content); //Se escribe el contenido
        $html2pdf->Output('informe_resumen'.$hoy.'.pdf'); //Nombre default del PDF
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

?>
