<?php 
echo "ENONTRAR Y ELIMINAR ARRAY";

$arreglo['01']="renuencia";
$arreglo['02']="otros";
$arreglo['00']="este";
$arreglo['04']="norte";
//$id = array_search('renuncia', $arreglo);


	$id = array('02','02','11','12','14');
	$counteo = count($id);
	for($i=0; $i < $counteo; $i++){ echo "===== ENTRO = ".$id[$i]."<br>";
		//------------------------------------------
		
		foreach($arreglo as $value){ //RECORRIDO ALL ARRAY busca 1 ID
			if( array_key_exists( $id[$i] , $arreglo)){
				echo "Encontro y elimiaaa<br>";
				//Encontro elimina ID_BUSQUEDA indice para no seguir buscando
				unset($id[$i]);				
				
				//Elimina _arreglo GET indice Primero				
				$clave = array_search($value, $arreglo);
				unset($arreglo[$clave]);
				echo "<pre>$clave</pre>";
				
				//---- OJO encontro id unico USA BREAK queda claro que indice =id ES UNICO
				break;
			}else{
				echo "No encontro<br>";
				//
			}
		echo "<pre>";
		print_r($arreglo);
		echo "</pre>";

		}			
		//------------------------------------------
	}



?>