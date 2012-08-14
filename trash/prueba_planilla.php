<?php

//strip

$haystack = "@hola pip";
var_dump(stripos($haystack, "@"));
echo "<hr>";
$variable = "500pepe";
var_dump(strval($variable));




$valor = "50.00%";

function getTipoMonedaPago($valor) {
    $tipoVal = array("%", "$");
    
    if ($valor) {
        for ($i = 0; $i < count($tipoVal); $i++) {
            $dato = stripos($valor, $tipoVal[$i]);

            if ($dato != false) {
                $encontro = $tipoVal[$i];
                break;
            }
        }
    }
}

echo "encontro " . $encontro;



var_dump(number_format($valor,3));






?>
