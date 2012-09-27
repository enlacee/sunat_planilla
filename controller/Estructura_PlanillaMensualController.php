<?php

session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];

if ($op) {
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    
    
    //Estructuras
    require_once '../dao/TrabajadorPdeclaracionDao.php';
    require_once '../dao/PlameDeclaracionDao.php';

    
    
    
    //ZIP
require_once '../util/zip/zipfile.inc.php';
//setlocale(LC_ALL, 'es_Es');
}



if ($op == "estructura-plame") {
    /*
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    */
    generarEstructurasPlame();
    //generarEstructurasTRegistro();
}


function generarEstructurasPlame(){
    
    //Lista de todos los trabajadores del mes de ENERO
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    
    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($id_pdeclaracion);   
    
    //..........................................................................
    //| Nombre del archivo: ffffaaaamm###########.jor			
    //| Donde:			
    //| ffff = Es el código del formulario (0601)			
    //| aaaa = Es el año del período que se está importando.			
    //| mm = Es el mes del periodo que se está importando.			
    //| ########## = Es el RUC de la empresa a la que pertenece el trabajador.
    //..........................................................................

    $ffff = '0601';
    $aaaa = getFechaPatron($data_pd['periodo'], "Y");
    $mm = getFechaPatron($data_pd['periodo'],"m");
    $ruc = RUC;    
    $archivo = $ffff.$aaaa.$mm.$ruc;
    
        
    //$data = 
    // LISTA DE TRABAJADORES    
    $dao_tpd = new TrabajadorPdeclaracionDao(); //[OK]
    $data = array();
    $data = $dao_tpd->listar_ID_Trabajador($id_pdeclaracion);

    $ids = array();
    for ($i = 0; $i < count($data); $i++) {
        $ids[$i]['id_trabajador'] = $data[$i]['id_trabajador'];
        $ids[$i]['id_trabajador_pdeclaracion'] = $data[$i]['id_trabajador_pdeclaracion'];
    }

    
    //---
    
    $file = array();
    $file[] = estrutura_14($archivo,$dao_tpd,$id_pdeclaracion,$ids);
    $file[] = estrutura_15($archivo,$dao_tpd,$id_pdeclaracion,$ids);
    $file[] = estrutura_18($archivo,$dao_tpd,$id_pdeclaracion,$ids);
 

//$file[] = ($file_name2);

    $zipfile = new zipfile();
    $carpeta = "plame-" .getFechaPatron($data_pd['periodo'], 'm-Y'). "/";
    $zipfile->add_dir($carpeta);

    for ($i = 0; $i < count($file); $i++) {
        $zipfile->add_file(implode("", file($file[$i])), $carpeta . $file[$i]);
        //$zipfile->add_file( file_get_contents($file[$i]),$carpeta.$file[$i]);
    }

    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=zipfile.zip");

    echo $zipfile->file();
    
}



//funciones Hijas
function estrutura_14($archivo,$dao_tpd,$id_pdeclaracion,$ids){    
    $archivo  = $archivo.".jor";    
    
    //listar trabajador con ID
    $dao_tpd = new TrabajadorPdeclaracionDao();
    
//    echo "<pre>";
 //   echoo($ids);
  //  echo "</pre>";
    
    $data = array();
    for($i=0;$i<count($ids);$i++){        
        $data_detalle = array();
        $data_detalle = $dao_tpd->estrutura_14($id_pdeclaracion, $ids[$i]['id_trabajador']);
        
        //echoo($data_detalle);
        $data[$i][] = $data_detalle['cod_tipo_documento'];
        $data[$i][] = $data_detalle['num_documento'];
        $data[$i][] = $data_detalle['ordinario_hora'];
        $data[$i][] = $data_detalle['ordinario_min'];
        $data[$i][] = $data_detalle['sobretiempo_hora'];
        $data[$i][] = $data_detalle['sobretiempo_min'];       
        
    }    
    generarEstructura($archivo, $data);
    
    return $archivo;
}


/*
function estrutura_15($archivo,$dao_tpd,$id_pdeclaracion,$ids){
    $archivo."snl";
    //listar trabajador con ID
    $dao_tpd = new TrabajadorPdeclaracionDao();
    
    $data = array();
    for($i=0;$i<count($ids);$i++){        
        $data_detalle = array();
        $data_detalle = $dao_tpd->estrutura_15($id_pdeclaracion, $ids[$i]['id_trabajador']);
        
        $data[$i][] = $data_detalle['cod_tipo_documento'];
        $data[$i][] = $data_detalle['num_documento'];
        $data[$i][] = $data_detalle['cod_tipo_suspen_relacion_laboral'];
        $data[$i][] = $data_detalle['cantidad_dia'];
    }    
    generarEstructura($archivo, $data);
    return $archivo;
}*/


