<?php

$data_val = array();


$data_val[0] = 200;
var_dump($data_val[0]);
echo "<br>";
unset($data_val);
var_dump($data_val[0]);


$data_val[0] = 300;
echo "<br>";
var_dump($data_val[0]);

echo "<hr>";


$a['dia'] =1;


if($a['dia']){
    var_dump($a['dia']);
}else{
    echo "<br><br>VACIO O NULL ?";
}

?>
