<?php

require_once '../util/funciones.php';
require_once '../dao/AbstractDao.php';
require_once '../dao/PlameDeclaracionDao.php';
require_once '../dao/DeclaracionDconceptoDao.php';
require_once '../dao/PlameDetalleConceptoAfectacionDao.php';
//configuracion

//require_once '../controller/ConfController.php';
//require_once '../dao/PlameAfectacionDao.php';
//// AFP
//require_once '../model/ConfAfp.php';
//require_once '../dao/ConfAfpDao.php';
// require_once '../dao/ConfAfpTopeDao.php';
//require_once '../controller/ConfAfpController.php';
//// IDE CONFIGURACION 
//require_once '../dao/ConfAsignacionFamiliarDao.php';
//require_once '../dao/ConfSueldoBasicoDao.php';
//require_once '../dao/ConfEssaludDao.php';
//require_once '../dao/ConfOnpDao.php';
//require_once '../dao/ConfUitDao.php';

// new $conceptos = antes de carga en base de datos.
function calcular_IR5_concepto_0605($ID_PDECLARACION, $id_trabajador, $PERIODO,array $conceptos) {
    
    echoo($ID_PDECLARACION);
    echoo($id_trabajador);
    echoo($PERIODO);
    
 /*
        echoo($_SESSION);
        echo "<br>\n\n";
        echo "ID_EMPLEADOR_MAESTRO = ".ID_EMPLEADOR_MAESTRO;     
        echo 'SB'. SB;
        echo '\n';
        echo 'T_AF'. T_AF;
        echo '\n';
        echo 'T_ESSALUD'. T_ESSALUD;
        echo '\n';
        echo 'T_ONP'. T_ONP;
        echo '\n';
        echo 'UIT'. UIT;
        echo '\n';
        echo 'ESSALUD_MAS'. ESSALUD_MAS;
        echo '\n';
        echo 'SNP_MAS'. SNP_MAS;
        echo '\n';
    */ 
    $periodo = $PERIODO;   

    //"2012-01-01"; //  Enero 2012
    $mes_periodo = intval(getFechaPatron($periodo, 'm'));
    $anio_periodo = intval(getFechaPatron($periodo, 'Y'));
//| -----------------------------------------------------
//| --- VOCABULARIO ---                                 |
//| r = remuneracion                                    |
//| gra = gratificacio-+
//| 
//| ----------------------------------------------------

// 01 remuneracion fija
    $_01 = get_IR5_Ingresos($ID_PDECLARACION, $id_trabajador,$conceptos); //$sueldo; //2000;
    echo "\n01= " . $_01;
    
// 02 num_meses que falta
    $_02 = get_IR5_mesFaltan($periodo);
    echo "\n02 = " . $_02;



// 03 remuneracion proyectada
    $_03 = $_01 * $_02;
    echo "\n03 = " . $_03;    
    //echo "R_PROYECTADA = " . $_03;
// 04 Gratificaciones ordinarias(JUL-DIC)
    $var_jul_dic = get_IR5_NumGratificacionesFaltaPagar($periodo);
    // 2 = mayor a Enero Y Menos a julio devuelve.
    // 1 = antes de diciembre devuelve.
    echo "\n-- = num de gratificacion " . $var_jul_dic;
    $_04 = $_01 * $var_jul_dic;
    echo "\n04 = " . $_04;   


// 05 Bonificacion Extraordinaria(1) 9% Essalud  ///obtener los ingresos de junio 
    $_05 = 0.00;
    if ($mes_periodo == 7) { //JULIO
        $periodo_armado = $anio_periodo . "-07-01";
        $arreglo_conceptos = array('0312');//bonificacion extraordinaria.
        $monto = getMontoConceptoPeriodo($id_trabajador, $arreglo_conceptos, $periodo_armado);
        $_05 = $monto;
    } else if ($mes_periodo == 12) {
        $periodo_armado = $anio_periodo . "-12-01";
        $arreglo_conceptos = array('0312');
        $monto = getMontoConceptoPeriodo($id_trabajador, $arreglo_conceptos, $periodo_armado);
        $_05 = $monto;
    }
    echo $_05;
    echo "\n05 = " . $_05."     ->Bonificacion extraordinaria"; 

    // 06 Total    
    $_06 = $_03 + $_04 + $_05;
    echo "\n06 = " . $_06."      ->Total";

// 07 Remuneraciones de meses anteriores
    $_07 = get_IR5_RMesesAnteriores($ID_PDECLARACION, $id_trabajador, $periodo); //remuneracion de enero ...getRMesesAnteriores();
    echo "\n07 = " . $_07;    

// 08 gratificaciones de meses anteriores
    $_08 = 0.00;  //Gratificacion anterior el Unico es 28 DE JULIO.   
    $_08 = get_IR5_NumGratificacionesdeMesesAnterioresXXX($id_trabajador, $periodo);
    echo "\n08 = " . $_08;

// 09 Bonificacion Extraordinaria, de meses anteriores
    $arreglo_conceptos = array('0312');
    $_09 = buscarMontodeConceptoMesesAtras($arreglo_conceptos, $id_trabajador, $periodo); //getIr5_NumBonificacionesDeMesesAnteriores($periodo);
    echo "\n09 = " . $_09;
   

// 17 Renta Mensual    
    $_17 = $_06 + $_07 + $_08 + $_09;

    echo "\n17 = " . $_17;

// 18 Menos 7 UIT = CANTIDAD DE DINERO EN UIT
    echoo(UIT);
    $_18 = 7 * UIT; // ejem : 7*3650
    echo "\n18 = " . $_18;    
    

if($_17 > $_18){  //Si mi sueldo no supera las 7 uit NO aplica RTA  
    
    $_19 = $_17 - $_18;
    echo "\n19 = ".$_19;
// --------------------- Tasas progresivas acumulativas-----------------------//
    // 20 : Hasta 27UIT
        $_20 = tasa_15($_19,UIT);
        echo "\n20 = $_20";

    // 21 : De 27 UIT hasta 54UIT
        $_21 = tasa_21($_19,UIT);
        echo "\n21 = $_21";
    // 22 : Exeso de 54UIT    
        $_22 = tasa_30($_19,UIT);
        echo "\n22 = $_22";
        
    // 23 : Impuesto anual
        $_23 = $_20 + $_21 + $_22;
        echo "\n23 = $_23";        
}



// 24 : Retenciones(2)

    $_24 = 0;    
    if ($mes_periodo == 12) { // Recalcular toma en cuenta todos los meses R5TA       
        $_24 = get_IR5_ImpuestoARetenerAnteriores( $id_trabajador, $periodo); 
        echo "\n24 solo ocurre 12=diciembre = $_24";
    }else{
        //echo "\n24 solo ocurre 1,2,3,..11  = $_24";
        $_24 = Retenciones($id_trabajador, $ID_PDECLARACION, $periodo);  
    }
    echo "\n24 = $_24";
 
// 25 : Impuesto a pagar
    $_25 = ($mes_periodo == 12) ? ($_24-$_23) : ($_23-$_24) ;    
//$_25 = $_23 - $_24;
    //echo "\n25 = MES = 12 ->(24 - 23)  O 23 -24 \n";
    echo "\n25 = $_25";

// 26 : Divisor del impuesto a la renta(3)
    $arreglo_fecha = array();
    $arreglo_fecha = get_IR5_DivisorDelImpuestoRenta($periodo);
    $_26 = $arreglo_fecha['padre']; //get_IR5_DivicisorImpuestoRenta($periodo);
    echo "\n26 = $_26";
// 27 : Impuesto a retener mensual 
    $_27 = ($_25 / $_26);
    echo "\n27 = $_27";
    return $_27;
}

