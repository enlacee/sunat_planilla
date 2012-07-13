<?php
//form
$form[1]='01';
$form[2]='02';
$form[3]='03';
$form[4]='04';
$form[5]='05';

//sql
$tipo_sercicio[1]='01';
$tipo_sercicio[2]='02';
$tipo_sercicio[3]='03';
$tipo_sercicio[4]='09';

//
echo "FORM";
echo "<pre>";
print_r($form);
echo "</pre>";

echo "<hr>tipo servicio";
echo "<pre>";
print_r($tipo_sercicio);
echo "</pre>";
echo "<pre>";

$data = array_diff($form,$tipo_sercicio);
echo "<pre> ARRAY DIF DE FORM AAAA TIPO SERVICIO<BR>";
print_r($data);
echo "</pre>";


$data1 = array_diff($tipo_sercicio,$form);
echo "<pre> ARRAY DIF DE TIPO SERVICIO AAAA FORM  <BR>";
print_r($data1);
echo "</pre>";


/*
//busco el indicee
$clave = array_search($tipo_sercicio[3], $form);
echo "<hr>";
echo "<pre>";
var_dump($clave);
echo "</pre>";
*/
?>