<?php 
session_start();

$data_serial = unserialize($_SESSION['data_serial']);




echo "<pre>";
print_r($data_serial);
//var_dump($data_serial);
echo "</pre>";

?>
