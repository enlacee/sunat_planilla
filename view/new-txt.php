<?php

//$archivo = "hola-anb.txt";

//fopen($archivo, "w");


$fp = fopen('data.txt', 'w');
fwrite($fp, '1año per\n');
fwrite($fp,  chr(13));
fwrite($fp,'|');
fwrite($fp, '23');
fclose($fp);

// ¡el contenido de 'data.txt' ahora es 123 y no 23!


/*salto de lineas

chr(10) = salto linea;
chr(9)  = tab;

char
*/


echo ord('27');


?>
