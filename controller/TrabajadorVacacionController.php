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
    require_once '../dao/PlameAfectacionDao.php';


    //DATA  fuck
    require_once '../dao/TrabajadorPdeclaracionDao.php';


    //vacacion
    require_once '../dao/VacacionDao.php';
    require_once '../model/TrabajadorVacacion.php';
    require_once '../dao/TrabajadorVacacionDao.php';
    require_once '../dao/VacacionDetalleDao.php';
    // daos adicionales planilla
    require_once '../dao/TrabajadorDao.php';
    
    //RENTA DE QUINTA
    require_once '../controller/IR5Controller.php';
     
     
      require_once '../dao/DeclaracionDConceptoVacacionDao.php';

      require_once '../model/TrabajadorVacacion.php';
      require_once '../model/DeclaracionDConceptoVacacion.php';

    
    //registro por concepto PREGUNTAR
    require_once '../dao/RegistroPorConceptoDao.php';
    //ZIP
    require_once '../util/zip/zipfile.inc.php';
    
    require_once '../controller/funcionesAyuda.php';
    
}

if ($op == "generar") {
    $response = planillaVacacion();
} else if ($op == "boleta_vacacion") {
    //boletaVacacacion();
}


function planillaVacacion() {
    //$ids = $_REQUEST['ids'];
    $PERIODO = $_REQUEST['periodo'];
    $anio = getFechaPatron($PERIODO, "Y");
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

    generarConfiguracion($PERIODO);
    generarConfiguracion2($PERIODO);
    //echoo($_REQUEST);
    //echoo($_SESSION);    
    // 01 consultar trabajadores con vacacion
    //DAO
    $daov = new VacacionDao();
    $daotv = new TrabajadorVacacionDao();
    $daovd = new VacacionDetalleDao();
    $dao_tra = new TrabajadorDao();
    //model
    $model_tpd = new TrabajadorVacacion();

    $data_tra = $daov->trabajadoresConVacacion(ID_EMPLEADOR_MAESTRO, $anio);

    // LLENA DE NULL SI YA FUERON REGISTRADOS EN LA PLANILLA MENSUAL.
    $data_id_tra_db = $daotv->listar_HIJO($ID_PDECLARACION);
    for ($i = 0; $i < count($data_id_tra_db); $i++):
        for ($j = 0; $j < count($data_tra); $j++):
            if ($data_id_tra_db[$i]['id_trabajador'] == $data_tra[$j]['id_trabajador']):
                $data_tra[$j]['id_trabajador'] = null;
                echo "encontro trabajador  = NULL Y  BREAK!!;";
                break;
            endif;
        endfor;
    endfor;
    //echoo($data_tra);

    for ($i = 0; $i < count($data_tra); $i++) {
        //$i = 0;
        if ($data_tra[$i]['id_trabajador'] != null) {
            // Variables
            $dia_vacacion = 0;
            
            //FECHAS
            //******************************************************************
            $data_vdetalle = $daovd->vacacionDetalle($data_tra[$i]['id_vacacion']);
            $fecha = getFechasDePago($PERIODO);
            $f_inicio = $fecha['first_day'];
            $f_fin = $fecha['last_day'];
            $data_ask = leerVacacionDetalle($data_vdetalle,$PERIODO, $f_inicio,$f_fin);
            //******************************************************************
            if ($data_ask['dia'] > 0) {

                $dia_vacacion = $dia_vacacion + ($data_ask['dia']);                
                $SUELDO_CAL = 0;
                $proceso_porcentaje = 0;
                if ($dia_vacacion == 30) { // dias completos del mes trabajados             
                    $SUELDO_CAL = $data_tra[$i]['monto_remuneracion'];
                    $proceso_porcentaje = 100;
                } else if ($dia_vacacion < 30) {
                    $smpd = sueldoMensualXDia($data_tra[$i]['monto_remuneracion']);
                    $SUELDO_CAL = $smpd * $dia_vacacion;
                    //--------
                    $calc_porcentaje = 100*($dia_vacacion/30);
                    $arregloNum = getRendondeoEnSoles($calc_porcentaje);
                    $proceso_porcentaje = $arregloNum['numero'];
                    //echo "sueldo calcl 3 =" . $SUELDO_CAL;
                }

                // -------------- obj Trabajador Pdeclaracion --------------
                $model_tpd->setId_pdeclaracion($ID_PDECLARACION);
                $model_tpd->setId_trabajador($data_tra[$i]['id_trabajador']);
                $model_tpd->setFecha_lineal($data_ask['fecha_lineal']);
                $model_tpd->setDia($dia_vacacion);
                $model_tpd->setSueldo($SUELDO_CAL);
                $model_tpd->setSueldo_base($data_tra[$i]['monto_remuneracion']);                
                $model_tpd->setProceso_porcentaje($proceso_porcentaje);
                $model_tpd->setFecha_creacion(date("Y-m-d"));

                // Registrar datos adicionales del Trabajador  
                $data_tra_adit = $dao_tra->buscarDataForPlanilla($data_tra[$i]['id_trabajador']);
                $model_tpd->setCod_regimen_pensionario($data_tra_adit['cod_regimen_pensionario']);
                $model_tpd->setCod_regimen_aseguramiento_salud($data_tra_adit['cod_regimen_aseguramiento_salud']);
                //echo "\n<br>i = $i<br>";
                //echoo($model_tpd);
            }//END IF

            
            //data_ayuda
            //$data_ayuda = array(
            //    'quincena' => $quincena_sueldo//$data_quincena['sueldo']
            //);
            conceptoPorConceptoXDVacacion($model_tpd, $data_ayuda, $ID_PDECLARACION, $PERIODO);
        }//END IF (id_trabajador = NULL)
    }//END-FOR
}//END-FUNCTION



