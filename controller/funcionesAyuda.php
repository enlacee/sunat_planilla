<?php
/*
//require_once '../util/funciones.php';
$data = array(
    array(
        'fecha_inicio' => '2013-01-08',
        'fecha_fin' => '2013-01-22'
    ),
    array(
        'fecha_inicio' => '2013-03-01',
        'fecha_fin' => '2013-03-15'
    )
);
$rpta = leerVacacionDetalle($data, '2013-03-01', '2013-03-01', '2013-03-31');
echoo($rpta);
 */
 
function leerVacacionDetalle($data, $periodo, $fecha_inicio, $fecha_fin) {
    //echo "RANGO DE FECHAS $fecha_inicio A $fecha_fin";
    $dia = 0;
    $fecha_lineal = null;
    for ($i = 0; $i < count($data); $i++) {
        $estado_inicio = (getFechaPatron($data[$i]['fecha_inicio'], "m") != getFechaPatron($periodo, "m")) ? false : true;
        $estado_fin = (getFechaPatron($data[$i]['fecha_fin'], "m") != getFechaPatron($periodo, "m")) ? false : true;

        if ($estado_inicio || $estado_fin) { // true pasa.
            //-----------------------------------------------------------
            if ($data[$i]['fecha_inicio'] > $fecha_inicio) {
                
            } else if ($data[$i]['fecha_inicio'] <= $fecha_inicio) {
                $data[$i]['fecha_inicio'] = $fecha_inicio;
            }
            if (is_null($data[$i]['fecha_fin'])) {
                $data[$i]['fecha_fin'] = $fecha_fin;
            } else if ($data[$i]['fecha_fin'] >= $fecha_fin) { //INSUE
                $data[$i]['fecha_fin'] = $fecha_fin;
            }
            //----------------------------------------------------------- 
            $rangoVaca = rangoDeFechas($data[$i]['fecha_inicio'], $data[$i]['fecha_fin'], "Y-m-d");
            //echoo($rangoVaca);
            //echo "\ncount =".count($rangoVaca);            
            if (is_array($rangoVaca)) {
                $dia = $dia + (count($rangoVaca));
                $fecha_lineal .= $data[$i]['fecha_inicio'] . "_" . $data[$i]['fecha_fin'] . ",";
            }
        }
    }
    return $arreglo = array('dia' => $dia, 'fecha_lineal' => $fecha_lineal);
}

//suma de prestamos, cuota
function sumaCuotasPrestamo($data_prestamo) {
    $sumaCuota = 0;
    $id_prestamo_cuota_pago = array();
    $prestamo_cuota_pago = array();

    for ($i = 0; $i < count($data_prestamo); $i++):
        //sumar las cuotas de varios Prestamos
        if (floatval($data_prestamo[$i]['monto']) > 0):
            $sumaCuota = $sumaCuota + floatval($data_prestamo[$i]['monto']);
            $id_prestamo_cuota_pago[] = $data_prestamo[$i]['id_prestamo_cutoa'];
            $prestamo_cuota_pago[] = $data_prestamo[$i]['monto'];
        endif;
    endfor;

    $data = array();
    $data['monto'] = $sumaCuota;
    $data['id_prestamo_cutoa'] = $id_prestamo_cuota_pago;
    //$data['monto_duplex'] = $prestamo_cuota_pago;
    return $data;
}

//==============================================================================
// registros por conceptos
//==============================================================================
// Buscar Concepto
function buscarConceptoPorConceptoXD(array $arreglo, $concepto) {
    $rpta = false;
    for ($i = 0; $i < count($arreglo); $i++) {
        if ($arreglo[$i]['cod_detalle_concepto'] == $concepto) {
            $rpta = $arreglo[$i];
            break;
        }
    }
    return $rpta;
}