/*
  $renta_mensual=184450;
  echo "\nRENTA MENSUAL = ".$renta_mensual;

  echo "\nrenta a 15\n";
  echo "<pre>";
  print_r(tasa_15($renta_mensual));
  echo "</pre>";

  echo "\nrenta a 21\n";
  echo "<pre>";
  print_r(tasa_21($renta_mensual));
  echo "</pre>";


  echo "\nrenta a 30\n";
  echo "<pre>";
  print_r(tasa_30($renta_mensual));
  echo "</pre>";
 */

//..............................................................................

function tasa_15($_19, $uit) { //ya se le aplica tasa SI o SI.

    $val_27UIT = 27 * $uit; // 27*3700=99900    
    $dato = 0;    
    if ($_19 >= $val_27UIT) { //corta los q exeden 27UIT
        $dato = $_19;//$val_UIT;
    } else if ($_19 < $val_27UIT) {//OKK
        $dato = $_19;
    }

    $_20 = $dato * 0.15;
    return ($_20 > 0) ? $_20 : 0;
}

function tasa_21($_19, $uit) {
    $val_27UIT = 27 * $uit;
    $val_54UIT = 54 * $uit; //UIT;// 54*3650 =197100

    $_19 = $_19 - $val_27UIT;

    //---------
    $dato = 0;
    if ($_19 >= $val_27UIT && $_19<=$val_54UIT) { //corta los q exeden 27UIT
        $dato = $_19;
    } else if ($_19 < $val_27UIT) {
        //$dato = $_19;
    }

    $_20 = $dato * 0.21;
    return ($_20 > 0) ? $_20 : 0;
}

