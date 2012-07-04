<?php
function getFechaPatron($fecha_es_us, $patron_date) {

    $fecha_es_us = eregi_replace("/", "-", $fecha_es_us);

    $fecha = date("Y-m-d", strtotime($fecha_es_us));

    if ($patron_date) {

        $fecha = date($patron_date, strtotime($fecha));
    }

    return $fecha;
}






$us = "2011-02-01";
$es = "01/05/2012";

echo getFechaPatron($us,"Y-m-d");










?>