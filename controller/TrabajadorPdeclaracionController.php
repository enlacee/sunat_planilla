<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    require_once '../controller/ConfConceptosController.php';

    //Actualizar PLAME   
    require_once '../model/TrabajadorPdeclaracion.php';
    require_once '../dao/TrabajadorPdeclaracionDao.php';
    require_once '../dao/DeclaracionDconceptoDao.php';

    require_once '../model/DeclaracionDconcepto.php';

    // AFP
    require_once '../model/ConfAfp.php';
    require_once '../dao/ConfAfpDao.php';
    require_once '../controller/ConfAfpController.php';
    // IDE CONFIGURACION 
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';


    // POR UNICA VEZ UTILIZAMOS  librerias  calcularSegudaQuincena
    require_once '../controller/EtapaPagoController.php';
    //ETAPA PAGO
    require_once '../dao/EtapaPagoDao.php';
    require_once '../model/EtapaPago.php';

    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/PlameDao.php';
    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';

    //PAGO
    require_once '../dao/PagoDao.php';
    require_once '../model/Pago.php';

    //EPAGO TRABAJADOR
    require_once '../dao/PeriodoRemuneracionDao.php';

    require_once '../dao/PlameDetalleConceptoAfectacionDao.php';
    //mass ++ + 5ta essalud, onp ,afp
    require_once '../dao/RegistroPorConceptoDao.php';


    //
    //
    //CONFIGURACION DE 28 Y NAVIDAD
    require_once '../dao/VacacionDao.php';

    // reporte txt
    require_once '../dao/EstablecimientoDao.php';
    require_once '../dao/EmpresaCentroCostoDao.php';
    require_once '../dao/EstablecimientoDireccionDao.php';
    require_once '../dao/PersonaDireccionDao.php';
    require_once '../dao/DetalleRegimenPensionarioDao.php';
    require_once '../dao/DetallePeriodoLaboralDao.php';
    //reporte tabla
    //ZIP
    require_once '../util/zip/zipfile.inc.php';

    require_once '../dao/ConfAfpTopeDao.php';


    //`Prestamo Y Pago
    require_once '../dao/PrestamoDao.php';
    require_once '../model/Prestamo.php';
    require_once '../model/PrestamoCuota.php';

    require_once '../dao/PrestamoCuotaDao.php';

    //require_once '../model/Ppago.php';
    require_once '../dao/ParatiFamiliaDao.php';
    require_once '../model/PtfPago.php';
    require_once '../dao/PtfPagoDao.php';
    //require_once '../model/ParatiFamilia.php';
    // Renta de QUINTA
    require_once '../controller/IR5Controller.php';

    //configuracion sueldo basico ++
    require_once '../controller/ConfController.php';

    //vacacion
    require_once '../dao/DiaNoSubsidiadoDao.php';
    require_once '../model/DiaNoSubsidiado.php';

    require_once '../dao/DiaSubsidiadoDao.php';
    require_once '../model/DiaSubsidiado.php';

    //Exel AFP.
    require_once '../controller/EstructuraAfp.php';
    require_once '../dao/EstructuraAfpDao.php';

    // Escribir Exel 2003
    require_once '../util/Spreadsheet/Excel/Writer.php';
    /* Establecer configuraci�n regional al holand�s */
    require_once '../dao/PromedioHoraExtraDao.php';

    //vacacion
    require_once '../dao/VacacionDao.php';
    require_once '../model/TrabajadorVacacion.php';
    require_once '../dao/TrabajadorVacacionDao.php';
    require_once '../dao/VacacionDetalleDao.php';


    // new OK
    require_once '../dao/PagoQuincenaDao.php';
    require_once '../dao/TrabajadorDao.php';
    require_once '../dao/PlameAfectacionDao.php';

    //AYUDA
    require_once '../controller/funcionesAyuda.php';

    //consultar # de vacaciones
    require_once '../dao/DeclaracionDConceptoVacacionDao.php';

    setlocale(LC_ALL, 'es_Es');
}


$response = NULL;

if ($op == "generar_declaracion") {
    planillaMensualXD();
} else if ($op == "cargar_tabla_2") {

    $response = listar_trabajadorPdeclaracion();
} else if ($op == "grid_lineal") {

    $response = cargar_tabla_grid_lineal();
} else if ($op == "del-pdeclaracion") {
    eliminarPdeclaracion();
} else if ($op == "del") {

    // Primera Alternativa
    $response = elimarEnCascada_trabajador_en_mes();
} else if ($op == "recibo30") {

    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    // DAO
    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->buscar_ID($ID_PDECLARACION);
    $PERIODO = $data_pd['periodo'];

    $estado = generarConfiguracion($PERIODO);

    if ($estado == true):
        generarBoletaTxt($ID_PDECLARACION);
    else:
        echo "ERROR CRITICO PERIODO";
    endif;
} else if ($op == 'eliminar_data_mes') {
    $response = eliminarDatosMes();
} else if ($op == 'reporte_emp_01') {

    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    generar_reporte_empresa_01($ID_PDECLARACION);
} else if ($op == 'reporte_exel_afp') {
    //echoo($_REQUEST);
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = buscarPeriodo($ID_PDECLARACION);
    $ESTADO = generarConfiguracion($PERIODO);
    //Generar Reporte EXEL AFP
    if ($ESTADO) {
        //echo "entroo";
        generarExelAfp($ID_PDECLARACION, $PERIODO);
    }
} else if ($op == 'reporte_afp') {

    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = buscarPeriodo($ID_PDECLARACION);
    $ESTADO = generarConfiguracion($PERIODO);
    generarConfiguracion2($PERIODO);
    //Generar Reporte EXEL AFP
    if ($ESTADO) {
        generarReporteAfp($ID_PDECLARACION, $PERIODO);
    }
}

echo (!empty($response)) ? json_encode($response) : '';

function buscarPeriodo($id_pdeclaracion) {
    // DAO
    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->buscar_ID($id_pdeclaracion);
    return $data_pd['periodo'];
}

// NEW ANB

function planillaMensualXD() {
    
    /*
      $ID_PDECLARACION = 29;
      $PERIODO = '2013-01-01';
      generarConfiguracion($PERIODO);
      generarConfiguracion2($PERIODO);
      calcular_IR5_concepto_0605($ID_PDECLARACION,13,$PERIODO);
      return false;
     */
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];
    $anio = getFechaPatron($PERIODO, 'Y');
    $ids = $_REQUEST['ids'];   // ids trabajador 
    generarConfiguracion($PERIODO);
    generarConfiguracion2($PERIODO);
    // INCIO PROCESO
    
    $dao_tpd = new TrabajadorPdeclaracionDao();
    $dao_plame = new PlameDao();

    $dao_pq = new PagoQuincenaDao();
    $dao_tra = new TrabajadorDao();

    $fecha = getFechasDePago($PERIODO);
    $fecha_inicio = $fecha['second_weeks_mas1'];
    $fecha_fin = $fecha['last_day'];

    // Operacion (01)
    // listar trabajadores.
    $data_tra = $dao_plame->listarTrabajadoresPorPeriodo(ID_EMPLEADOR, $fecha['second_weeks_mas1'], $fecha['last_day']);

    // Operacion (01.1) FILTRO
    //ID seleccionados en el Grid    
    if (isset($ids)) {
        echo "<pre>[idsS]  Que Usted Selecciono en el Grid\n";
        print_r($ids);
        echo "</pre>";
        //------- filtro-------//
        $ids_tra = array();
        for ($i = 0; $i < count($ids); $i++) {
            for ($j = 0; $j < count($data_tra); $j++) {
                if ($ids[$i] == $data_tra[$j]['id_trabajador']) {
                    $ids_tra[] = $data_tra[$j];
                    break;
                }
            }
        }
        $data_tra = null;
        $data_tra = $ids_tra;
    }    
    
    
    

    // Operacion (02)
    // listar trabajador con vacacion
    $countDataTra = count($data_tra);
    //echo "\ncontador data_tra =".$countDataTra;
    $counterVacacion = 0;
    if ($countDataTra > 0) {

        for ($i = 0; $i < /*1*/$countDataTra; $i++) {
            // DATA QUINCENA 
            //$i = 12;
            //$i = 130;
            //$i=  intval(10);
            //echo "\necho   iiii = $i";
            //$i=1;
            //$i=20;
            //$i=25;
            $data_quincena = $dao_pq->listarPorPdeclaracionTrabajador($ID_PDECLARACION, $data_tra[$i]['id_trabajador']);
            $quincena_sueldoPorcentaje = ($data_quincena['sueldo_porcentaje'] > 0) ? $data_quincena['sueldo_porcentaje'] : 0;
            $quincena_sueldo = ($data_quincena['sueldo'] > 0) ? $data_quincena['sueldo'] : 0;
            $quincena_devengado = ($data_quincena['devengado'] > 0) ? $data_quincena['devengado'] : 0;
            $quincena_diaLaborado = ($data_quincena['dia_laborado'] > 0) ? $data_quincena['dia_laborado'] : 0;

            $plaboral = $dao_plame->obtenerPeriodoLaboral($data_tra[$i]['id_trabajador']);

            if (is_array($plaboral)) {
                //-----------------------------------------------------------
                if ($plaboral['fecha_inicio'] > $fecha['second_weeks_mas1']) {
                    
                } else if ($plaboral['fecha_inicio'] <= $fecha['second_weeks_mas1']) {
                    $plaboral['fecha_inicio'] = $fecha['second_weeks_mas1'];
                }

                if (is_null($plaboral['fecha_fin'])) {
                    $plaboral['fecha_fin'] = $fecha['last_day'];
                } else if ($plaboral['fecha_fin'] >= $fecha['last_day']) { //INSUE
                    $plaboral['fecha_fin'] = $fecha['last_day'];
                }
                //-----------------------------------------------------------   
                // **Calc dias no trabajados por Fecha.
                if (($fecha['second_weeks_mas1'] == $plaboral['fecha_inicio']) && ($fecha['last_day'] == $plaboral['fecha_fin'])) {
                    echo "\nHOLAAA dias completos";
                    //dias completos
                } else {
                    echo "\nHOLA dias imcompletos";
                    if ($plaboral['fecha_inicio'] > $fecha['second_weeks_mas1']) {
                        //dia16 -dia18
                        $dia_no_trabajado = $dia_no_trabajado + count(rangoDeFechas($fecha['second_weeks_mas1'], $plaboral['fecha_inicio'], 'd'));
                        //$dia_no_trabajado = $dia_no_trabajado + count(rangoDeFechas($b_fecha_inicio, $r_fecha_inicio,'d'));
                        $dia_no_trabajado--;
                    }
                    if ($plaboral['fecha_fin'] < $fecha['last_day']) {
                        $dia_no_trabajado = $dia_no_trabajado + count(rangoDeFechas($plaboral['fecha_fin'], $fecha['last_day'], 'd'));
                        //$dia_no_trabajado = $dia_no_trabajado + count(rangoDeFechas($r_fecha_fin, $b_fecha_fin,'d'));
                        $dia_no_trabajado--;
                    }
                }

                echo "\ndia_no_trabajado = $dia_no_trabajado";
                //****  Variables            
                $dia_laborado = ( (getFechaPatron($fecha['last_day'], "d")) >= 31 ) ? 16 : 15;
                $dia_vacacion_15 = 0;
                $dia_vacacion_mes = 0;                
                $data_trav = array();
                $data_ask = array();
                //--
                $SUELDO_CAL = 0;
                $DESC_DIA_31 = 0;                

                //
                $dia_laborado = $dia_laborado - $dia_no_trabajado;
                echo "\ndia_laborado = $dia_laborado";
                // **Calc dias no trabajados por Vacaciones.  


                $daov = new VacacionDao();
                $data_trav = $daov->trabajadorVacacion($data_tra[$i]['id_trabajador'], $anio);

                if (count($data_trav) > 0) {        // TOMA EN CUENTA TODO EL MES!   :D
                    $data_ask_mes = leerVacacionDetalle($data_trav, $PERIODO, $fecha['first_day'], $fecha['last_day']);
                    //REALMENTE TIENE VACACION EN ESTE MES! .
                    if ($data_ask_mes['dia'] > 0) { 
                        $dia_vacacion_mes = $data_ask_mes['dia'];                       
                        
                        $data_ask_15 = leerVacacionDetalle($data_trav, $PERIODO, $fecha['second_weeks_mas1'], $fecha['last_day']);                        
                        if($data_ask_15['dia']>0){
                           $dia_vacacion_15 = $data_ask_15['dia']; // dia no laborado del segundo periodo de 15 dias. 
                        }                                                
                        echo"\ndia_vacacion_mes (16-30) = " . $dia_vacacion_mes;
                        echo"\ndia_vacacion_15 (16-30) = " . $dia_vacacion_15;
                        echo "\n";
                    }
                }//EndIf_vacacion                
                $dia_laborado = $dia_laborado - $dia_vacacion_15;
                echo "\ndia_laborado = $dia_laborado";
                
                
                
            // **---------------------CALCULOS LOGIC----------------------------
            //    
            if ((getFechaPatron($fecha['last_day'], "d")) == 31) {
                $smpd = sueldoMensualXDia($data_tra[$i]['monto_remuneracion']);
                $DESC_DIA_31 = $smpd * 1;
            }

            if ($dia_laborado == 15 || $dia_laborado == 16) { // dias completos del mes trabajados. PAGA POR 15 DIAS                
                if ($quincena_sueldoPorcentaje > 0) { // CON PORCENTAJE
                    $percent = (100 - $quincena_sueldoPorcentaje);
                    $SUELDO_CAL = $data_tra[$i]['monto_remuneracion'] * ($percent / 100);
                } else { // = 0
                    $smpd = sueldoMensualXDia($data_tra[$i]['monto_remuneracion']);
                    $SUELDO_CAL = $smpd * 15;
                }
            } else {
                $smpd = sueldoMensualXDia($data_tra[$i]['monto_remuneracion']);
                $SUELDO_CAL = $smpd * $dia_laborado;
                echo "DIA LABORADO < 15 dias";
            }

            // SUMA 1ERA QUINCENA           
            $SUELDO_CAL = $SUELDO_CAL + ($quincena_sueldo + $quincena_devengado);// - $DESC_DIA_31;
            if($dia_vacacion_mes==30){
                
            }else if($dia_vacacion_mes>0 &&$dia_vacacion_mes<=29){
                $SUELDO_CAL = $SUELDO_CAL - $DESC_DIA_31;
            }
            
            // -------------- obj Trabajador Pdeclaracion --------------
            $model_tpd = new TrabajadorPdeclaracion();
            $model_tpd->setId_pdeclaracion($ID_PDECLARACION);
            $model_tpd->setId_trabajador($data_tra[$i]['id_trabajador']);
            $model_tpd->setDia_laborado(($quincena_diaLaborado + $dia_laborado));
            $model_tpd->setDia_total((count(arregloDiaMes($PERIODO))));    // dias del mes 
            $model_tpd->setOrdinario_hora(0);
            $model_tpd->setOrdinario_min(0);
            $model_tpd->setSobretiempo_hora(0);
            $model_tpd->setSobretiempo_min(0);
            $model_tpd->setFecha_creacion(date("Y-m-d H:i:s"));
            $model_tpd->setSueldo($SUELDO_CAL);
            echo "\nSUELDO QUINCENA + DEVENGADO QUINCENA = " . ($quincena_sueldo + $quincena_devengado);
            echo "\nDESCUENTO DESC_DIA_31 =" . $DESC_DIA_31;
            echo "\nSUELDO_CAL FINAL OK= $SUELDO_CAL";
            $model_tpd->setSueldo_base($data_tra[$i]['monto_remuneracion']);            

            //Registrar datos adicionales del Trabajador  
            $data_tra_adit = $dao_tra->buscarDataForPlanilla($data_tra[$i]['id_trabajador']);            
            $model_tpd->setId_empresa_centro_costo($data_tra_adit['id_empresa_centro_costo']);
            $model_tpd->setCod_tipo_trabajador($data_tra_adit['cod_tipo_trabajador']);
            $model_tpd->setCod_regimen_pensionario($data_tra_adit['cod_regimen_pensionario']);
            $model_tpd->setCod_regimen_aseguramiento_salud($data_tra_adit['cod_regimen_aseguramiento_salud']);
            $model_tpd->setCod_situacion($data_tra_adit['cod_situacion']);
            $model_tpd->setCod_ocupacion_p($data_tra_adit['cod_ocupacion_p']);

            //data_ayuda
            $data_ayuda = array(
                'quincena' => $quincena_sueldo, //$data_quincena['sueldo']                
                'dia_vacacion_15' => $dia_vacacion_15,
                'dia_vacacion_mes' => $dia_vacacion_mes,
            );
            echoo($model_tpd);
            echoo($data_ayuda);
            conceptoPorConceptoXD($model_tpd, $data_ayuda, $PERIODO);
            $counterVacacion++;
                
            }
        }//ENDFOR
    }
    
    
    echo "\nNum procesados es : ".$counterVacacion;

}

