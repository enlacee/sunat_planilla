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

//------------------------------------------------------------------------------
// planilla inicio
function generarConfiguracion2($periodo) {

    if ( !$_SESSION['afectaciones'] || !$_SESSION['afp']||!$_SESSION['afp_tope']) {
        echo "\nCREO POR PRIMERA VEZ : generarConfiguracion2()";
        // Dao
        $dao_pa = new PlameAfectacionDao();
        $dao_afecto = new PlameDetalleConceptoAfectacionDao();

        // afectaciones
        $data_afect = $dao_pa->listar();

        // la afectacion (rtaquinta) se aplican a una lista de conceptos:
        // Lista de Conceptos que estan afectos (ejm: reta 5 = 0121,0201...)
        $afecctaciones = array();
        $a = 0;
        for ($i = 0; $i < count($data_afect); $i++) {
            $cod_afectacion = $data_afect[$i]['cod_afectacion'];
            if ($cod_afectacion == '01' || $cod_afectacion == '08' || $cod_afectacion == '09' || $cod_afectacion == '10') {
                $data_afecto = $dao_afecto->conceptosAfecto_a($cod_afectacion);
                $conceptos_afectos = array();
                for ($x = 0; $x < count($data_afecto); $x++) {
                    $conceptos_afectos[] = $data_afecto[$x]['cod_detalle_concepto'];
                }
                $afecctaciones[$a]['cod_afectacion'] = $cod_afectacion;
                $afecctaciones[$a]['conceptos'] = $conceptos_afectos;
                $a++;
            }
        }
        //----------------------------------------
        $dao_afp = new ConfAfpDao();
        $_arregloAfps = array(21, 22, 23, 24);
        $afp = array();
        for ($i = 0; $i < count($_arregloAfps); $i++) {
            $afp[$i]['cod_regimen_pensionario'] = $_arregloAfps[$i];
            $afp[$i]['data'] = $dao_afp->vigenteAfp($_arregloAfps[$i], $periodo);
        }
        //------------------------------------------
        $dao_tafp = new ConfAfpTopeDao();
        $afp_tope = $dao_tafp->vigenteAux($periodo);
        //------------------------------------------
        
        $_SESSION['afectaciones'] = $afecctaciones;
        $_SESSION['afp'] = $afp;
        $_SESSION['afp_tope'] = $afp_tope;
    } else {
        //echo "<BR>ELSE XQ YA EXISTE!!!";
    }
    //echoo($_SESSION);
}

// FUNCION ALIADA A generarConfiguracion2
function arrayConceptosAfectos_a($cod_afectacion) {// 10
    $arreglo = $_SESSION['afectaciones'];
    for ($i = 0; $i < count($arreglo); $i++) {
        if ($arreglo[$i]['cod_afectacion'] == $cod_afectacion) {
            return $arreglo[$i]['conceptos'];
        }
    }
}

function arrayAfp($cod_regimen_pensionario) {
    $arreglo = $_SESSION['afp'];
    for ($i = 0; $i < count($arreglo); $i++) {
        if ($arreglo[$i]['cod_regimen_pensionario'] == $cod_regimen_pensionario) {
            return $arreglo[$i]['data'];
        }
    }
}

function afpTope() {
    return $_SESSION['afp_tope'];
}

// planilla final
//------------------------------------------------------------------------------

// ojo funcion observada para vacacion 15 dia no funciona
/*function asignacionFamiliar() {
    $SB = SB;
    $CAL_AF = $SB * (T_AF / 100);
    return $CAL_AF;
}
*/
// eliminar Funcion
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

// funciones sin relacion de datos calculados por periodo
// init migrado
function sueldoMensualXHora($monto) {
    $valor = ($monto / DIA_BASE) / HORA_BASE;
    return $valor;
}

function sueldoMensualXDia($monto) {
    $valor = ($monto / DIA_BASE);
    return $valor;
}

// end migrado
//-----------------------------------------------------------------------------
//FUNCION AYUDA  getSumaTodosIngresosTrabajador
function arrayConceptosIngresos() {
    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    $conceptos = array('100', '200', '300', '400', '500', '900');
    $data_concepto = $dao->view_listarConcepto(ID_EMPLEADOR_MAESTRO, $conceptos);

    $concepto_ingresos = array();
    for ($x = 0; $x < count($data_concepto); $x++) {
        $concepto_ingresos[] = $data_concepto[$x]['cod_detalle_concepto'];
    }
    return $concepto_ingresos;
}

/**
 * SUMA DE TODOS LOS INGRESOS DEL TRABAJADOR
 * listado de todos los conceptos que se encuentran seleccionado por el
 * empleador Maestro
 */
function getSumaTodosIngresosTrabajador($id_trabajador, $id_pdeclaracion) {//fuck! no es muy util
    $dao_dconcepto = new DeclaracionDconceptoDao();
    $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion);
    $concepto_ingresos = arrayConceptosIngresos();

    $sum = 0.00;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $concepto_ingresos)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}

?>