// Buscar y sumar Concepto Vacacion
function buscarSumarConceptoVacacion(array $arreglo, $concepto, $montoSoles = 0) {
    $montoSoles = ($montoSoles > 0||  is_null($montoSoles)) ? $montoSoles : 0;
    $rpta = 0;
    if (count($arreglo) > 0) {
        for ($i = 0; $i < count($arreglo); $i++) {
            if ($arreglo[$i]['cod_detalle_concepto'] == $concepto) {
                $rpta = $arreglo[$i];
                break;
            }
        }
    }
    // sumar
    $monto_pagado = ($rpta['monto_pagado'] > 0) ? $rpta['monto_pagado'] : 0;
    $monto_devengado = ($rpta['monto_devengado'] > 0) ? $rpta['monto_devengado'] : 0;

    return ($monto_pagado + $monto_devengado + $montoSoles);
}

// Buscar y sumar Concepto Vacacion
function buscarSumarConceptoVacacion2(array $rpc, $concepto, array $rpcv) {

    // 01
    for ($i = 0; $i < count($rpc); $i++) {
        if ($rpc[$i]['cod_detalle_concepto'] == $concepto) {
            $r_rpc = $rpc[$i];
            break;
        }
    }//end for
    // sumar 1
    $r_rpc_monto_pagado = ($r_rpc['monto_pagado'] > 0) ? $r_rpc['monto_pagado'] : 0;
    $r_rpc_monto_devengado = ($r_rpc['monto_devengado'] > 0) ? $r_rpc['monto_devengado'] : 0;

    // 02
    for ($i = 0; $i < count($rpcv); $i++) {
        if ($rpcv[$i]['cod_detalle_concepto'] == $concepto) {
            $r_rpcv = $rpcv[$i];
            break;
        }
    }//end for 

    $r_rpcv_monto_pagado = ($r_rpcv['monto_pagado'] > 0) ? $r_rpcv['monto_pagado'] : 0;
    $r_rpcv_monto_devengado = ($r_rpcv['monto_devengado'] > 0) ? $r_rpcv['monto_devengado'] : 0;

    // 03
    $monto_pagado = $r_rpc_monto_pagado + $r_rpcv_monto_pagado;
    $monto_devengado = $r_rpc_monto_devengado + $r_rpcv_monto_devengado;

    return ($monto_pagado + $monto_devengado);
}

/**
 *
 * @param type $id
 * @param type $monto
 * @param type $horas No debe ser Mayor a dos
 * @return type 
 */
function concepto_0105($monto = 0, $afamiliar = 0, $horas = 0) {
    $sueldo_por_hora = sueldoMensualXHora(($monto + $afamiliar));
    $nuevo_sueldo_por_hora = $sueldo_por_hora * 1.25;
    $neto = $nuevo_sueldo_por_hora * $horas;    //number_format_2($number);    
    //..............................................
    $minutos = $horas * 60;
    $hour = 0;
    $min = 0;
    while ($minutos >= 60) {
        $hour = $hour + 1;
        $minutos = $minutos - 60;
    }
    $min = $minutos;
    //..............................................
    $arreglo = array(
        'concepto' => $neto,
        'hour' => $hour,
        'min' => $min
    );
    return $arreglo;
}

function concepto_0106($monto = 0, $afamiliar = 0, $horas = 0) {
    $sueldo_por_hora = sueldoMensualXHora(($monto + $afamiliar));
    $nuevo_sueldo_por_hora = $sueldo_por_hora * 1.35;
    $neto = $nuevo_sueldo_por_hora * $horas;
    //..............................................   
    $minutos = $horas * 60;
    $hour = 0;
    $min = 0;
    while ($minutos >= 60) {
        $hour = $hour + 1;
        $minutos = $minutos - 60;
    }
    $min = $minutos;
    //..............................................
    $arreglo = array(
        'concepto' => $neto,
        'hour' => $hour,
        'min' => $min
    );
    return $arreglo;
}

