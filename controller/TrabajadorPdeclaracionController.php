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

    // vacacion
    require_once '../dao/TrabajadorVacacionDao.php';


    // new OK
    require_once '../dao/PagoQuincenaDao.php';
    require_once '../dao/TrabajadorDao.php';
    require_once '../dao/PlameAfectacionDao.php';



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
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];
    $ids = $_REQUEST['ids'];   // ids trabajador 
    generarConfiguracion($PERIODO);
    generarConfiguracion2($PERIODO);
    // INCIO PROCESO
    $model_tpd = new TrabajadorPdeclaracion();
    $dao_tpd = new TrabajadorPdeclaracionDao();
    $dao_plame = new PlameDao();

    $dao_pq = new PagoQuincenaDao();
    $dao_tra = new TrabajadorDao();

    $fecha = getFechasDePago($PERIODO);
    $fecha_inicio = $fecha['second_weeks_mas1'];
    $fecha_fin = $fecha['last_day'];
    echo "ssesssion";
    echoo($_SESSION);
  

    //|-------------------------------------------------------------------------
    //| Aki para mejorar. la aplicacion debe de preguntar por un Trabajador en 
    //| concreto:
    //|
    //| XQ esta funcion devuelve una lista de trabajadores. Si la persona tubiera
    //| por registros de trabajador. el sistema crearia :
    //| reportes de la persona.. duplicadooooo.
    //|-------------------------------------------------------------------------    
    $data_tra = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $fecha_inicio, $fecha_fin);

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

    // LLENA DE NULL SI YA FUERON REGISTRADOS EN LA PLANILLA MENSUAL.
    $data_id_tra_db = $dao_tpd->listar_HIJO($ID_PDECLARACION);
    for ($i = 0; $i < count($data_id_tra_db); $i++):
        for ($j = 0; $j < count($data_tra); $j++):
            if ($data_id_tra_db[$i]['id_trabajador'] == $data_tra[$j]['id_trabajador']):
                $data_tra[$j]['id_trabajador'] = null;
                //echo "encontro trabajador  = NULL Y  BREAK!!;";
                break;
            endif;
        endfor;
    endfor;
    //echoo($data_tra);
