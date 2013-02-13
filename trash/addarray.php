<?php
$arreglo = array('0105', '0106', '0107', '0118', '0121', '0122', '0201', '0306', '0701', '0702', '0706');
$arreglo2 = array('1001','10002');

$arreglo = array_merge($arreglo, $arreglo2);

echo "<pre>";
print_r($arreglo);
echo "</pre";

$a = array(
  array('numero'=>1,'estado'=>'z'),
  array('numero'=>2,'estado'=>'z'),
);

$b = array(
  array('numero'=>9,'estado'=>'x'),
  array('numero'=>0,'estado'=>'z'),
);

$a = array_merge($a,$b);

echo "<pre>";
print_r($a);
echo "</pre";

?>
