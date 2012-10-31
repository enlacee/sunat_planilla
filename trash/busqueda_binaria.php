<?php

//REUISITOS
//UNICOS Y ORDENADOS!

$aguaja= 80;

$id = array(5,10,15,20,25,30,35,40);
array_multisort(&$id,SORT_ASC);
//VAR_DUMP($id_1);

$a = 0;
$b = sizeof($id)-1;
$k = -1;

echo $a;
echo $b;
echo $k;
$m = 0;



while($b>$a){
    
    echo "<br>b = $b";
    echo "<br>a = $a";
    echo "<br>m = $m";
    echo "<br>aguja = ".$aguaja;
    //punto  medio = m = inicio + final / 2
    $m = intval(($b-$a)/2);
    
    if($aguaja > $id[$m]):
        echo "<br>  ".$aguaja .">".$id[$m];
        $a = $m + 1; 
        //first = mid + 1
    elseif($aguaja < $id[$m]):
        echo "<br>entro 222";
        $a = $m; 
        //$a = $m -1;
    else :
        echo "<br>entro else";
        $k = $m;
        break;
    endif;    

    
   
}


if($k != -1):
    echo "<br>encontro id = ".$id[$k];
    else:
    echo "<br>No encontro id";
endif;



?>