function conceptoPorConceptoXD($obj, $data_ayuda, $PERIODO) {
 
    $ID_PDECLARACION = $obj->getId_pdeclaracion();
    $pporcentaje = 1;
    $datarpc = array();
    $datarpcv = array();     
    
    
    $dao_rpc = new RegistroPorConceptoDao(); 
    $datarpc = $dao_rpc->buscar_RPC_PorTrabajador2($ID_PDECLARACION, $obj->getId_trabajador());
    //==========================================================================
    if ($data_ayuda['dia_vacacion_mes']>0) {
        $daotv = new TrabajadorVacacionDao();
        $daotvc = new DeclaracionDConceptoVacacionDao();
        $datarpcv = $daotvc->listarTrabajadorPorDeclaracion($obj->getId_trabajador(), $ID_PDECLARACION);

        $datatv = $daotv->listarTv($ID_PDECLARACION, $obj->getId_trabajador());        
        if ($datatv['proceso_porcentaje'] > 0) {
            $pporcentaje = (100 - $datatv['proceso_porcentaje']) / 100;
        }
    }
    //==========================================================================
    echo "\nPORCENTAJE ((100-100)/100) = " . $pporcentaje;
    //Variables locales
    $_arregloAfps = array(21, 22, 23, 24);
    $_asigFamiliar = 0;
    $_adelanto = 0;
    $_sueldoBasico = 0;
    $_r5ta = 0;
    $_essaludMasVida = 0;
    $_aseguraPensionMas = 0;
    $_trabajoDiaFeriado = 0;
    $_bonifRiesgoCaja = 0;
    $_dsctoMandatoJudicial = 0;
    $_tardanza = 0;
    $_movilidad = 0;
    $_trabajoDiaFeriadoMayo = 0;
    $_onp = 0;
    $_essalud = 0;  // concepto_0804();
    // CONCEPTO : ASIGNACION FAMILIAR

    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C201);    
    if (is_array($datarpc_val)) {
        if (intval($datarpc_val['valor']) == 1) {
            $_asigFamiliar = (concepto_0201() * $pporcentaje);
        }
    }
    unset($datarpc_val);
    echo "\n_asigFamiliarr = $_asigFamiliar";
    // CONCEPTO : ADELANTO
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C701);
    if (is_array($datarpc_val)) {
        if (intval($datarpc_val['valor']) > 0) {
            $_adelanto = concepto_0701($data_ayuda['quincena']);
        }
    }
    unset($datarpc_val);


    if ($obj->getSueldo() > 0) {
        // CONCEPTO : INASISTENCIAS   
        $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C705);
        if (intval($datarpc_val['valor']) > 0) {
            $_inasistencias = concepto_0705($obj->getSueldo_base(), $_asigFamiliar, $datarpc_val['valor']); // OK SULEDO BASE!

            $obj_dianosub = new DiaNoSubsidiado();
            $obj_dianosub->setId_trabajador_pdeclaracion('');
            $obj_dianosub->setCantidad_dia($datarpc_val['valor']);
            $obj_dianosub->setCod_tipo_suspen_relacion_laboral('07');
            $obj->setDia_laborado(($obj->getDia_laborado() - $datarpc_val['valor']));
            //Add
            //DiaNoSubsidiadoDao::anb_add($obj_dianosub);                        
            $arreglo_0705 = array(//OJO SOLO PARA REGISTRAR OBJ
                'concepto' => $_inasistencias,
                'objdianosub' => $obj_dianosub
            );
        }
        // CONCEPTO : SUELDO BASICO   
        ECHO "\nmodel_tpd->setSueldo(SUELDO_CAL)=".$obj->getSueldo();
        $_sueldoBasico = concepto_0121($obj->getSueldo(), $_inasistencias); //ok
        echo "\n_inasistencias = ".$_inasistencias;
        echo "\n_sueldoBasico = ".$_sueldoBasico;
        unset($datarpc_val);
    }


    //ECHO " <<<< 0706 == CONCEPTO EXCLUSIVO DE EMPRESA  PRESTAMO >>> ";
    // CONCEPTO : OTROS DESCUENTOS NO DEDUCIBLES A LA BASE IMPONIBLE PRESTAMO
    $arreglo_0706 = concepto_0706($obj->getId_trabajador(), $ID_PDECLARACION, $PERIODO);
    $arreglo_0706['concepto'] = $arreglo_0706['concepto'] * $pporcentaje;

    //----------------- fijo no mover varia el calculo-------------------
    // CONCEPTO : ESSALUD_MAS VIDA    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C604);
    if (intval($datarpc_val['valor']) == 1) {
        $_essaludMasVida = concepto_0604() * $pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : ASEGURA PENSION_MAS     
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C612);
    if (intval($datarpc_val['valor']) == 1) {
        $_aseguraPensionMas = concepto_0612() * $pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 25%        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C105);
    if (floatval($datarpc_val['valor']) > 0) {
        $arreglo_0105 = concepto_0105($obj->getSueldo_base(), $_asigFamiliar, $datarpc_val['valor']);
        //full
        $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + $arreglo_0105['hour']));
        $obj->setSobretiempo_min(($obj->getSobretiempo_min() + $arreglo_0105['min']));
    }
    unset($datarpc_val);

    // CONCEPTO : TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 35%    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C106);
    if (floatval($datarpc_val['valor']) > 0) {
        $arreglo_0106 = concepto_0106($obj->getSueldo_base(), $_asigFamiliar, $datarpc_val['valor']);
        //full
        $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + $arreglo_0106['hour']));
        $obj->setSobretiempo_min(($obj->getSobretiempo_min() + $arreglo_0106['min']));
    }
    unset($datarpc_val);

    // CONCEPTO : TRABAJO EN DÍA FERIADO O DÍA DE DESCANSO        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C107); //ok
    if (intval($datarpc_val['valor']) > 0) {
        $_trabajoDiaFeriado = concepto_0107($obj->getSueldo_base(), $_asigFamiliar, $datarpc_val['valor']);

        $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + ($datarpc_val['valor'] * HORA_BASE)));
    }
    unset($datarpc_val);

    // CONCEPTO : REMUNERACION VACACIONAL
    // CONCEPTO : BONIFICACIÓN POR RIESGO DE CAJA
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C304);
    if (floatval($datarpc_val['valor']) > 0) {
        $_bonifRiesgoCaja = concepto_0304($datarpc_val['valor']) * $pporcentaje;
    }
    unset($datarpc_val);


    // CONCEPTO : DESCUENTO AUTORIZADO U ORDENADO POR MANDATO JUDICIAL    *****************************************************************    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C703);
    if (floatval($datarpc_val['valor']) > 0) {
        $_dsctoMandatoJudicial = concepto_0703($datarpc_val['valor']) * $pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : TARDANZAS        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C704);
    if (floatval($datarpc_val['valor']) > 0) {
        $_tardanza = concepto_0704($obj->getSueldo_base(), $_asigFamiliar, $datarpc_val['valor']);
    }
    unset($datarpc_val);


    // CONCEPTO : MOVILIDAD SUPEDITADA A ASISTENCIA Y QUE CUBRE SÓLO EL TRASLADO        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C909); //ok
    if (floatval($datarpc_val['valor']) > 0) {
        $_movilidad = concepto_0909($datarpc_val['valor']) * $pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : REMUNERACIÓN DIA DE DESCANSO Y FERIADOS (INCLUIDA LA DEL 1° DE MAYO)
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C115);
    if (intval($datarpc_val['valor']) > 1) {
        $num_dia = $datarpc_val['valor'];
        $_trabajoDiaFeriadoMayo = concepto_0115($obj->getSueldo_base(), $_asigFamiliar, $_bonifRiesgoCaja, $_movilidad, $num_dia);

        $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + ($num_dia * HORA_BASE)));
    }
    unset($datarpc_val);


    // CONCEPTO : RENTA DE QUINTA CATEGORIA 0605
    // USAR RENTA DE 5TA DESPUES  DE OPERACION !!!! es MAS EFICIENTE!!!
    // xq ka base de datos tiene que estar llena.
    //$_r5ta = calcular_IR5_concepto_0605($ID_PDECLARACION, $obj->getId_trabajador(),$PERIODO);
    //|##############################################################################
    //|                            Init Cargar Conceptos
    // HERE SUMAR ACUMULADO rpc vacion sumar here add Y LUEGO renta de quinta.!!
//buscarSumarConceptoVacacion
    echo "\n_asigFamiliar =  $_asigFamiliar";
    $conceptos = array(
        array(
            'cod_detalle_concepto' => C201,
            'monto_pagado' => $_asigFamiliar,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C701,
            'monto_pagado' => $_adelanto,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C121,
            'monto_pagado' => $_sueldoBasico,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C706, //OTROS DESCUENTOS NO DEDUCIBLES DE LA BASE IMPONIBLE
            'monto_pagado' => ($arreglo_0706['concepto']),
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C604,
            'monto_pagado' => $_essaludMasVida,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C612,
            'monto_pagado' => $_aseguraPensionMas,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C105,
            'monto_pagado' => $arreglo_0105['concepto'], //full
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C106,
            'monto_pagado' => $arreglo_0106['concepto'], //full
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C107,
            'monto_pagado' => $_trabajoDiaFeriado, //full
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C304,
            'monto_pagado' => $_bonifRiesgoCaja,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C703,
            'monto_pagado' => $_dsctoMandatoJudicial,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C704,
            'monto_pagado' => $_tardanza,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C909,
            'monto_pagado' => $_movilidad,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C115,
            'monto_pagado' => $_trabajoDiaFeriadoMayo,
            'monto_devengado' => 0
        )
    );

    //concento renta 5 
//  Concepto suma [1]  buscarSumarConceptoVacacion REFOR PARA DUPLICAR RAPID..! :D       OK!! delete low
    echo "\nprint ";
    //echoo($datarpcv);
    echo "\nprint ";
    echo "\n_asigFamiliar = $_asigFamiliar";

    $conceptosSum = array(
        array(
            'cod_detalle_concepto' => C201,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C201, $_asigFamiliar),
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C701,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C701, $_adelanto),
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C121, //sueldo basico
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C121, $_sueldoBasico),
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C706, //OTROS DESCUENTOS NO DEDUCIBLES DE LA BASE IMPONIBLE
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C706, ($arreglo_0706['concepto'])),
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C604,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C604, $_essaludMasVida),
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C612,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, c6012, $_aseguraPensionMas),
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C105,
            'monto_pagado' => $arreglo_0105['concepto'], //(HORAS EXTRAS) 25%
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C106,
            'monto_pagado' => $arreglo_0106['concepto'], //(HORAS EXTRAS) 35%
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C107,
            'monto_pagado' => $_trabajoDiaFeriado, //TRABAJDO EN FERIADO U DESCANZO
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C304,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C304, $_bonifRiesgoCaja), //$_bonifRiesgoCaja,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C703,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C703, $_dsctoMandatoJudicial), //$_dsctoMandatoJudicial,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C704,
            'monto_pagado' => $_tardanza, //$_tardanza,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C909,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C909, $_movilidad), //$_movilidad,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C115,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C703, $_trabajoDiaFeriadoMayo), //$_trabajoDiaFeriadoMayo, // 1° DE MAYO)
            'monto_devengado' => 0
        )
    );