//==============================================================================

    for ($i = 0; $i < count($data_tra); $i++) {
        if ($data_tra[$i]['id_trabajador'] != null) {
            // DATA QUINCENA
            echo "EN FOR";
            $data_quincena = $dao_pq->listarPorPdeclaracionTrabajador($ID_PDECLARACION, $data_tra[$i]['id_trabajador']);
            $quincena_sueldoPorcentaje = ($data_quincena['sueldo_porcentaje'] > 0) ? $data_quincena['sueldo_porcentaje'] : 0;
            $quincena_sueldo = ($data_quincena['sueldo'] > 0) ? $data_quincena['sueldo'] : 0;
            $quincena_devengado = ($data_quincena['devengado'] > 0) ? $data_quincena['devengado'] : 0;
            $quincena_diaLaborado = ($data_quincena['dia_laborado'] > 0) ? $data_quincena['dia_laborado'] : 0;
            // PROCESO MENSUAL
            //-----------------------------------------------------------
            if ($data_tra[$i]['fecha_inicio'] > $fecha_inicio) {
                
            } else if ($data_tra[$i]['fecha_inicio'] <= $fecha_inicio) {
                $data_tra[$i]['fecha_inicio'] = $fecha_inicio;
            }
            if (is_null($data_tra[$i]['fecha_fin'])) {
                $data_tra[$i]['fecha_fin'] = $fecha_fin;
            } else if ($data_tra[$i]['fecha_fin'] >= $fecha_fin) { //INSUE
                $data_tra[$i]['fecha_fin'] = $fecha_fin;
            }
            //-----------------------------------------------------------
            
            
            /***/
            //base
            $b_fecha_inicio = $fecha_inicio;
            $b_fecha_fin = $fecha_fin;
            //relative
            $r_fecha_inicio = $data_tra[$i]['fecha_inicio'];
            $r_fecha_fin = $data_tra[$i]['fecha_fin'];
            
            $dia_no_trabajado = 0;            
            
            echoo($b_fecha_inicio);
            echoo($r_fecha_inicio);
            echoo($b_fecha_fin);
            echoo($r_fecha_fin);            
            
            if(($b_fecha_inicio==$r_fecha_inicio) && ($b_fecha_fin==$r_fecha_fin)){
                //echo "HOLAAA";
                //dias completos
            }else{    
                
                if($r_fecha_inicio > $b_fecha_inicio){
                    //dia16 -dia18
                    $dia_no_trabajado = $dia_no_trabajado + count(arregloDiaMes2($b_fecha_inicio, $r_fecha_inicio));
                    $dia_no_trabajado--;
                    //echo "entro 1";
                }                
                if($r_fecha_fin<$b_fecha_fin){
                    $dia_no_trabajado = $dia_no_trabajado + count(arregloDiaMes2($r_fecha_fin, $b_fecha_fin));
                    $dia_no_trabajado--;
                    //echo "entro 2";
                }
                
            }        

            
            //****
            $dia_laborado = 15 - $dia_no_trabajado;
/*
            ECHO "<BR>\n\n\n\n";
            echo "dia_no_trabajado = ".$dia_no_trabajado;
            echoo($data_tra[$i]['fecha_inicio']);
            echo '<pre>dia laborado';
            print_r($dia_laborado);
            echo '</pre>';
            echo "<br>";
            ECHO "<BR>\n\n\n\n";
*/
            //CALCULOS LOGIC
            $SUELDO_CAL = 0;
            /*
             * Estos dias son variables
             * enero = 30 = 15
             * febrero = 28 = 14
             * marzo = 31 = 16
             */
            //echo "\n\nquincena_sueldoPorcentaje = ".$quincena_sueldoPorcentaje;
            //echo "data_tra[$i]['monto_remuneracion'] ".$data_tra[$i]['monto_remuneracion'];
            //ECHOO($data_tra);
            
            if ($dia_laborado == 15) { // dias completos del mes trabajados. PAGA POR 15 DIAS                
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
                echo "sueldo calcl 3 =".$SUELDO_CAL;
            }

            // SUMA 1ERA QUINCENA           
            $SUELDO_CAL = $SUELDO_CAL + ($quincena_sueldo + $quincena_devengado);


            // -------------- obj Trabajador Pdeclaracion --------------
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
            $model_tpd->setSueldo_base($data_tra[$i]['monto_remuneracion']);
            $model_tpd->setId_empresa_centro_costo($data_tra[$i]['id_empresa_centro_costo']);
            /*
              // BUSCAR ADICIONAL
              NOTA:
              -En el primer filtro select fecha inicio a fecha fin ;
              -es posible que se dupliquen pensonas xq no se controla
              -a un trabajador con 2 mas fechas de inicio en 1 mes.
              NOTA2:
              -X eso existe una funcion exlusiva para el momento de obtener
              -dato de afp o essalud
             */
            //Registrar datos adicionales del Trabajador  
            $data_tra_adit = $dao_tra->buscarDataForPlanilla($data_tra[$i]['id_trabajador']);
            $model_tpd->setCod_tipo_trabajador($data_tra_adit['cod_tipo_trabajador']);
            $model_tpd->setCod_regimen_pensionario($data_tra_adit['cod_regimen_pensionario']);
            $model_tpd->setCod_regimen_aseguramiento_salud($data_tra_adit['cod_regimen_aseguramiento_salud']);
            $model_tpd->setCod_situacion($data_tra_adit['cod_situacion']);
            $model_tpd->setCod_ocupacion_p($data_tra_adit['cod_ocupacion_p']);

            //data_ayuda
            $data_ayuda = array(
                'quincena' => $quincena_sueldo//$data_quincena['sueldo']
            );            
            conceptoPorConceptoXD($model_tpd,$data_ayuda,$ID_PDECLARACION, $PERIODO);
        }//END IF (id_trabajador = NULL)
    }
}

