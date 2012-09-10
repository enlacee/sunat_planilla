<?php

//$file_name2 = NAME_COMERCIAL . '-BOLETA QUINCENA.txt';

$BREAK = chr(13) . chr(10);
//..............................................................................
// Inicio Exel
//..............................................................................
$fpx = fopen($file_name2, 'w');

fwrite($fpx, str_pad(NAME_EMPRESA, 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad(NAME_EMPRESA, 50, " ", STR_PAD_LEFT));
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);

fwrite($fpx, str_pad('R E C I B O', 20, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad('R E C I B O', 50, " ", STR_PAD_LEFT));
fwrite($fpx, $BREAK);
fwrite($fpx, str_pad('* * * * * *', 20, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad('* * * * * *', 50, " ", STR_PAD_LEFT));

fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);

fwrite($fpx, str_pad('ADELANTO DE QUINCENA CORRESPONDIENTE', 20, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad('ADELANTO DE QUINCENA CORRESPONDIENTE', 50, " ", STR_PAD_LEFT));
fwrite($fpx, $BREAK);
fwrite($fpx, str_pad('Al MES DE ' . strtoupper($nombre_mes) . " DEL " . $anio, 20, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad('Al MES DE ' . strtoupper($nombre_mes) . " DEL " . $anio, 50, " ", STR_PAD_LEFT));

fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
$_NOMBRE_ = $data['apellido_paterno'] . " " . $data['apellido_materno'] . " " . $data['nombres'];
fwrite($fpx, str_pad('NOMBRES:', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad($_NOMBRE_, 42, " ", STR_PAD_RIGHT));
fwrite($fpx, str_pad('NOMBRES:', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad($_NOMBRE_, 8, " ", STR_PAD_LEFT));
//fwrite($fpx, $BREAK);



fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, str_pad('SUELDO: ', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad($data['sueldo'], 42, " ", STR_PAD_RIGHT));
fwrite($fpx, str_pad('SUELDO: ', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad($data['sueldo'], 8, " ", STR_PAD_LEFT));
fwrite($fpx, $BREAK);




fwrite($fpx, $BREAK);
fwrite($fpx, str_pad('N. CTA: ', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad(' -  -', 42, " ", STR_PAD_RIGHT));
fwrite($fpx, str_pad('N. CTA: ', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad(' -  -', 8, " ", STR_PAD_LEFT));
fwrite($fpx, $BREAK);



fwrite($fpx, $BREAK);
$_FECHA_CREACION_ = getFechaPatron($data['fecha_creacion'], "d/m/Y");
fwrite($fpx, str_pad('FECHA: ', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad($_FECHA_CREACION_, 43, " ", STR_PAD_RIGHT));
fwrite($fpx, str_pad('FECHA: ', 0, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad($_FECHA_CREACION_, 7, " ", STR_PAD_LEFT));



fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);




fwrite($fpx, $BREAK);
fwrite($fpx, str_pad('_______________', 0, " ", STR_PAD_LEFT)); //VO
fwrite($fpx, str_pad('_______________', 30, " ", STR_PAD_LEFT));

fwrite($fpx, str_pad('_______________', 20, " ", STR_PAD_LEFT));   //VO           
fwrite($fpx, str_pad('_______________', 30, " ", STR_PAD_LEFT));


fwrite($fpx, $BREAK);
fwrite($fpx, str_pad('      Vo.Bo.   ', 0, " ", STR_PAD_LEFT)); //VO
fwrite($fpx, str_pad('RECIBI CONFORME', 30, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad('      Vo.Bo.   ', 20, " ", STR_PAD_LEFT));  //VO
fwrite($fpx, str_pad('RECIBI CONFORME', 30, " ", STR_PAD_LEFT));



//fwrite($fpx, str_pad('RECIBI CONFORME', 0, " ", STR_PAD_LEFT));   
fwrite($fpx, $BREAK);
fwrite($fpx, str_pad('DNI', 33, " ", STR_PAD_LEFT));
fwrite($fpx, str_pad('DNI', 50, " ", STR_PAD_LEFT));



fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);


fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fwrite($fpx, $BREAK);
fclose($fpx);

?>