function tasa_30($_19, $uit) {
    $val_27UIT = 27 * $uit;
    $val_54UIT = 54 * $uit; // 54*3650 =197100

    $_19 = $_19 - ($val_27UIT * 2); //Paso 2 tasas ok.
    //---------
    $dato = 0;
    if ($_19 > $val_54UIT) { //corta los q exeden 54UIT
        $dato = $_19;
    }

    $_20 = $dato * 0.30;
    return ($_20 > 0) ? $_20 : 0;
}

//..............................................................................


function get_IR5_SumaImpuestoRetenidosMensuales($id_trabajador, $id_pdeclaracion, $arreglo_mes) {

    // padre['padre'] =; 
    // padre['hijo'] =;
    if (is_array($arreglo_mes)) { //consultar a base datos        


    }
}

/*
 * 
 * 
 * 
 * PRUEBA
 * 
 * 
  $periodo = "2012-12-01";
  $id_trabajador = 1;
  Retenciones($id_trabajador, $id_pdeclaracion, $periodo);

 * 
 * 
 * 
 * 
 * 
 * 
 *  
 * 
 * 
 *
 */

function Retenciones($id_trabajador, $id_pdeclaracion, $periodo) {

    $num_mes = intval(getFechaPatron($periodo, "m"));
    $rpta = null;
//$fecha = '2012-01-01';
    $data = get_IR5_DivisorDelImpuestoRenta($periodo);

    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";

//CLAVES R5
    if ($num_mes == 4) {
        $num_mes = $num_mes - 1;
        $r = recursiva($id_trabajador, $num_mes, $periodo);
    } else if ($num_mes == 5 || $num_mes == 6 || $num_mes == 7) {
        //$num_mes = 3;
        $_03 = recursiva($id_trabajador, 3, $periodo);
        //$num_mes = 4;//$num_mes - 1;
        $_04 = recursiva($id_trabajador, 4, $periodo);

        $r = ($_03 + $_04);
        //echo "suma para $num_mes = ".$r; //227
    } else if ($num_mes == 8) {


        $_03 = recursiva($id_trabajador, 3, $periodo);
        //$num_mes = 4;//$num_mes - 1;
        $_04 = recursiva($id_trabajador, 4, $periodo);

        $r = ($_03 + $_04) + $_03;

        //echo "suma para $num_mes = ".$r;
    } else if ($num_mes == 9 || $num_mes == 10 || $num_mes == 11) {
        //$num_mes = $num_mes - 1;
        //----------------------------------------------------
        $_03 = recursiva($id_trabajador, 3, $periodo);
        //$num_mes = 4;//$num_mes - 1;
        $_04 = recursiva($id_trabajador, 4, $periodo);

        $r = ($_03 + $_04) + $_03;
        //---------------------------------------------------
        $_09 = recursiva($id_trabajador, 8, $periodo); //THIS = 8 

        $r = $r + $_09;
        //echo "suma para $num_mes = ".$r;
    } else if ($num_mes == 12) { // Recorrer Preguntar.
        $r = regularizacionEnDiciembre($id_trabajador, $num_mes, $periodo);
    } else {
        $r = 0;
    }




    return $r;
}

