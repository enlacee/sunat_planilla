<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    // IDE CONFIGURACION 
    require_once '../controller/ConfController.php';


    //Actualizar PLAME   
    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../model/TrabajadorPdeclaracion.php';
    require_once '../dao/TrabajadorPdeclaracionDao.php';
    require_once '../dao/DeclaracionDconceptoDao.php';

    require_once '../model/DeclaracionDconcepto.php';

    // AFP
    require_once '../model/ConfAfp.php';
    require_once '../dao/ConfAfpDao.php';
    require_once '../controller/ConfAfpController.php';




    // POR UNICA VEZ UTILIZAMOS  librerias  calcularSegudaQuincena
    require_once '../controller/EtapaPagoController.php';
    //ETAPA PAGO
    require_once '../dao/EtapaPagoDao.php';
    require_once '../model/EtapaPago.php';

    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/PlameDao.php';

    //PAGO
    require_once '../dao/PagoDao.php';
    require_once '../model/Pago.php';

    //EPAGO TRABAJADOR
    require_once '../dao/PeriodoRemuneracionDao.php';
}
//????
$responce = NULL;
if ($op == "add") {
    //$responce = add_PtrabajadorPdeclaracion();
} else if ($op == "generar_declaracion") {
    generarDeclaracionPlanillaMensual();
}


echo (!empty($responce)) ? json_encode($responce) : '';

//EtapaPagoController
function calcularSegudaQuincena($ID_PDECLARACION) {
    $COD_PERIODO_REMUNERACION = 2;
    //$COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];
    $ids_trabajador = $_REQUEST['ids'];
    //========================================================================//
    $daoPlame = new PlameDeclaracionDao();
    $data_d = $daoPlame->buscar_ID($ID_PDECLARACION);

    $FECHA_PERIODO = $data_d['periodo'];
    $FECHAX = getFechasDePago($FECHA_PERIODO);
    $FECHA = array();
    //========================================================================//
    //---
    if (/* count($data_id_etapa_pago) == 1 */true) { //Segunda QUINCENA SI o SI
        $FECHA['inicio'] = $FECHAX['second_weeks_mas1']; //16/01/2012 a 31/01/2012
        $FECHA['fin'] = $FECHAX['last_day'];
        //================================
        $dao = new EtapaPagoDao();
        $id_etapa_pago = $dao->buscarEtapaPago_ID($ID_PDECLARACION, 2, 2);

        if (is_null($id_etapa_pago)) {

            $model = new EtapaPago();
            $model->setId_pdeclaracion($ID_PDECLARACION);
            $model->setCod_periodo_remuneracion($COD_PERIODO_REMUNERACION);
            $model->setFecha_inicio($FECHA['inicio']);
            $model->setFecha_fin($FECHA['fin']);
            $model->setGlosa("Segunda Quincena");
            $model->setTipo("2");
            $model->setFecha_creacion(date("Y-m-d"));

            $id_etapa_pago = $dao->registrar($model);
        }
        //--------------------------------
        registrar_15($id_etapa_pago, $FECHA['inicio'], $FECHA['fin']/* , $ids_trabajador */);

        //--------------------------------
        // registrar_15($id_etapa_pago, $FECHA['inicio'], $FECHA['fin']);
    } else {
        echo "CASO INCONTROLABLE";
    }
}

