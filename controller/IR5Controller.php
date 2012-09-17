<?php

//require_once '../util/funciones.php';

function calcular_IR5_concepto_0605($ID_PDECLARACION, $id_trabajador, $sueldo) {

    // Dao pdeclaracion
    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($ID_PDECLARACION);
    echo "<pre> -------------------- HOLA  = IR 5TA --------------------";
    print_r($data_pd);
    echo "</pre>";

    $periodo = $data_pd['periodo']; //"2012-01-01"; //  Enero 2012
//| -----------------------------------------------------
//| --- VOCABULARIO ---                                 |
//| r = remuneracion                                    |
//| gra = gratificacio-+
//| 
//| ----------------------------------------------------
    //echo "<pre> INGRESOS AFECTOS 5TA";
    //$d = get_IR5_Ingresos($ID_PDECLARACION, $id_trabajador, $conceptos_afectos5ta);
    //print_r($d);
    //echo "</pre>";
// 01 remuneracion fija
    $r_fija = get_IR5_Ingresos($ID_PDECLARACION, $id_trabajador); //$sueldo; //2000;
    echo "R_FIJA= " . $r_fija;
// 02 num_meses que falta
    $num_mes_faltan = get_IR5_mesFaltan($periodo);

// 03 remuneracion proyectada
    $r_proyectada = $r_fija * $num_mes_faltan;
    echo "R_PROYECTADA = " . $r_proyectada;
// 04 gratificacion
    $var_jul_dic = get_IR5_NumGratificaciones($periodo);
    $grat_julio_dic = $r_fija * $var_jul_dic;


// 05 total 1:
    $total_1 = ($r_proyectada + $grat_julio_dic);
    echo "TOTAL :: ++ = " . $total_1;


// 06 r_meses_anteriores
    $r_meses_anteriores = get_IR5_RMesesAnteriores($ID_PDECLARACION, $id_trabajador, $periodo); //remuneracion de enero ...getRMesesAnteriores();
    echo "r_meses_anteriores = " . $r_meses_anteriores;

// 07 gratificaciones de meses anteriores
    $gra_meses_anteriores = null;
    if (get_IR5_NumGratificaciones($periodo) == 1) {
        //$gra_meses_anteriores =
    }


//echo date("Y",  strtotime($periodo));
//echo "num_mes_faltan = ".$num_mes_faltan;
//echo "<hr>";
//echo "total_1; =".$total_1;
}

function get_IR5_mesFaltan($periodo) {
    $num_mes = date("m", strtotime($periodo));
    $r = 13 - intval($num_mes);
    return $r;
}

//
function get_IR5_RMesesAnteriores($id_pdeclaracion, $id_trabajador, $periodo) {
    $mes = date("m", strtotime($periodo));
    $anio = date("Y", strtotime($periodo));
    $dia = date("d", strtotime($periodo));

    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $anio);
    //echo "MES PERIODO = ==== " . $periodo;
//..............................................................................
    $conceptos_afectos = array();
    $conceptos_afectos = arrayConceptosAfectos_5ta();
//..............................................................................    
// -- -- - -- -IMPORTANTE LISTA TODOS LOS CONCEPTOS EN BASE DE DATOS - -- - - -//
    

    $dao_dconcepto = new DeclaracionDconceptoDao();
    $sum_jbasico = 0;
    for ($i = ($mes - 1); $i > 0; $i--) {

        $periodo_lab = "$anio-$i-$dia";
        $periodo_lab = getFechaPatron($periodo_lab, "Y-m-d");

        //BUSCAR ID_PDECLARACION
        $id_pdeclaracion_lab = null;

        for ($j = 0; $j < count($data_pd); $j++) {

            if ($data_pd[$j]['periodo'] == $periodo_lab) {
                $id_pdeclaracion_lab = $data_pd[$j]['id_pdeclaracion'];

                $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion_lab);
                for ($z = 0; $z < count($data_dconcepto); $z++) {
                    if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {

                        $sum_jbasico = $sum_jbasico + $data_dconcepto[$z]['monto_pagado'];
                    }
                }
            }
        }
        //$sum_jbasico =
    }


    return $sum_jbasico;
}

// Get all ingresos 
function get_IR5_Ingresos($id_pdeclaracion, $id_trabajador) {

    $conceptos_afectos5ta = arrayConceptosAfectos_a('10');

    $dao_dconcepto = new DeclaracionDconceptoDao();
    $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion);

    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos5ta)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}

// Get all ingresos 
function get_SNP_Ingresos($id_pdeclaracion, $id_trabajador) {

    $conceptos_afectos = arrayConceptosAfectos_a('08');

    $dao_dconcepto = new DeclaracionDconceptoDao();
    $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion);

    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}

// Get all ingresos 
function get_AFP_Ingresos($id_pdeclaracion, $id_trabajador) {

    $conceptos_afectos = arrayConceptosAfectos_a('09');

    $dao_dconcepto = new DeclaracionDconceptoDao();
    $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion);

    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}

// Get all ingresos 
function get_ESSALUD_REGULAR_Ingresos($id_pdeclaracion, $id_trabajador) {

    $conceptos_afectos = arrayConceptosAfectos_a('01');

    $dao_dconcepto = new DeclaracionDconceptoDao();
    $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion);

    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}

/**
 *
 * @param type $cod_afectacion
 * @return type 
 * 
 *  Esto es variable segun 
 *  cod_afectacion:
 *  - 10 = Renta de quinta
 *  - 08 = SNP
 *  - 01 = Esalud Regular
 */
function arrayConceptosAfectos_a($cod_afectacion) {

//..............................................................................
    $dao_afecto = new PlameDetalleConceptoAfectacionDao();
    $data_afecto = $dao_afecto->conceptosAfecto_a($cod_afectacion);

    $conceptos_afectos = array();
    for ($x = 0; $x < count($data_afecto); $x++) {
        $conceptos_afectos[] = $data_afecto[$x]['cod_detalle_concepto'];
    }
//..............................................................................   
    return $conceptos_afectos;
}

//------------------------------------------------------------------------------

function listaPdeclaraciones() {
    $dao = new PlameDeclaracionDao();
    return $dao->listar($id_empleador_maestro, $anio);
}

function get_IR5_NumGratificaciones($periodo) {
    $num_mes = getFechaPatron($periodo, "m");

    $num = intval($num_mes);
    $rpta = null;

    if (($num > 0) && ($num < 7)) {
        $rpta = 2;
    } else if (($num >= 7) && ($num <= 12)) {
        $rpta = 1;
    } else {
        $rpta = "error critico";
    }
    return $rpta;
}

//Concepto 3012
function get_IR5_BonificacionExtraordinaria($periodo) {
    // Obtiene bonificacion de Febrero marz-abrl-may-jun....
}

function get_IR5_7Uit() {
    
}

//echo getNumGratificaciones("2012-06-01");
//getRMesesAnteriores("2012-05-01");
?>
