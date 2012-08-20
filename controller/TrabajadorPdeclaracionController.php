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
    require_once '../dao/TrabajadorPdeclaracionDao.php';
    require_once '../dao/DeclaracionDconceptoDao.php';

    require_once '../model/DeclaracionDconcepto.php';
}

$responce = NULL;
if ($op == "add") {
    //$responce = add_PtrabajadorPdeclaracion();
} else if ($op == "generar_declaracion") {
    generarDeclaracionPlanillaMensual();
}


echo (!empty($responce)) ? json_encode($responce) : '';

function generarDeclaracionPlanillaMensual() {
//==============================================================================
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

    $dao = new PlameDeclaracionDao();
    $data = $dao->listarDeclaracionEtapa($ID_PDECLARACION);
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    // paso 01 :: Get todos los -> id_trabajador
    $ID_TRABAJADOR = array();
    for ($i = 0; $i < count($data); $i++) {
        $ID_TRABAJADOR[] = $data[$i]['id_trabajador'];
    }
    $data = null;
//==============================================================================
    //paso 02 :: Registrar [trabajadores_pdeclaraciones]


    for ($i = 0; $i < count($ID_TRABAJADOR); $i++) {
        
        echo "             -----------------------------             ";
        //-----------------------------------------------------------
        //REGISTRAMOS TRABAJADOR (declaracion Mensual)
        $dao = new TrabajadorPdeclaracionDao();
        $id_trabajador_pdeclaracion = $dao->registrar($ID_PDECLARACION, $ID_TRABAJADOR[$i]);
        echo "id_trabajador_pdeclaracion = " . $id_trabajador_pdeclaracion;

        // paso 03 :: Consultar Conceptos
        $conceptos = array('0121','0201');
        

            //SUELDO BASICO
            ECHO "SEULDO BASICO";
            concepto_0121($id_trabajador_pdeclaracion);
            //Asignacion familiar
            ECHO "ASIGNACION FAMILIAR";
            concepto_0201($id_trabajador_pdeclaracion);
            
            //ADELANTO
            ECHO "DESCUENTO";
            concepto_0701($ID_TRABAJADOR[$i],$ID_PDECLARACION, $id_trabajador_pdeclaracion);

   ??????
       

       /* switch ($concepto) {
            case "0121":
                concepto_0121($id_trabajador_pdeclaracion);
                echo "genero 0121";
                break;

            default:
                break;
        }*/
        //-----------------------------------------------------------
    }//ENDFOR
}


// Sueldo Basico
function concepto_0121($id_trabajador_pdeclaracion) {

    //SUELDO BASICO
    $SUELDO_BASE = SB;   
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($SUELDO_BASE);
    $model->setMonto_pagado($SUELDO_BASE);
    $model->setCod_detalle_concepto('0121');

    $dao = new DeclaracionDconceptoDao();
    echo "<pre>";
    print_r($model);
    echo "</pre>";
    $dao->registrar($model);
}


// ASIGNACION FAMILIAR
function concepto_0201($id_trabajador_pdeclaracion) {
    //SUELDO BASICO
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
}




// Adelanto en este caso la suma de las 2 QUINCENAS ?????????? DUDAAA!!! 
function concepto_0701($ID_TRABAJADOR,$ID_PDECLARACION, $id_trabajador_pdeclaracion) {
    
    // 01 :: = Consultar Trabajador
    $dao = new PlameDeclaracionDao();
    $ADELANTO = $dao->PrimerAdelantoMensual($ID_TRABAJADOR, $ID_PDECLARACION);
     
    
    // 02 ::
    // 
    //ADELANTO    
    $model = new DeclaracionDconcepto();
    $model->setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion);
    $model->setMonto_devengado($ADELANTO);
    $model->setMonto_pagado($ADELANTO);
    $model->setCod_detalle_concepto('0701');

    $dao = new DeclaracionDconceptoDao();
    echo "<pre>";
    print_r($model);
    echo "</pre>";

    $dao->registrar($model);
}