function conceptoPorConceptoXDVacacion($obj, $data_ayuda, $ID_PDECLARACION, $PERIODO) {
    //DAO
    $dao_rpc = new RegistroPorConceptoDao();  // a calcular data 50/50 si<30 dias.
    // Arreglo data_rpc = lista de conceptos del trabajador.    
    $datarpc = $dao_rpc->buscar_RPC_PorTrabajador2($ID_PDECLARACION, $obj->getId_trabajador());
    $pporcentaje = $obj->getProceso_porcentaje()/100;

    //Variables locales
    $_arregloAfps = array(21, 22, 23, 24);
    $_asigFamiliar = 0;
    $_sueldoBasico = 0;
    
    //$_r5ta = 0;
    $_essaludMasVida = 0;
    $_aseguraPensionMas = 0;
    $_bonifRiesgoCaja = 0;
    $_dsctoMandatoJudicial = 0;    
    $_movilidad = 0;
    $_onp = 0;
    $_essalud = 0;  // concepto_0804();
    
    // CONCEPTO : ASIGNACION FAMILIAR
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C201);
    if (is_array($datarpc_val)) {
        if (intval($datarpc_val['valor']) == 1) {
            $_asigFamiliar = concepto_0201()*$pporcentaje;
        }
    }
    unset($datarpc_val);


    if ($obj->getSueldo() > 0) {
        // CONCEPTO : SUELDO BASICO             
        $_sueldoBasico = concepto_0121($obj->getSueldo()); //ok
        unset($datarpc_val);
    }


    //  ECHO " <<<< 0706 == CONCEPTO EXCLUSIVO DE EMPRESA  PRESTAMO >>> ";
    //  CONCEPTO : OTROS DESCUENTOS NO DEDUCIBLES A LA BASE IMPONIBLE PRESTAMO
   // $arreglo_0706 = concepto_0706($obj->getId_trabajador(), $ID_PDECLARACION, $PERIODO);


    //----------------- fijo no mover varia el calculo-------------------
    // CONCEPTO : ESSALUD_MAS VIDA    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C604);
    if (intval($datarpc_val['valor']) == 1) {
        $_essaludMasVida = concepto_0604()*$pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : ASEGURA PENSION_MAS     
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C612);
    if (intval($datarpc_val['valor']) == 1) {
        $_aseguraPensionMas = concepto_0612()*$pporcentaje;
    }
    unset($datarpc_val);


    // CONCEPTO : BONIFICACIÓN POR RIESGO DE CAJA
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C304);
    if (floatval($datarpc_val['valor']) > 0) {
        $_bonifRiesgoCaja = concepto_0304($datarpc_val['valor'])*$pporcentaje;
    }
    unset($datarpc_val);


    // CONCEPTO : DESCUENTO AUTORIZADO U ORDENADO POR MANDATO JUDICIAL    *****************************************************************    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C703);
    if (floatval($datarpc_val['valor']) > 0) {
        $_dsctoMandatoJudicial = concepto_0703($datarpc_val['valor'])*$pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : MOVILIDAD SUPEDITADA A ASISTENCIA Y QUE CUBRE SÓLO EL TRASLADO        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C909);//ok
    if (floatval($datarpc_val['valor']) > 0) {
        $_movilidad = concepto_0909($datarpc_val['valor'])*$pporcentaje;
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
            'cod_detalle_concepto' => C121,
            'monto_pagado' => $_sueldoBasico,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C706,
            'monto_pagado' => $arreglo_0706['concepto'], // OJO!!!!!!!!!!!!!!!!!!!!!!
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
            'cod_detalle_concepto' => C909,
            'monto_pagado' => $_movilidad,
            'monto_devengado' => 0
        ),
    );

    //|                            End Cargar Conceptos
    //|##############################################################################
    // CONCEPTO : RENTA DE QUINTA 
    $ingresos5ta = get_IR5_Ingresos($ID_PDECLARACION, $obj->getId_trabajador(), $conceptos);
    if($ingresos5ta > 1400){ // 2012-2013  promedio para optimizar        
        $_r5ta = calcular_IR5_concepto_0605($ID_PDECLARACION, $obj->getId_trabajador(),$PERIODO,$conceptos);
        $conceptos[] = array('cod_detalle_concepto' => C605,'monto_pagado' => ($_r5ta),'monto_devengado' => 0);
    }   
    
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
  
    //==========================================================================
    // --------------------- Registrar Data -------------------------
    //==========================================================================
    echo "<br>\n";
    echo "<hr>";
    //echoo($obj);
    echo "<br>\n";
    echo "<hr>";
    //echoo($conceptos);
    
    
    
    
    $dao_tv = new TrabajadorVacacionDao();
    $dao_ddc = new DeclaracionDConceptoVacacionDao();    
    $id = $dao_tv->add($obj);