function regularizacionEnDiciembre($id_trabajador, $num_mes, $periodo) {

    $p_dia = getFechaPatron($periodo, "d");
    $p_mes = getFechaPatron($periodo, "m");
    $p_anio = getFechaPatron($periodo, "Y");

    $perido_armado = array();
    for ($i = 1; $i < 12; $i++) {
        $fecha = $p_anio . "-" . $i . "-" . $p_dia;
        $perido_armado[] = getFechaPatron($fecha, "Y-m-d");
    }


    $id_pdeclaracion_lab = array();

    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $p_anio);

    for ($z = 0; $z < count($data_pd); $z++) {
        if (in_array($data_pd[$z]['periodo'], $perido_armado)) {

            $id_pdeclaracion_lab[] = $data_pd[$z]['id_pdeclaracion'];
            //echo "\n $z\n";
            //break;
        }
    }

    //echo "<hr>NEW NEW id_pdeclaracion_lab";
    //echo "<pre>";
    //print_r($id_pdeclaracion_lab);
    //echo "</pre>";
//..............................................................................
    $conceptos_afectos = array('0605');
//..............................................................................
    $dao_dconcepto = new DeclaracionDconceptoDao();

    $sum = 0;
    for ($i = 0; $i < count($id_pdeclaracion_lab); $i++) {

        $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion_lab[$i]);
        //var_dump ($data_dconcepto);
        for ($z = 0; $z < count($data_dconcepto); $z++) {
            if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {
                //echo "\<br>encontro en id_pdeclaracion_lab = ".$id_pdeclaracion_lab[$i];

                $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
                //echo "<br>";
                //echo "id_pdeclaracion_lab =".$id_pdeclaracion_lab[$i];           
                //echo "\n<br> sum =".$data_dconcepto[$z]['monto_pagado'];
                //echo "<br>";
                //break; //encontro SALE.
            }
        }
    }

    //echo "sum = ".$sum;
    return $sum;
}

function recursiva($id_trabajador, $num_mes, $periodo) {

    //-------  
    $p_dia = getFechaPatron($periodo, "d");
    $p_mes = getFechaPatron($periodo, "m");
    $p_anio = getFechaPatron($periodo, "Y");

    $periodo_lab = $p_anio . "-" . $num_mes . "-" . $p_dia;

    $arreglo = get_IR5_DivisorDelImpuestoRenta($periodo_lab); //OJO

    //echo "<pre> this vale FOR";
    //print_r($arreglo);
    //echo "</pre>";
    //ECHO "\n\n\n";

    $meses_cal = $arreglo['hijo'];

    //Armnado periodos
    $perido_armado = array();
    for ($i = 0; $i < count($meses_cal); $i++) {
        $fecha = $p_anio . "-" . $meses_cal[$i] . "-" . $p_dia;
        $perido_armado[] = getFechaPatron($fecha, "Y-m-d");
    }


    //echo "<hr>Periodo Armado";
    //echo "<pre>";
    //print_r($perido_armado);
    //echo "</pre>";



    $id_pdeclaracion_lab = array();

    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(/*2*/ID_EMPLEADOR_MAESTRO, $p_anio);

    for ($z = 0; $z < count($data_pd); $z++) {
        if (in_array($data_pd[$z]['periodo'], $perido_armado)) {

            $id_pdeclaracion_lab[] = $data_pd[$z]['id_pdeclaracion'];
            //echo "\n $z\n";
            //break;
        }
    }

    //echo "<hr>id_pdeclaracion_lab";
    //echo "<pre>";
    //print_r($id_pdeclaracion_lab);
    //echo "</pre>";


//..............................................................................
    $conceptos_afectos = array('0605');
//..............................................................................
    $dao_dconcepto = new DeclaracionDconceptoDao();

    $sum = 0;
    for ($i = 0; $i < count($id_pdeclaracion_lab); $i++) {

        $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion_lab[$i]);
        //var_dump ($data_dconcepto);
        for ($z = 0; $z < count($data_dconcepto); $z++) {
            if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {
                //echo "\<br>encontro en id_pdeclaracion_lab = ".$id_pdeclaracion_lab[$i];

                $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
                //echo "\n<br> sum =".$sum;
                //break; //encontro SALE.
            }
        }
    }


    //echo "<hr>sum";
    //echo "<pre>";
    //print_r($sum);
    //echo "</pre>";

    //-------   
    return $sum;
}

