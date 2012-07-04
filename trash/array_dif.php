<?php
//form
$form[1]='101';
$form[2]='102';
$form[3]='103';
$form[4]='104';
$form[5]='103';

//sql
$tipo_sercicio[1]=101;
$tipo_sercicio[2]=102;
$tipo_sercicio[3]=103;



$data = array_diff($form,$tipo_sercicio);
echo "<pre>";
print_r($data);
echo "</pre>";


//busco el indicee
$clave = array_search($tipo_sercicio[3], $form);
echo "<hr>";
echo "<pre>";
var_dump($clave);
echo "</pre>";

?>