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
    
    //libreria FUNCIONES_AYUDA
    require_once '../dao/PrestamoDao.php';
    require_once '../dao/ParatiFamiliaDao.php';
    require_once '../model/PrestamoCuota.php';
    require_once '../model/PtfPago.php';
}

if ($op == "generar") {
    $response = planillaVacacion();
} else if ($op == "boleta_vacacion") {
    //boletaVacacacion();
} else if ($op == 'cargar_tabla') {
    $response = cargarTablaTrabajdorVacacion();
}else if($op =="del"){    
    $response = eliminarTVacacion();
}else if($op =="delAll"){
    $response = eliminarAll();
}


echo (!empty($response)) ? json_encode($response) : '';

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
                //echo "encontro trabajador  = NULL Y  BREAK!!;";
                break;
            endif;
        endfor;
    endfor;
    //echoo($data_tra);
    $contador = 0;
    for ($i = 0; $i < count($data_tra); $i++) {        
        //$i = 0;       
        if ($data_tra[$i]['id_trabajador'] != null) {
            // Variables
            $contador++;
            $dia_vacacion = 0;

            //FECHAS
            //******************************************************************
            $data_vdetalle = $daovd->vacacionDetalle($data_tra[$i]['id_vacacion']);
            $fecha = getFechasDePago($PERIODO);
//            echo "\ndata_vdetalle vacaciones";
//            echoo($data_vdetalle);
//            echo "\n\n";
            $data_ask = leerVacacionDetalle($data_vdetalle, $PERIODO, $fecha['first_day'], $fecha['last_day']);
            //******************************************************************
            if ($data_ask['dia'] > 0) {

                $dia_vacacion = $dia_vacacion + ($data_ask['dia']);
                echo "\ndia_vacacion antes = $dia_vacacion";
                $SUELDO_CAL = 0;
                $proceso_porcentaje = 0;
                if ($dia_vacacion == 30) { // dias completos del mes trabajados             
                    $SUELDO_CAL = $data_tra[$i]['monto_remuneracion'];
                    $proceso_porcentaje = 100;
                } else if ($dia_vacacion < 30) {
                    $smpd = sueldoMensualXDia($data_tra[$i]['monto_remuneracion']);
                    //xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxX
                    //$rest_bug_31 = (getFechaPatron($f_fin, "d")>30)? 1 : 0;         // bug en 31 dias
                    //$dia_vacacion = $dia_vacacion -$rest_bug_31;
                    if((getFechaPatron($fecha['last_day'],"d"))>=31){
                        if($dia_vacacion==16){
                            $dia_vacacion = 15;
                        }
                    }
                    //xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxX
                    $SUELDO_CAL = $smpd * $dia_vacacion;
                    //--------
                    $calc_porcentaje = 100 * ($dia_vacacion / 30);
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
    $response->rpta = true;
    $response->mensaje = "Num de trabajadores Procesados [$contador]";
    return $response;
}

//END-FUNCTION

function conceptoPorConceptoXDVacacion($obj, $data_ayuda, $ID_PDECLARACION, $PERIODO) {
    //DAO
    $dao_rpc = new RegistroPorConceptoDao();  // a calcular data 50/50 si<30 dias.
    // Arreglo data_rpc = lista de conceptos del trabajador.    
    $datarpc = $dao_rpc->buscar_RPC_PorTrabajador2($ID_PDECLARACION, $obj->getId_trabajador());
    $pporcentaje = $obj->getProceso_porcentaje() / 100;
    echo "\nPORCENTAJE = ".$pporcentaje;
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
            $_asigFamiliar = concepto_0201() * $pporcentaje;
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
    $arreglo_0706 = concepto_0706($obj->getId_trabajador(), $ID_PDECLARACION, $PERIODO);


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

    // CONCEPTO : MOVILIDAD SUPEDITADA A ASISTENCIA Y QUE CUBRE SÓLO EL TRASLADO        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C909); //ok
    if (floatval($datarpc_val['valor']) > 0) {
        $_movilidad = concepto_0909($datarpc_val['valor']) * $pporcentaje;
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
            'monto_pagado' => $arreglo_0706['concepto'] * $pporcentaje, // OK FULL PRESTAMO+PTF EMPRESA
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C604,
            'monto_pagado' => $_essaludMasVida, //no usado
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C612,
            'monto_pagado' => $_aseguraPensionMas, //no usado
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
    if ($ingresos5ta > 1400) { // 2012-2013  promedio para optimizar        
        $_r5ta = calcular_IR5_concepto_0605($ID_PDECLARACION, $obj->getId_trabajador(), $PERIODO, $conceptos);
        $_r5ta = $_r5ta * $pporcentaje;
        $conceptos[] = array('cod_detalle_concepto' => C605, 'monto_pagado' => ($_r5ta), 'monto_devengado' => 0);
    }
    
    // NOTA:
    // No es necesario sacar Porcentaje xq solo  el desvuento es en base a los conceptos Es proporcional el descuento que
    // se realiza en estas Operacions.
    // CONCEPTO : ONP , AFP  
    if ($obj->getCod_regimen_pensionario() == '02') { //ONP        
        $_onp = concepto_0607($conceptos);
        $conceptos[] = array('cod_detalle_concepto' => C607, 'monto_pagado' => $_onp, 'monto_devengado' => 0);
    } else if (in_array($obj->getCod_regimen_pensionario(), $_arregloAfps)) { 
        $arreglo_afp = concepto_AFP($obj->getCod_regimen_pensionario(), $conceptos); // 3 CONCEPTOS        
        //$conceptos = array_merge($conceptos, $arreglo_afp); 
        $_601 = $arreglo_afp['0601'];
        $_606 = $arreglo_afp['0606'];
        $_608 = $arreglo_afp['0608'];        
        $conceptos[] = array('cod_detalle_concepto' => C601, 'monto_pagado' => $_601, 'monto_devengado' => 0);
        $conceptos[] = array('cod_detalle_concepto' => C606, 'monto_pagado' => $_606, 'monto_devengado' => 0);
        $conceptos[] = array('cod_detalle_concepto' => C608, 'monto_pagado' => $_608, 'monto_devengado' => 0);
    }

    // CONCEPTO : ESSALUD
    if ($obj->getCod_regimen_aseguramiento_salud() == '00') {
        $_essalud = concepto_0804($conceptos);
        $conceptos[] = array('cod_detalle_concepto' => C804, 'monto_pagado' => $_essalud, 'monto_devengado' => 0);
    }

    //==========================================================================
    // --------------------- Registrar Data -------------------------
    //==========================================================================
//    echo "<br>\n";
//    echo "<hr>";
//    echoo($obj);
//    echo "<br>\nSIN REDONDEO";
//    echo "<hr>";
//    echoo($conceptos);



                 
    $dao_tv = new TrabajadorVacacionDao();
    $dao_ddc = new DeclaracionDConceptoVacacionDao();
    $id = $dao_tv->add($obj);
    
/* REORGANIZAR TABLAS DE PAGOS :prestamo y paratifamilia.
    //registrar Conceptos Empresa
    if ($arreglo_0706['prestamo_cuota'] || $arreglo_0706['obj_ptf']) {
        $prestamo_cuota = $arreglo_0706['prestamo_cuota'];
        //Prestamo Cuota        
        if ($prestamo_cuota) {
            $dao_pc = new PrestamoCuotaDao();
            $obj_pc = new PrestamoCuota();
            for ($i = 0; $i < count($prestamo_cuota); $i++) {
                $obj_pc = $prestamo_cuota[$i];
                //Re-setear
                $obj_pc->setMonto_pagado(($obj_pc->getMonto()*$pporcentaje));
                $dao_pc->pagarPrestamoCuota($obj_pc);
                $dao_pc->add($obj);
            }
        }
        //Para ti familia OK FULL.  ojoooooooooooooooooo NECESARIO LIMPIAR SI ELIMINA PLANILLA DE VACACIONES!!!!!!!!!!!!!!!!!!         
        if ($arreglo_0706['obj_ptf']) {
            $dao_ptf_pago = new PtfPagoDao();
            $objPtf = new PtfPago();
            $objPtf = $arreglo_0706['obj_ptf'];
            //Re-setear
            $objPtf->setValor(($objPtf->getValor() * $pporcentaje));
            //$dao_ptf_pago->add($objPtf);
            echo "\nPARA TI FAMILIA obj";
            echoo($objPtf);
        }
    }
*/
    
    //registrar declaraciones de conceptos.
    $concepto_redondeo = array();
    for ($i = 0; $i < count($conceptos); $i++) {
        if ($conceptos[$i]['monto_pagado'] > 0) {

            $soles = getRendondeoEnSoles($conceptos[$i]['monto_pagado']);
            $monto_pagado = $soles['numero'];
            $monto_devengado = $soles['decimal'];
            //LOAD
            $concepto_redondeo[] = array(
                'cod_detalle_concepto' => $conceptos[$i]['cod_detalle_concepto'],
                'monto_pagado' => $monto_pagado,
                'monto_devengado' => $monto_devengado
            );

            $obj_ddc = new DeclaracionDConceptoVacacion();
            $obj_ddc->setId_trabajador_vacacion($id);
            $obj_ddc->setCod_detalle_concepto($conceptos[$i]['cod_detalle_concepto']);
            $obj_ddc->setMonto_pagado($monto_pagado);
            $obj_ddc->setMonto_devengado($monto_devengado);
            $dao_ddc->add($obj_ddc);
        }
    }
    //echo "\nCON REFONDEADO !!";
    //echoo($concepto_redondeo);
}

//-----------------------------------------------------------------------------//
//.............................................................................//
//-----------------------------------------------------------------------------//
function cargarTablaTrabajdorVacacion(){
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];

    //$dao_trabajador = new TrabajadorDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
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
    $dao_tv = new TrabajadorVacacionDao();    
    $count = $dao_tv->listarCount($ID_PDECLARACION, $WHERE);


    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        //$total_pages = 0;
    }

    if ($page > $total_pages)
        $page = $total_pages;


    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;

    //llena en al array
    $lista = $dao_tv->listar($ID_PDECLARACION, $WHERE, $start, $limit, $sidx, $sord);
    //$lista = $daoVacacion->listar(ID_EMPLEADOR_MAESTRO, $ID_PDECLARACION, $WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
    //$lista = $lista[0];
    foreach ($lista as $rec) {
        $param = $rec["id_trabajador_vacacion"];
        $_01 = $rec["id_trabajador"];
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];
        $_06 = $rec["nombres"];

        $js7 = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_vacacion_2.php?id_vacacion=" . $param . "&id_pdeclaracion=" . $ID_PDECLARACION . "&periodo=" . $PERIODO . "','#CapaContenedorFormulario')";
        $_07 = '<a href="' . $js7 . '" class="divEditar" ></a>';
        
        $js8 = "javascript:eliminarTrabajadorVacacion('$param',$_01)";        
        $_08 = '<a href="' . $js8 . '" class="divEliminar" ></a>';        
  

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
            $_08
        );

        $i++;
    }

    return $response;
}

function eliminarTVacacion(){   //OK 
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $id_trabajador = $_REQUEST['id_trabajador'];
    $id = $_REQUEST['id'];

    //paso 01 Elimar trabajador_vacacion
    $dao_tv = new TrabajadorVacacionDao();
    $rpta = $dao_tv->eliminar($id);
    return $rpta;
}
function eliminarAll(){ //OK
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];  
    $dao_tv = new TrabajadorVacacionDao();
    $rpta = $dao_tv->eliminarAll($id_pdeclaracion);
    return $rpta;
}
//---- end vacacion
function boletaVacacacion() {
    
}
