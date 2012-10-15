<?php
$number = 1234.56;

// muestra el formato internacional para la configuración regional en_US
//setlocale(LC_MONETARY, 'en_US');
//echo money_format("w", $number);
$va = money_format('%=*(#10.2n', 220) . "\n";
echo $va;
// USD 1,234.56