//??????????

function get_IR5_DivisorDelImpuestoRenta($periodo) {

    $num_mes = intval(getFechaPatron($periodo, "m"));
    $rpta = null;

    //| Condiciones
    //| (12) = 1, 2, 3  
    //| (9) = 4
    //| (8) = 5,6,7
    //| (5) = 8
    //| (4) = 9,10,11
    //| (1) = 12
    // if($num_mes==1 ||)

    switch ($num_mes) {
        case 1:
        case 2:
        case 3:
            $rpta['mes'] = $num_mes;
            $rpta['padre'] = 12;
            $rpta['hijo'] = array(1, 2, 3);
            break;
        case 4:
            $rpta['mes'] = $num_mes;
            $rpta['padre'] = 9;
            $rpta['hijo'] = array(4);
            break;
        case 5:
        case 6:
        case 7:
            $rpta['mes'] = $num_mes;
            $rpta['padre'] = 8;
            $rpta['hijo'] = array(5, 6, 7);
            break;
        case 8:
            $rpta['mes'] = $num_mes;
            $rpta['padre'] = 5;
            $rpta['hijo'] = array(8);
            break;
        case 9:
        case 10:
        case 11:
            $rpta['mes'] = $num_mes;
            $rpta['padre'] = 4;
            $rpta['hijo'] = array(9, 10, 11);
            break;
        case 12:
            $rpta['mes'] = $num_mes;
            $rpta['padre'] = 1;
            $rpta['hijo'] = array(12);
            break;

        default:
            break;
    }
    return $rpta;
    //echo     var_dump($rpta);
}

/*
  function getIr5_NumBonificacionesDeMesesAnteriores($periodo) {
  $num_mes = getFechaPatron($periodo, "m");
  $num_mes = intval($num_mes);

  $rpta = null;

  if ($num_mes > 7) { // SI ES Agosto se aplica!.
  $rpta = 1;
  } else {
  $rpta = 0;
  }
  return $rpta;
  }
 */


/*
  //edit 18/10/2012 fuck!!! orororororor
  function get_IR5_NumGratificacionesdeMesesAnteriores($periodo) {

  $num_mes = getFechaPatron($periodo, "m");
  $num_mes = intval($num_mes);

  $rpta = null;
  if (($num_mes >= 7) && ($num_mes <= 12)) {
  $rpta = 1;

  } else if ($num_mes < 7) {
  $rpta = 2;
  } else {
  $rpta = "Error Critico fn";
  }

  return $rpta;
  }
 */

