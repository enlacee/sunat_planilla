<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    // IDE CONFIGURACION 
    //require_once '../controller/ConfController.php';
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

    //PAGO
    require_once '../dao/PagoDao.php';
    require_once '../model/Pago.php';

    //EPAGO TRABAJADOR
    require_once '../dao/PeriodoRemuneracionDao.php';
}

$response = NULL;
if ($op == "add") {
    //$response = add_PtrabajadorPdeclaracion();
} else if ($op == "generar_declaracion") {
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    generarConfiguracion($ID_PDECLARACION);

    generarDeclaracionPlanillaMensual($ID_PDECLARACION);
} else if ($op == "cargar_tabla_2") {

    $response = listar_trabajadorPdeclaracion();
} else if ($op == "grid_lineal") {

    $response = cargar_tabla_grid_lineal();
} else if ($op == "del") {
    $response = eliminar_trabajadorPdeclaracion();
}


echo (!empty($response)) ? json_encode($response) : '';

function generarConfiguracion($ID_PDECLARACION) {

    $daox = new PlameDeclaracionDao();
    $datax = $daox->buscar_ID($ID_PDECLARACION);
    $periodo = $datax['periodo'];

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
    $ESSALUD_MAS = 5;
    $SNP_MAS = 5;
    echo "8888888888888888888888888888888888888888888888";
    
    echo "SB =".$SB;
    echo "T_AF =".$T_AF;
    echo "T_ESSALUD =".$T_ESSALUD;
    echo "T_ONP =".$T_ONP;
    echo "UIT =".$UIT;
    
    echo "8888888888888888888888888888888888888888888888";



    // DEFINE
    if (is_null($SB) || is_null($T_AF) || is_null($T_ESSALUD) || is_null($T_ONP) || is_null($UIT)) {
        //header($string, $replace)
        //header('Location: www.google.com');

        $SB = 0;
        $T_AF = 0;
        //$T_ESSALUD = 0;
        $T_ONP = 0;
        $UIT = 0;

        $ESSALUD_MAS = 0;
        $SNP_MAS = 0;
    } else {
        define('SB', $SB);
        define('T_AF', $T_AF);
        //define('T_ESSALUD', $T_ESSALUD);
        define('T_ONP', $T_ONP);
        define('UIT', $UIT);

        define('ESSALUD_MAS', $ESSALUD_MAS);
        define('SNP_MAS', $SNP_MAS);
    }
}

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

