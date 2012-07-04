<?php


$cadena = preg_split("/[\s\e*]+/", "lenguaje de programación, hipertexto pepe | crudo 123");

$hola = "0003|2";

$cadena2 = "hola1,hola2,hola3,";

echo "<pre>";
print_r(preg_split("/[,]/",$cadena2));
echo "<pre>";

 ?>
