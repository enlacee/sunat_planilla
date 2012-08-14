<?php 

$ciudades = array ("Badajoz","Mérida","Cáceres","Plasencia") ; 
$ciudades["España"] = "Madrid"; 
$ciudades["Portugal"] = "Lisboa"; 
$ciudades["Francia" ] = "Paris"; 

echo "<pre>";
print_r($ciudades);
echo "</pre>";

/*
do{
	echo "hoaa =".current($ciudades);
	echo "<br>";
}while(next($ciudades));
*/



function recorre($ciudades){
	do{
		echo "=".current($ciudades);
		echo "<br>";
	}while(next($ciudades));
//	$ciudades['new'] = "texto";
//	$ciudades[count($ciudades)] = "numero";
}
recorre(&$ciudades);

$ciudades["aaa"]="aaa";
$ciudades["bbb"]="bbb";


echo "<hr>";
//var_dump($ciudades);

recorre(&$ciudades);

?>