function generarDeclaracionPlanillaMensual($ID_PDECLARACION) {
    /* OJO Para controlar mejor :
     * 01 :: listado de todos los trabajadores activos con su padre Persona.
     * 02 :: Preguntar sii pertenece al periodo N.
     * 03 :: listar con certesa. 
     */

//==============================================================================
    //$ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
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

    if (count($data_traa) == count($_data_id_trabajador)) {
        echo "DATOS YA SON IGUALES NO PUEDE seguir registrando MAS. [TRUNCADO-MENSUAL]! ";
        return false;
    }


//    echo "<pre> _data_id_trabajador";
//    print_r($_data_id_trabajador);
//    echo "</pre>";

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


        // LISTADO DE trabajadores ya registrados en el periodo o mes ejem: mm/yyyy               
        //echo "id_trabajador_pdeclaracion = " . $id_trabajador_pdeclaracion;
        //....................................................................//
        // Otras utilidades
        $dao = new TrabajadorPdeclaracionDao();
        $DATA_TRA = $dao->buscar_ID_trabajador($ID_TRABAJADOR[$i]);

        //Registrar datos adicionales del Trabajador
        $obj->setIngreso_5ta_categoria(0);
        $obj->setCod_tipo_trabajador($DATA_TRA['cod_tipo_trabajador']);
        $obj->setCod_regimen_pensionario($DATA_TRA['cod_regimen_pensionario']);
        $obj->setCod_regimen_aseguramiento_salud($DATA_TRA['cod_regimen_aseguramiento_salud']);
        $obj->setCod_situacion($DATA_TRA['cod_situacion']);

        $id_trabajador_pdeclaracion = $dao->registrar($obj);
        //....................................................................//
        // paso 03 :: Consultar Conceptos
        // INGRESOS
        ECHO "SEULDO BASICO";
        concepto_0121($id_trabajador_pdeclaracion, $data_sum['sueldo']/* $DATA_TRA['monto_remuneracion'] */);
        //Asignacion familiar
        ECHO "ASIGNACION FAMILIAR";
        concepto_0201($id_trabajador_pdeclaracion, $data_sum['sueldo']/* $DATA_TRA['monto_remuneracion'] */);

        // DESCUENTOS - ADELANTO
        ECHO "DESCUENTO -ADELANTO ";
        concepto_0701($ID_TRABAJADOR[$i], $ID_PDECLARACION, $id_trabajador_pdeclaracion);

        // paso 04 :: Preguntar si el trabajador cumple:
        // TRIBUTOS Y APORTACIONES
        // Regimen de Salud
        if ($DATA_TRA['cod_regimen_aseguramiento_salud'] == '00') { //ok Regimen de Salud Regular
            concepto_0804($id_trabajador_pdeclaracion, $ID_PDECLARACION);
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
    $SB = null;
    if ($monto_remuneracion < SB) {
        $SB = SB;
    } else {
        $SB = $monto_remuneracion;
    }

    //$SUELDO_BASE = $monto_remuneracion;
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($SB);
    $model->setMonto_pagado($SB);
    $model->setCod_detalle_concepto('0121');

    $dao = new DeclaracionDconceptoDao();

    return $dao->registrar($model);
}

// ASIGNACION FAMILIAR
function concepto_0201($id_trabajador_pdeclaracion, $monto_remuneracion) {
    //SUELDO BASICO
    $SB = null;
    if ($monto_remuneracion < SB) {
        $SB = SB;
    } else {
        $SB = $monto_remuneracion;
    }
    //$SB = $monto_remuneracion;

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
    ECHO " OHMM ALL all_ingreso = " . $all_ingreso;
    //====================================================

    $CALC = (floatval($all_ingreso)) * (T_ONP / 100);
    
    echo "T_ONP  = ".T_ONP;
    echo "*-**-*--**-*-*-**-*";
    echo "CALC = ".$CALC;
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
    echo "all_ingreso =" . $all_ingreso;

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
function concepto_0804($id_trabajador_pdeclaracion, $ID_PDECLARACION) {
    //====================================================          
    $all_ingreso = allConceptos_Afectos_Ingreso($id_trabajador_pdeclaracion);
    //====================================================
    $dao = new PlameDeclaracionDao();
    $arreglo = $dao->buscar_ID($ID_PDECLARACION);

    //--- TASA ESSALUD = T_ ESSALUD
    $dao_3 = new ConfEssaludDao();
    $T_ESSALUD = $dao_3->vigenteAux($arreglo['periodo']); //2012-01-01
    echo "<pre>";
    print_r($arreglo);
    echo "</pre>";

    echo " T_ESSALUD " . $T_ESSALUD;
    var_dump($T_ESSALUD);
    echo "herehereherhe ****************************** ";

    $CALC = floatval($all_ingreso) * ($T_ESSALUD / 100);
    //$CALC = 

    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($CALC);
    $model->setMonto_pagado($CALC);
    $model->setCod_detalle_concepto('0804');

    $dao = new DeclaracionDconceptoDao();
    return $dao->registrar($model);
}

//-----------------------------------------------------------------------------//
//.............................................................................//
//-----------------------------------------------------------------------------//

function listar_trabajadorPdeclaracion() {

    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

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
    $lista = $dao->listar($ID_PDECLARACION, null, $WHERE);
//echo "<pre>";
//print_r($lista);
//echo "</pre>";
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


        $js2 = "javascript:eliminarTrabajadorPdeclaracion('" . $param . "')";
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar"   >
		<a href="' . $js . '" class="divEditar" ></a>
		</span>              
                
		<span  title="Editar"   >
		<a href="' . $js2 . '" class="divEliminar" ></a>
		</span>

		</div>';




        //hereee
        //$_02 = '<a href="javascript:add_15('.$param.',\''.$_01.'\')" title = "Agregar UNICO Adelanto 15">1era 15</a>';
        // $_04 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/view_etapaPago.php?id_declaracion='.$param.'&periodo='.$_01.'\',\'#CapaContenedorFormulario\')"title = "VER">Ver</a>';
        //hereee
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

//echo "<pre>";
//print_r($response);
//echo "</pre>";
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

// view-plame
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
    $model->setSueldo_neto($data['sueldo_neto']);
    $model->setEstado($data['estado']);
    $model->setFecha_creacion($data['fecha_creacion']);
    $model->setFecha_modificacion($data['fecha_modificacion']);
    $model->setIngreso_5ta_categoria($data['ingreso_5ta_categoria']);
    $model->setCod_tipo_trabajador($data['cod_tipo_trabajador']);
    $model->setCod_regimen_pensionario($data['cod_regimen_pensionario']);
    $model->setCod_regimen_aseguramiento_salud($data['cod_regimen_aseguramiento_salud']);
    $model->setCod_situacion($data['cod_situacion']);

    return $model;
}

function eliminar_trabajadorPdeclaracion() {
    $id = $_REQUEST['id'];

    $dao = new TrabajadorPdeclaracionDao();
    return $dao->eliminar($id);
}