<?php
require_once '../util/funciones.php';

$name = "COPITAN NORABUENA VICTOR ANIBAL";

//echoo(strlen($name));
//echo str_word_count($name);
echo "<br>";
//echoo(str_split($name));


function textoaMedida($num_limite,$texto){    
    //return sizeof(explode(" ", $texto));  
    $num_palabra = str_word_count($texto);
    $num_len = strlen($texto);        
            
    $txt  =str_replace(" ", ",", $texto);
    $arreglo_txt = (str_getcsv($txt, ","));
    
    $cadena = null;
    $count_leng = 0;
    for($i=0;$i<count($arreglo_txt);$i++){

        $count_leng = $count_leng + intval(strlen($arreglo_txt[$i]));
        if($count_leng <= $num_limite){
            if($i==0):
               $cadena .= $arreglo_txt[$i]; 
            else:
               $cadena .= " ".$arreglo_txt[$i]; 
               $count_leng = $count_leng + 1; //sum espacio
            endif;
                       
        }else{
            break;
        }
        
    }
    
    return $cadena;
    
    
}

echo strlen($name);


echo "<br>";
echo "<br>";
echo "<br>";
echoo(textoaMedida(45,$name));



    
    
?>