function conceptoPorConceptoXD($obj, $data_ayuda, $ID_PDECLARACION, $PERIODO) {
    //DAO
    $dao_rpc = new RegistroPorConceptoDao();    
    // Arreglo data_rpc = lista de conceptos del trabajador.    
    $datarpc = $dao_rpc->buscar_RPC_PorTrabajador2($ID_PDECLARACION, $obj->getId_trabajador());

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
            $_asigFamiliar = concepto_0201();
        }
    }
    unset($datarpc_val);

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
        $_sueldoBasico = concepto_0121($obj->getSueldo(), $_inasistencias); //ok
        unset($datarpc_val);
    }


    //ECHO " <<<< 0706 == CONCEPTO EXCLUSIVO DE EMPRESA  PRESTAMO >>> ";
    // CONCEPTO : OTROS DESCUENTOS NO DEDUCIBLES A LA BASE IMPONIBLE PRESTAMO
    $arreglo_0706 = concepto_0706($obj->getId_trabajador(), $ID_PDECLARACION, $PERIODO);


    //----------------- fijo no mover varia el calculo-------------------
    // CONCEPTO : ESSALUD_MAS VIDA    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C604);
    if (intval($datarpc_val['valor']) == 1) {
        $_essaludMasVida = concepto_0604();
    }
    unset($datarpc_val);

    // CONCEPTO : ASEGURA PENSION_MAS     
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C612);
    if (intval($datarpc_val['valor']) == 1) {
        $_aseguraPensionMas = concepto_0612();
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
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C107);//ok
    if (intval($datarpc_val['valor']) > 0) {
        $_trabajoDiaFeriado = concepto_0107($obj->getSueldo_base(), $_asigFamiliar, $datarpc_val['valor']);

        $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + ($datarpc_val['valor'] * HORA_BASE)));
    }
    unset($datarpc_val);

    // CONCEPTO : BONIFICACIÓN POR RIESGO DE CAJA
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C304);
    if (floatval($datarpc_val['valor']) > 0) {
        $_bonifRiesgoCaja = concepto_0304($datarpc_val['valor']);
    }
    unset($datarpc_val);


    // CONCEPTO : DESCUENTO AUTORIZADO U ORDENADO POR MANDATO JUDICIAL    *****************************************************************    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C703);
    if (floatval($datarpc_val['valor']) > 0) {
        $_dsctoMandatoJudicial = concepto_0703($datarpc_val['valor']);
    }
    unset($datarpc_val);

    // CONCEPTO : TARDANZAS        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C704);
    if (floatval($datarpc_val['valor']) > 0) {
        $_tardanza = concepto_0704($obj->getSueldo_base(), $_asigFamiliar, $datarpc_val['valor']);
    }
    unset($datarpc_val);


    // CONCEPTO : MOVILIDAD SUPEDITADA A ASISTENCIA Y QUE CUBRE SÓLO EL TRASLADO        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C909);//ok
    if (floatval($datarpc_val['valor']) > 0) {
        $_movilidad = concepto_0909($datarpc_val['valor']);
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
            'cod_detalle_concepto' => C605,
            'monto_pagado' => $_r5ta,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C706,
            'monto_pagado' => $arreglo_0706['concepto'],
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
            'monto_pagado' => $arreglo_0105['concepto'],//full
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C106,
            'monto_pagado' => $arreglo_0106['concepto'],//full
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C107,
            'monto_pagado' => $_trabajoDiaFeriado,
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

    //|                            End Cargar Conceptos
    //|##############################################################################
    // CONCEPTO : ONP , AFP  
    if ($obj->getCod_regimen_pensionario() == '02') { //ONP
        $_onp = concepto_0607($conceptos);
        $conceptos[] = array('cod_detalle_concepto' => C607,'monto_pagado' => $_onp,'monto_devengado' => 0); 
        
    } else if (in_array($obj->getCod_regimen_pensionario(), $_arregloAfps)) {
        $arreglo_afp = concepto_AFP($obj->getCod_regimen_pensionario(), $conceptos); // 3 CONCEPTOS        
        $conceptos = array_merge($conceptos, $arreglo_afp);
    }


    // CONCEPTO : ESSALUD
    if ($obj->getCod_regimen_aseguramiento_salud() == '00') {
        $_essalud = concepto_0804($conceptos);
        $conceptos[] = array('cod_detalle_concepto' => C804,'monto_pagado' => $_essalud,'monto_devengado' => 0); 
    }
        
    
    $obj->setOrdinario_hora(($obj->getOrdinario_hora() + ($obj->getDia_laborado() * HORA_BASE)));
    //$dao_trapdecla->actualizar($obj);
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
    $id = $dao_tpd->registrar($obj);
    
    //registrar dia no subsidiado
    if($arreglo_0705['objdianosub']){
        echoo($arreglo_0705);
        echo "\n";
        echo "DIA NO SUBSIDIADO";
        echo "\n";
        echoo($obj_dianosub);
        
        $obj_dianosub = $arreglo_0705['objdianosub'];
        $obj_dianosub->setId_trabajador_pdeclaracion($id);        
        DiaNoSubsidiadoDao::anb_add($obj_dianosub);  
    }
    //registrar Conceptos Empresa
     if($arreglo_0706['prestamo_cuota'] || $arreglo_0706['obj_ptf']){
         $prestamo_cuota = $arreglo_0706['prestamo_cuota'];
         $ptf = $arreglo_0706['obj_ptf'];
         //Prestamo Cuota        
         if($prestamo_cuota){
             $dao_pc = new PrestamoCuotaDao();
             for($i=0;$i<count($prestamo_cuota);$i++){
                 $obj_pc = $prestamo_cuota[$i];                 
                 $dao_pc->pagarPrestamoCuota($obj_pc);                
             }
         }
         //Para ti familia
         if($ptf){ 
           $dao_ptf_pago = new PtfPagoDao();
            $dao_ptf_pago->add($ptf);
         }
     }
    
    //registrar declaraciones de conceptos.
    for($i=0;$i<count($conceptos);$i++){
        if($conceptos[$i]['monto_pagado']>0){
            $obj_ddc = new DeclaracionDconcepto();
            $obj_ddc->setId_trabajador_pdeclaracion($id);
            $obj_ddc->setCod_detalle_concepto($conceptos[$i]['cod_detalle_concepto']);
            $obj_ddc->setMonto_pagado($conceptos[$i]['monto_pagado']);
            $obj_ddc->setMonto_devengado($conceptos[$i]['monto_devengado']);
            $dao_ddc->registrar($obj_ddc);
        }
    }
    
    
    
}















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