//  Concepto suma [2]-----------------------------------------------------------
    /*
    $conceptosSum2 = array(
        array(
            'cod_detalle_concepto' => C605,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C605, $_r5ta),
            'monto_devengado' => 0
        ),
        array(//ONP
            'cod_detalle_concepto' => C607,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C607, $_onp),
            'monto_devengado' => 0
        ),
        array(//AFP
            'cod_detalle_concepto' => C601,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C601, $_601),
            'monto_devengado' => 0
        ),
        array(//AFP
            'cod_detalle_concepto' => C606,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C606, $_606),
            'monto_devengado' => 0
        ),
        array(//AFP
            'cod_detalle_concepto' => C608,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C608, $_608),
            'monto_devengado' => 0
        ),
        array(//AFP
            'cod_detalle_concepto' => C804,
            'monto_pagado' => buscarSumarConceptoVacacion($datarpcv, C804, $_608),
            'monto_devengado' => 0
        ),
    ); 
    $conceptosSum = array_merge($conceptosSum, $conceptosSum2);
*/

    echo "\nCONCEPTOS";
    echoo($conceptos);
    echo "\n****************************○2\n";
    echo "\nCONCEPTOSSUMAA";
    echoo($conceptosSum);
    
    

    //|                            End Cargar Conceptos
    //|##############################################################################
    // CONCEPTO : RENTA DE QUINTA 
    $ingresos5ta = get_IR5_Ingresos($ID_PDECLARACION, $obj->getId_trabajador(), $conceptosSum);
    if ($ingresos5ta > 1400) { // 2012-2013  promedio para optimizar        
        $_r5ta = calcular_IR5_concepto_0605($ID_PDECLARACION, $obj->getId_trabajador(), $PERIODO, $conceptosSum);
        $_r5ta = $_r5ta;
        //add
        $vacacion_605 = buscarSumarConceptoVacacion($datarpcv, C605, 0);
        $conceptosSum[] = array('cod_detalle_concepto' => C605, 'monto_pagado' => ($_r5ta+$vacacion_605), 'monto_devengado' => 0);
    }

    // CONCEPTO : ONP , AFP  
    if ($obj->getCod_regimen_pensionario() == '02') { //ONP
        $_onp = concepto_0607($conceptos);
        //add
        $vacacion_607 = buscarSumarConceptoVacacion($datarpcv, C607, 0);
        //echo "\nONP FINAL ES:onp =".$_onp;
        //echo "\nvacacion_607 = ".$vacacion_607;        
        $conceptosSum[] = array('cod_detalle_concepto' => C607, 'monto_pagado' => ($_onp+$vacacion_607), 'monto_devengado' => 0);
    } else if (in_array($obj->getCod_regimen_pensionario(), $_arregloAfps)) {
        $arreglo_afp = concepto_AFP($obj->getCod_regimen_pensionario(), $conceptos); // 3 CONCEPTOS        
        //$conceptos = array_merge($conceptos, $arreglo_afp); 
        $_601 = $arreglo_afp['0601'];
        $vacacion_601 = buscarSumarConceptoVacacion($datarpcv, C601, 0);
        echo "\n_601 = ".$_601;
        echo "\nvacacion_601 = ".$vacacion_601;
        $_606 = $arreglo_afp['0606'];
        $vacacion_606 = buscarSumarConceptoVacacion($datarpcv, C606, 0);
        $_608 = $arreglo_afp['0608'];
        $vacacion_608 = buscarSumarConceptoVacacion($datarpcv, C608, 0);
        $conceptosSum[] = array('cod_detalle_concepto' => C601, 'monto_pagado' => ($_601+$vacacion_601), 'monto_devengado' => 0);
        $conceptosSum[] = array('cod_detalle_concepto' => C606, 'monto_pagado' => ($_606+$vacacion_606), 'monto_devengado' => 0);
        $conceptosSum[] = array('cod_detalle_concepto' => C608, 'monto_pagado' => ($_608+$vacacion_608), 'monto_devengado' => 0);
    }

    // CONCEPTO : ESSALUD
    if ($obj->getCod_regimen_aseguramiento_salud() == '00') {
        //echo "\nINICIOOOOOOOOOOOOOOOOOOOOOOOOOO PLANILLA";
        $_essalud = concepto_0804($conceptos);
        //echo "\nFINALLLLLLLLLLLLLLLLLLLLLLLLLLL PLANILLA";
        //add
        //ECHO "\nPORCENTAJE ES EN ESSSALUDDDD = PORCENTAJE = $pporcentaje";
        //ECHO "\nESSALUD eS = _essalud = ".$_essalud;        
        $vacacion_804 = buscarSumarConceptoVacacion($datarpcv, C804, 0);
        $conceptosSum[] = array('cod_detalle_concepto' => C804, 'monto_pagado' => ($_essalud+$vacacion_804), 'monto_devengado' => 0);
    }



    echo "\n****************************○2\n";
    echo "\nCONCEPTOS SUMADOS DESPUESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS";
    echoo($conceptosSum);    









    $obj->setOrdinario_hora(($obj->getOrdinario_hora() + ($obj->getDia_laborado() * HORA_BASE)));
    //==========================================================================
    // --------------------- Registrar Data -------------------------
    //==========================================================================
//    echo "<br>\n";
//    echo "<hr>";
//    echoo($obj);
//    echo "<br>\n";
//    echo "<hr>";
//    echoo($conceptos);

    $dao_tpd = new TrabajadorPdeclaracionDao();
    $dao_ddc = new DeclaracionDconceptoDao();
    
    $id = $dao_tpd->existe($ID_PDECLARACION,  $obj->getId_trabajador());
    if(is_null($id)){
        $id = $dao_tpd->registrar($obj);
    }else{        
        $obj->setId_trabajador_pdeclaracion($id);
        $obj->setFecha_actualizacion(date("Y-m-d"));
        $dao_tpd->update($obj);
    }
    
    // dia no subsidiado [01]
    if ($arreglo_0705['objdianosub']) {
        echo "\nDATA DIA NO SUBSIADIADO";
        echoo($arreglo_0705);
        //echo "\n";
        //echo "DIA NO SUBSIDIADO";
        //echo "\n";
        //echoo($obj_dianosub);

        $obj_dianosub = $arreglo_0705['objdianosub'];
        $obj_dianosub->setId_trabajador_pdeclaracion($id);
        DiaNoSubsidiadoDao::anb_add($obj_dianosub);
    }
    // dia no subsidiado [02]
    // vacacion
    //registrar Conceptos Empresa
    /*    if ($arreglo_0706['prestamo_cuota'] || $arreglo_0706['obj_ptf']) {
      $prestamo_cuota = $arreglo_0706['prestamo_cuota'];
      //Prestamo Cuota
      if ($prestamo_cuota) {
      $dao_pc = new PrestamoCuotaDao();
      for ($i = 0; $i < count($prestamo_cuota); $i++) {
      $obj_pc = $prestamo_cuota[$i];
      $dao_pc->pagarPrestamoCuota($obj_pc);
      }
      }
      //Para ti familia
      $ptf = $arreglo_0706['obj_ptf'];
      if ($ptf) {
      $dao_ptf_pago = new PtfPagoDao();
      $dao_ptf_pago->add($ptf);
      }
      }
     */
    //registrar declaraciones de conceptos.
    
    // .................. LIMPIANDO CONCEPTOS  . .............................    
    $dao_ddc->limpiar($id);
    
    
    for ($i = 0; $i < count($conceptosSum); $i++) {
        if ($conceptosSum[$i]['monto_pagado'] > 0) {
            $obj_ddc = new DeclaracionDconcepto();
            $obj_ddc->setId_trabajador_pdeclaracion($id);
            $obj_ddc->setCod_detalle_concepto($conceptosSum[$i]['cod_detalle_concepto']);
            $obj_ddc->setMonto_pagado(number_format_2($conceptosSum[$i]['monto_pagado']));
            $obj_ddc->setMonto_devengado($conceptosSum[$i]['monto_devengado']);
            $dao_ddc->registrar($obj_ddc);
        }
    }
    echo "\nPLANILLA MENSUAL ";
    //echoo($conceptos);

    //conceptos renta
    echo "\ CONCEPTO SUMAA ::: planilla con renta 5ta";
    //echoo($conceptosSum);
}

//-----------------------------------------------------------------------------//
//.............................................................................//
//-----------------------------------------------------------------------------//

/**
 *  Utilizado para calcular de Enero a Junio = 
 *  Utilizado para Gratificacion de 28 de Julio
 * ---
 * @param type $id_trabajador
 * @param type $periodo 
 */
function getSumaTodosIngresosDeDiasFeriadosTrabajador($id_trabajador, $periodo, $break = null) {
    $mes = date("m", strtotime($periodo));
    $anio = date("Y", strtotime($periodo));
    $dia = date("d", strtotime($periodo));

    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $anio);

//..............................................................................
    // 0107 = TRABAJO EN DÍA FERIADO O DÍA DE DESCANSO
    // 0115 = REMUNERACIÓN DÍA DE DESCANSO Y FERIADOS (INCLUIDA LA DEL 1° DE MAYO)
    $conceptos_afectos = array('0107', '0115');
    //$conceptos_afectos = arrayConceptosAfectos_5ta();
//.............................................................................. 

    $dao_dconcepto = new DeclaracionDconceptoDao();
    $sum_jbasico = 0.00;
    $contador_estado = 0; //solo si tiene 3 dias feriados devuelve EL valor SINO = 0; En el estos meses Imposible 2 juntos Feriados JAJA.
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
                        $contador_estado = $contador_estado + 1;
                        //echo "contador_estado=" . $contador_estado . "   ===== " . $data_dconcepto[$z]['cod_detalle_concepto'];
                        $sum_jbasico = $sum_jbasico + $data_dconcepto[$z]['monto_pagado'];
                    }
                }
            }
        }


        if ($i == $break) { // TERMINA DE RECORRER EL 7 = JULIO Y SALIR DEL BUCLE.
            //echo "break en 7ete";
            break;
        }
    }

    //echo "SUM_JBASICO = ".$sum_jbasico;
    $rpta = null;
    if ($contador_estado >= 3) {
        $rpta = $sum_jbasico;
    } else {
        $rpta = 0.00;
    }

    return $rpta;
//--------------------
}