function generarDeclaracionPlanillaMensual() {
    /* OJO Para controlar mejor :
     * 01 :: listado de todos los trabajadores activos con su padre Persona.
     * 02 :: Preguntar sii pertenece al periodo N.
     * 03 :: listar con certesa. 
     */

//==============================================================================
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $ids = $_REQUEST['ids'];

    calcularSegudaQuincena($ID_PDECLARACION);

    echo "**************** paso de CALC segunda quincena 15";
    ECHO " ID_PDECLARACION   :::" . $ID_PDECLARACION;

    //DAO (workers list of declaracion)
    $dao = new PlameDeclaracionDao();
    $data_traa = $dao->listarDeclaracionEtapa($ID_PDECLARACION);
    
    echo "<pre> data_traa";
    print_r($data_traa);
    echo "</pre>";
    
    //TRABAJADORES YA REGISTRADOS (0 si no hay registrados aun)
    $dao_trapdecla = new TrabajadorPdeclaracionDao();
    $_data_id_trabajador = $dao_trapdecla->listar($ID_PDECLARACION, "id_trabajador");

    echo "<pre> _data_id_trabajador";
    print_r($_data_id_trabajador);
    echo "</pre>";

    /* --------------filtro de  id_trabajadores ------------- */
    for ($i = 0; $i < count($_data_id_trabajador); $i++) {
        for ($j = 0; $j < count($data_traa); $j++) {
            if ($_data_id_trabajador[$i]['id_trabajador'] == $data_traa[$j]['id_trabajador']) {
                unset($data_traa[$j]);
                break;
            }
        }
    }
    $data = array_values($data_traa);
    /* --------------filtro de  id_trabajadores ------------- */

    echo "<pre>  IDDDSSSSDDSSS";
      print_r($ids);
    echo "</pre>";  
    if (isset($ids)) {
        //filtro//
        $ids_tra = array();
        for ($i = 0; $i < count($ids); $i++) {
            for ($j = 0; $j < count($data); $j++) {
                if ($ids[$i] == $data[$j]['id_trabajador']) {
                    echo $data[$j]['id_trabajador']."ssssssssssssssssssssssssssssssss";
                    $ids_tra[]['id_trabajador'] = $data[$j]['id_trabajador']; //$data[$j];
                    break;
                }
            }
        }
        $data = null;
        $data = $ids_tra; //array_values($data_traa);  
    }

    // paso 01 :: Get todos los -> id_trabajador
    $ID_TRABAJADOR = array();
    for ($i = 0; $i < count($data); $i++) {
        $ID_TRABAJADOR[] = $data[$i]['id_trabajador'];
    }
    $data = null;
//==============================================================================
    //paso 02 :: Registrar [trabajadores_pdeclaraciones]

    echo "<pre> Insert  list trabajadores";
    print_r($ID_TRABAJADOR);
    echo "</pre>";
    for ($i = 0; $i < count($ID_TRABAJADOR); $i++) {

        //REGISTRAMOS TRABAJADOR (declaracion Mensual)
        // ..... anes Genero la Seguna Quincenaaaaa
        $dao_pago = new PagoDao();
        $data_sum = $dao_pago->dosQuincenas($ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        
        $obj = new TrabajadorPdeclaracion();
        $obj->setId_pdeclaracion($ID_PDECLARACION);
        $obj->setId_trabajador($ID_TRABAJADOR[$i]); 
        $obj->setDia_laborado($data_sum['dia_laborado']);
        $obj->setDia_total($data_sum['dia_laborado']);
        $obj->setOrdinario_hora($data_sum['ordinario_hora']);
        $obj->setOrdinario_min($data_sum['ordinario_min']);
        $obj->setSobretiempo_hora($data_sum['sobretiempo_hora']);
        $obj->setSobretiempo_min($data_sum['sobretiempo_min']);
        $obj->setSueldo($data_sum['sueldo']);
        $obj->setSueldo_neto($data_sum['sueldo_neto']);
        $obj->setEstado(0);
        $obj->setFecha_creacion(date("Y-m-d H:i:s"));
        
        
        $dao = new TrabajadorPdeclaracionDao();
        $id_trabajador_pdeclaracion = $dao->registrar($obj);
        //echo "id_trabajador_pdeclaracion = " . $id_trabajador_pdeclaracion;
        //....................................................................//
        // Otras utilidades
        $DATA_TRA = $dao->buscar_ID_trabajador($ID_TRABAJADOR[$i]);
        //....................................................................//
        // paso 03 :: Consultar Conceptos
        // INGRESOS
        ECHO "SEULDO BASICO";
        concepto_0121($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']);
        //Asignacion familiar
        ECHO "ASIGNACION FAMILIAR";
        concepto_0201($id_trabajador_pdeclaracion, $DATA_TRA['monto_remuneracion']);

        // DESCUENTOS - ADELANTO
        ECHO "DESCUENTO -ADELANTO ";
        concepto_0701($ID_TRABAJADOR[$i], $ID_PDECLARACION, $id_trabajador_pdeclaracion);

        // paso 04 :: Preguntar si el trabajador cumple:
        // TRIBUTOS Y APORTACIONES
        // Regimen de Salud
        if ($DATA_TRA['cod_regimen_aseguramiento_salud'] == '00') { //ok Regimen de Salud Regular
            concepto_0804($id_trabajador_pdeclaracion);
        } else {
            // null 
        }
        // Regimen Pensionario
        //AFP 


        if ($DATA_TRA['cod_regimen_pensionario'] == '02') { //ONP
            concepto_0607($id_trabajador_pdeclaracion);
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '21') { //Integra            
            concepto_AFP($id_trabajador_pdeclaracion, '21');
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '22') { //horizonte
            concepto_AFP($id_trabajador_pdeclaracion, '22');
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '23') { //Profuturo
            concepto_AFP($id_trabajador_pdeclaracion, '23');
        } else if ($DATA_TRA['cod_regimen_pensionario'] == '24') { //Prima
            concepto_AFP($id_trabajador_pdeclaracion, '24');
        } else {
            //null
        }


        //Otra utilidades
        if ($DATA_TRA['aporta_essalud_vida'] == '1') { // ESSALUD_MAS
            concepto_0604($id_trabajador_pdeclaracion);
        }

        if ($DATA_TRA['aporta_asegura_tu_pension'] == '1') { //ASEGURA PENSION_MAS
            concepto_0612($id_trabajador_pdeclaracion);
        }
        //-----------------------------------------------------------
    }//ENDFOR
}

// Sueldo Basico
function concepto_0121($id_trabajador_pdeclaracion, $monto_remuneracion) {

    //SUELDO BASICO    
    $SUELDO_BASE = $monto_remuneracion;
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($SUELDO_BASE);
    $model->setMonto_pagado($SUELDO_BASE);
    $model->setCod_detalle_concepto('0121');

    $dao = new DeclaracionDconceptoDao();

    return $dao->registrar($model);
}

// ASIGNACION FAMILIAR
function concepto_0201($id_trabajador_pdeclaracion, $monto_remuneracion) {
    //SUELDO BASICO
    $SB = $monto_remuneracion;
    $CAL_AF = $SB * (T_AF / 100);
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($CAL_AF);
    $model->setMonto_pagado($CAL_AF);
    $model->setCod_detalle_concepto('0201');

    $dao = new DeclaracionDconceptoDao();
    $dao->registrar($model);

    return true;
}

// Adelanto en este caso la suma de las 2 QUINCENAS ?????????? DUDAAA!!! 
function concepto_0701($ID_TRABAJADOR, $ID_PDECLARACION, $id_trabajador_pdeclaracion) {

    // 01 :: = Consultar Trabajador
    $dao = new PlameDeclaracionDao();
    $ADELANTO = $dao->PrimerAdelantoMensual($ID_TRABAJADOR, $ID_PDECLARACION);


    // 02 ::    
    //ADELANTO    
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($ADELANTO);
    $model->setMonto_pagado($ADELANTO);
    $model->setCod_detalle_concepto('0701');

    $dao = new DeclaracionDconceptoDao();
    return $dao->registrar($model);
}

// RENTA DE QUINTA CATEGORIA
function concepto_0605($id_trabajador_pdeclaracion) {
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
function concepto_0607($id_trabajador_pdeclaracion) {

    //====================================================          
    $all_ingreso = allConceptos_Afectos_Ingreso($id_trabajador_pdeclaracion);
    //ECHO " all_ingreso = " . $all_ingreso;
    //====================================================

    $CALC = (floatval($all_ingreso)) * (T_ONP / 100);

    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    //$model->setMonto_devengado($CALC);
    $model->setMonto_pagado($CALC);
    $model->setCod_detalle_concepto('0607');
    //dao
    $dao = new DeclaracionDconceptoDao();
    return $dao->registrar($model);
}

// AFP o SPP 
// 0601 = Comision afp porcentual
// 0606 = Prima de suguro AFP
// 0608 = SPP aportacion obligatoria
function concepto_AFP($id_trabajador_pdeclaracion, $cod_regimen_pensionario) {

    //====================================================          
    $all_ingreso = allConceptos_Afectos_Ingreso($id_trabajador_pdeclaracion);
    //ECHO " all_ingreso = " . $all_ingreso;
    //====================================================    
    //----
    $afp = new ConfAfp();
    $afp = vigenteAfp($cod_regimen_pensionario);
    //----

    $A_OBLIGATORIO = floatval($afp->getAporte_obligatorio());
    $COMISION = floatval($afp->getComision());
    $PRIMA_SEGURO = floatval($afp->getPrima_seguro());


    // UNO = comision porcentual
    $_601 = (floatval($all_ingreso)) * ($COMISION / 100);

    // DOS prima de seguro
    $_606 = (floatval($all_ingreso)) * ($PRIMA_SEGURO / 100);

    // TRES = aporte obligatorio
    $_608 = (floatval($all_ingreso)) * ($A_OBLIGATORIO / 100);


    // uno DAO
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    //$model->setMonto_devengado($CALC);
    $model->setMonto_pagado($_601);
    $model->setCod_detalle_concepto('0601');
    $dao = new DeclaracionDconceptoDao();
    $dao->registrar($model);


    // dos DAO
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    //$model->setMonto_devengado($CALC);
    $model->setMonto_pagado($_606);
    $model->setCod_detalle_concepto('0606');
    $dao = new DeclaracionDconceptoDao();
    $dao->registrar($model);

    // tres DAO
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    //$model->setMonto_devengado($CALC);
    $model->setMonto_pagado($_608);
    $model->setCod_detalle_concepto('0608');
    $dao = new DeclaracionDconceptoDao();
    $dao->registrar($model);

    return true;
}

// 604 ESSALUD + VIDA
function concepto_0604($id_trabajador_pdeclaracion) {

    $CALC = ESSALUD_MAS;
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    //$model->setMonto_devengado($CALC);
    $model->setMonto_pagado($CALC);
    $model->setCod_detalle_concepto('0604');

    $dao = new DeclaracionDconceptoDao();
    return $dao->registrar($model);
}

// 612 SNP ASEGURA TU PENSIÓN +
function concepto_0612($id_trabajador_pdeclaracion) {
    $CALC = SNP_MAS;
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    // $model->setMonto_devengado($CALC);
    $model->setMonto_pagado($CALC);
    $model->setCod_detalle_concepto('0612');

    $dao = new DeclaracionDconceptoDao();
    return $dao->registrar($model);
}

/*
 * {Funcion ayudante} OjO conceptos bien ordenados
 */

function allConceptos_Afectos_Ingreso($id_trabajador_pdeclaracion) {

    //====================================================
    //DAO
    $dao = new DeclaracionDconceptoDao();
    $DATA = $dao->buscar_ID_TrabajadorPdeclaracion($id_trabajador_pdeclaracion);

    $monto = 0;
    for ($i = 0; $i < count($DATA); $i++) {
        if ($DATA[$i]['cod_detalle_concepto'] == '0121') {
            // Sueldo basico
            $monto = $monto + (floatval($DATA[$i]['monto_pagado']));
            //continue;           
        } else if ($DATA[$i]['cod_detalle_concepto'] == '0201') {
            // Asignacion Familiar
            $monto = $monto + (floatval($DATA[$i]['monto_pagado']));
        } else if ($DATA[$i]['cod_detalle_concepto'] == '0107') {
            //trabajo en feriado o dia descanso  -----------------PENDIENTE
            $monto = $monto + (floatval($DATA[$i]['monto_pagado']));
        } else if ($DATA[$i]['cod_detalle_concepto'] == '0118') {
            //Remuneracion Vacacional *?*   -----------------PENDIENTE
            $monto = $monto + (floatval($DATA[$i]['monto_pagado']));
        }
    }
    //====================================================
    return $monto;
}

// 804 ESSALUD trabajador
function concepto_0804($id_trabajador_pdeclaracion) {
    //====================================================          
    $all_ingreso = allConceptos_Afectos_Ingreso($id_trabajador_pdeclaracion);
    //====================================================

    $CALC = floatval($all_ingreso) * (T_ESSALUD / 100);

    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($CALC);
    $model->setMonto_pagado($CALC);
    $model->setCod_detalle_concepto('0804');

    $dao = new DeclaracionDconceptoDao();
    return $dao->registrar($model);
}

