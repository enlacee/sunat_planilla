<?php

$op = $_REQUEST["oper"];
if (/* $op */true) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    require_once '../controller/ConfConceptosController.php';

    // IDE CONFIGURACION  -- generarConfiguracion(periodo);    
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';

    // Adicional onp
    require_once '../dao/ConfAfpDao.php';
    require_once '../dao/ConfAfpTopeDao.php';
    // Conceptos estan afectos
    require_once '../dao/PlameDetalleConceptoAfectacionDao.php';

    // CONTROLLER CONFIGURACION
    require_once '../controller/ConfController.php';

    //DATA
    require_once '../dao/TrabajadorPdeclaracionDao.php';


    //vacacion
    require_once '../dao/VacacionDao.php';
    require_once '../dao/TrabajadorDao.php';
    require_once '../dao/TrabajadorVacacionDao.php';
    require_once '../dao/DeclaracionDConceptoVacacionDao.php';

    require_once '../model/TrabajadorVacacion.php';
    require_once '../model/DeclaracionDConceptoVacacion.php';




    //registro por concepto PREGUNTAR
    require_once '../dao/RegistroPorConceptoDao.php';
    //ZIP
    require_once '../util/zip/zipfile.inc.php';
}

if ($op == "vacacion") {
    $response = vacacion();
} else if ($op == "boleta_vacacion") {
    //boletaVacacacion();
}