/*    
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
   */    
    //registrar declaraciones de conceptos.
    $concepto_redondeo = array();
    for($i=0;$i<count($conceptos);$i++){
        if($conceptos[$i]['monto_pagado']>0){
            
            $soles = getRendondeoEnSoles($conceptos[$i]['monto_pagado']);            
            $monto_pagado = $soles['numero'];
            $monto_devengado = $soles['decimal'];
            //LOAD
            $concepto_redondeo[] =  array(
                'cod_detalle_concepto' => $conceptos[$i]['cod_detalle_concepto'],
                'monto_pagado'         => $monto_pagado,
                'monto_devengado'      => $monto_devengado
             );
            
            $obj_ddc = new DeclaracionDConceptoVacacion();
            $obj_ddc->setId_trabajador_vacacion($id);
            $obj_ddc->setCod_detalle_concepto($conceptos[$i]['cod_detalle_concepto']);
            $obj_ddc->setMonto_pagado($monto_pagado);
            $obj_ddc->setMonto_devengado($monto_devengado);
            $dao_ddc->add($obj_ddc);
        }
    }
    echo "REFONDEADO !!";
    echoo($concepto_redondeo);

  
    
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






// Sueldo Basico
function concepto_0121($monto) {
    $neto = ($monto);
    return $neto;
}

// ASIGNACION FAMILIAR
function concepto_0201() { 
    //$CAL_AF = asignacionFamiliar();
    $CAL_AF = SB*(T_AF / 100);    
    return $CAL_AF;
}

// BONIFICACION POR RIESGO DE CAJA.
function concepto_0304($monto) {
    return $monto;
}


// DESCUENTO POR MANDATO JUDICIAL
function concepto_0703($monto,$pporcentaje) {// 10/09/2012
    return ($monto*$pporcentaje);
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
    return (ESSALUD_MAS);
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
    return ($monto);
}

//-----------------------------------------------------------------------------//
//.............................................................................//
//-----------------------------------------------------------------------------//




// 0706 OTROS DESCUENTOS NO DEDUCIBLES A LA BASE IMPONIBLE
/*
  require_once '../dao/AbstractDao.php';
  require_once '../dao/PrestamoDao.php';
  require_once '../dao/PrestamoCuotaDao.php';
  require_once '../model/PrestamoCuota.php';
  require_once '../util/funciones.php';
  concepto_0706(1, 2, 1, '2012-10-01');
 */
// OJO CONCEPTO EN OBSERVACION!!!
function concepto_0706($id_trabajador, $id_pdeclaracion, $PERIODO,$pporcentaje=0) {
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
        $concepto_val = $concepto_val + ($pcuota['monto']*$pporcentaje);

        for ($i = 0; $i < count($pcuota['id_prestamo_cutoa']); $i++):
            $obj_pc = new PrestamoCuota();
            $obj_pc->setId_prestamo_cutoa($pcuota['id_prestamo_cutoa'][$i]);
            $obj_pc->setMonto_pagado( ($pcuota['monto_duplex'][$i]*$pporcentaje) );
            $obj_pc->setFecha_pago($PERIODO);
            //$obj_pc->setEstado(1);
            $prestamo_val[] = $obj_pc;
        endfor;
    }

    // PARA TI FAMILIA  - solo registrados en db
    $ptfamilia = $dao_ptf->buscar_idTrabajador($id_trabajador, $PERIODO);
    if (isset($ptfamilia['id_para_ti_familia'])) {
        $calculo_ptf = $ptfamilia['valor']* $pporcentaje;
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



// usado pàra prestamo en vacacion y demas concepto de empleador
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









//delete1!!!!
//--------------------------------------- UHM----------------------------------!
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
function boletaVacacacion() {
    
}