// 0406	= GRATIFICACIONES DE FIESTAS PATRIAS Y NAVIDAD – LEY 29351
function concepto_28_Navidad_LEY_29351($id, $ID_PDECLARACION, $id_trabajador, $PERIODO) { //0406
    $dao_pdeclaracion = new PlameDeclaracionDao();

    $data = array();
    $data['periodo'] = $PERIODO;

    $anio_periodo = getFechaPatron($data['periodo'], "Y");
    $mes_periodo = getFechaPatron($data['periodo'], "m");
    //..........................................................................
    $daoPlame = new PlameDao();
    $trabajador = array();
    $trabajador = $daoPlame->listarTrabajadorPeriodo(ID_EMPLEADOR_MAESTRO, $id_trabajador);


//VARIABLES LOCALES FOR gratificacion ini fin
    $dao_rpc = new RegistroPorConceptoDao();
    $dao_phe = new PromedioHoraExtraDao();

    /**
     * C O N F I G U R A R   Gratificacion Proporcional. 
     * 
     * 
     */
    //$trabajador['fecha_fin'] = '2012-06-30';
    //$trabajador['fecha_inicio'] = '2012-01-05';
    /**
     * C O N F I G U R A R   Gratificacion Proporcional. 
     * 
     * 
     * 
     */
    if ($mes_periodo == '07') {

        $fecha = getRangoJulio($anio_periodo);
        $fj_inicio = $fecha['inicio'];
        $fj_fin = $fecha['fin'];

        $periodo_junio = $anio_periodo . "-06-01"; // MES DE JUNIO = 2012-06-01
        $data_pdeclaracion = $dao_pdeclaracion->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $periodo_junio);

        // OBTENER Los Ingresos solo Mes = JUNIO.         
        $monto_ingresos_junio = getSumaTodosIngresosTrabajador($id_trabajador, $data_pdeclaracion['id_pdeclaracion']);

        // Suma De Periodos        
        $monto_dias_feriados = getSumaTodosIngresosDeDiasFeriadosTrabajador($id_trabajador, $data['periodo']);


        //|--------------------------------------------------------------------
        //| f = suma(ingreos) + suma(feriados)
        //|--------------------------------------------------------------------
        // GRATIFICACIONES DE FIESTAS PATRIAS – LEY 29351
        if (($trabajador['fecha_inicio'] <= $fj_inicio ) && (($trabajador['fecha_fin'] >= $fj_fin) || ($trabajador['fecha_fin'] == null) )) {
            echo "\n\nINSERT -> 0406 = GRATIFICACION COMPLETA 28 julio y NAVIDAD\n\n";
            // falta el  9 %
            $model = new DeclaracionDconcepto();
            $model->setId_trabajador_pdeclaracion($id);
            //$model->setMonto_devengado($monto_ingresos_junio);
            $model->setMonto_pagado($monto_ingresos_junio);
            $model->setCod_detalle_concepto(C406);
            //dao
            $dao = new DeclaracionDconceptoDao();
            $dao->registrar($model);



            echo "\nINSERT ->0312=BONIFICACIÓN EXTRAORDINARIA TEMPORAL – LEY 29351\n";

            echo "\nmonto_dias_feriados " . $monto_dias_feriados;
            // 6 = 6 meses que pasaron
            // 9% = tasa extraordinaria.   
            // 001 = SI dias feriados >=3 : se le suma todos los dias feriados trabajados /6
            $bextraordinario = ($monto_dias_feriados / 6);
            $bextraordinario = ($bextraordinario + $monto_ingresos_junio) * (T_ESSALUD / 100); /* 0.09; */

            $model = new DeclaracionDconcepto();
            $model->setId_trabajador_pdeclaracion($id);
            //$model->setMonto_devengado($bextraordinario);
            $model->setMonto_pagado($bextraordinario);
            $model->setCod_detalle_concepto(C312);
            //dao
            $dao = new DeclaracionDconceptoDao();
            $dao->registrar($model);
        } else if ($trabajador['fecha_inicio'] > $fj_inicio) {


            // $monto_ingresos_junio ->>Gratificacion no es = a ALl ingresos de junio
            //SINO es el proporcional ->gratificacion proporcional q se le calcula

            echo "\n\n0407=GRATIFICACIONES PROPORCIONAL – LEY 29351\n\n";

            //GRATIFICACION PROPORCIONAL
            $mesx = getFechaPatron($trabajador['fecha_inicio'], "m");
            $diax = getFechaPatron($trabajador['fecha_inicio'], "d");

            $mes_q_falta = null;

            if (intval($diax) == 1) { //DIA EMPEZO 01-mes-anio = OK NORMAL
                $mes_q_falta = numMesQueFalta($mesx, getFechaPatron($fj_fin, "m"));
            } else if (intval($diax) > 1) {
                //SE CALCULA DEL SIGUIENTE MES.           
                //asume que empezo diaz despues. .:. + 1 siguiente Mes
                $mesx = intval($mesx) + 1;

                //++++++++++++++++++++ ARTILUGIO  NEED++++++++++++++++++++++++//
                //Existe un error cuando el mes es  6=06;
                if ($mesx == (getFechaPatron($fj_fin, "m"))) {
                    $mes_q_falta = 1;
                } else {
                    $mes_q_falta = numMesQueFalta($mesx, getFechaPatron($fj_fin, "m"));
                }
                //++++++++++++++++++++ ARTILUGIO  NEED++++++++++++++++++++++++//
            }


            $bextraordinario = 0.00;
            $bextraordinario = ($monto_dias_feriados / 6) * $mes_q_falta; //---------------------------------->mes q falta OK
            $bextraordinario = ($bextraordinario + $monto_ingresos_junio) * (T_ESSALUD / 100); // 0.09; // ESSALUD = 9%
            //X1 -------------------------- 01 ---------------------------------
            //$model->setMonto_devengado($monto_ingresos_junio);
            //Calculo proporcional ->> a los meses q entroooo
            echo "\nUSTED EMPEZO A TRABAJAR DESPUES DE  $fj_inicio \n";
            echo "\n\nmes que faltaaaaaaaaaaaa " . $mes_q_falta;
            echo "\n\n MONTO DE SOLO JUNIO afectos" . $monto_ingresos_junio;

            $nuevo = ($monto_ingresos_junio / 6) * $mes_q_falta;
            //
            echo "\nformula ";
            echo "\n ($monto_ingresos_junio/6) * $mes_q_falta  = " . $nuevo;
            echo "\n nuevo = " . $nuevo;
            //
            $model = new DeclaracionDconcepto();
            $model->setId_trabajador_pdeclaracion($id);
            $model->setMonto_pagado($nuevo); /* $monto_ingresos_junio */
            $model->setCod_detalle_concepto(C407);
            //$dao
            $dao = new DeclaracionDconceptoDao();
            $dao->registrar($model);

            echo "INSERT ->0312=BONIFICACIÓN EXTRAORDINARIA TEMPORAL – LEY 29351\n";
            // BONIFIACACION EXTRAORDINARIAA ..... esaluda 9% de su sueldo
            //X2 $monto_ingresos_junio--------- 02 -----------------------------
            $model = new DeclaracionDconcepto();
            $model->setId_trabajador_pdeclaracion($id);
            //$model->setMonto_devengado($bextraordinario);            
            $model->setMonto_pagado($bextraordinario);
            $model->setCod_detalle_concepto(C312);
            //dao
            $dao = new DeclaracionDconceptoDao();
            $dao->registrar($model);
        }



        //END GRATIFICACION DE JULIO !!!!!!!!!!
    } else if ($mes_periodo == '12') {
        /*
          //------------------------------------------------------------------------------
          echo "\nENTRO GRATIFICACION DICIEMBRE EN \n";

          $fecha = getRangoDiciembre($anio_periodo); //RPTA Enero a Noviembre
          $fj_inicio = $fecha['inicio'];
          $fj_fin = $fecha['fin'];


          $periodo_diciembre = $anio_periodo . "-11-01"; // MES DE NOVIEMBRE = 2012-11-01
          $data_pdeclaracion = $dao_pdeclaracion->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $periodo_diciembre);

          //------------------------------------------------------------------------------
          // OBTENER Los Ingresos solo Mes = NOVIEMBRE.  ------------------------------------------->ok dato de noviembre
          $monto_ingresos_noviembre = getSumaTodosIngresosTrabajador($id_trabajador, $data_pdeclaracion['id_pdeclaracion']);

          // Suma De Periodos        function (id_trabajador,mes=12,break=7)
          $monto_dias_feriados = getSumaTodosIngresosDeDiasFeriadosTrabajador($id_trabajador, $data['periodo'], 7);


          //|--------------------------------------------------------------------
          //| f = suma(ingreos) + suma(feriados)
          //|--------------------------------------------------------------------
          // GRATIFICACIONES DE FIESTAS PATRIAS – LEY 29351  -->ABAJO OK
          echo "\n !ENTRO EN CONDICION DE Diciembre.... \n";
          echo "trabajador :: " . $trabajador['fecha_inicio'];
          echo "\n....................................................\n";


          echo "\nFECHAS Rango diciembrePARAMETRO\n";
          echo "<pre>";
          print_r($fecha);
          echo "</pre>";



          if (($trabajador['fecha_inicio'] <= $fj_inicio ) && (($trabajador['fecha_fin'] >= $fj_fin) || ($trabajador['fecha_fin'] == null) )) {
          echo "\n\nINSERT -> 0406 = GRATIFICACION COMPLETA NAVIDAD\n\n";
          // falta el  9 %
          ECHO "\nALL INGRESOS DE NOVIEMBRE = " . $monto_ingresos_noviembre;
          echo"\n TRABAJADOR se Encuentra para GRATIFICACACION DE DICIEMBRE";
          echo "\n $fj_inicio  al $fj_fin o null";



          $model = new DeclaracionDconcepto();
          $model->setId_trabajador_pdeclaracion($id);
          $model->setMonto_pagado($monto_ingresos_noviembre);
          $model->setCod_detalle_concepto(C406);
          //dao
          $dao = new DeclaracionDconceptoDao();
          $dao->registrar($model);


          echo "\nINSERT ->0312=BONIFICACIÓN EXTRAORDINARIA TEMPORAL – LEY 29351\n";
          // 6 = 6 meses que pasaron
          // 9% = tasa extraordinaria.
          // 001 = SI dias feriados >=3 : se le suma todos los dias feriados trabajados /6
          $bextraordinario = ($monto_dias_feriados / 6);
          $bextraordinario = ($bextraordinario + $monto_ingresos_noviembre) * (T_ESSALUD / 100);

          echo "\nbonificacion extraordiraria 9% DE ESALUD es  =" . $bextraordinario;


          $model = new DeclaracionDconcepto();
          $model->setId_trabajador_pdeclaracion($id);
          $model->setMonto_pagado($bextraordinario);
          $model->setCod_detalle_concepto(C312);
          //dao
          $dao = new DeclaracionDconceptoDao();
          $dao->registrar($model);
          } else if ($trabajador['fecha_inicio'] > $fj_inicio) {

          echo "\n\n0407=GRATIFICACIONES PROPORCIONAL – LEY 29351\n\n";

          //GRATIFICACION PROPORCIONAL
          $mesx = getFechaPatron($trabajador['fecha_inicio'], "m");
          $diax = getFechaPatron($trabajador['fecha_inicio'], "d");

          $mes_q_falta = null;
          echo "\nanyes de pasaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaar   fecha fin partes = $fj_fin \n";



          if (intval($diax) == 1) { //DIA EMPEZO 01-mes-anio = OK NORMAL
          $mes_q_falta = numMesQueFalta($mesx, getFechaPatron($fj_fin, "m"));
          echo "\nEntro dia  = 1\n.";
          echo "\nmes_q_falta = $mes_q_falta\n";
          } else if (intval($diax) > 1) {
          //SE CALCULA DEL SIGUIENTE MES.
          //asume que empezo diaz despues. .:. + 1 siguiente Mes
          $mesx = intval($mesx) + 1;

          //++++++++++++++++++++ ARTILUGIO  NEED++++++++++++++++++++++++//
          //Existe un error cuando el mes es  11=11;
          if ($mesx == (getFechaPatron($fj_fin, "m"))) {
          $mes_q_falta = 1;
          } else {
          $mes_q_falta = numMesQueFalta($mesx, getFechaPatron($fj_fin, "m"));
          }
          //++++++++++++++++++++ ARTILUGIO  NEED++++++++++++++++++++++++//
          }

          echo "\nMES QUE FALTA = " . $mes_q_falta;
          var_dump($mes_q_falta);
          echo "\n\n\n";

          echo "\nmonto_dias_feriados " . $monto_dias_feriados;


          $bextraordinario = 0.00;
          $bextraordinario = ($monto_dias_feriados / 6) * $mes_q_falta;
          $bextraordinario = ($bextraordinario + $monto_ingresos_noviembre) * (T_ESSALUD / 100);

          $nuevo = ($monto_ingresos_noviembre / 6) * $mes_q_falta;
          //X1
          $model = new DeclaracionDconcepto();
          $model->setId_trabajador_pdeclaracion($id);
          $model->setMonto_pagado($nuevo);
          $model->setCod_detalle_concepto(C407);
          //$daodao
          $dao = new DeclaracionDconceptoDao();
          $dao->registrar($model);

          echo "\nINSERT ->0312=BONIFICACIÓN EXTRAORDINARIA TEMPORAL – LEY 29351\n";

          //X2 $monto_ingresos_junio
          $model = new DeclaracionDconcepto();
          $model->setId_trabajador_pdeclaracion($id);
          //$model->setMonto_devengado($bextraordinario);
          $model->setMonto_pagado($bextraordinario);
          $model->setCod_detalle_concepto(C312);
          //dao
          $dao = new DeclaracionDconceptoDao();
          $dao->registrar($model);
          }
          //------------------------------------------------------------------------------
         */

        // -- gratificacion ini

        $num_meses = contarNumerosMesesCompletos($trabajador['fecha_inicio'], $PERIODO);


        //SUELDO
        $_01_sb = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $id_trabajador, C121);
        $_01_sb['valor'] = (floatval($_01_sb['valor']) > 0) ? floatval($_01_sb['valor']) : 0;
        //PROMEDIO HORAS EXTRAS
        $_02_phe = $dao_phe->buscar_RPC2_PorTrabajador($ID_PDECLARACION, $id_trabajador);
        $_02_phe = (floatval($_02_phe) > 0) ? floatval($_02_phe) : 0;


        echo "\n" . $trabajador['fecha_inicio'] . "num_mes = $num_meses";

        echo "\nnum_meses = $num_meses";



        echo "\nPROMEDIO HORAS EXTRA = $_02_phe";

        ECHO "\n % T_ESSALUD = " . T_ESSALUD;

        $grati = 0;
        $essalud = 0;
        $total = 0;

        if ($num_meses != 0) {


            // variable 1    
            $grati = ($_01_sb['valor'] / 6 ) * $num_meses;

            echo "\nVARIABLE 1 grati = $grati";

            // variable 2
            //$_02_phe;
            echo "\nVARIABLE 2 _02_phe = $_02_phe";

            // variable 3
            $essalud = ($grati + $_02_phe) * (T_ESSALUD / 100);
            echo "\nVARIABLE 3 essalud = $essalud";
            //total
            $total = $grati + $_02_phe + $essalud;


            echo "\n\n\nGRATIFICACION ES: " . $total;

            //---------------------------------------------------------------------
            // GRATIFICACION
            $model = new DeclaracionDconcepto();
            $model->setId_trabajador_pdeclaracion($id);
            $model->setMonto_devengado(number_format_2($grati));
            $model->setMonto_pagado(number_format_2($grati));
            $model->setCod_detalle_concepto(C406);

            $dao = new DeclaracionDconceptoDao();
            $dao->registrar($model);

            // 0107 TRABAJO EN DIA FERIADO O DÍA DE DESCANSO     
            $model = new DeclaracionDconcepto();
            $model->setId_trabajador_pdeclaracion($id);
            $model->setMonto_devengado(number_format_2($_02_phe));
            $model->setMonto_pagado(number_format_2($_02_phe));
            $model->setCod_detalle_concepto(C107);

            //$dao = new DeclaracionDconceptoDao();
            $dao->registrar($model);

            // BONIFICACION EXTRAORDINARIA -- 0312 -- essalud   
            $model = new DeclaracionDconcepto();
            $model->setId_trabajador_pdeclaracion($id);
            $model->setMonto_devengado(number_format_2($essalud));
            $model->setMonto_pagado(number_format_2($essalud));
            $model->setCod_detalle_concepto(C312);

            //$dao = new DeclaracionDconceptoDao();
            $dao->registrar($model);
            //------------------------------------------------------------------
        } elseif ($num_meses == 0) {
            
        }




        // -- gratificacion fin
    } else {
        echo "    OTROS MESES !!  SIN GRATIFICACION  ";
    }
}

/*
  require_once '../util/funciones.php';
  $fecha_inicio = '2010-12-02';
  $fecha_fin = '2012-12-29';
  contarNumerosMesesCompletos($fecha_inicio,$fecha_fin);
 */

function contarNumerosMesesCompletos($fecha_inicio, $fecha_fin) {


    $inicio_dia = intval(getFechaPatron($fecha_inicio, 'd'));

    $inicio_mes = intval(getFechaPatron($fecha_inicio, 'm'));
    $fin_mes = intval(getFechaPatron($fecha_fin, 'm'));

    $num_mes = ($fin_mes - $inicio_mes) + 1;

    //condicion 01

    if ($inicio_dia > 1):
        $num_mes = $num_mes - 1;
    endif;


    //condicion 02
    if (getFechaPatron($fecha_inicio, 'Y') < getFechaPatron($fecha_fin, 'Y')) {
        $num_mes = 6;
    } else {



        if ($num_mes >= 6):
            echo "MESES MAS DE 6 MESE O =" . $num_mes;
            $num_mes = 6;
        //GRATIFICACION MORMAL
        elseif ($num_mes > 0 && $num_mes < 6): // tienen trabajador 5 meses o menos  
            echo "MENOS DE 6 MESES  $num_mes";
        //GRATIFICACION PROPORCIONAL
        else:
            ECHO "0 meses o negativo  $num_mes";
            $num_mes = 0;
        //SIN GRATIFICACION
        endif;
    }

    echoo($num_mes);
    return $num_mes;
}