function vacacion() {

    $periodo = $_REQUEST['periodo'];
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];

    generarConfiguracion($periodo);
    //$periodo_rel = $periodo; //periodo_relativo

    /*
      //::: VACACION
      concepto_0118($id_trabajador_pdeclaracion, $data_sum['sueldo_vacacion']);
      //registrar dias vacaciones
      echo"\n\n";
      echo"\Napunto de registrar el DIA VACACIONE TOTAL = " . $data_sum['dia_nosubsidiado'] . "\n";
      echo"\n\n";
      $obj_dianosub = new DiaNoSubsidiado();
      $obj_dianosub->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
      $obj_dianosub->setCantidad_dia($data_sum['dia_nosubsidiado']);
      $obj_dianosub->setCod_tipo_suspen_relacion_laboral(23);
      $obj_dianosub->setEstado(1); //no modificable
      //Add
      DiaNoSubsidiadoDao::anb_add($obj_dianosub);
     */

    // DATA
    $dao_trapdecla = new TrabajadorPdeclaracionDao();
    // OTHER
    $dao_v = new VacacionDao();
    $dao_tra = new TrabajadorDao();
    $dao_rpc = new RegistroPorConceptoDao();
    $dao_tra_va = new TrabajadorVacacionDao();
    //conceptos
    $dao_ddc_va = new DeclaracionDConceptoVacacionDao();

    $data_va = $dao_v->vacacionPeriodo2(ID_EMPLEADOR, $periodo);


    for ($i = 0; $i < count($data_va); $i++) {
        //$i = 0;
        echoo($data_va[$i]);

        $vacacion_inicio = $data_va[$i]['fecha_programada'];
        $vacacion_fin = $data_va[$i]['fecha_programada_fin'];

        // CONDICION MES 1
        if ($vacacion_inicio >= $periodo) {//MAYOR =
        //
        } else { // MENOR
            $vacacion_inicio = $periodo;
        }

        // CONDICION MES 2
        $mes = getMesInicioYfin($periodo);
        if ($vacacion_fin >= $mes['mes_fin']) {
            $vacacion_fin = $mes['mes_fin'];
        }

        echo "\n fecha_inicio = " . $vacacion_inicio;
        echo "\n fecha_fin = " . $vacacion_fin;
        // Preguntar si no  esta registrado
        $dias_vac = 0;
        $bandera = true;
        $dias_vac = numDiaVacacion($vacacion_inicio, $vacacion_fin);
        echo "\ndia_vacacion = " . $dias_vac;

        $bandera = $dao_tra_va->existeTrabajadorVacacion($id_pdeclaracion, $data_va[$i]['id_trabajador'], $vacacion_inicio);
        var_dump($bandera);
        echo "\n\nANTES DE ENYAT";


        if ($bandera) {
            //variables
            $arreglo_afps = array(21, 22, 23, 24);
            $data_conceptos = array();
            $sueldo_base = 0;
            $asig_familiar = 0;
            $bonif_riesgocaja = 0;
            $movilidad = 0;

            $retencion_j = 0;
            $essalud = 0;  // concepto_0804();
            //||------------------------------------------------------------------||
            $DATA_TRA = $dao_trapdecla->buscar_ID_trabajador($data_va[$i]['id_trabajador']);
            echoo($DATA_TRA);

            $sueldo_base = $DATA_TRA['monto_remuneracion']; //$dao_tra->buscar_what($data_va[$i]['id_trabajador'], 'monto_remuneracion');
            //||------------------------------------------------------------------||
            // C201 ASIGNACION FAMILIAR
            $data_val = array();
            $data_val = $dao_rpc->buscar_RPC_PorTrabajador($id_pdeclaracion, $data_va[$i]['id_trabajador'], C201);
            if (intval($data_val['valor']) == 1) {
                $asig_familiar = asignacionFamiliar();
                $asig_familiar = sueldoMensualXDia($asig_familiar) * $dias_vac;
            }

            // C304 = BONIFICACIÓN POR RIESGO DE CAJA        
            $data_val = array();
            $data_val = $dao_rpc->buscar_RPC_PorTrabajador($id_pdeclaracion, $data_va[$i]['id_trabajador'], C304);
            if (floatval($data_val['valor']) > 0) {
                $bonif_riesgocaja = $data_val['valor'];
                $bonif_riesgocaja = sueldoMensualXDia($bonif_riesgocaja) * $dias_vac;
            }

            // C909 MOVILIDAD
            $data_val = array();
            $data_val = $dao_rpc->buscar_RPC_PorTrabajador($id_pdeclaracion, $data_va[$i]['id_trabajador'], C909);
            if (intval($data_val['valor']) > 0) {
                $movilidad = $data_val['valor'];
                $movilidad = sueldoMensualXDia($movilidad) * $dias_vac;
            }


            // C703 RETENCION MANDADTO JUDICIAL
            $data_val = array();
            $data_val = $dao_rpc->buscar_RPC_PorTrabajador($id_pdeclaracion, $data_va[$i]['id_trabajador'], C703);
            if ($data_val['valor'] > 0) {
                $retencion_j = $data_val['valor'];
            }

            // C118 SUELDO VACACIONAL
            //$sueldo_vacacion = (sueldoMensualXDia(($sueldo_base + $asig_familiar)) * $dias_vac);  //214.0833333333333
            echo "... sueldo_base = " . $sueldo_base;
            $sueldo_vacacion = (sueldoMensualXDia($sueldo_base) * $dias_vac);  //214.0833333333333


            /* echo "\n---------------------------------\n";
              echoo($sueldo_base);
              echoo($asig_familiar);
              echoo($sueldo_vacacion);
              echoo($retencion_j);
              echo "\n---------------------------------\n";
             */

// ingresos
//        C118;
//        C201;
//        C703;
//        C804;
// descuento
//        C704;
//        C705;

            $data_conceptos = array(
                0 => array(
                    'concepto' => C118,
                    'monto' => number_format_2($sueldo_vacacion),
                ),
                1 => array(
                    'concepto' => C201,
                    'monto' => number_format_2($asig_familiar),
                ),
                2 => array(
                    'concepto' => C304,
                    'monto' => number_format_2($bonif_riesgocaja),
                ),
                3 => array(
                    'concepto' => C703,
                    'monto' => number_format_2($retencion_j),
                ),
                4 => array(
                    'concepto' => C909,
                    'monto' => number_format_2($movilidad),
                ),
            );


            // C804 ESSALUD
            $all_ingreso = conceptoAfectacion($data_conceptos, '01');  //essaludVacacion($data_trabajador);
            echo "\nEssalud valor es all_ingreso: " . $all_ingreso;
            //echoo($all_ingreso);

            $essalud = floatval($all_ingreso) * (T_ESSALUD / 100);
            $essalud = sueldoMensualXDia($essalud) * $dias_vac;

            echo '\nall_ingreso = ' . $all_ingreso;
            echo '\nT_ESSALUD = ' . T_ESSALUD;
            echo '\ndias_vac = ' . $dias_vac;
            echo '\nessalud = ' . $essalud;

            $data_conceptos[] = array(
                'concepto' => C804,
                'monto' => number_format_2($essalud)
            );






            // ONP  
            if ($DATA_TRA['cod_regimen_pensionario'] == '02') { //ONP
                $all_ingreso = conceptoAfectacion($data_conceptos, '08');
                $CALC = (floatval($all_ingreso)) * (T_ONP / 100);

                $data_conceptos[] = array(
                    'concepto' => C612,
                    'monto' => number_format_2($CALC)
                );
            } else if (in_array($DATA_TRA['cod_regimen_pensionario'], $arreglo_afps)) {

                $all_ingreso = conceptoAfectacion($data_conceptos, '09'); //AFP

                $dao_afp = new ConfAfpDao();
                $afp = $dao_afp->vigenteAfp($DATA_TRA['cod_regimen_pensionario'], $periodo);

                // Configuracion de Tope Afp 02/10/2012
                $dao_tafp = new ConfAfpTopeDao();
                $monto_tope = $dao_tafp->vigenteAux($periodo);

                //------------------------------------------------------------------

                $A_OBLIGATORIO = floatval($afp['aporte_obligatorio']);
                $COMISION = floatval($afp['comision']);
                $PRIMA_SEGURO = floatval($afp['prima_seguro']);

                // UNO = comision porcentual
                $_601 = number_format_2((floatval($all_ingreso)) * ($COMISION / 100));

                // DOS prima de seguro
                $_606 = number_format_2((floatval($all_ingreso)) * ($PRIMA_SEGURO / 100));

                // TRES = aporte obligatorio
                $_608 = number_format_2((floatval($all_ingreso)) * ($A_OBLIGATORIO / 100));

                $_608 = ($_608 > $monto_tope) ? $monto_tope : $_608;


                $data_conceptos[] = array(
                    'concepto' => C601,
                    'monto' => $_601
                );
                $data_conceptos[] = array(
                    'concepto' => C606,
                    'monto' => $_606
                );
                $data_conceptos[] = array(
                    'concepto' => C608,
                    'monto' => $_608
                );
            }

            //AFP
            //insert data 1
            $model = new TrabajadorVacacion();
            $model->setId_trabajador($data_va[$i]['id_trabajador']);
            $model->setId_pdeclaracion($id_pdeclaracion);
            $model->setFecha_inicio($vacacion_inicio);
            $model->setFecha_fin($vacacion_fin);
            $model->setNum_dia($dias_vac);
            $model->setFecha_creacion(date("Y-m-d"));
            $id = $dao_tra_va->add($model);

            // insert data 2
            //detalle       
            foreach ($data_conceptos as $key => $value) {
                $obj_ddv = new DeclaracionDConceptoVacacion();
                $obj_ddv->setId_trabajador_vacacion($id);
                $obj_ddv->setCod_detalle_concepto($value['concepto']);
                $obj_ddv->setMonto_devengado(0);
                $obj_ddv->setMonto_pagado($value['monto']);
                $dao_ddc_va->add($obj_ddv);
            }
        }
    }// END-IF
    echoo($data_conceptos);
}