function buscarMontodeConceptoMesesAtras($arreglo_conceptos, $id_trabajador, $periodo) {

    //$arreglo_conceptos = array('0312'); //bonificacion extraordinaria
    $p_dia = getFechaPatron($periodo, "d");
    $p_mes = getFechaPatron($periodo, "m");
    $p_anio = getFechaPatron($periodo, "Y");

    $p_mes = intval($p_mes);

    $perido_armado = array();
    for ($i = 1; $i < $p_mes; $i++) {
        $fecha = $p_anio . "-" . $i . "-" . $p_dia;
        $perido_armado[] = getFechaPatron($fecha, "Y-m-d");
    }

    $id_pdeclaracion_lab = array();

    $dao_pd = new PlameDeclaracionDao();
    
    $data_pd = $dao_pd->listar(/*2*/ID_EMPLEADOR_MAESTRO, $p_anio);

    for ($z = 0; $z < count($data_pd); $z++) {
        if (in_array($data_pd[$z]['periodo'], $perido_armado)) {
            $id_pdeclaracion_lab[] = $data_pd[$z]['id_pdeclaracion'];
        }
    }

//------------------------------------------------------------------------------
    $dao_dconcepto = new DeclaracionDconceptoDao();

    $sum = 0.00;
    for ($i = 0; $i < count($id_pdeclaracion_lab); $i++) {
        $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion_lab[$i]);

        for ($z = 0; $z < count($data_dconcepto); $z++) {
            if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $arreglo_conceptos)) {
                $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
            }
        }
    }
//------------------------------------------------------------------------------  
    return $sum;
}


/**
 * Obtener datos solo el mes de gratificacion y returm.
 * $periodo = julio o diciembres
 */
function getMontoConceptoPeriodo($id_trabajador, $arreglo_conceptos, $periodo) { //0312
    //$periodo = "2012-07-01"; // EN JULIO...
    $num_anio = getFechaPatron($periodo, "Y");
    
    //**************
    $id_pdeclaracion_lab = null;
    //echo "\n\n\nentroooo IF julioo";
    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $num_anio);

    for ($z = 0; $z < count($data_pd); $z++) {
        if ($data_pd[$z]['periodo'] == $periodo) {
            $id_pdeclaracion_lab = $data_pd[$z]['id_pdeclaracion'];
            break;
        }
    }

    if (!is_null($id_pdeclaracion_lab)) {
        //$arreglo_conceptos = array('0403', '0405', '0406', '0407');
        //$arreglo_conceptos;
        $dao_dconcepto = new DeclaracionDconceptoDao();
        $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion_lab);

        $sum = 0.00;
        for ($z = 0; $z < count($data_dconcepto); $z++) {
            if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $arreglo_conceptos)) {
                $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
            }
        }
    }
    //************** 
    return $sum;
}

// no usar xq hay otra funcion util
function get_IR5_Ingresos_PeriodoArmado($id_trabajador, /* $arreglo_conceptos, */ $periodo_armado) {
    $periodo = $periodo_armado; //"2012-06-01"; //

    $num_mes = getFechaPatron($periodo, "m");
    $num_mes = intval($num_mes);
    $num_anio = getFechaPatron($periodo, "Y");
    //---------------
    //---------------
    // 02 Declaraciones
    $id_pdeclaracion_lab = null;
    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $num_anio);


    for ($z = 0; $z < count($data_pd); $z++) { //get_IR5_Ingresos
        if ($data_pd[$z]['periodo'] == $periodo) {
            $id_pdeclaracion_lab = $data_pd[$z]['id_pdeclaracion'];
            break;
        }
    }

    if (!is_null($id_pdeclaracion_lab)) {
        $monto = get_IR5_Ingresos($id_pdeclaracion_lab, $id_trabajador);
    } else {
        $monto = 0;
    }
    return $monto;
}

