<?php

require_once '../util/funciones.php';


$fecha_ISO = "18/07/2012";
//getRangoFechaSemana($fecha_ISO);
$data = getMesInicioYfin($fecha_ISO);
echo "<pre>";
print_r($data);
echo "</pre>";
echo "<hr>";

function getMesInicioYfin($fecha_ISO) {
    
    $fecha = explode("/", $fecha_ISO);
    $diax = $fecha[0];
    $mesx = $fecha[1];
    $aniox = $fecha[2];

    $format_fecha = "$aniox-$mesx-$diax";
    $fff = strtotime($format_fecha);
    $fecha_string = date("l d F Y", $fff);
    
    // data 1
    $mes_inicio_seg = strtotime($fecha_string."first day");
    $mes_inicio = date("Y-m-d", $mes_inicio_seg);
    
    $mes_fin_seg = strtotime($fecha_string."last day");
    $mes_fin = date("Y-m-d", $mes_fin_seg);
    
    //echo  date("Y-m-d", $f);
    
    //return
    $rpta = array("fecha" => $fecha_string,
        "mes_inicio" => $mes_inicio,
        "mes_fin" => $mes_fin   
    );
    
    return $rpta;    
}



/*
  echo strtotime("now"), "\n";
  echo strtotime("10 September 2000"), "\n";
  echo strtotime("+1 day"), "\n";
  echo strtotime("+1 week"), "\n";
  echo strtotime("+1 week 2 days 4 hours 2 seconds"), "\n";
  echo strtotime("next Thursday"), "\n";
  echo strtotime("last Monday"), "\n";
 */


?>