function conceptoAfectacion($data_concepto, $cod_afecctacion) {
    //01 	ESSALUD SEGURO REGULAR TRABAJADOR.
    //02 	ESSALUD - CBSSP - SEG TRAB PESQUERO
    //03 	ESSALUD SEGURO AGRARIO / ACUICULTOR
    //04 	ESSALUD SCTR
    //05 	IMPUESTO EXTRAORD. DE SOLIDARIDAD
    //06 	FONDO DERECHOS SOCIALES DEL ARTISTA
    //07 	SENATI
    //08 	SISTEMA NACIONAL DE PENSIONES 19990.
    //09 	SISTEMA PRIVADO DE PENSIONES.
    //10 	RENTA 5TA CATEGORÍA RETENCIONES.
    //11 	ESSALUD SEGURO REGULAR PENSIONISTA
    //12 	CONTRIB. SOLIDARIA ASISTENCIA PREVIS
    //13 	APORTE AL FCJMMS - LEY 29741
    //..........................................................................
    $dao_afecto = new PlameDetalleConceptoAfectacionDao();
    $data_afecto = $dao_afecto->conceptosAfecto_a($cod_afecctacion);  //$cod_afecctacion   
    $conceptos_afectos = arrayId($data_afecto, 'cod_detalle_concepto');
    //echoo($conceptos_afectos);
    //echo "para esssaluddddddddddddddddddddddddddddddddddddd\n";
    //..........................................................................

    $sum = 0;
    for ($z = 0; $z < count($data_concepto); $z++) {
        if (in_array($data_concepto[$z]['concepto'], $conceptos_afectos)) {
            echo "\n\n   " . $data_concepto[$z]['concepto'];
            echo "\n\n   " . $data_concepto[$z]['monto'];

            $sum = $sum + $data_concepto[$z]['monto'];
        }
    }

    return $sum;
}

function numDiaVacacion($fecha_inicio, $fecha_fin) {

    $inicio = date("z", strtotime($fecha_inicio));
    $fin = date("z", strtotime($fecha_fin));

    $num_dias = ($fin - $inicio) + 1;
    return $num_dias;
}

//---- end vacacion

function boletaVacacacion(){
    
    //THIS NULL
    
}
