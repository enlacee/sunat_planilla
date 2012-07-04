<?php


//echo substr_compare("abcdebcbc", "BC", 1,8,true);

$indice['cod_departamento']="0105";

$codigo_departamento_extraido = substr($indice['cod_departamento'], 0, 2);

echo $codigo_departamento_extraido;

?>



<?PHP


$a[0]="CERO";
$a[1]="UNO";
$a[2]="DOS";
$a[3]="TRES";

echo "contador = ".count($a);

for($i=0; $i<count($a);$i++){
	if($a[$i] = "UNO"){
		unset($a[$i]);
	}
}

echo "<hr>";
var_dump($a);


?>