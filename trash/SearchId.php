<?php
/*
    //ID A ELIMINAR
    if ($id_tipo_empleador == 1) { //data BASE DATOS !!	
        $id = array('14', '15', '16', '17');
        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['cod_tipo_contrato']) {
                    //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                    unset($id[$i]);
                    //Elimina _arreglo GET indice Primero				
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
        return $arreglo;
    }//ENDIF




*/


$arreglo = array();
$arreglo[0]['cod']= '45269187';
$arreglo[0]['nombre']= 'anibal';
$arreglo[0]['apellido']= 'copitan norabuena';
$arreglo[0]['edad']= '23';

$arreglo[1]['cod']= '12345678';
$arreglo[1]['nombre']= 'pepe';
$arreglo[1]['apellido']= 'noraia lora';
$arreglo[1]['edad']= '18';

$arreglo[2]['cod']= '9582545';
$arreglo[2]['nombre']= 'mario';
$arreglo[2]['apellido']= 'riva nelia';
$arreglo[2]['edad']= '15';


//$search_array = array('first' => 1, 'second' => 4);
//id a buscar y Extraer
$salvados = array();
$a=0;

$ID =array('23','18');
$counteo = (count($arreglo));
for($i=0; $i<$counteo; $i++){ echo "<br>  ".$i;
    
    foreach ($arreglo AS $indice){
        echo "entro -><br>";
        $bandera = array_search($ID[$i], $indice);        
        if($bandera){ //Record ALL array
            $salvados[] = $indice;
            $a++;
            //echo "encontro!!<br>";
            unset ($indice[$bandera]);
            break;
        }else{
            //echo "!!!";
        }
        echo "<pre>";
       // print_r($indice);
        echo "</pre>";
    }//ENDFOREACH
    
}

echo "<pre>sal<br>";
print_r($salvados);
echo "</pre>";
//if (array_key_exists('first', $search_array)) {
 //   echo "The 'first' element is in the array";
//}

















?>
