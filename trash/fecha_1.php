<?php 

require_once '../util/funciones.php';

function getFechasDePago($fecha) {
  
    $format_fecha = getFechaPatron($fecha, "Y-m-d");

    $fff = strtotime($format_fecha);
    $fecha_string = date("l d F Y", $fff);

    // data 1
    $dos_sem_seg = strtotime($fecha_string . "second weeks");
    $dos_sem = date("Y-m-d", $dos_sem_seg);
    //
    $mes_inicio_seg = strtotime($fecha_string . "first day");
    $mes_inicio = date("Y-m-d", $mes_inicio_seg);
    //
    $mes_fin_seg = strtotime($fecha_string . "last day");
    $mes_fin = date("Y-m-d", $mes_fin_seg);

    //echo  date("Y-m-d", $f);
    //return
    $rpta = array("fecha" => $fecha_string,
        "second_weeks" => $dos_sem,
        "first_day" => $mes_inicio,
        "las_day" => $mes_fin
    );
???????
    return $rpta;
}



$fecha = "2012-08-01";

echo "<pre>";
$var = getFechasDePago($fecha);
print_r($var);
echo "</pre>";