function concepto_0107($monto = 0, $afamiliar = 0, $dias = 0) {
    $sueldo_por_dia = sueldoMensualXDia(($monto + $afamiliar));
    $nuevo_sueldo_por_dia = $sueldo_por_dia * 2; //DOBLE SUELDO
    $neto = $nuevo_sueldo_por_dia * $dias;
    return $neto;
}

// REMUNERACIÓN DÍA DE DESCANSO Y FERIADOS (INCLUIDA LA DEL 1° DE MAYO)
// Se aplica cuando el 1 DE mayo ('dia del trabajador') cae domingo o
// o dia de trabajao para el empleado.
// En este caso se le pafara el dia al trabajador.... xq necesariamente es un dia
// de descanzo.
function concepto_0115($sueldo, $_0201, $bonif_riesgocaja, $movilidad, $num_dia) {
    $neto = ( sueldoMensualXDia(($sueldo + $_0201 + $bonif_riesgocaja + $movilidad)) ) * $num_dia;
    return $neto;
}

// 0118 = REMUNERACION VACACIONAL
function concepto_0118($id, $monto) {

    //__ 01 Listar Periodo
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id);
    $model->setMonto_devengado($monto);
    $model->setMonto_pagado($monto);
    $model->setCod_detalle_concepto(C118);

    $dao = new DeclaracionDconceptoDao();
    $rpta = $dao->registrar($model);

    return ($rpta > 0) ? true : false;
}

// Sueldo Basico
function concepto_0121($monto, $descuento = 0) {
    return ($monto - $descuento);
}

// ASIGNACION FAMILIAR
function concepto_0201() {
    //$CAL_AF = asignacionFamiliar();
    $CAL_AF = SB * (T_AF / 100);
    return $CAL_AF;
}

// BONIFICACION POR RIESGO DE CAJA.
function concepto_0304($monto) {
    return $monto;
}

//OK ADELANTO
function concepto_0701($adelanto) {
    return $adelanto;
}

function concepto_0703($monto) {// 10/09/2012
    return $monto;
}

// TARDANZAS : DESCUENTOS
function concepto_0704($monto, $_0201 = 0, $hora = 0) {
    $sueldo_x_hora = sueldoMensualXHora($monto + $_0201);
    $neto = $sueldo_x_hora * $hora;
    return $neto;
}

// 0705 INASISTENCIAS : 
function concepto_0705($monto, $_0201 = 0, $dias = 0) {
    $sueldo_x_dia = sueldoMensualXDia(($monto + $_0201));
    $neto_descontar = $sueldo_x_dia * $dias;
    return $neto_descontar;
}

// 0706 OTROS DESCUENTOS NO DEDUCIBLES A LA BASE IMPONIBLE
/*
  require_once '../dao/AbstractDao.php';
  require_once '../dao/PrestamoDao.php';
  require_once '../dao/PrestamoCuotaDao.php';
  require_once '../model/PrestamoCuota.php';
  require_once '../util/funciones.php';
  concepto_0706(1, 2, 1, '2012-10-01');
 */
