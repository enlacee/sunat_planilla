<?php

function generarConfiguracion($periodo) {
//--- SUELDO BASE = SB
    $dao_1 = new ConfSueldoBasicoDao();
    $SB = $dao_1->vigenteAux($periodo);

//--- ASIGNACION FAMILIAR = AF
    $dao_2 = new ConfAsignacionFamiliarDao();
    $T_AF = $dao_2->vigenteAux($periodo);

//--- TASA ESSALUD = T_ ESSALUD
    $dao_3 = new ConfEssaludDao();
    $T_ESSALUD = $dao_3->vigenteAux($periodo);

//--- TASA ONP = T_ONP
    $dao_4 = new ConfOnpDao();
    $T_ONP = $dao_4->vigenteAux($periodo);

//--- UIT
    $dao_5 = new ConfUitDao();
    $UIT = $dao_5->vigenteAux($periodo);

// Valores Fijos
    $ESSALUD_MAS = 5.00;
    $SNP_MAS = 5.00;
    /*
      echo "\n8888888888888888888888888888888888888888888888\n";
      echo "\nSB =".$SB;
      echo "\nT_AF =".$T_AF;
      echo "\nT_ESSALUD =".$T_ESSALUD;
      echo "\nT_ONP =".$T_ONP;
      echo "\nUIT =".$UIT;
      echo "\n8888888888888888888888888888888888888888888888\n";
     */
    // DEFINE
    if (is_null($SB) || is_null($T_AF) || is_null($T_ESSALUD) || is_null($T_ONP) || is_null($UIT)) {
        //header($string, $replace)
        //header('Location: www.google.com');
        $SB = 0;
        $T_AF = 0;
        $T_ESSALUD = 0;
        $T_ONP = 0;
        $UIT = 0;

        $ESSALUD_MAS = 0;
        $SNP_MAS = 0;
    } else {
        define('SB', $SB);
        define('T_AF', $T_AF);
        define('T_ESSALUD', $T_ESSALUD); //
        define('T_ONP', $T_ONP);
        define('UIT', $UIT);

        define('ESSALUD_MAS', $ESSALUD_MAS);
        define('SNP_MAS', $SNP_MAS);
        return true;
    }
}




function sueldoDefault($sueldo) {
    $sueldo = floatval($sueldo);
    $new_sueldo = 0.00;
    if ($sueldo < SB) {
        $new_sueldo = SB;
    } else {
        $new_sueldo = $sueldo;
    }
    return $new_sueldo;
}

?>
