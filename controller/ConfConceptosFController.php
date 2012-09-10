<?php

/**
 *  Funciones que ayudan a generar Calculos:
 *  ALL conceptos.
 */
require_once '../controller/ConfConceptosController.php';

function sueldoMensualXHora($monto) {
    $valor = ($monto / DIA_BASE) / HORA_BASE;
    return roundTwoDecimal($valor);
}

function sueldoMensualXDia($monto) {
    $valor = ($monto / DIA_BASE);
    return roundTwoDecimal($valor);
}

/**
 *
 * @param type $num
 * @return Decimal Numero Redondeado a dos Decimales
 */
function roundTwoDecimal($num) {
    $valor = number_format($num, 4);

    $redondeo = round(($valor * 100));

    return $redondeo / 100;
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
