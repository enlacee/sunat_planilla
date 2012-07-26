<?php
$arreglo = array('0105', '0106', '0107', '0118', '0121', '0122', '0201', '0306', '0701', '0702', '0706');

$arreglo2 = array('1001','10002');

$arreglo = array_merge($arreglo, $arreglo2);

echo "<pre>";
print_r($arreglo);
echo "</pre";
?>
