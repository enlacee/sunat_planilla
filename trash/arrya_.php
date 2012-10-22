
<?php
$array = array(
    'fruta1' => 'manzana',
    'fruta2' => 'naranja',
    'fruta3' => 'uva',
    'fruta4' => 'manzana',
    'fruta5' => 'manzana');

/*$array = array(
    '0' => 'manzana',
    '1' => 'naranja',
    '2' => 'uva',
    '3' => 'manzana',
    '4' => 'manzana');*/

// Este ciclo muestra todas las claves del array asociativo
// donde el valor equivale a "manzana"
while ($nombre_fruta = current($array)) {
    if ($nombre_fruta == 'manzana') {
        echo key($array).'<br />';
    }
    next($array);
}
?>
