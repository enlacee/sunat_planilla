<?php
/*
echo date("M-d-Y", mktime(0, 0, 0, 12, 32, 1997));
echo "<br>";
echo date("M-d-Y", mktime(0, 0, 0, 13, 1, 1997));
echo "<br>";
echo date("M-d-Y", mktime(0, 0, 0, 1, 1, 1998));
echo "<br>";
echo date("M-d-Y", mktime(0, 0, 0, 1, 1, 98));
*/


echo date("M-d-Y", mktime(0, 0, 0, 12, 5, 2012));
echo "<br>";
echo date("M-d-Y", mktime(0, 0, 0, 9, 0, 2012));
echo "<br>";
echo date("M-d-Y", mktime(0, 0, 0, 1, 1, 2012));
echo "<br>";
echo date("M-d-Y", mktime(0, 0, 0, 1, 1, 12));


echo "<br>";

$ultimo_día = mktime(0, 0, 0, 3, 0, 2012);
echo strftime("El último día en Febrero 2012 es: %d", $ultimo_día);


function dates_range($date1, $date2)
{
   if ($date1<$date2)
   {
       $dates_range[]=$date1;
       $date1=strtotime($date1);
       $date2=strtotime($date2);
       while ($date1!=$date2)
       {
           $date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1));
           $dates_range[]=date('Y-m-d', $date1);
       }
   }
   return $dates_range;
} 

//echo "<pre>";
//print_r(dates_range('2011-01-01','2012-01-01'));
//echo "</pre>";



$fecha = "2012-05-01";

$mes = date("m",strtotime($fecha));
$dia = date("d",strtotime($fecha));
$anio = date("Y",strtotime($fecha));

echo "<br>";
$str_time = mktime(0,0,0,$mes,$dia+365,$anio);

echo "<br>";
echo "<br>";
echo date("Y-m-d",$str_time);


?>
