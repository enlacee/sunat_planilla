<?php
require_once '../util/funciones.php';

$inicio = '2013-01-01';
$fin = '2013-01-15';
$dias = array();
$i = 0;
do {
    $fecha_variable = crearFecha($inicio, $i, 0, 0);   
    //$dias [] = $fecha_variable;    
    $dias [] = getFechaPatron($fecha_variable, 'd');
    $i++;
} while ($fin != $fecha_variable);

echo "<pre>dias ";
print_r($dias);
echo "</pre>";
echo "<br>";


$data_vacacion = array(
    array('fecha_inicio'=>'2013-01-01','fecha_fin'=>'2013-01-05'),
    array('fecha_inicio'=>'2013-01-13','fecha_fin'=>'2013-01-17')
);
$diasv = array();
for($a=0;$a<count($data_vacacion);$a++){
    
    if(getFechaPatron($data_vacacion[$a]['fecha_inicio'], 'd')>15)
        continue;
    if(getFechaPatron($data_vacacion[$a]['fecha_fin'], 'd')>15)
        $data_vacacion[$a]['fecha_fin'] = $fin;    
        
    $i = 0;
    do {
        $fecha_variable = crearFecha($data_vacacion[$a]['fecha_inicio'], $i, 0, 0);   
        //$diasv [] = $fecha_variable;    
        $diasv [] = getFechaPatron($fecha_variable, 'd');
        $i++;
    } while ($data_vacacion[$a]['fecha_fin'] != $fecha_variable);
    
    echo "<H1>$a</H1>";
    
}

echo "<pre>dias vacacion";
print_r($diasv);
echo "</pre>";
echo "<br>";

$diast = array_values(array_diff($dias, $diasv));

echo "<pre>";
print_r($diast);
echo "</pre>";




?>
