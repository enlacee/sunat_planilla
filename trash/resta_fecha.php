<?php 
require_once '../util/funciones.php';


$num  = strtotime('2012-02-15');

echo $num;
echo "<br>";

echo date("Y-m-d",$num);
echo "<br># de dia del mes".date("j",$num);
echo "<br># Número de días del mes dado".date("t",$num);
//echo "<br># de dias  del MES".date("t",$num);

echo "<hr>";

$fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
$fecha_entrada = strtotime("19-11-2008");

if($fecha_actual > $fecha_entrada){
        echo "La fecha entrada ya ha pasado";
}else{
        echo "Aun falta algun tiempo";
}


echo "<hr>";

$FF = '2012-01-31';
$FI = '2012-01-01';


$finicio = "2012-01-01";
$ffin = "2012-01-23";

$dia = getFechaPatron($finicio,"d");
$mes = getFechaPatron($finicio,"m");
$anio = getFechaPatron($finicio,"Y");

$d_lab = 0;
if(isset($ffin)){
    $num  = strtotime( $ffin );
    
    echo "<br># de dia del mes sin 0".date("j",$num);
    
    
   // $finicio
    
}

echo "fecha_fin".getMonthDays($Month, $Year)


?>