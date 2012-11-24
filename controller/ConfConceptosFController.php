<?php

/**
 *  Funciones que ayudan a generar Calculos:
 *  ALL conceptos.
 */
require_once '../controller/ConfConceptosController.php';
require_once '../util/funciones.php';

function sueldoMensualXHora($monto) {
    $valor = ($monto / DIA_BASE) / HORA_BASE;
    return $valor;
}

function sueldoMensualXDia($monto) {
    $valor = ($monto / DIA_BASE);
    //return number_format_2($valor); 
    return $valor;    
}


/*
$monto = 1000;
$horas = 2;
$sueldo_por_hora = sueldoMensualXHora($monto);
$nuevo_sueldo_por_hora = $sueldo_por_hora * 1.25;

$neto = $nuevo_sueldo_por_hora * $horas;
echo $neto;
*/
?>