function listar_trabajadorPdeclaracion() {

    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

    // variable Integridad de datos
    $dao_pd = new PlameDeclaracionDao();
    $data_pdecla = $dao_pd->buscar_ID($ID_PDECLARACION);
    //echoo($data_pdecla);



    $dao = new TrabajadorPdeclaracionDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    $WHERE = "";

    if (isset($_GET['searchField']) && ($_GET['searchString'] != null)) {

        $operadores["eq"] = "=";
        $operadores["ne"] = "<>";
        $operadores["lt"] = "<";
        $operadores["le"] = "<=";
        $operadores["gt"] = ">";
        $operadores["ge"] = ">=";
        $operadores["cn"] = "LIKE";
        if ($_GET['searchOper'] == "cn")
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . " '%" . $_GET['searchString'] . "%' ";
        else
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . "'" . $_GET['searchString'] . "'";
    }


    if (!$sidx)
        $sidx = 1;

    $count = $dao->listarCount($ID_PDECLARACION, $WHERE); //count($lista);
    // $count = $count['numfilas'];
    if ($count > 0) {
        $total_pages = ceil($count / $limit); //CONTEO DE PAGINAS QUE HAY
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit; // do not put $limit*($page - 1)
    //valida
    if ($start < 0)
        $start = 0;

// CONTRUYENDO un JSON

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    $lista = array();
    $lista = $dao->listar($ID_PDECLARACION, $WHERE, $start, $limit, $sidx, $sord);
    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
//print_r($lista);

    foreach ($lista as $rec) {

        $param = $rec["id_trabajador_pdeclaracion"];

        $_01 = $rec['id_trabajador'];
        $_02 = $rec['cod_tipo_documento'];
        $_03 = $rec['num_documento'];
        $_04 = $rec['apellido_paterno'];
        $_05 = $rec['apellido_materno'];
        $_06 = $rec['nombres'];
        $_07 = $rec['dia_laborado'];
        $_08 = $rec['sueldo'];
        // $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_pago.php?id_etapa_pago=" . $param . "&id_pdeclaracion=" . $_00 . "','#CapaContenedorFormulario')";

        $js = "javascript:cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/edit_trabajador.php?id_trabajador_pdeclaracion=" . $param . "&id_trabajador=" . $_01 . "','#detalle_declaracion_trabajador')";

        if ($data_pdecla['estado'] == '0') {
            $js2 = null;
        } else {
            $js2 = "javascript:eliminarTrabajadorPdeclaracion('" . $param . "',$_01)";
            $js2_html = ' <span  title="Editar"   >
		<a href="' . $js2 . '" class="divEliminar" ></a>
		</span>';
        }

        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar"   >
		<a href="' . $js . '" class="divEditar" ></a>
		</span>' . $js2_html . '
                    
		</div>';

        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            //$_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            $_08,
            $opciones
        );
        $i++;
    }
    /*
      echo "<pre>";
      print_r($response);
      echo "</pre>"; */
    return $response;
}

