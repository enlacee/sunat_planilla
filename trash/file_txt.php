<?php
header("Content-Type:text/html; charset=iso-8859-1");
//header("Content-Type:text/html; charset=utf-8"); 

$archivo = "demo_txt.txt";
$fp = fopen($archivo, "w+");

$cab_uno = chr(15);
fwrite($fp, $cab_uno);
fwrite($fp, chr(13) . chr(10));

$cab_uno = "PERIODO : " . $fecha;
$cab_dos = "RUC : ";
$cab_tre = "RAZON SOCIAL : ";
$cab_cua = "ESTABLECIMIENTO :  ";
$cab_cin = "CODIGO DE EXISTENCIA :  " . $codarticulo2;
$cab_sei = "TIPO DE OPERACION :  " . $existencia;
$cab_sie = "DESCRIPCION : " . $descripcion;
$cab_och = "UNIDAD DE MEDIDA : " . $umedida;
$cab_nue = "METODO DE VALUACION : PONDERADO MOVIL";
$sim_line = str_repeat('-', 132);
$dob_line = str_repeat('=', 132);

fwrite($fp, $cab_uno);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_dos);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_tre);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_cua);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_cin);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_sei);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_sie);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_och);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $cab_nue);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $sim_line);
fwrite($fp, chr(13) . chr(10));
$det_uno = "DOC.TRASLADO,INTERNO O SIMILAR TIPO  -----------ENTRADAS------------- -----------SALIDAS------------ ---------SALDO FINAL-----------";
$det_dos = "  FECHA  TIPO SERIE   NUMERO   OPERA   CANTIDAD COST/UND COSTO TOTAL   CANTIDAD COST/UND COSTO TOTAL   CANTIDAD COST/UND COSTO TOTAL";
//       "01-01-2010 12 1234  123456789x XX    9999999.99 99999.99 99999999.99 9999999.99 99999.99 99999999.99 9999999.99 99999.99 99999999.99
//       "123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x123456789x12
//                 1         2         3         4         5         6         7         8         9         0         1         2         3         4         5         6         7         8         9         0         1         2         3
fwrite($fp, $det_uno);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $det_dos);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $sim_line);
fwrite($fp, chr(13) . chr(10));



$fp_line = str_pad($fecha2, 10) . str_pad($cv, 91) . str_pad(number_format($can, 2, ".", ","), 11, " ", STR_PAD_LEFT) . str_pad(number_format($pre, 4, ".", ","), 9, " ", STR_PAD_LEFT) . str_pad(number_format($tot, 2, ".", ","), 11, " ", STR_PAD_LEFT);
fwrite($fp, $fp_line);
fwrite($fp, chr(13) . chr(10));


//

$data1 = "      N      DNI          APELLIDOS Y NOMBRES            IMPORTE       FIRMA   ";
$data_line = str_pad("| 01",7," ",STR_PAD_RIGHT).str_pad("| 45269187", 18," ",STR_PAD_RIGHT).str_pad("| copitan norabuena anibal", 40," ",STR_PAD_RIGHT).str_pad("| ________________",20," ", STR_PAD_LEFT);
$data_line2 = str_pad("| 02",7," ",STR_PAD_RIGHT).str_pad("| 45269125", 18," ",STR_PAD_RIGHT).str_pad("| copitan mru liona", 40," ",STR_PAD_RIGHT).str_pad("| ________________",20," ", STR_PAD_LEFT);


fwrite($fp, $data_line);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $data_line2);
fwrite($fp, chr(13) . chr(10));

fwrite($fp, str_pad("-", 300));

$sim_line=str_repeat('-',80);
$dob_line=str_repeat('=',80);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, chr(13) . chr(10));
fwrite($fp, str_pad("FECHA : ", 47, " ", STR_PAD_LEFT));
fwrite($fp, chr(13) . chr(10));
fwrite($fp, $dob_line);
fwrite($fp, chr(13) . chr(10));
fwrite($fp, chr(13) . chr(10));

// 81 columnas

fwrite($fp, chr(13) . chr(10));
fwrite($fp,  "81 COLUMNAS");
fwrite($fp,  str_repeat("Í",81));
fwrite($fp, chr(13) . chr(10));
fwrite($fp, chr(13) . chr(10));

// 137 columnas
fwrite($fp, $dob_line);
fwrite($fp, chr(13) . chr(10));
fwrite($fp,  "137 COLUMNAS");
fwrite($fp, chr(13) . chr(10));

fwrite($fp,  str_repeat("È",1));
fwrite($fp,  str_repeat("Í",135));
fwrite($fp,  str_repeat("¼",1));

for($i=0;$i<3;$i++){
    fwrite($fp, chr(13) . chr(10));
    fwrite($fp, "º");
}
fwrite($fp, chr(13) . chr(10));
fwrite($fp,  str_repeat("Í",137));
for($i=0;$i<3;$i++){
    fwrite($fp, chr(13) . chr(10));
    fwrite($fp, "*");
}
fwrite($fp, chr(13) . chr(10));






// ----------



fclose($fp);

?>
