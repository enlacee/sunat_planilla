<?php 

require_once '../util/funciones.php';
/*
echo getFechaPatron("2012-12-01", "Y/m/d");
echo "<br>";
echo getFechaPatron("1988/12/10", "d/m/Y");

echo "<br>";
echo getFechaPatron("1988-12-10", "Y-m-d");

*/

echo getFechaPatron("2011-08-01","m");



$Month = 18;
$Year = 2012;

//echo date("Y","2011-01-01");


//echo "dias de agosto es : ".date("d",mktime(0,0,0,$Month+1,0,$Year));

/*
// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
date_default_timezone_set('UTC');

// Imprime: July 1, 2000 is on a Saturday
echo "July 1, 2000 is on a " . date("l", mktime(0, 0, 0, 7, 1, 2000));
echo "<br>";
// Imprime algo como: 2006-04-05T01:02:03+00:00
echo date('c', mktime(1, 2, 3, 7, 21, 2012));

*/