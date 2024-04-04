<?php
require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Incluir el contenido HTML
include 'liqui_detalle_pdf.php';

// Crear una instancia de Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($plantilla);

// Renderizar y mostrar el PDF
$dompdf->render();
$dompdf->stream('ejemplo.pdf', array('Attachment' => 0));