<?php

require_once '../util/funciones.php';

$cadena = preg_split("/[\s\e*]+/", "lenguaje de programación, hipertexto pepe | crudo 123");

$hola = "0003|2";

$cadena2 = "hola1,hola2,hola3,";

echo "<pre>";
print_r(preg_split("/[,]/", $cadena2));
echo "<pre>";








$cadena3 = "2013-01-01_2013-01-30,2012-05-01_2012-12-30,,2012-05-01_2012-12-30";
echo "<pre>";
$fechaPadre = preg_split("/[,]/", $cadena3);
echo "<pre>";
$arreglo = array();
$contador_fecha_mensual = 0;
for ($i = 0; $i < count($fechaPadre); $i++) {
    if (!is_null($fechaPadre[$i]) && !empty($fechaPadre[$i])) {
        echo " \n" . $fechaPadre[$i];
        $arreglo[$i];
        $fechaHijo = preg_split("/[_]/", $fechaPadre[$i]);
        $arreglo[$i]['fecha_inicio'] = $fechaHijo[0];
        $arreglo[$i]['fecha_fin'] = $fechaHijo[1];
        $contador_fecha_mensual++;
    }
}
echo "\n" . $contador_fecha_mensual;

echoo($arreglo);
?>
