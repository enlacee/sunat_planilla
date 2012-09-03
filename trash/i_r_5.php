<?php

require_once '../util/funciones.php';

$periodo = "2012-01-01"; //  Enero 2012
// v
// r =remuneracion
// 01 remuneracion fija
$r_fija = 2000;
// 02 num_meses que falta
//$num_mes_faltan = mesFaltan_ir5ta($periodo);
// 03 remuneracion proyectada
$r_proyectada = $r_fija * $num_mes_faltan;

// 04 gratificacion
$var_jul_dic = 2;
$grat_julio_dic = $r_fija * $var_jul_dic;


// 05 total 1:
$total_1 = ($r_proyectada + $grat_julio_dic);
// 06 r_meses_anteriores
$r_meses_anteriores = 0; //remuneracion de enero ...getRMesesAnteriores();
//echo date("Y",  strtotime($periodo));
//echo "num_mes_faltan = ".$num_mes_faltan;
//echo "<hr>";
//echo "total_1; =".$total_1;







function get_IR5_mesFaltan($fecha) {
    $num_mes = date("m", strtotime($fecha));
    $r = 13 - intval($num_mes);
    return $r;
}

//
function get_IR5_RMesesAnteriores($id_pdeclaracion, $fecha=null) {
    $num_mes = date("m", strtotime($fecha));

    // num_mes = 2  =febrero
    //obtener mes anterior Monto que gano.
    $dao = new r5Dao();
    $data = $dao->getData_idPdeclaracion($id_pdeclaracion);
/*
    $sum_jbasico =
    for ($i = ($num_mes - 1); $i > 0; $i--) {
        echo "$i";
        echo "<br>";
        $sum_jbasico =
    }*/
}

function listaPdeclaraciones() {
    $dao = new PlameDeclaracionDao();
    return $dao->listar($id_empleador_maestro, $anio);
}

function get_IR5_NumGratificaciones($fecha) {
    $num_mes = getFechaPatron($fecha, "m");
    
    $num = intval($num_mes);
    $rpta = null;

    if(($num>0) && ($num<7)) {
        $rpta = 2;
    }else if(($num >= 7) && ($num <= 12)){
        $rpta = 1;
    }else{
        $rpta = "error critico";
    }    
    return $rpta;
}

//Concepto 3012
function get_IR5_BonificacionExtraordinaria($periodo){
    // Obtiene bonificacion de Febrero marz-abrl-may-jun....
    
    
    
}

function get_IR5_7Uit(){
    
}

//echo getNumGratificaciones("2012-06-01");

//getRMesesAnteriores("2012-05-01");

echo "mi mes es = ".getFechaPatron("2012-7-08", "m");




?>