function concepto_0706($id_trabajador, $id_pdeclaracion, $PERIODO) {

    $acumulador = 0;
    $prestamo_val = array();
    $ptf_val = 0;

    //Dao
    $dao_prestamo = new PrestamoDao();
    $dao_ptf = new ParatiFamiliaDao();

    // PRESTAMO - no intereza activo solo suma monto de fecha.
    $data_prestamo = $dao_prestamo->buscar_idTrabajador($id_trabajador, $PERIODO);
    if (count($data_prestamo) > 0) {
        $pcuota = sumaCuotasPrestamo($data_prestamo);
        $acumulador = $acumulador + $pcuota['monto'];
        // setear a pagado 
        for ($i = 0; $i < count($pcuota['id_prestamo_cutoa']); $i++):
            $obj_pc = new PrestamoCuota();
            $obj_pc->setId_prestamo_cutoa($pcuota['id_prestamo_cutoa'][$i]);
            $obj_pc->setFecha_pago(date("Y-m-d"));
            $obj_pc->setEstado(1);
            $prestamo_val[] = $obj_pc;
        endfor;
    }

    // PARA TI FAMILIA  - solo registrados en db
    $ptfamilia = $dao_ptf->buscar_idTrabajador($id_trabajador, $PERIODO);
    if (isset($ptfamilia['id_para_ti_familia'])) {
        $calculo_ptf = ($ptfamilia['valor']);
        $acumulador = $acumulador + $calculo_ptf;
        // new registro  de pago ptf
        $obj_pdt = new PtfPago();
        $obj_pdt->setId_para_ti_familia($ptfamilia['id_para_ti_familia']);
        $obj_pdt->setId_pdeclaracion($id_pdeclaracion);
        $obj_pdt->setFecha(date("Y-m-d"));
        $obj_pdt->setValor($calculo_ptf);
        //
        $ptf_val = $obj_pdt;
    }

    $arreglo = array(
        'concepto' => $acumulador,
        'prestamo_cuota' => $prestamo_val,
        'obj_ptf' => $ptf_val
    );
    return $arreglo;
}

// SNP [ONP = 02]
function concepto_0607($conceptos) {
    //====================================================   
    $all_ingreso = get_SNP_Ingresos($conceptos);
    //====================================================
    $CALC = (floatval($all_ingreso)) * (T_ONP / 100);
    return $CALC;
}

// AFP o SPP 
// 0601 = Comision afp porcentual
// 0606 = Prima de suguro AFP
// 0608 = SPP aportacion obligatoria
function concepto_AFP($cod_regimen_pensionario, $conceptos) {
    //==================================================== 
    //$all_ingreso = get_AFP_Ingresos($conceptos);
    $all_ingreso = get_AFP_IngresosPlanilla($conceptos);
    //====================================================  
    $afp = arrayAfp($cod_regimen_pensionario);
    $monto_tope = afpTope();

    $A_OBLIGATORIO = floatval($afp['aporte_obligatorio']);
    $COMISION = floatval($afp['comision']);
    $PRIMA_SEGURO = floatval($afp['prima_seguro']);

    // UNO = comision porcentual
    $_601 = (floatval($all_ingreso)) * ($COMISION / 100);
    // DOS prima de seguro
    $_606 = (floatval($all_ingreso)) * ($PRIMA_SEGURO / 100);
    // TRES = aporte obligatorio
    $_608 = (floatval($all_ingreso)) * ($A_OBLIGATORIO / 100);
    /*
     *  Conficion Parametro Tope. Monto maximo a pagar por all las
     *  afp segun el periodo  d/m/Y
     */
    $_608 = ($_608 > $monto_tope) ? $monto_tope : $_608;
    /*
      $arreglo = array(
      array(
      'cod_detalle_concepto' => C601,
      'monto_pagado' => $_601,
      'monto_devengado' => 0
      ),
      array(
      'cod_detalle_concepto' => C606,
      'monto_pagado' => $_606,
      'monto_devengado' => 0
      ),
      array(
      'cod_detalle_concepto' => C608,
      'monto_pagado' => $_608,
      'monto_devengado' => 0
      )
      ); */
    $arreglo = array(
        '0601' => $_601,
        '0606' => $_606,
        '0608' => $_608,
    );

    return $arreglo;
}

// 604 ESSALUD + VIDA
function concepto_0604() {
    return ESSALUD_MAS;
}

// 612 SNP ASEGURA TU PENSIÓN +
function concepto_0612() {
    return SNP_MAS;
}

// 804 ESSALUD trabajador
function concepto_0804($conceptos) {
    //==================================================== 
    $all_ingreso = get_ESSALUD_REGULAR_Ingresos($conceptos);
    //====================================================
    $CALC = floatval($all_ingreso) * (T_ESSALUD / 100);
    return $CALC;
}

function concepto_0909($monto) {
    return $monto;
}

?>