// FUNCIONANDO
function get_IR5_NumGratificacionesdeMesesAnterioresXXX($id_trabajador, $periodo) {

    $num_mes = getFechaPatron($periodo, "m");
    $num_mes = intval($num_mes);
    $num_anio = getFechaPatron($periodo, "Y");

    $rpta = null;
    if (($num_mes >= 7) && ($num_mes <= 12)) {

        //**************
        $fecha_28_julio = $num_anio . "-07-01";

        $id_pdeclaracion_lab = null;
        //echo "\n\n\nentroooo IF julioo";
        $dao_pd = new PlameDeclaracionDao();
        $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $num_anio);

        for ($z = 0; $z < count($data_pd); $z++) {
            if ($data_pd[$z]['periodo'] == $fecha_28_julio) {
                $id_pdeclaracion_lab = $data_pd[$z]['id_pdeclaracion'];
                break;
            }
        }

        if (!is_null($id_pdeclaracion_lab)) {
            //echo "entro id_pdeclaracion_lab =".$id_pdeclaracion_lab;
            // ------------ ojo Tambien se podria  hacer un select de los conceptos de Vacaciones 0400 en este caso No.
            // Ojo solo sumara debe sumar una gratificacion que se dio en 28 DE JULIO-- completo o proporcional
            $conceptos_gratificacion = array('0403', '0405', '0406', '0407');

            $dao_dconcepto = new DeclaracionDconceptoDao();
            $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion_lab);

            $sum = 0.00;
            for ($z = 0; $z < count($data_dconcepto); $z++) {
                if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_gratificacion)) {
                    $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
                }
            }
        }
        //**************
        $rpta = $sum;
    } else if ($num_mes < 7) {
        $rpta = 0;
    }
    return $rpta;
}

function get_IR5_mesFaltan($periodo) {
    $num_mes = date("m", strtotime($periodo));
    $r = 13 - intval($num_mes);
    return $r;
}

// FUCK!!!!
// Solo se Aplica 

//echo " ".get_IR5_ImpuestoARetenerAnteriores(10, '2012-12-01');

function get_IR5_ImpuestoARetenerAnteriores($id_trabajador, $periodo) {
//SOLO CUANDO EL PERIODO ES   = Y-12-01;    
// SUMA LAS RENTAS DE QUINTA DE LOS MESES 1 a 11.
    
    
    //**************
    $p_dia = getFechaPatron($periodo, "d");
    $p_mes = getFechaPatron($periodo, "m");
    $p_anio = getFechaPatron($periodo, "Y");

    $p_mes = intval($p_mes);

    $perido_armado = array();
    for ($i = 1; $i < $p_mes; $i++) {
        $fecha = $p_anio . "-" . $i . "-" . $p_dia;
        $perido_armado[] = getFechaPatron($fecha, "Y-m-d");
    }

    $id_pdeclaracion_lab = array();

    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $p_anio);



    for ($z = 0; $z < count($data_pd); $z++) {
        if (in_array($data_pd[$z]['periodo'], $perido_armado)) {
            $id_pdeclaracion_lab[] = $data_pd[$z]['id_pdeclaracion'];
        }
    }

    //sum por declaraciones
    $monto = 0.00;
    //-------------------------
    //suma todos los periodos que tengan el concepto 605 = renta de quinta
    $conceptos_afectos5ta = array('0605');
    $dao_dconcepto = new DeclaracionDconceptoDao();    

    for ($i = 0; $i < count($id_pdeclaracion_lab); $i++) {

        $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion_lab[$i]);

        $sum = 0;
        for ($z = 0; $z < count($data_dconcepto); $z++) {
            if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos5ta)) {
                $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
            }
        }
        $monto = $monto + $sum;
    }

    return $monto;
}

// FUCK!!!!
function get_IR5_RMesesAnteriores($id_pdeclaracion, $id_trabajador, $periodo) {

    //**************
    $p_dia = getFechaPatron($periodo, "d");
    $p_mes = getFechaPatron($periodo, "m");
    $p_anio = getFechaPatron($periodo, "Y");

    $p_mes = intval($p_mes);

    $perido_armado = array();
    for ($i = 1; $i < $p_mes; $i++) {
        $fecha = $p_anio . "-" . $i . "-" . $p_dia;
        $perido_armado[] = getFechaPatron($fecha, "Y-m-d");
    }
    //dao
    $dao_pd = new PlameDeclaracionDao();
    $data_pd = $dao_pd->listar(ID_EMPLEADOR_MAESTRO, $p_anio);
    $id_pdeclaracion_lab = array();
    for ($z = 0; $z < count($data_pd); $z++) {
        if (in_array($data_pd[$z]['periodo'], $perido_armado)) {
            $id_pdeclaracion_lab[] = $data_pd[$z]['id_pdeclaracion'];
        }
    }

    //sum por declaraciones
    $var_07 = 0.00;
    for ($i = 0; $i < count($id_pdeclaracion_lab); $i++) {
        $monto = get_IR5_Ingresos($id_pdeclaracion_lab[$i], $id_trabajador);        
        $var_07 = $var_07 + $monto;
    }
    //**************
    return $var_07;
}