function estrutura_15($archivo,$dao_tpd,$id_pdeclaracion,$ids){
    $archivo  =  $archivo.".snl";    
    
    //listar trabajador con ID
    $dao_tpd = new TrabajadorPdeclaracionDao();
   
    $data = array();
    for($i=0;$i<count($ids);$i++){

        // -- 01
        $data_detalle = array();
        $data_detalle = $dao_tpd->estrutura_15_a($id_pdeclaracion, $ids[$i]['id_trabajador']);
        for($j=0;$j<count($data_detalle);$j++){
            $data[$i][$j][] = $data_detalle[$j]['cod_tipo_documento'];
            $data[$i][$j][] = $data_detalle[$j]['num_documento'];
            $data[$i][$j][] = $data_detalle[$j]['cod_tipo_suspen_relacion_laboral'];
            $data[$i][$j][] = $data_detalle[$j]['cantidad_dia'];
        }
        
        // --- contando arreglo y sumar.. a nuevo array---
        $counteo = count($data_detalle);
        // -- 02
        $data_detalle2 = array();
        $data_detalle2 = $dao_tpd->estrutura_15_b($id_pdeclaracion, $ids[$i]['id_trabajador']);
        for($j=0;$j<count($data_detalle2);$j++){
            $data[$i][($j+$counteo)][] = $data_detalle2[$j]['cod_tipo_documento'];
            $data[$i][($j+$counteo)][] = $data_detalle2[$j]['num_documento'];
            $data[$i][($j+$counteo)][] = $data_detalle2[$j]['cod_tipo_suspen_relacion_laboral'];
            $data[$i][($j+$counteo)][] = $data_detalle2[$j]['cantidad_dia'];
        }
    }
    


    generarEstructura_11($archivo, $data);
    
    return $archivo;
    
}



function estrutura_18($archivo,$dao_tpd,$id_pdeclaracion,$ids){
    $archivo  =  $archivo.".rem";
    //listar trabajador con ID
    $dao_tpd = new TrabajadorPdeclaracionDao();
    
    $data = array();
    for($i=0;$i<count($ids);$i++){  
        
        $data_detalle = array();
        $data_detalle = $dao_tpd->estrutura_18($id_pdeclaracion, $ids[$i]['id_trabajador']);
        
        for($j=0;$j<count($data_detalle);$j++){
            $data[$i][$j][] = $data_detalle[$j]['cod_tipo_documento'];
            $data[$i][$j][] = $data_detalle[$j]['num_documento'];
            $data[$i][$j][] = $data_detalle[$j]['cod_detalle_concepto'];
            $data[$i][$j][] = $data_detalle[$j]['monto_devengado'];
            $data[$i][$j][] = $data_detalle[$j]['monto_pagado'];
        }
        
    }    
    generarEstructura_11($archivo, $data);
    return $archivo;
}









// ---------------------- FUNCION ESTRUCTURA--------------------------------//
function generarEstructura($file_name, $arreglo){

    $SEPADADOR = '|';
    $fp = fopen($file_name, 'w');
    $counteo = count($arreglo);

    for ($i = 0; $i < $counteo; $i++) {

        for ($j = 0; $j < count($arreglo[$i]); $j++) {
            fwrite($fp, $arreglo[$i][$j]);
            fwrite($fp, $SEPADADOR);
            if ((count($arreglo[$i]) - 1) == $j) { // OKKK
                fwrite($fp, chr(13) . chr(10));
            }
        }//ENDFOR
    }//ENDFOR

    fclose($fp);
}


function generarEstructura_11($file_name, $arreglo) {

    $SEPADADOR = '|';
    $fp = fopen($file_name, 'w');
    $counteo = count($arreglo);

    for ($i = 0; $i < /* 1 */$counteo; $i++) {

        for ($j = 0; $j < count($arreglo[$i]); $j++) { // Persona
            for ($k = 0; $k < count($arreglo[$i][$j]); $k++) { // Tipo de Registros
                fwrite($fp, $arreglo[$i][$j][$k]);
                fwrite($fp, $SEPADADOR);

                if ((count($arreglo[$i][$j]) - 1) == $k) { // OKKK
                    fwrite($fp, chr(13) . chr(10));
                }
            }//ENDFOR
        }//ENDFOR
    }//ENDFOR
    fclose($fp);

    //ECHO __FILE__;
}




?>