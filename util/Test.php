<?php

//class Testear {




function echoo($var1, $var2=null) {
    echo"<hr>";
    echo "<pre>";
    print_r($var1);
    echo "</pre>";
    echo"<hr>";
    echo"<br>";
    if ($var2) {
        echo "<hr>";
        echo "<pre>";
        echo print_r($var2);
        echo "</pre>";
        echo "<hr>";
        echo "<br>";
    }
}

//}
?>
