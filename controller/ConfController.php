<?php
//require_once '../dao/ConfAfpDao.php';

require_once '../dao/ConfAsignacionFamiliarDao.php';
require_once '../dao/ConfSueldoBasicoDao.php';
require_once '../dao/ConfEssaludDao.php';
require_once '../dao/ConfOnpDao.php';
require_once '../dao/ConfUitDao.php';

//--- SUELDO BASE = SB
$dao_1 = new ConfSueldoBasicoDao();
$SB = $dao_1->vigente();

//--- ASIGNACION FAMILIAR = AF
$dao_2 = new ConfAsignacionFamiliarDao();
$T_AF = $dao_2->vigente();

//--- TASA ESSALUD = T_ ESSALUD
$dao_3 = new ConfEssaludDao();
$T_ESSALUD = $dao_3->vigente();

//--- TASA ONP = T_ONP
$dao_4 = new ConfOnpDao();
$T_ONP = $dao_4->vigente();

//--- UIT
$dao_5 = new ConfUitDao();
$UIT = $dao_5->vigente();

// Valores Fijos
$ESSALUD_MAS = 5;
$SNP_MAS = 5;

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
    define('T_ESSALUD', $T_ESSALUD);
    define('T_ONP', $T_ONP);
    define('UIT', $UIT);
    
    define('ESSALUD_MAS',$ESSALUD_MAS);
    define('SNP_MAS', $SNP_MAS);
    
}
?>
