<?php

require_once '../util/funciones.php';

$numeros = array(59,59,59,0);
$contador = array();

function numeroMayor($numeros = array()){    
$contador = array();

for($i=0; $i< count($numeros);$i++){    
    $contador[$i]=0;
    for($j=0; $j< count($numeros);$j++){
        if($numeros[$i]>=$numeros[$j] && $numeros[$i]!=$numeros[$j]){            
            $contador[$i] = $contador[$i] + 1;            
        }
    }
}

for($x = 0;$x<count($contador);$x++){        
    for($z=0;$z<count($contador);$z++):
        if($contador[$x]>=$contador[$z]  && $contador[$x]!=$contador[$z]):            
            $indice = $x; // Contador con mas votos
            //$key =key($contador);
        endif;
    endfor;
    
}
//echoo($numeros[$indice]);
return $numeros[$indice];

}

$n = array(3,2,5);
echoo(numeroMayor($n));


?>