function conceptoPorConcepto($data_tra, $ID_PDECLARACION, $PERIODO) {


    for ($i = 0; $i < count($data_tra); $i++) {

        calcular_IR5_concepto_0605($id_trabajador_pdeclaracion, $ID_PDECLARACION, $ID_TRABAJADOR[$i], $PERIODO);
        //SI
        //concepto_0605($id_trabajador_pdeclaracion, $monto);
        // Regimene Pensionario = xq suma segun los conceptos que Fueron pagados.
        // orden afecta al calculo al momento de consultar por datos!!!!
        //$arreglo_afps = array(21, 22, 23, 24);
        if ($DATA_TRA['cod_regimen_pensionario'] == '02') { //ONP
            concepto_0607($id_trabajador_pdeclaracion, $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '21') { //Integra
            concepto_AFP($id_trabajador_pdeclaracion, '21', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '22') { //horizonte
            concepto_AFP($id_trabajador_pdeclaracion, '22', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '23') { //Profuturo
            concepto_AFP($id_trabajador_pdeclaracion, '23', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '24') { //Prima
            concepto_AFP($id_trabajador_pdeclaracion, '24', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        }

        //SI
        // ++++++++++++++ ORDEN CONCEPTO ES NECESARIO ++++++++++++++
        // paso 04 :: Preguntar si el trabajador cumple:
        // TRIBUTOS Y APORTACIONES
        // Regimen de Salud
        if ($DATA_TRA['cod_regimen_aseguramiento_salud'] == '00') {
            //ESSALUD
            concepto_0804($id_trabajador_pdeclaracion, $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        }

        /*
          echo "<br> obj->getId_trabajador() = " . $obj->getId_trabajador();
          echo "<br>obj->getOrdinario_hora() = " . $obj->getOrdinario_hora();
          echo "<br>obj->getDia_laborado() = " . $obj->getDia_laborado();
          echo "<br>HORA_BASE = " . HORA_BASE;
         */
        $obj->setOrdinario_hora(($obj->getOrdinario_hora() + ($obj->getDia_laborado() * HORA_BASE)));
        $dao_trapdecla->actualizar($obj);
    }//ENDFOR    
}

function generarDeclaracionPlanillaMensual($ID_PDECLARACION, $PERIODO) {
    /* OJO Para controlar mejor :
     * 01 :: listado de todos los trabajadores activos con su padre Persona.
     * 02 :: Preguntar sii pertenece al periodo N.
     * 03 :: listar con certesa. 
     */

    //DAO    
    $dao_pd = new PlameDeclaracionDao();
    $dao_trapdecla = new TrabajadorPdeclaracionDao();

//==============================================================================

    $ids = $_REQUEST['ids'];
    //$ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];



    ECHO "ENTRO EN FUNCION UNICA PARA QUINCENA 2\n\n";
    $banderin = calcularSegudaQuincena($ID_PDECLARACION, $ids, $PERIODO);
    //var_dump($banderin);


    if (true) {
        // lista de trabajadores listados pagos =2 que x lo menos tiene 1 quincena
        // y agrupados 

        $data_traa = $dao_pd->listarDeclaracionEtapa_HIJO($ID_PDECLARACION);

        //echo "<pre> data_traa List 2 etapas 1 y 2 quincena Y agrupa";
        //print_r($data_traa);
        //echo "</pre>";
//-------------------------------------------------------------------
// ID seleccionados en el Grid    
        if (isset($ids)) {
            echo "<pre>[idsS]  Que Usted Selecciono en el Grid\n";
            print_r($ids);
            echo "</pre>";
            //------- filtro-------//
            $ids_tra = array();
            for ($i = 0; $i < count($ids); $i++) {
                for ($j = 0; $j < count($data_traa); $j++) {
                    if ($ids[$i] == $data_traa[$j]['id_trabajador']) {
                        $ids_tra[] = $data_traa[$j];
                        break;
                    }
                }
            }
            $data_traa = null;
            $data_traa = $ids_tra; //array_values($data_traa);  
        }



        //========== ELIMINAR LO QUE YA EXISTE en BD PLANILLA Conceptos ===================//
        $data_id_tra_db = $dao_trapdecla->listar_HIJO($ID_PDECLARACION);

        //print_r($data_id_tra_db);

        if (count($data_id_tra_db) > 0) {
            $data_tra_ref = $data_traa;

            for ($i = 0; $i < count($data_id_tra_db); $i++):

                for ($j = 0; $j < count($data_tra_ref); $j++):
                    //echo "\n<< i = $i : j = $j >>\n";                    
                    if ($data_id_tra_db[$i]['id_trabajador'] == $data_tra_ref[$j]['id_trabajador']):
                        $data_tra_ref[$j]['id_trabajador'] = null;
                        echo "encontro trabajador Y  BREAK!!;";
                        break;
                    endif;
                endfor;

            endfor;
            $data_traa = null;
            $data_traa = array_values($data_tra_ref);
        }
        //--


        $data = array_values($data_traa);
        //$ID_TRABAJADOR = array_values($data_traa);
        // paso 01 :: Get todos los -> id_trabajador
        $ID_TRABAJADOR = array();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['id_trabajador'] != null):
                $ID_TRABAJADOR[] = $data[$i]['id_trabajador'];
            endif;
        }
        $data = null;

//==============================================================================
        //paso 02 :: Registrar [trabajadores_pdeclaraciones]

        echo "\n<pre>INSERT PLANILLA ..!\n";
        VAR_DUMP($ID_TRABAJADOR);
        echo "</pre>\n";

        //DAO
        $dao_rpc = new RegistroPorConceptoDao();
        $dao_pago = new PagoDao();
        // Variables globales in
        //$data_val = array();
        // trabajador con 30 dias de vaccacion no pasara.
        // !!! ----------------- Array_id_vacaciones ---------------!!!//
        $dao_va = new TrabajadorVacacionDao();
        $data_vacacion = $dao_va->listaIDTrabajadoresVacacion($ID_PDECLARACION);
        $vaca_id = array_unique(arrayId($data_vacacion, 'id_trabajador'));
        $vaca_id = array_values($vaca_id);
        echo "id trabajadores de vacacion";
        echoo($vaca_id);

        // !!! ----------------- Array_id_vacaciones ---------------!!!//        




        for ($i = 0; $i < count($ID_TRABAJADOR); $i++) {
            $num_dia = 0;
            $bandera_vacacion = false;

            if (in_array($ID_TRABAJADOR[$i], $vaca_id)) {
                //$num_dia = $dao_va->getDiaVacacion($ID_PDECLARACION, $ID_TRABAJADOR[$i]);
                //if($num_dia == 30){
                continue;
                //}
                //$bandera_vacacion = true;
            }

            if (true) { // status vacacion!!
                $_0201 = 0; // asignacion familiar
                $_0705 = 0; // inasistencias 
                //Segenero 2 quincenas

                $data_sum = $dao_pago->dosQuincenas($ID_PDECLARACION, $ID_TRABAJADOR[$i]);

                // Nuevo TrabajadorPdeclaracion
                $obj = new TrabajadorPdeclaracion();
                $obj->setId_pdeclaracion($ID_PDECLARACION);
                $obj->setId_trabajador($ID_TRABAJADOR[$i]);
                $dia_lab = ($data_sum['dia_laborado']) ? $data_sum['dia_laborado'] : 0;
                $obj->setDia_laborado($dia_lab);
                $obj->setDia_total($data_sum['dia_total']);
                $obj->setOrdinario_hora(0);
                $obj->setOrdinario_min($data_sum['ordinario_min']);
                $obj->setSobretiempo_hora(0);
                $obj->setSobretiempo_min($data_sum['sobretiempo_min']);
                $data_sum['sueldo_no_tocado'] = $data_sum['sueldo'];
                $obj->setFecha_creacion(date("Y-m-d H:i:s"));
                $obj->setFecha_modificacion(date("Y-m-d"));
                $obj->setSueldo_neto($data_sum['sueldo']); //Sueldo Historial

                $DATA_TRA = $dao_trapdecla->buscar_ID_trabajador($ID_TRABAJADOR[$i]);

                //Registrar datos adicionales del Trabajador            
                $obj->setCod_tipo_trabajador($DATA_TRA['cod_tipo_trabajador']);
                $obj->setCod_regimen_pensionario($DATA_TRA['cod_regimen_pensionario']);
                $obj->setCod_regimen_aseguramiento_salud($DATA_TRA['cod_regimen_aseguramiento_salud']);
                $obj->setCod_situacion($DATA_TRA['cod_situacion']);
                //ADD 22/09/2012 
                $obj->setCod_ocupacion_p($DATA_TRA['cod_ocupacion_p']);
                $obj->setId_empresa_centro_costo($DATA_TRA['id_empresa_centro_costo']);

                $id_trabajador_pdeclaracion = $dao_trapdecla->registrar($obj);


// ------------------------------ INSERT CONCEPTOS -----------------------------//
                $obj = $dao_trapdecla->buscar_IDOject($id_trabajador_pdeclaracion);

                // "ASIGNACION FAMILIAR"; asignacion familiar fue MOvido ????????? ALERTTTT
                //DAO
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C201);
                if (intval($data_val['valor']) == 1) {
                    $_0201 = concepto_0201($id_trabajador_pdeclaracion);
                }
                //NO
                if ($data_sum['sueldo'] > 0) {
                    // 0705 = INASISTENCIAS
                    $data_val = array();
                    $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C705);
                    if (intval($data_val['valor']) > 0) {
                        $obj_dianosub = new DiaNoSubsidiado();
                        $obj_dianosub->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
                        $obj_dianosub->setCantidad_dia($data_val['valor']);
                        $obj_dianosub->setCod_tipo_suspen_relacion_laboral('07');
                        $obj->setDia_laborado(($obj->getDia_laborado() - $data_val['valor']));

                        //Add
                        DiaNoSubsidiadoDao::anb_add($obj_dianosub);

                        echo "\n\n\n\n0705 = INASISTENCIAS";
                        echo "\nSueldo a pasar : = " . $data_sum['sueldo'];
                        echo "\nAsignacio  Familiar : =" . $_0201;
                        echo "\ndata_val[valor] inasistencia = " . $data_val['valor'];

                        //INASISTENCIAS
                        $_0705 = concepto_0705($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']/* $data_sum['sueldo'] */, $_0201, $data_val['valor']);
                        unset($data_val);
                    }


                    //SUELDO BASICO 
                    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% SUELDO BASIC MODIFICADO %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%//  OK              
                    $data_sum['sueldo'] = concepto_0121($id_trabajador_pdeclaracion, $data_sum['sueldo'], $_0705);
                    // %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% SUELDO BASIC MODIFICADO %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%//
                    //ADELANTO 0
                    $bandera_adelanto = false;
                    if ($DATA_TRA['fecha_fin']) {
                        if (getFechaPatron($PERIODO, 'Y') == getFechaPatron($DATA_TRA['fecha_fin'], 'Y')):
                            if (getFechaPatron($PERIODO, 'm') == getFechaPatron($DATA_TRA['fecha_fin'], 'm')):
                                if (intval(getFechaPatron($DATA_TRA['fecha_fin'], 'd')) < 15):
                                    $bandera_adelanto = true;
                                endif;
                            endif;
                        endif;
                    }

                    // ADELANTO 1
                    if ($bandera_adelanto == false):
                        concepto_0701($id_trabajador_pdeclaracion, $ID_TRABAJADOR[$i], $ID_PDECLARACION);
                    endif;
                }


// ############################################################################## ojo.. sueldo normal.. no ALTERNA NADA.
                //SUELDO SETEADO PARA CALCULO: SB DEL PERIODO.            
                if ($data_sum['sueldo_no_tocado'] == $data_sum['sueldo']):
                    if ($DATA_TRA['monto_remuneracion_fijo']):
                    // monto Remuneracion Fijo MODIFICACION = 2
                    else:
                        $data_sum['sueldo'] = sueldoDefault($data_sum['sueldo']);
                    endif;
                else:
                // sueldo basico se resto Inasistencia! OK-FULL
                endif;

                $obj->setSueldo($data_sum['sueldo']);
// ##############################################################################
                // 001 = Prestamo ¿Preguntar si existe prestamo echo ?
                ECHO " <<<< 0706 == CONCEPTO EXCLUSIVO DE EMPRESA  PRESTAMO>>> ";
                //OTROS DESCUENTOS NO DEDUCIBLES A LA BASE IMPONIBLE PRESTAMO
                concepto_0706($id_trabajador_pdeclaracion, $ID_TRABAJADOR[$i], $ID_PDECLARACION, $PERIODO);


                /* // "ASIGNACION FAMILIAR";
                  //DAO
                  $data_val = array();
                  $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C201);
                  if (intval($data_val['valor']) == 1) {
                  $_0201 = concepto_0201($id_trabajador_pdeclaracion);
                  } */

// antes Asignacion Familiar
//FIJO NO MOVER varia calculo
                //--------------ANTES AFP -------------//
                // ESSALUD_MAS VIDA
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C604);
                if (intval($data_val['valor']) == 1) {
                    concepto_0604($id_trabajador_pdeclaracion);
                }

                //ASEGURA PENSION_MAS
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C612);
                if (intval($data_val['valor']) == 1) {
                    concepto_0612($id_trabajador_pdeclaracion);
                }
                //-----------------------------------------------------------
                // 0105 = TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 25%
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C105);
                if (floatval($data_val['valor']) > 0) {
                    $data_0105 = concepto_0105($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']/* $data_sum['sueldo'] */, $_0201, $data_val['valor']);
                    $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + $data_0105['hour']));
                    $obj->setSobretiempo_min(($obj->getSobretiempo_min() + $data_0105['min']));
                }


                // 0106 = TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 35%
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C106);
                if (floatval($data_val['valor']) > 0) {
                    $data_0106 = null;
                    $data_0106 = concepto_0106($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']/* $data_sum['sueldo'] */, $_0201, $data_val['valor']);
                    $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + $data_0106['hour']));
                    $obj->setSobretiempo_min(($obj->getSobretiempo_min() + $data_0106['min']));
                }


                // 0107 = TRABAJO EN DÍA FERIADO O DÍA DE DESCANSO
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C107);
                if (intval($data_val['valor']) > 0) {
                    concepto_0107($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']/* $data_sum['sueldo'] */, $_0201, $data_val['valor']);
                    $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + ($data_val['valor'] * HORA_BASE)));
                }


                //SI
                // 0304 = BONIFICACIÓN POR RIESGO DE CAJA
                $bonif_riesgocaja = 0;
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C304);
                if (floatval($data_val['valor']) > 0) {
                    $bonif_riesgocaja = concepto_0304($id_trabajador_pdeclaracion, $data_val['valor']);
                }

                //SI
                // 0703 = DESCUENTO AUTORIZADO U ORDENADO POR MANDATO JUDICIAL
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C703);
                if (floatval($data_val['valor']) > 0) {
                    concepto_0703($id_trabajador_pdeclaracion, $data_val['valor']);
                }

                //NO
                // 0704 = TARDANZAS
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C704);
                if (floatval($data_val['valor']) > 0) {
                    concepto_0704($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']/* $data_sum['sueldo'] */, $_0201, $data_val['valor']);
                }


                //SI
                // 0909 = MOVILIDAD SUPEDITADA A ASISTENCIA Y QUE CUBRE SÓLO EL TRASLADO
                $movilidad = 0;
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C909);
                if (floatval($data_val['valor']) > 0) {
                    $movilidad = concepto_0909($id_trabajador_pdeclaracion, $data_val['valor']);
                }

                //CALCULO AUTOMATICO DE:
                // - 28 de julio
                // - Navidad
                // - Bonificacion Extraordinaria... ()opcional se desabilita !!!!!!!!:  -_-|-_-
                //LINEA antes 512
                // 0115 = REMUNERACIÓN DÍA DE DESCANSO Y FERIADOS (INCLUIDA LA DEL 1° DE MAYO)
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $ID_TRABAJADOR[$i], C115);
                if (intval($data_val['valor']) > 1) {
                    $num_dia = $data_val['valor'];
                    concepto_0115($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']/* $data_sum['sueldo'] */, $_0201, $bonif_riesgocaja, $movilidad, $num_dia);
                    $obj->setSobretiempo_hora(($obj->getSobretiempo_hora() + ($data_val['valor'] * HORA_BASE)));
                }


                ECHO "\***INICIO*** FUNCTION gratificaccion de JULIO Y DICIEMBRE\n";
                //concepto_28_Navidad_LEY_29351($id_trabajador_pdeclaracion, $ID_PDECLARACION, $ID_TRABAJADOR[$i], $PERIODO);


                /**
                 * Calculo Renta de Quinta
                 */
                calcular_IR5_concepto_0605($id_trabajador_pdeclaracion, $ID_PDECLARACION, $ID_TRABAJADOR[$i], $PERIODO);
                //SI
                //concepto_0605($id_trabajador_pdeclaracion, $monto);
                // Regimene Pensionario = xq suma segun los conceptos que Fueron pagados.
                // orden afecta al calculo al momento de consultar por datos!!!!
                //$arreglo_afps = array(21, 22, 23, 24);
                if ($DATA_TRA['cod_regimen_pensionario'] == '02') { //ONP
                    concepto_0607($id_trabajador_pdeclaracion, $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
                } else if ($DATA_TRA['cod_regimen_pensionario'] == '21') { //Integra
                    concepto_AFP($id_trabajador_pdeclaracion, '21', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
                } else if ($DATA_TRA['cod_regimen_pensionario'] == '22') { //horizonte
                    concepto_AFP($id_trabajador_pdeclaracion, '22', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
                } else if ($DATA_TRA['cod_regimen_pensionario'] == '23') { //Profuturo
                    concepto_AFP($id_trabajador_pdeclaracion, '23', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
                } else if ($DATA_TRA['cod_regimen_pensionario'] == '24') { //Prima
                    concepto_AFP($id_trabajador_pdeclaracion, '24', $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
                }

                //SI
                // ++++++++++++++ ORDEN CONCEPTO ES NECESARIO ++++++++++++++
                // paso 04 :: Preguntar si el trabajador cumple:
                // TRIBUTOS Y APORTACIONES
                // Regimen de Salud
                if ($DATA_TRA['cod_regimen_aseguramiento_salud'] == '00') {
                    //ESSALUD
                    concepto_0804($id_trabajador_pdeclaracion, $ID_PDECLARACION, $ID_TRABAJADOR[$i]);
                }

                /*
                  echo "<br> obj->getId_trabajador() = " . $obj->getId_trabajador();
                  echo "<br>obj->getOrdinario_hora() = " . $obj->getOrdinario_hora();
                  echo "<br>obj->getDia_laborado() = " . $obj->getDia_laborado();
                  echo "<br>HORA_BASE = " . HORA_BASE;
                 */
                $obj->setOrdinario_hora(($obj->getOrdinario_hora() + ($obj->getDia_laborado() * HORA_BASE)));
                $dao_trapdecla->actualizar($obj);
            }//ENDFOR
        }//IF bandera vacacion
    }//Banderin 
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
function concepto_0121($monto, $_0705) {
    $neto = ($monto - $_0705);
    return $neto;
}

// ASIGNACION FAMILIAR
function concepto_0201() {
    //$SB = SB;
    //$CAL_AF = $SB * (T_AF / 100);
    $CAL_AF = asignacionFamiliar();
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
    $concepto_val = 0;
    $prestamo_val = array();
    $ptf_val=null;

    //Dao
    $dao_prestamo = new PrestamoDao();
    $dao_ptf = new ParatiFamiliaDao();

    // PRESTAMO - activo = 1
    $data_prestamo = $dao_prestamo->buscar_idTrabajador($id_trabajador, $PERIODO);
    if (count($data_prestamo) > 0) {
        $pcuota = prestamoCobrar_PorTrabajador($data_prestamo);
        $concepto_val = $concepto_val + $pcuota['monto'];

        for ($i = 0; $i < count($pcuota['id_prestamo_cutoa']); $i++):
            $obj_pc = new PrestamoCuota();
            $obj_pc->setId_prestamo_cutoa($pcuota['id_prestamo_cutoa'][$i]);
            $obj_pc->setMonto_pagado($pcuota['monto_duplex'][$i]);
            $obj_pc->setFecha_pago($PERIODO);
            $obj_pc->setEstado(1);
            $prestamo_val[] = $obj_pc;
        endfor;
    }

    // PARA TI FAMILIA  - solo registrados en db
    $ptfamilia = $dao_ptf->buscar_idTrabajador($id_trabajador, $PERIODO);
    if (isset($ptfamilia['id_para_ti_familia'])) {
        $calculo_ptf = ($ptfamilia['valor']);
        $concepto_val = $concepto_val + $calculo_ptf;
        // model
        $obj_pdt = new PtfPago();
        $obj_pdt->setId_para_ti_familia($ptfamilia['id_para_ti_familia']);
        $obj_pdt->setId_pdeclaracion($id_pdeclaracion);
        $obj_pdt->setFecha(date("Y-m-d"));
        $obj_pdt->setValor($calculo_ptf);
        //
        $ptf_val = $obj_pdt;
    }
    //echo "\n Calculo ah hacer es :";
    //echo "\ncalculado es  prestamo + para ti familia: " . $concepto_val . "\n";
    //echo "\n";
    $arreglo = array(
        'concepto' => $concepto_val,
        'prestamo_cuota' => $prestamo_val,
        'obj_ptf' => $ptf_val
    );
    return $arreglo;
}

function prestamoCobrar_PorTrabajador($data_prestamo) {

    $monto_pagar = 0;
    $id_prestamo_cuota_pago = array();
    $prestamo_cuota_pago = array();

    for ($i = 0; $i < count($data_prestamo); $i++):
        //sumar las cuotas de varios Prestamos
        if ($data_prestamo[$i]['cubodin'] == 1):
        //$monto_pagar = 0;
        else:
            if (floatval($data_prestamo[$i]['monto_variable']) > 0):
                $monto_pagar = $monto_pagar + floatval($data_prestamo[$i]['monto_variable']);
                $id_prestamo_cuota_pago[] = $data_prestamo[$i]['id_prestamo_cutoa'];
                $prestamo_cuota_pago[] = $data_prestamo[$i]['monto_variable'];
            else: // = NULL OR = 0.00
                $monto_pagar = $monto_pagar + floatval($data_prestamo[$i]['monto']);
                $id_prestamo_cuota_pago[] = $data_prestamo[$i]['id_prestamo_cutoa'];
                $prestamo_cuota_pago[] = $data_prestamo[$i]['monto'];
            endif;
        endif;

    endfor;

    $data = array();
    $data['monto'] = $monto_pagar;
    $data['id_prestamo_cutoa'] = $id_prestamo_cuota_pago;
    $data['monto_duplex'] = $prestamo_cuota_pago;
    return $data;
}

// RENTA DE QUINTA CATEGORIA
function concepto_0605($id_trabajador_pdeclaracion, $monto) {
    /*
      $CAL_AF = SB * (T_AF/100);
      $model = new DeclaracionDconcepto();
      $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
      $model->setMonto_devengado($CAL_AF);
      $model->setMonto_pagado($CAL_AF);
      $model->setCod_detalle_concepto('0201');

      $dao = new DeclaracionDconceptoDao();
      echo "<pre>";
      print_r($model);
      echo "</pre>";
      $dao->registrar($model);
     */
}

// SNP [ONP = 02]
function concepto_0607($conceptos) {
    //====================================================   
    $all_ingreso = get_SNP_Ingresos($conceptos);
    //====================================================
    $CALC = (floatval($all_ingreso)) * (T_ONP / 100);
    return $CALC;
}

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

// AFP o SPP 
// 0601 = Comision afp porcentual
// 0606 = Prima de suguro AFP
// 0608 = SPP aportacion obligatoria
function concepto_AFP($cod_regimen_pensionario, $conceptos) {
    //==================================================== 
    $all_ingreso = get_AFP_Ingresos($conceptos);
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

    $arreglo = array(
        array(
            'cod_detalle_concepto' => C601,
            'monto_pagado' => $_601,
            'monto_devengado'=>0
        ),
        array(
            'cod_detalle_concepto' => C606,
            'monto_pagado' => $_606,
            'monto_devengado'=>0
        ),
        array(
            'cod_detalle_concepto' => C608,
            'monto_pagado' => $_608,
            'monto_devengado'=>0
        )
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

//-----------------------------------------------------------------------------//
//.............................................................................//
//-----------------------------------------------------------------------------//

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
    if($id){
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
    $dao_pc = new PrestamoCuotaDao();
    $dao_pc->bajaPrestamoCuota($periodo, 0);

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

    $id = $_REQUEST['id'];//id_trabajadorpdeclaracion
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
    $dao_pc = new PrestamoCuotaDao();
    $dao_pc->bajaPrestamoCuota2($id_pdeclaracion, ID_EMPLEADOR,$id_trabajador); //ok?

    //eliminar para ti familia - pagos 
    $dao_ptf = new PtfPagoDao();
    $dao_ptf->del_idpdeclaracion2($id_trabajador);  //ok ?
    
    
    
    
    
    
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