// GRID SIN PIE 
function cargar_tabla_grid_lineal() {
    $ID = $_REQUEST['id'];

    $dao = new TrabajadorPdeclaracionDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    $WHERE = "";

    if (isset($_GET['searchField']) && ($_GET['searchString'] != null)) {

        $operadores["eq"] = "=";
        $operadores["ne"] = "<>";
        $operadores["lt"] = "<";
        $operadores["le"] = "<=";
        $operadores["gt"] = ">";
        $operadores["ge"] = ">=";
        $operadores["cn"] = "LIKE";
        if ($_GET['searchOper'] == "cn")
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . " '%" . $_GET['searchString'] . "%' ";
        else
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . "'" . $_GET['searchString'] . "'";
    }


    if (!$sidx)
        $sidx = 1;

    $lista = array();
    $lista = $dao->buscar_ID_GRID_LINEAL($ID);

    $count = count($lista);

    // $count = $count['numfilas'];
    if ($count > 0) {
        $total_pages = ceil($count / $limit); //CONTEO DE PAGINAS QUE HAY
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit; // do not put $limit*($page - 1)
    //valida
    if ($start < 0)
        $start = 0;

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        //return $response;
    }
//print_r($lista);


    foreach ($lista as $rec) {
        $param = $rec["id_trabajador_pdeclaracion"];
        $dia_total = $rec['dia_total'];


        /*
          $dao1 = new PdiaSubsidiadoDao();
          $dia_subsidiado = $dao1->busacar_IdPago($param,"SUMA");

          $dao2 =new PdiaNoSubsidiadoDao();
          $dia_NOsubsidiado = $dao2->buscar_IdPago($param,"SUMA");


          $dia_laborado_calc = $dia_total - ($dia_subsidiado +$dia_NOsubsidiado);
         */
        //$_00 = $rec['id_trabajador'];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $dia_laborado_calc;
        $_07 = $rec['sueldo']; //INGRESOS
        $_08 = $rec['descuento']; //$rec['descuento']; 
        $_09 = $rec['sueldo_neto']; //$rec['valor_neto'];
        $_10 = $rec['estado'];

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param . "&id_trabajador=" . $_00 . "','#detalle_declaracion_trabajador')";

        // $js2 = "javascript:eliminarPersona('" . $param . "')";		
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
		</div>';

        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            $_08,
            $_09,
            $_10
                //$opciones*/
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;
}

function eliminar_trabajadorPdeclaracion() {//?
    $id = $_REQUEST['id'];
    if ($id) {
        $dao = new TrabajadorPdeclaracionDao();
        return $dao->eliminar($id);
    }
}

//|---------------------------------------------------------------------------//
//| REPORTES TXT
//|---------------------------------------------------------------------------//

function generarBoletaTxt($id_pdeclaracion) {
//---------------------------------------------------
// Variables secundarios para generar Reporte en txt
    $master_est = null;
    $master_cc = null;

    if ($_REQUEST['todo'] == "todo") {
        $cubo_est = "todo";
    }

    $id_est = $_REQUEST['id_establecimientos'];
    $id_cc = $_REQUEST['cboCentroCosto']; // OJOO SI NO SE HA SELECCIONADO C.C.

    $master_est = (!is_null($id_est)) ? $id_est : '';
    $master_cc = (!is_null($id_cc)) ? $id_cc : '';


    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($id_pdeclaracion);

    $nombre_mes = getNameMonth(getFechaPatron($data_pd['periodo'], "m"));
    $anio = getFechaPatron($data_pd['periodo'], "Y");

    $file_name = '01.txt'; //NAME_COMERCIAL . '-BOLETA PAGO.txt';
    $file_name2 = '02.txt'; //NAME_COMERCIAL . '-MENSUAL.txt';


    $BREAK = chr(13) . chr(10);
    $BREAK2 = chr(13) . chr(10) . chr(13) . chr(10);
    $LINEA = str_repeat('-', 80);
    $FORMATO_0 = chr(27) . '@' . chr(27) . 'C!';
    $FORMATO = chr(18) . chr(27) . "P";
//..............................................................................
// Inicio Exel
//..............................................................................
    $fpx = fopen($file_name2, 'w');
    $fp = fopen($file_name, 'w');

    //fwrite($fp, $FORMATO_0);
    fwrite($fpx, $FORMATO);
    //fwrite($fpx, $BREAK);
    // paso 01 Listar ESTABLECIMIENTOS del Emplearo 'Empresa'
    $dao_est = new EstablecimientoDao();
    $est = array();
    $est = $dao_est->listar_Ids_Establecimientos(ID_EMPLEADOR);
    $contador_break = 0;

    // paso 02 listar CENTROS DE COSTO del establecimento.    
    if (count($est) > 0) {

        //DAO
        $dao_cc = new EmpresaCentroCostoDao();
        $dao_pago = new TrabajadorPdeclaracionDao(); //[OK]
        $dao_estd = new EstablecimientoDireccionDao();
        $dao_rp = new DetalleRegimenPensionarioDao(); //[OK]
        $dao_pdireccion = new PersonaDireccionDao(); //[OK]
        $dao_dpl = new DetallePeriodoLaboralDao(); //[OK]
        // -------- Variables globales --------//        
        $SUM_TOTAL_CC = array();
        $SUM_TOTAL_EST = array();

        for ($i = 0; $i < count($est); $i++) { // ESTABLECIMIENTO
            //fwrite($fp, $BREAK2);
            //fwrite($fp, "Conteo de eSTABLECIMNETO = i = $i");
            //fwrite($fp, $BREAK2);
            $bandera_1 = false;
            if ($est[$i]['id_establecimiento'] == $master_est) {
                $bandera_1 = true;
            } else if ($cubo_est == "todo") {
                $bandera_1 = true;
            }

            if ($bandera_1) {
                //$contador_break = $contador_break + 1;
                // paso 02 Establecimiento direccion Reniec
                $data_est_direc = $dao_estd->buscarEstablecimientoDireccionReniec($est[$i]['id_establecimiento']);


                // paso 03 Centro de costo ' lista los trabajadores por' 
                $ecc = $dao_cc->listar_Ids_EmpresaCentroCosto($est[$i]['id_establecimiento']);

                //fwrite($fp, $BREAK2);
                //fwrite($fp, "NUM DE establecimiento y cuantos CENTROS  COSTOS TIENEN   =".count($ecc));                 
                //fwrite($fp, $BREAK2);

                for ($j = 0; $j < count($ecc); $j++) {

                    //fwrite($fp, $BREAK2);
                    //fwrite($fp, "entra a for j = $j ");


                    $bandera_2 = false;
                    if ($ecc[$j]['id_empresa_centro_costo'] == $master_cc) {
                        $bandera_2 = true;
                    } else if ($cubo_est == "todo") {
                        $bandera_2 = true;
                    } else if ($master_cc == 0) {
                        $bandera_2 = true;
                    }

                    if ($bandera_2) {
                        fwrite($fp, $FORMATO_0);
                        // LISTA DE TRABAJADORES
                        $data_tra = array();
                        $data_tra = $dao_pago->listar_2($id_pdeclaracion, $est[$i]['id_establecimiento'], $ecc[$j]['id_empresa_centro_costo']);


                        if (count($data_tra) > 0) {

                            $SUM_TOTAL_CC[$i][$j]['establecimiento'] = $data_est_direc['ubigeo_distrito'];
                            $SUM_TOTAL_CC[$i][$j]['centro_costo'] = strtoupper($ecc[$j]['descripcion']);
                            $SUM_TOTAL_CC[$i][$j]['monto'] = 0;

                            // .......................Inicio Cabecera  $fpx ........ 
                            fwrite($fpx, NAME_EMPRESA);

                            fwrite($fpx, str_pad("FECHA : ", 56, " ", STR_PAD_LEFT));
                            fwrite($fpx, str_pad(date("d/m/Y"), 11, " ", STR_PAD_LEFT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("PAGINA :", 69, " ", STR_PAD_LEFT));
                            $contador_break++;
                            fwrite($fpx, str_pad($contador_break, 11, " ", STR_PAD_LEFT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("MENSUAL", 80, " ", STR_PAD_BOTH));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("PLANILLA DEL MES DE " . strtoupper($nombre_mes) . " DEL " . $anio, 80, " ", STR_PAD_BOTH));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, "LOCALIDAD : " . $data_est_direc['ubigeo_distrito']);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, "CENTRO DE COSTO : " . strtoupper($ecc[$j]['descripcion']));
                            fwrite($fpx, $BREAK);
                            //$worksheet->write($row, $col, "##################################################");
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("N ", 4, " ", STR_PAD_LEFT));
                            fwrite($fpx, str_pad("DNI", 12, " ", STR_PAD_RIGHT));
                            fwrite($fpx, str_pad("APELLIDOS Y NOMBRES", 40, " ", STR_PAD_RIGHT));
                            fwrite($fpx, str_pad("IMPORTE", 9, " ", STR_PAD_RIGHT));
                            fwrite($fpx, str_pad("FIRMA", 15, " ", STR_PAD_RIGHT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            // .......................final Cabecera  $fpx ........                        
                            //$contador_break = 0;
                            $num_trabajador = 0;
                            for ($k = 0; $k < count($data_tra); $k++) {
                                //fwrite($fp,$FORMATO);
                                fwrite($fp, str_pad("BOLETA DE PAGO", 136, " ", STR_PAD_BOTH));
                                fwrite($fp, $BREAK);
                                fwrite($fp, str_pad("D.S. 020-2008-TR DEL 17-01-2008", 136, " ", STR_PAD_BOTH));
                                fwrite($fp, $BREAK);

                                fwrite($fp, NAME_EMPRESA);
                                fwrite($fp, $BREAK);


                                $direccion = $data_est_direc['ubigeo_nombre_via'] . " " . $data_est_direc['nombre_via'];
                                $direccion .=" " . $data_est_direc['numero_via'] . " - " . $data_est_direc['ubigeo_distrito'];

                                fwrite($fp, str_pad($direccion, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("CODIGO: " . $data_tra[$k]['num_documento'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("DNI: " . $data_tra[$k]['num_documento'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);

                                $nombre_c = $data_tra[$k]['apellido_paterno'] . " " . $data_tra[$k]['apellido_materno'] . " " . $data_tra[$k]['nombres'];
                                fwrite($fp, str_pad("R.U.C. " . RUC, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("NOMBRE Y APELLIDOS: " . $nombre_c, 88, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);

                                // Buscar fecha inicio
                                $data_fech_inicio = $dao_dpl->buscarDetallePeriodoLaboral_2($data_tra[$k]['id_trabajador']);

                                fwrite($fp, str_pad("Reo.Pat. 2010033861100000", 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("CARNET DE ESSALUD : -", 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("FECHA INGRESO : " . getFechaPatron($data_fech_inicio, "d/m/Y"), 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);

                                //......................................................                                                
                                $afp_carnet_value = null;
                                $afp_nombre_value = null;

                                if ($data_tra[$k]['cod_regimen_pensionario'] == '02') { //ONP                            
                                    $afp_nombre_value = "R.P. : " . $data_tra[$k]['nombre_afp'];
                                } else { //AFP                            
                                    //dao                            
                                    $arreglo_data_rp = $dao_rp->buscarDetalleRegimenPensionario($data_tra[$k]['id_trabajador']);
                                    $afp_nombre_value = "A.F.P. : " . $data_tra[$k]['nombre_afp'];
                                    $afp_carnet_value = "NRO.CARNET AFP : " . $arreglo_data_rp['cuspp'];
                                }

                                //......................................................


                                fwrite($fp, str_pad(" ", 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad($afp_carnet_value, 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad($afp_nombre_value, 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);


                                $num_mes = intval(getFechaPatron($data_pd['periodo'], "m"));
                                $fecha_0 = getNameMonth($num_mes);
                                $fecha_1 = getFechaPatron($data_pd['periodo'], "d.Y");
                                $cadena_fecha = "MES : " . $fecha_0 . " DE : " . $fecha_1;
                                fwrite($fp, str_pad($cadena_fecha, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("CARGO : " . $data_tra[$k]['nombre_ocupacion'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("SECCION : " . $data_tra[$k]['nombre_centro_costo'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);


                                //......................................................
                                $data_direccion = array();
                                $data_direccion = $dao_pdireccion->listarPersonaDirecciones($data_tra[$k]['id_persona']);
                                //foreach ($lista as $rec) {
                                $cadena = '';
                                for ($a = 0; $a < 1/* count($lista) */; $a++) {
                                    $rec = $data_direccion[$a];

                                    //$id_persona = $rec['id_persona'];                                    
                                    $ubigeo_nombre_via = $rec["ubigeo_nombre_via"];
                                    $nombre_via = $rec['nombre_via'];
                                    $numero_via = $rec['numero_via'];

                                    $ubigeo_nombre_zona = $rec['ubigeo_nombre_zona'];
                                    $nombre_zona = $rec['nombre_zona'];
                                    $etapa = $rec['etapa'];
                                    $manzana = $rec['manzana'];
                                    $blok = $rec['blok'];
                                    $lote = $rec['lote'];

                                    $departamento = $rec['departamento'];
                                    $interior = $rec['interior'];

                                    $kilometro = $rec['kilometro'];

                                    $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec['ubigeo_departamento']);
                                    $ubigeo_provincia = $rec['ubigeo_provincia'];
                                    $ubigeo_distrito = $rec['ubigeo_distrito'];


                                    $cadena .= ($ubigeo_nombre_via != "-") ? " " . $ubigeo_nombre_via : '';
                                    $cadena .= (!empty($nombre_via)) ? " " . $nombre_via : '';
                                    $cadena .= (!empty($numero_via)) ? " " . $numero_via : '';

                                    $cadena .= ($ubigeo_nombre_zona != "-") ? $ubigeo_nombre_zona : '';
                                    $cadena .= (!empty($nombre_zona)) ? " " . $nombre_zona : '';
                                    $cadena .= (!empty($etapa)) ? " " . $etapa : '';

                                    $cadena .= (!empty($manzana)) ? ' MZA. ' . $manzana : '';
                                    $cadena .= (!empty($blok)) ? " " . $blok : '';
                                    $cadena .= (!empty($etapa)) ? " " . $etapa : '';
                                    $cadena .= (!empty($lote)) ? ' LOTE. ' . $lote : '';

                                    $cadena .= (!empty($departamento)) ? " " . $departamento : '';
                                    $cadena .= (!empty($interior)) ? " " . $interior : '';
                                    $cadena .= (!empty($kilometro)) ? " " . $kilometro : '';

                                    $cadena .= ($ubigeo_departamento != "-") ? $ubigeo_departamento . "-" : '';
                                    $cadena .= ($ubigeo_provincia != "-") ? $ubigeo_provincia . "-" : '';
                                    $cadena .= ($ubigeo_distrito != "-") ? $ubigeo_distrito : '';

                                    $cadena = strtoupper($cadena);
                                }

                                //......................................................

                                $cadena_dialab = $data_tra[$k]['dia_laborado'] . " DIAS TRAB. " . $data_tra[$k]['ordinario_hora'] . " HORAS TRAB.";

                                fwrite($fp, str_pad($cadena_dialab, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("DIRECCION : " . $cadena, 88, " ", STR_PAD_RIGHT));

                                fwrite($fp, $BREAK);


                                $array_mixto = generarBotletaTabla($fp, $data_tra[$k]['id_trabajador_pdeclaracion'], $data_tra[$k]['cod_regimen_pensionario'], $data_pd['periodo'], $id_pdeclaracion, $data_tra[$k]['id_trabajador'], $BREAK, $BREAK2);

                                $neto_pagar = $array_mixto['numero'];

                                if ($array_mixto['centinela'] == true) {
                                    //SALIO DE MARCO SGTE PAGINA!
                                    fwrite($fp, chr(12));
                                } else {
                                    fwrite($fp, chr(12));
                                }


                                //++                            
                                // Generar Boleta...
                                $data = array();
                                $data = $data_tra[$k];

                                //...................................................
                                /* $fpx = */  //generarBoletaLineal($fpx, $data,$neto_pagar,$k,$BREAK);

                                $texto_3 = $data_tra[$k]['apellido_paterno'] . " " . $data_tra[$k]['apellido_materno'] . " " . $data_tra[$k]['nombres'];
                                fwrite($fpx, $BREAK);
                                fwrite($fpx, str_pad(($k + 1) . " ", 4, " ", STR_PAD_LEFT));
                                fwrite($fpx, str_pad($data_tra[$k]['num_documento'], 12, " ", STR_PAD_RIGHT));
                                fwrite($fpx, str_pad(strtoupper($texto_3), 40, " ", STR_PAD_RIGHT));
                                fwrite($fpx, str_pad(number_format($neto_pagar, 2), 9, " ", STR_PAD_RIGHT));
                                fwrite($fpx, str_pad("_______________", 15, " ", STR_PAD_RIGHT));
                                fwrite($fpx, $BREAK);

                                $num_trabajador++;
                                if ($num_trabajador >= 23):
                                    fwrite($fpx, chr(12));
                                    $num_trabajador = 0;
                                endif;

                                // por persona
                                $SUM_TOTAL_CC[$i][$j]['monto'] = $SUM_TOTAL_CC[$i][$j]['monto'] + $neto_pagar;
                            }//enfFor $k 
                            //$SUM_TOTAL_EST[$i]['monto'] = $SUM_TOTAL_EST[$i]['monto'] + $SUM_TOTAL_CC[$i][$j]['monto'];
                            //--- LINE
                            fwrite($fpx, $BREAK);
                            //fwrite($fp, $LINEA);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("TOTAL " . $SUM_TOTAL_CC[$i][$j]['centro_costo'] . " " . $SUM_TOTAL_EST[$i]['establecimiento'], 56, " ", STR_PAD_RIGHT));
                            fwrite($fpx, number_format($SUM_TOTAL_CC[$i][$j]['monto'], 2));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, chr(12));
                        }//EXISTEN TRABAJADORES!
                    }
                }//END FOR CCosto

                fwrite($fp, $BREAK);
            }
        }//END FOR Est
    }//END IF


    fclose($fp);
    fclose($fpx);








    $file = array();
    $file[] = $file_name;
    $file[] = $file_name2;

    $zipfile = new zipfile();
    $carpeta = "file-" . date("d-m-Y") . "/";
    $zipfile->add_dir($carpeta);

    for ($i = 0; $i < count($file); $i++) {
        $zipfile->add_file(implode("", file($file[$i])), $carpeta . $file[$i]);
        //$zipfile->add_file( file_get_contents($file[$i]),$carpeta.$file[$i]);
    }

    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=zipfile.zip");

    echo $zipfile->file();
}

function generarBotletaTabla($fp, $id_trabajador_pdeclaracion, $cod_regimen_pensionario, $periodo, $id_pdeclaracion, $id_trabajador, $BREAK, $BREAK2) {
    $array_mixto = array();
    //..............................................................................
    $cod_conceptos_ingresos = array('100', '200', '300', '400', '500', '900');

    $cod_conceptos_descuentos = array('600', '700');

    $cod_conceptos_aportes = array(/* '600', */ '800');
    //..............................................................................

    $dao_ddc = new DeclaracionDconceptoDao();
    $dao_pdcem = new PlameDetalleConceptoEmpleadorMaestroDao();


    $LINEA = str_repeat('-', 135);
    $VACIO = str_repeat(' ', 135);
    $PUNTO = "|";
    $BORDE_R = str_pad('', 3, " ", STR_PAD_RIGHT); // $BORDER
    $BORDE_L = str_pad('', 3, " ", STR_PAD_LEFT); // $BORDER
    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("R E M U N E R A C I O N E S", 38, " ", STR_PAD_BOTH));
    fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    //fwrite($fp, $BORDE_R);   
    fwrite($fp, str_pad("R E T E N C I O N E S / D E S C U E N T O S", 44, " ", STR_PAD_BOTH));
    //fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("A P O R T A C I O N E S", 37, " ", STR_PAD_BOTH));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("DESCRIPCION", 19, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("IMPORTE", 19, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("DESCRIPCION", 19, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("IMPORTE", 19, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);

    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("DESCRIPCION", 19, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("IMPORTE", 18, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);

    fwrite($fp, $BREAK);
    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK);

    // ----- INICIO CUERPO 
    //conceptos calculados base
    $calc = array();
    $calc = $dao_ddc->buscar_ID_TrabajadorPdeclaracion_2($id_trabajador_pdeclaracion);


    // INGRESOS
    // 01 lista de todos conceptos Ingresos
    $c_pingreso = array();
    $c_pingreso = $dao_pdcem->view_listarConcepto(ID_EMPLEADOR_MAESTRO, $cod_conceptos_ingresos, 1);

    // armado de array
    $array_ingreso = array();
    for ($i = 0; $i < count($c_pingreso); $i++) {
        $array_ingreso[] = $c_pingreso[$i]['cod_detalle_concepto'];
    }
    //$new_array_ingreso = array('0121','0201','0105','0106','0107','0114','0115','0118','0304','0406','0407','0909');

    $ingresos = array();
    $x = 0;
    $sum_i = 0.00;
    for ($o = 0; $o < count($calc); $o++):
        if (in_array($calc[$o]['cod_detalle_concepto'], $array_ingreso)):
            //if ($calc[$o]['cod_detalle_concepto'] == '0406' || $calc[$o]['cod_detalle_concepto'] == '0312' || $calc[$o]['cod_detalle_concepto'] == '0107') {

            $ingresos[$x]['descripcion'] = $calc[$o]['descripcion'];
            $ingresos[$x]['descripcion_abreviada'] = $calc[$o]['descripcion_abreviada'];
            //$ingresos[$x]['cod_detalle_concepto'] = $calc[$o]['cod_detalle_concepto'];            
            $ingresos[$x]['monto_pagado'] = $calc[$o]['monto_pagado'];
            $sum_i = $sum_i + $calc[$o]['monto_pagado'];
            $x++;
        //}
        endif;
    endfor;

//echoo($ingresos);
//------------------------------------------------------------------------------
// DESCUENTOS
// 01 lista de todos conceptos 
    $c_pdescuento = array();
    $c_pdescuento = $dao_pdcem->view_listarConcepto(ID_EMPLEADOR_MAESTRO, $cod_conceptos_descuentos, $seleccionado = array(0, 1));

// armado de array
    $array_descuento = array();
    for ($i = 0; $i < count($c_pdescuento); $i++) {
        $array_descuento[] = $c_pdescuento[$i]['cod_detalle_concepto'];
    }


    $descuentos = array();
    $x = 0;
    $sum_d = 0.00;
    for ($o = 0; $o < count($calc); $o++):
        //if ($calc[$o]['cod_detalle_concepto'] == '0703') {
        if (in_array($calc[$o]['cod_detalle_concepto'], $array_descuento)):
            $descuentos[$x]['descripcion'] = $calc[$o]['descripcion'];
            $descuentos[$x]['descripcion_abreviada'] = $calc[$o]['descripcion_abreviada'];
            $descuentos[$x]['cod_detalle_concepto'] = $calc[$o]['cod_detalle_concepto'];
            $descuentos[$x]['monto_pagado'] = $calc[$o]['monto_pagado'];
            $sum_d = $sum_d + $calc[$o]['monto_pagado'];
            $x++;
        endif;
    //}
    endfor;


    //------------------------------------------------------------------------------
    // 01 lista de todos conceptos
    $c_paporte = array();
    $c_paporte = $dao_pdcem->view_listarConcepto(ID_EMPLEADOR_MAESTRO, $cod_conceptos_aportes, 0);

    // armado de array
    $array_aporte = array();
    for ($i = 0; $i < count($c_paporte); $i++) {
        $array_aporte[] = $c_paporte[$i]['cod_detalle_concepto'];
    }


    $aportes = array();
    $x = 0;
    $sum_a = 0.00;
    for ($o = 0; $o < count($calc); $o++):
        if (in_array($calc[$o]['cod_detalle_concepto'], $array_aporte)):
            $aportes[$x]['descripcion'] = $calc[$o]['descripcion'];
            $aportes[$x]['descripcion_abreviada'] = $calc[$o]['descripcion_abreviada'];
            $aportes[$x]['monto_pagado'] = $calc[$o]['monto_pagado'];
            $sum_a = $sum_a + $calc[$o]['monto_pagado'];
            $x++;
        endif;
    endfor;


//----------------------------PINTAR EN TABLA-----------------------------------
    $cnt_ingreso = count($ingresos);
    $cnt_descuento = count($descuentos);
    $cnt_aporte = count($aportes);


    $numeros = array($cnt_ingreso, $cnt_descuento, $cnt_aporte);

    $mayor = getNumeroMayor($numeros) + 1;
    //$mayor = $mayor + 1;
    //array mixto
    if ($mayor > 7) {
        $array_mixto['centinela'] = true;
    } else {
        $array_mixto['centinela'] = false;
    }


    for ($i = 0; $i < $mayor; $i++): // [7] limite maX por hoja

        fwrite($fp, $PUNTO);
        fwrite($fp, $BORDE_R);
        $descripcion_1 = ($ingresos[$i]['descripcion_abreviada'] == "") ? $ingresos[$i]['descripcion'] : $ingresos[$i]['descripcion_abreviada'];

        if (strlen($descripcion_1) > 29):
            $descripcion_1 = substr($descripcion_1, 0, 26);
            $descripcion_1.= "...";
        endif;

        fwrite($fp, str_pad($descripcion_1, 29, " ", STR_PAD_RIGHT));
        $ingreso_boo = ($ingresos[$i]['monto_pagado']) ? number_format_var($ingresos[$i]['monto_pagado']) : '';
        fwrite($fp, str_pad($ingreso_boo, 9, " ", STR_PAD_LEFT));
        fwrite($fp, $BORDE_L);
        fwrite($fp, $PUNTO);

        fwrite($fp, $BORDE_R);
        $descripcion_2 = ($descuentos[$i]['descripcion_abreviada'] == "") ? $descuentos[$i]['descripcion'] : $descuentos[$i]['descripcion_abreviada'] . " ";

        //,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
        $dao_afp = new ConfAfpDao();
        $afp = $dao_afp->vigenteAfp($cod_regimen_pensionario, $periodo);

        //......................................................................
        if ($descuentos[$i]['cod_detalle_concepto'] == '0704') {
            $dao_rpc = new RegistroPorConceptoDao();
            $data_tiempo = $dao_rpc->buscar_RPC_PorTrabajador($id_pdeclaracion, $id_trabajador, C704);
            //buscar_tardanza($id_trabajador_pdeclaracion)
            $descripcion_2 .= ". " . $data_tiempo['valor'] . " .hrs";
        }
        //......................................................................
        if ($cod_regimen_pensionario == '02') { //ONP
        } else { //AF --Q ESTA AFILIADO
            if ($descuentos[$i]['cod_detalle_concepto'] == '0601') {

                $descripcion_2 .= $afp['comision'] . "%";
            } else if ($descuentos[$i]['cod_detalle_concepto'] == '0606') {
                $descripcion_2 .= $afp['prima_seguro'] . "%";
            } else if ($descuentos[$i]['cod_detalle_concepto'] == '0608') {
                $descripcion_2 .= $afp['aporte_obligatorio'] . "%";
            }
        }
        //,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,   
        //$descripcion_2 = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        if (strlen($descripcion_2) > 29):
            $descripcion_2 = substr($descripcion_2, 0, 26);
            $descripcion_2.= "...";
        endif;

        fwrite($fp, str_pad($descripcion_2, 29, " ", STR_PAD_RIGHT));
        $descuento_boo = ($descuentos[$i]['monto_pagado']) ? number_format_var($descuentos[$i]['monto_pagado']) : '';
        fwrite($fp, str_pad($descuento_boo, 9, " ", STR_PAD_LEFT));
        fwrite($fp, $BORDE_L);
        fwrite($fp, $PUNTO);

        fwrite($fp, $BORDE_R);
        $descripcion_3 = ($aportes[$i]['descripcion_abreviada'] == "") ? $aportes[$i]['descripcion'] : $aportes[$i]['descripcion_abreviada'];
//   $descripcion_3 = "aaaaaaaaaaaaaaaaaaaaaaaaaaaa";

        if (strlen($descripcion_3) > 28):
            $descripcion_3 = substr($descripcion_3, 0, 25);
            $descripcion_3.= "...";
        endif;
        fwrite($fp, str_pad($descripcion_3, 28, " ", STR_PAD_RIGHT));
        $aporte_boo = ($aportes[$i]['monto_pagado']) ? number_format_var($aportes[$i]['monto_pagado']) : '';
        fwrite($fp, str_pad($aporte_boo, 9, " ", STR_PAD_LEFT));
        fwrite($fp, $BORDE_L);
        fwrite($fp, $PUNTO);
        fwrite($fp, $BREAK);

    endfor;
//----------------------------PINTAR EN TABLA-----------------------------------
// ----- FIN CUERPO  

    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK);


    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("TOTAL REMUNERACIONES ", 29, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(number_format_var($sum_i), 9, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);


    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("TOTAL RETENC./DESCUENTOS ", 29, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(number_format_var($sum_d), 9, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("TOTAL APORTACIONES", 28, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(number_format_var($sum_a), 9, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);
    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK2);

    if ($mayor < 7) {
        for ($d = 7; $mayor < $d; $d--):
            fwrite($fp, $VACIO);
            fwrite($fp, $BREAK);
        endfor;
    }

    //--------- 
    $arreglo_numero = array();
    $arreglo_numero = roundFaborContra(($sum_i - $sum_d));

    $linea_caja = str_repeat('-', 29);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);

    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('', 1, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("REDONDEO", 13, " ", STR_PAD_RIGHT));
    fwrite($fp, ":");
    fwrite($fp, str_pad($arreglo_numero['decimal'], 11, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad('', 1, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);
    fwrite($fp, $linea_caja);


    fwrite($fp, $BREAK);
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('', 1, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("NETO A PAGAR", 13, " ", STR_PAD_RIGHT));
    fwrite($fp, ":");
    fwrite($fp, str_pad(number_format_var($arreglo_numero['numero']), 11, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad('', 1, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('_________________________', 53, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad('_________________________', 43, " ", STR_PAD_LEFT));
    fwrite($fp, $BREAK);
    fwrite($fp, $linea_caja);
    fwrite($fp, str_pad('P.' . NAME_EMPRESA, 49, " ", STR_PAD_LEFT)); //VO
    fwrite($fp, str_pad('RECIBI CONFORME', 43, " ", STR_PAD_LEFT));
    fwrite($fp, $BREAK);

    $array_mixto['numero'] = $arreglo_numero['numero'];

    return $array_mixto; //$arreglo_numero['numero'];
}

function generarBoletaLineal($fp, $data_tra, $neto_pagar, $k, $BREAK) {

    $texto_3 = $data_tra['apellido_paterno'] . " " . $data_tra['apellido_materno'] . " " . $data_tra['nombres'];
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad(($k + 1) . " ", 4, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad($data_tra['num_documento'], 12, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(strtoupper($texto_3), 40, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(number_format($neto_pagar, 2), 9, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("_______________", 15, " ", STR_PAD_RIGHT));
    fwrite($fp, $BREAK);

    return $fp;
}

//FUNCTION ELIMINAR O LIMPIAR MES DE DATA
// Elimina 2da quincena Y Mensual
// - pagos
// - tranbajadores_pdeclaraciones
function eliminarDatosMes() {
    //var_dump($_REQUEST);
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $periodo = $_REQUEST['periodo'];

    $dao = new TrabajadorPdeclaracionDao();
    $rpta_1 = $dao->eliminarDatosMes($id_pdeclaracion);

    //eliminar prestamos - pagos 
    //$dao_pc = new PrestamoCuotaDao();
    //$dao_pc->bajaPrestamoCuota($periodo, 0);
    //eliminar para ti familia - pagos 
    $dao_ptf = new PtfPagoDao();
    $dao_ptf->del_idpdeclaracion($id_pdeclaracion);

    return true;
}

/**
 *
 * @return type 
 * @var elimina solo por trabajador y declaracion registrada.
 */
function elimarEnCascada_trabajador_en_mes() {
    //echoo($_REQUEST);

    $id = $_REQUEST['id']; //id_trabajadorpdeclaracion
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $id_trabajador = $_REQUEST['id_trabajador'];

    //paso 01 Elimar trabajador_pdeclaracion
    $dao_tpd = new TrabajadorPdeclaracionDao();
    $r_1 = $dao_tpd->eliminar_idPdeclaracion($id_pdeclaracion, $id_trabajador);

    //ELIMINAR PRESTAMO
    //paso 04 Eliminar Pago Para ti familia
    $dao_ptfpago = new PtfPagoDao();
    $dao_ptfpago->delInnerPdeclaracion($id_pdeclaracion, $id_trabajador);


    //eliminar prestamos - pagos 
    //$dao_pc = new PrestamoCuotaDao();
    //$dao_pc->bajaPrestamoCuota2($id_pdeclaracion, ID_EMPLEADOR, $id_trabajador); //ok?
    //eliminar para ti familia - pagos 


    /*  DELETE OK.
      $dao_ptf = new PtfPagoDao();
      $dao_ptf->del_idpdeclaracion2($id_trabajador);  //ok ?
     */
    return true;
}

// new PLANILLA UNICA DE PAGOS - TRABAJADORES
function generar_reporte_empresa_01($id_pdeclaracion) {

//---------------------------------------------------

    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($id_pdeclaracion);

    $num_mes = getFechaPatron($data_pd['periodo'], "m");
    $nombre_mes = getNameMonth($num_mes);
    $anio = getFechaPatron($data_pd['periodo'], "Y");

    $file_name = /* NAME_COMERCIAL . */'planilla.txt'; //-PLANILLA UNICA

    $BREAK = chr(13) . chr(10);
    $BREAK2 = chr(13) . chr(10) . chr(13) . chr(10);
    $PUNTO = '|';
    //$LINEA = str_repeat('-', 80);
    $fp = fopen($file_name, 'w');
    $cab_comprimido = chr(27) . M . chr(15);
    fwrite($fp, $cab_comprimido);



    //DAO
    //$dao_cc = new EmpresaCentroCostoDao();
    $dao_pago = new TrabajadorPdeclaracionDao(); //[OK]
    //$dao_estd = new EstablecimientoDireccionDao();
    //$dao_rp = new DetalleRegimenPensionarioDao(); //[OK]
    //$dao_pdireccion = new PersonaDireccionDao(); //[OK]
    //$dao_dpl = new DetallePeriodoLaboralDao(); //[OK]
    // 01 Crear Cabecera del Documento.

    $fp = helper_cabecera($fp, $nombre_mes, $anio, $BREAK, $BREAK2, $PUNTO);


    if (true) {
        // LISTA DE TRABAJADORES
        $data_tra = array();
        $data_tra = $dao_pago->listar_3($id_pdeclaracion);


        $dao_ddc = new DeclaracionDconceptoDao();
        $total = array();
        $total['ingresos_h_v'] = 0.00;
        $total['ingresos_h_b'] = 0.00;
        $total['ingresos_a_f'] = 0.00;
        $total['ingresos_r_c'] = 0.00;
        $total['ingresos_a_t'] = 0.00;
        $total['ingresos_t_f'] = 0.00;
        $total['ingresos_h_e'] = 0.00;
        $total['ingresos_total'] = 0.00;

        $total['descuentos_snp'] = 0.00;
        $total['descuentos_5ta'] = 0.00;
        $total['descuentos_afp'] = 0.00;
        $total['descuentos_a_q'] = 0.00;
        $total['descuentos_d_p'] = 0.00;
        $total['descuentos_d_ptf'] = 0.00;
        $total['descuentos_jdl'] = 0.00;
        $total['descuentos_otros'] = 0.00;
        $total['descuentos_total'] = 0.00;

        $total['aportes_essalud'] = 0.00;
        //$total['aportes_otros'] = 0.00;
        $total['aportes_total'] = 0.00;

        $total['total_rnd'] = 0.00;
        $total['total_total'] = 0.00;

        $contador_break = 0;
        //                
        for ($k = 0; $k < count($data_tra); $k++) {

            //++
            $contador_break = $contador_break + 1;
            if ($contador_break == 47) {
                fwrite($fp, chr(12));
                $fp = helper_cabecera($fp, $nombre_mes, $anio, $BREAK, $BREAK2, $PUNTO);
                //fwrite($fp,"-ANB-");
                $contador_break = 0;
            }
            //++
            // LISTA DE TRABAJADORES VACACION
            $data_vaca = PagoDao::anb_dosQuincenasEnBoleta($id_pdeclaracion, $data_tra[$k]['id_trabajador']);

            //..............................................................................
            $cod_conceptos_ingresos = array('100', '200', '300', '400', '500', '900');
            $cod_conceptos_descuentos = array('600', '700');
            $cod_conceptos_aportes = array(/* '600', */'800');
            //..............................................................................            
            //conceptos calculados base
            $calc = array();
            $calc = $dao_ddc->buscar_ID_TrabajadorPdeclaracion_3($data_tra[$k]['id_trabajador_pdeclaracion']);


            //-------------------------------- I PINTANDO LINEA --------------------------------//        
            //--
            fwrite($fp, str_pad($k + 1, 4, " ", STR_PAD_RIGHT));

            $name_tra = $data_tra[$k]['apellido_paterno'] . ' ' . $data_tra[$k]['apellido_materno'] . ' ' . $data_tra[$k]['nombres'];
            fwrite($fp, str_pad(textoaMedida(29, $name_tra), 31, " ", STR_PAD_RIGHT));

            fwrite($fp, str_pad($data_tra[$k]['num_documento'], 12, " ", STR_PAD_RIGHT));

            fwrite($fp, str_pad($data_vaca['dia_nosubsidiado'], 5, " ", STR_PAD_RIGHT)); //DIA Vacacion

            fwrite($fp, str_pad($data_tra[$k]['dia_laborado'], 5, " ", STR_PAD_RIGHT));

            fwrite($fp, str_pad($data_tra[$k]['ordinario_hora'], 5, " ", STR_PAD_RIGHT));

            $_00 = 0; //($data_vaca['sueldo_vacacion'] > 0) ? $data_vaca['sueldo_vacacion'] : 0;
            fwrite($fp, str_pad($_00, 9, " ", STR_PAD_LEFT)); //SUELDO VACACION
            $total['ingresos_h_v'] = $total['ingresos_h_v'] + $_00;


            $_01 = buscar_buscar_concepto($calc, C121);
            fwrite($fp, str_pad($_01, 9, " ", STR_PAD_LEFT));
            $total['ingresos_h_b'] = $total['ingresos_h_b'] + $_01;

            $_02 = buscar_buscar_concepto($calc, C201);
            fwrite($fp, str_pad($_02, 8, " ", STR_PAD_LEFT));
            $total['ingresos_a_f'] = $total['ingresos_a_f'] + $_02;

            $_03 = buscar_buscar_concepto($calc, C304);
            fwrite($fp, str_pad($_03/* CAJA */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_r_c'] = $total['ingresos_r_c'] + $_03;

            $_04 = buscar_buscar_concepto($calc, C909);
            fwrite($fp, str_pad($_04/* TRANSP */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_a_t'] = $total['ingresos_a_t'] + $_04;

            $_05 = buscar_buscar_concepto($calc, C107);
            fwrite($fp, str_pad($_05/* TRAB FERIDO 0107 */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_t_f'] = $total['ingresos_t_f'] + $_05;

            // CALCULADO!
            $_06_1 = buscar_buscar_concepto($calc, C105); // al 25%
            $_06_2 = buscar_buscar_concepto($calc, C106);  // al 35%
            $_06 = ($_06_1 + $_06_2);
            fwrite($fp, str_pad($_06/* EXTRAS */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_h_e'] = $total['ingresos_h_e'] + $_06;

            //..................................................................            
            $total_ingresos = ($_00 + $_01 + $_02 + $_03 + $_04 + $_05 + $_06);
            fwrite($fp, str_pad(number_format_var($total_ingresos)/* TOTAL */, 9, " ", STR_PAD_LEFT));
            $total['ingresos_total'] = $total['ingresos_total'] + $total_ingresos;
            //..................................................................

            fwrite($fp, $PUNTO);

            $_07 = buscar_buscar_concepto($calc, C607);
            fwrite($fp, str_pad($_07/* SNP */, 8, " ", STR_PAD_LEFT));
            $total['descuentos_snp'] = $total['descuentos_snp'] + $_07;

            $_08 = buscar_buscar_concepto($calc, C605); //5ta 
            fwrite($fp, str_pad($_08, 8, " ", STR_PAD_LEFT));
            $total['descuentos_5ta'] = $total['descuentos_5ta'] + $_08;

            // CALCULADO!
            $_09_1 = buscar_buscar_concepto($calc, C601);
            $_09_2 = buscar_buscar_concepto($calc, C606);
            $_09_3 = buscar_buscar_concepto($calc, C608);

            $_09 = ($_09_1 + $_09_2 + $_09_3);
            fwrite($fp, str_pad($_09/* A.F.P. */, 8, " ", STR_PAD_LEFT));
            $total['descuentos_afp'] = $total['descuentos_afp'] + $_09;

            $_10 = buscar_buscar_concepto($calc, C701); // QUINCENA 
            fwrite($fp, str_pad($_10, 9, " ", STR_PAD_LEFT));
            $total['descuentos_a_q'] = $total['descuentos_a_q'] + $_10;

            //======Prestamo         =Funcion Gemela=============================
            $dao_pres = new PrestamoDao();
            $_11 = $dao_pres->getPagoCuotaPorPeriodo_Reporte(/* $data_pd['id_pdeclaracion'] */$data_pd['periodo'], $data_tra[$k]['id_trabajador']);
            $_11 = (isset($_11)) ? $_11 : 0;
            fwrite($fp, str_pad($_11/* desc PRESTAMO-EMP */, 8, " ", STR_PAD_LEFT));
            $total['descuentos_d_p'] = $total['descuentos_d_p'] + $_11;

            //======Para ti Familia  =Funcion Gemela=============================
            $dao_ptf = new ParatiFamiliaDao();
            $_12 = $dao_ptf->getPagoCuotaPorPeriodo_Reporte($data_pd['id_pdeclaracion'], $data_tra[$k]['id_trabajador']);
            $_12 = (isset($_12)) ? $_12 : 0; // P.T.FAML-EMP
            fwrite($fp, str_pad($_12, 8, " ", STR_PAD_LEFT));
            $total['descuentos_d_ptf'] = $total['descuentos_d_ptf'] + $_12;

            $_13 = buscar_buscar_concepto($calc, C703); // Dscto judicial
            fwrite($fp, str_pad($_13, 8, " ", STR_PAD_LEFT));
            $total['descuentos_jdl'] = $total['descuentos_jdl'] + $_13;

            $_14_1 = buscar_buscar_concepto($calc, C704); // Tardanzas
            $_14_2 = buscar_buscar_concepto($calc, C705); // Inasistencias            
            $_14 = ($_14_1 + $_14_2);
            fwrite($fp, str_pad($_14/* OTROSDESC. */, 8, " ", STR_PAD_LEFT));
            $total['descuentos_otros'] = $total['descuentos_otros'] + $_14;

//=======================================================================================
            $descuentos = ($_07 + $_08 + $_09 + $_10 + $_11 + $_12 + $_13 + $_14);

            $_15 = $total_ingresos - $descuentos;
            $_15_round = roundFaborContra($_15);
//=======================================================================================            

            fwrite($fp, str_pad($descuentos/* 'TOTAL.' */, 9, " ", STR_PAD_LEFT));
            $total['descuentos_total'] = $total['descuentos_total'] + $descuentos;
            // number_format_var($number);
            fwrite($fp, $PUNTO);

            $_16 = buscar_buscar_concepto($calc, C804);
            fwrite($fp, str_pad(number_format_var($_16)/* ESSALUD */, 8, " ", STR_PAD_LEFT));
            $total['aportes_essalud'] = $total['aportes_essalud'] + $_16;

            //$_17 = 0.00;
            fwrite($fp, str_pad('-', 6, " ", STR_PAD_LEFT));

            $_18 = $_16;
            fwrite($fp, str_pad(number_format_var($_18)/* TOTAL. */, 9, " ", STR_PAD_LEFT));
            $total['aportes_total'] = $total['aportes_total'] + $_18;



            fwrite($fp, $PUNTO);

            fwrite($fp, str_pad($_00, 9, " ", STR_PAD_LEFT)); //MONTO VACACION



            fwrite($fp, str_pad($_15_round['decimal']/* RND */, 6, " ", STR_PAD_LEFT));
            $total['total_rnd'] = $total['total_rnd'] + ( $_15_round['decimal'] );

            fwrite($fp, str_pad(number_format_var($_15_round['numero'])/* A. */, 10, " ", STR_PAD_LEFT));
            $total['total_total'] = $total['total_total'] + ( $_15_round['numero'] );


            //-------------------------------- I PINTANDO LINEA --------------------------------//        

            fwrite($fp, $BREAK);
        }//enfFor $k 
        // imprimir :PIE:
        $fp = helper_pie($fp, $total, $BREAK, $PUNTO);
    }

    fclose($fp);

    $file = array();
    $file[] = $file_name;
    //$file[] = ($file_name2);
    //generarRecibo15_txt2($id_pdeclaracion, $id_etapa_pago);


    $zipfile = new zipfile();
    $carpeta = "file-" . date("d-m-Y") . "/";
    $zipfile->add_dir($carpeta);

    for ($i = 0; $i < count($file); $i++) {
        $zipfile->add_file(implode("", file($file[$i])), $carpeta . $file[$i]);
        //$zipfile->add_file( file_get_contents($file[$i]),$carpeta.$file[$i]);
    }

    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=zipfile.zip");

    echo $zipfile->file();
}

function helper_pie($fp, $total, $BREAK, $PUNTO) {
    $PUNTO = " ";
    $linea_caja = str_repeat('-', 254);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);

    //cabecera 2
    fwrite($fp, str_pad('#', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'COD APELLIDOS Y NOMBRE' */, 31, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'DNI'12 */, 11, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'DIAS' */, 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'DIAS' */, 5, " ", STR_PAD_BOTH));

    //fwrite($fp, str_pad(' '/*'HORAS'*/, 8, " ", STR_PAD_BOTH));


    fwrite($fp, str_pad(number_format_var($total['ingresos_h_v'])/* BASICO */, 15, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_a_f'])/* 'ASIG.' FAMIL */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_a_t'])/* ASIG.TRANSP */, 16, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['ingresos_h_e'])/* HORAS EXTRAS */, 16, " ", STR_PAD_LEFT)); //-------
    //

    fwrite($fp, $PUNTO);


    fwrite($fp, str_pad(number_format_var($total['descuentos_snp'])/* 'S.N.P.' */, 17, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['descuentos_afp'])/* 'A.F.P.' */, 16, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['descuentos_d_p'])/* DESC. PRESTAMO */, 17, " ", STR_PAD_LEFT));



    fwrite($fp, str_pad(number_format_var($total['descuentos_jdl'])/* DESC. JUDIC. */, 16, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['descuentos_total'])/* TOTAL. */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['aportes_total'])/* TOTAL. DESC. */, 23, " ", STR_PAD_LEFT));

    //?
    //fwrite($fp, str_pad('', 9, " ", STR_PAD_LEFT)); //sobra

    fwrite($fp, $PUNTO);

    //fwrite($fp, str_pad(number_format_var($total['total_rnd'])/*RND.*/, 8, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad(number_format_var($total['total_rnd'])/* RND. */, 15/* 18 */, " ", STR_PAD_LEFT));


    fwrite($fp, $BREAK);


    //==========================================================================Ç
    // segunda parte
    //==========================================================================Ç


    fwrite($fp, str_pad('#', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'COD APELLIDOS Y NOMBRE' */, 38, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'DNI' */, 12, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'DIAS' */, 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'HORAS' */, 5, " ", STR_PAD_BOTH));


    fwrite($fp, str_pad(number_format_var($total['ingresos_h_b'])/* BASICO */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_r_c'])/* RIESGO. CAJA */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_t_f'])/* TRAB FERIADO */, 16, " ", STR_PAD_LEFT)); //-------

    fwrite($fp, str_pad(number_format_var($total['ingresos_total'])/* TOTAL */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['descuentos_5ta'])/* '5TA */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['descuentos_a_q'])/* ADEL QUINCENA */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['descuentos_d_ptf'])/* DESC. P.T.FAML */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['descuentos_otros'])/* OTROS.DESC. */, 16, " ", STR_PAD_LEFT));
//    fwrite($fp, str_pad('RND.'/**/, 8, " ", STR_PAD_BOTH));


    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['aportes_essalud'])/* 'ESSALUD.' */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('-'/* OTROS. DESC. */, 15, " ", STR_PAD_LEFT));


    //fwrite($fp, str_pad('', 12, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['ingresos_h_v']), 9, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['total_total'])/* A. */, 16, " ", STR_PAD_LEFT));


    //fwrite($fp, str_pad(number_format_var($total['total_total'])/*A.*/, 10, " ", STR_PAD_LEFT));


    fwrite($fp, $BREAK);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);

    return $fp;
}

function helper_cabecera($fp, $nombre_mes, $anio, $BREAK, $BREAK2, $PUNTO) {

    //$PUNTO = '³';
    $linea_caja = str_repeat('-', 254);

    fwrite($fp, str_pad(NAME_EMPRESA, 50, " ", STR_PAD_RIGHT));
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('DIRECCION', 105, " ", STR_PAD_RIGHT));
    //fwrite($fp, $BREAK);

    fwrite($fp, str_pad("PLANILLA UNICA DE PAGOS - TRABAJADORES", 50, " ", STR_PAD_RIGHT));


    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('', 105, " ", STR_PAD_BOTH));
    fwrite($fp, str_pad($nombre_mes . ' - ' . $anio, 38, " ", STR_PAD_BOTH));
    fwrite($fp, $BREAK2);



    //cabecera    
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('', 65, " ", STR_PAD_BOTH));
    fwrite($fp, str_pad('R E M U N E R A C I O N E S', 64, " ", STR_PAD_RIGHT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('D E S C U E N T O S', 74, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('A P O R T A C I O N E S', 23, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('NETO', 25, " ", STR_PAD_LEFT));
    fwrite($fp, $BREAK);


    //cabecera 2
    fwrite($fp, str_pad('#', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad('COD APELLIDOS Y NOMBRE', 31, " ", STR_PAD_RIGHT)); //38

    fwrite($fp, str_pad('DNI', 12, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('DIAS', 5, " ", STR_PAD_BOTH)); //new 4

    fwrite($fp, str_pad('DIAS', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('HORAS', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('HABER'/* BASICO */, 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('HABER'/* VACACION */, 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('ASIG.'/* FAMIL */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('RIESGO.'/* CAJA */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('ASIG.'/* TRANSP */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('TRAB'/* FERIADO */, 8, " ", STR_PAD_LEFT)); //-------

    fwrite($fp, str_pad('HORAS'/* EXTRAS */, 8, " ", STR_PAD_LEFT)); //-------


    fwrite($fp, str_pad('TOTAL'/**/, 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('S.N.P.'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('5TA'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('A.F.P.'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('ADEL'/* QUINCENA */, 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DESC.'/* PRESTAMO */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DESC.'/* P.T.FAML */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DESC.'/* JUDIC. */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('OTROS.'/* DESC. */, 8, " ", STR_PAD_LEFT));

//    fwrite($fp, str_pad('RND.'/**/, 8, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('TOTAL.'/**/, 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('ESSALUD.'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('OTRO'/* DESC. */, 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('TOTAL.'/* DESC. */, 9, " ", STR_PAD_LEFT));

    //fwrite($fp, str_pad('', 12, " ", STR_PAD_BOTH));


    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('NETO.', 9, " ", STR_PAD_LEFT)); //neto vacaciones

    fwrite($fp, str_pad('RND.', 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('A.', 10, " ", STR_PAD_LEFT));

    fwrite($fp, $BREAK);

    //--
    fwrite($fp, str_pad('', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad('', 31, " ", STR_PAD_RIGHT)); //8


    fwrite($fp, str_pad('', 12, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('VAC.', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('TRAB', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('TRAB', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('VAC.', 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('BASICO', 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('FAMIL', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('CAJA', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('TRANSP', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('FERIADO', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('EXTRAS', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('TOTAL', 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('CATEG', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('QUINCEN', 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('PRESTAM', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('PT.FAML', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('JUDIC.', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DSCT.', 8, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad('TOTAL.', 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DSCT', 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('', 9, " ", STR_PAD_LEFT));


    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('VAC.', 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('', 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('PAGAR', 10, " ", STR_PAD_LEFT));

    fwrite($fp, $BREAK);

    fwrite($fp, $linea_caja);

    fwrite($fp, $BREAK);

    return $fp;
}

/*
  require_once '../dao/AbstractDao.php';
  require_once '../dao/DeclaracionDconceptoDao.php';



  $calc = array();
  $dao_ddc = new DeclaracionDconceptoDao();
  $calc = $dao_ddc->buscar_ID_TrabajadorPdeclaracion_3(31);

  echo "<pre>";
  print_r($calc);
  echo "</pre>";
  $cod_concepto = '0605';
 */

function buscar_buscar_concepto($calc, $cod_concepto) {


    if (is_array($calc)) {
        $monto = 0;
        for ($j = 0; $j < count($calc); $j++) {

            if ($calc[$j]['cod_detalle_concepto'] == $cod_concepto) {
                $monto = $calc[$j]['monto_pagado'];
                break;
            }
        }
    } else {
        $monto = null;
    }
    return $monto;
}

//echo "encontro codigo = ".buscar_buscar_concepto($calc, $cod_concepto);
// FUNCIONANDOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
function buscar_ID_TrabajadorPdeclaracion($id) {
    //$id = $_REQUEST[''];
    $dao = new TrabajadorPdeclaracionDao();
    $data = $dao->buscar_ID($id);
    $model = new TrabajadorPdeclaracion();
    $model->setId_trabajador_pdeclaracion($data['id_trabajador_pdeclaracion']);
    $model->setId_pdeclaracion($data['id_pdeclaracion']);
    $model->setId_trabajador($data['id_trabajador']);
    $model->setDia_laborado($data['dia_laborado']);
    $model->setDia_total($data['dia_total']);
    $model->setOrdinario_hora($data['ordinario_hora']);
    $model->setOrdinario_min($data['ordinario_min']);
    $model->setSobretiempo_hora($data['sobretiempo_hora']);
    $model->setSobretiempo_min($data['sobretiempo_min']);
    $model->setSueldo($data['sueldo']);
    $model->setSueldo_base($data['sueldo_base']);
    $model->setFecha_creacion($data['fecha_creacion']);
    $model->setCod_tipo_trabajador($data['cod_tipo_trabajador']);
    $model->setCod_regimen_pensionario($data['cod_regimen_pensionario']);
    $model->setCod_regimen_aseguramiento_salud($data['cod_regimen_aseguramiento_salud']);
    $model->setCod_situacion($data['cod_situacion']);

    return $model;
}