// Get all ingresos 
// OJO SOLO SE UTILIZA ayuda. $data_dconcepto EN PLANILLA. 
function get_IR5_Ingresos($id_pdeclaracion, $id_trabajador,$data_dconcepto=null) { 
    
    $conceptos_afectos5ta = arrayConceptosAfectos_a('10');
    
    if(is_null($data_dconcepto)){
        $dao_dconcepto = new DeclaracionDconceptoDao();
        $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion);
    }
    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos5ta)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}

// Get all ingresos 
function get_SNP_Ingresos($data_dconcepto) { //ok
    $conceptos_afectos = arrayConceptosAfectos_a('08');
    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}

// Get all ingresos 
//UTIL REPORTE AFP
function get_AFP_Ingresos($id_pdeclaracion, $id_trabajador) {
    $conceptos_afectos = arrayConceptosAfectos_a('09');
    $dao_dconcepto = new DeclaracionDconceptoDao();
    $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion);

    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {            
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}
//UTIL REPORTE PLANILLA
function get_AFP_IngresosPlanilla($data_dconcepto) {
    $conceptos_afectos = arrayConceptosAfectos_a('09');
    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {            
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) {
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }
    return $sum;
}
// Get all ingresos 
function get_ESSALUD_REGULAR_Ingresos($data_dconcepto) {//ok
    $conceptos_afectos = arrayConceptosAfectos_a('01');
    $sum = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if (in_array($data_dconcepto[$z]['cod_detalle_concepto'], $conceptos_afectos)) { 
            $sum = $sum + $data_dconcepto[$z]['monto_pagado'];
        }
    }    
    // .........................................................................
    // PASO ADICIONAL PARA MANTENER DATOS UNIFORMES CON PTD-PLAME
    // Resta 0704 = tardanza, y 0705  =inasistencia.
    $dscto = 0;
    for ($z = 0; $z < count($data_dconcepto); $z++) {
        if ($data_dconcepto[$z]['cod_detalle_concepto']==704 ||$data_dconcepto[$z]['cod_detalle_concepto']==705 ) { 
            $dscto = $dscto + $data_dconcepto[$z]['monto_pagado'];
        }
    } 
    // .........................................................................    
    $sum = ($sum - $dscto);    
    return $sum;
}

/**
 *
 * @param type $cod_afectacion
 * @return type 
 * 
 *  Esto es variable segun 
 *  cod_afectacion:
 *  - 10 = Renta de quinta
 *  - 08 = SNP
 *  - 01 = Esalud Regular
 */


//------------------------------------------------------------------------------

/**
 * Si el mes es 7 = julio devuelve : 2
 * # De gratificaciones que falta Pagar
 * 
 * @param type $periodo
 * @return string 
 */
function get_IR5_NumGratificacionesFaltaPagar($periodo) {
    $num_mes = getFechaPatron($periodo, "m");

    $num = intval($num_mes);
    $rpta = null;

    if (($num > 0) && ($num < 7)) {
        // 2 = XQ falta pagar 2 gratificaciones.
        $rpta = 2;
    } else if (($num >= 7) && ($num <= 12)) {
        // 1 = XQ falta pagar 1 gratificacion.
        $rpta = 1;
    } else {
        $rpta = "error critico";
    }
    return $rpta;
}

//Concepto 3012
function get_IR5_BonificacionExtraordinaria($periodo) {
    // Obtiene bonificacion de Febrero marz-abrl-may-jun....
}

function get_IR5_7Uit() {
    
}

//echo getNumGratificaciones("2012-06-01");
//getRMesesAnteriores("2012-05-01");
?>
