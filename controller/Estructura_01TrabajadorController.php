<?php

session_start();
//header("Content-Type: text/html; charset=utf-8");
require_once '../util/funciones.php';
require_once '../dao/AbstractDao.php';
require_once '../dao/Estructura_01Dao.php';

//Ayuda Estrutura 04
require_once '../dao/PersonaDao.php';
require_once '../dao/PersonaDireccionDao.php';

//Ayuda Estrutura 05
require_once '../dao/TrabajadorDao.php';

//Ayuda Estructura 06
require_once '../dao/PensionistaDao.php';

//ZIP
require_once '../util/zip/zipfile.inc.php';
//setlocale(LC_ALL, 'es_Es');

$op = $_REQUEST["oper"];

if ($op) {
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/Estructura_01Dao.php';

    //Ayuda Estrutura 04
    require_once '../dao/PersonaDao.php';
    require_once '../dao/PersonaDireccionDao.php';

    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
}




if ($op == "t-registro") {

    generarEstructurasTRegistro();
} else if ($op == "t-registro-alta") {

    generarEstructurasTRegistro_ALTA();
} else if ($op == "t-registro-baja") {

    generarEstructurasTRegistro_BAJA();
}

function generarEstructurasTRegistro() {

    $chek_box = $_REQUEST['t-registro'];
//    echo "<pre>";
//    print_r($chek_box);
//    echo "</pre>";
    $counteo = count($chek_box);
    for ($i = 0; $i < $counteo; $i++) {
        $function = "estructura_" . $chek_box[$i];
        $function();
    }
}

function generarEstructurasTRegistro_ALTA() { //ALTA
    $data = $_REQUEST['ids'];

    $ids = array();
    for ($i = 0; $i < count($data); $i++) {
        $ids[$i]['id_trabajador'] = $data[$i];
    }


    $file = array();
    $file[] = estructura_4($ids);
    $file[] = estructura_5($ids);
    $file[] = estructura_11a($ids);
    $file[] = estructura_17($ids);

    //---
    $dao = new TrabajadorDao();
    for ($i = 0; $i < count($ids); $i++) {
        $dao->Alta($ids[$i]['id_trabajador']); //cambia estado
    }


    $zipfile = new zipfile();
    $carpeta = "Alta-Trabajador-" . date("d-m-Y") . "/";
    $zipfile->add_dir($carpeta);

    for ($i = 0; $i < count($file); $i++) {
        $zipfile->add_file(implode("", file($file[$i])), $carpeta . $file[$i]);
        //$zipfile->add_file( file_get_contents($file[$i]),$carpeta.$file[$i]);
    }

    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=zipfile.zip");

    echo $zipfile->file();
}

function generarEstructurasTRegistro_BAJA() { //BAJA
    $data = $_REQUEST['ids'];
    $ids = array();
    for ($i = 0; $i < count($data); $i++) {
        $ids[$i]['id_trabajador'] = $data[$i];
    }


    $dao = new TrabajadorDao();
    for ($i = 0; $i < count($ids); $i++) {
        $dao->Baja($ids[$i]['id_trabajador']);
    }

    $file = array();
    $file[] = estructura_11b($ids);

      $zipfile = new zipfile();
      $carpeta = "Baja-Trabajador-".date("d-m-Y")."/";
      $zipfile->add_dir($carpeta);
    
    
    for ($i = 0; $i < count($file); $i++) {
        $zipfile->add_file(implode("", file($file[$i])), $carpeta.$file[$i]);
    }

    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=zipfile.zip");

    echo $zipfile->file();
}

/**
 * ESTRUCTURA 1: "Establecimientos Propios del Empleador"				
 * 
 */
function estructura_1() {

    $dao = new Estructura_01Dao();
    $data = $dao->estructura_1(ID_EMPLEADOR);
    $dao = null;

    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($data); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $data[$i]['cod_establecimiento'];
            $new[$i][$j + 1] = $data[$i]['realizaran_actividad_riesgo'];
            //$new[$i][$j+2] = $data[$i]['realizaran_actividad_riesgo'];
            //$new[$i][$j+3] = $data[$i]['realizaran_actividad_riesgo'];           
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo
    $file_name = 'RP_' . RUC . '.esp';
    generarEstructura($file_name, $new);
}

/**
 * OJO este reporte INCLUYE servicios  Historicos de Servicio (check-box) 
 */
function estructura_2() {

    //estructura_2_1()
    $dao = new Estructura_01Dao();
    $data = $dao->estructura_2_1(ID_EMPLEADOR_MAESTRO);
    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($data); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $data[$i]['ruc'];
            $new[$i][$j + 1] = $data[$i]['cod_tipo_actividad'];
            $new[$i][$j + 2] = $data[$i]['fecha_inicio'];
            $new[$i][$j + 3] = $data[$i]['fecha_fin'];
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo
    $file_name = 'RP_' . RUC . '.edd';
    generarEstructura($file_name, $new);

    //estructura_2_2()
    $data = $dao->estructura_2_2(ID_EMPLEADOR_MAESTRO);
    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($data); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $data[$i]['ruc'];
            $new[$i][$j + 1] = $data[$i]['cod_establecimiento'];
            $new[$i][$j + 2] = $data[$i]['realizan_trabajo_de_riesgo'];
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo
    $file_name = 'RP_' . RUC . '.ldd';
    generarEstructura($file_name, $new);
}

/**
 * 
 */
function estructura_3() {

    $dao = new Estructura_01Dao();
    $data = $dao->estructura_3(ID_EMPLEADOR_MAESTRO);
    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($data); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $data[$i]['ruc'];
            $new[$i][$j + 1] = $data[$i]['cod_tipo_actividad'];
            $new[$i][$j + 2] = $data[$i]['fecha_inicio'];
            $new[$i][$j + 3] = $data[$i]['fecha_fin'];
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo RP_###########.med
    $file_name = 'RP_' . RUC . '.med';
    generarEstructura($file_name, $new);
}

function estructura_4($ids = '') {

    // PASO 01  Listar
    $dao = new TrabajadorDao();

    if (empty($ids)) {
        $ids = $dao->listarTrabajadoresSoloID_E4(ID_EMPLEADOR_MAESTRO);
    }
    // PASO 02  Listar 
    $persona = array();
    for ($i = 0; $i < count($ids); $i++) {
        $persona[] = $dao->buscarTrabajadorPorId_E4($ids[$i]['id_trabajador']);
    }

//    echo "Estreuctura 04";
//   echo "<pre> id_trabajador";
//   print_r($ids);
//   echo "<pre>";
//      echo "<pre>";
//   print_r($persona);
//   echo "<pre>";
    // PASO 03  Listar direccion 1 y 2
    $lista = array();

    $counteo = count($persona);
    for ($i = 0; $i < $counteo; $i++) {//persona
        //$lista[$i]['id_persona'] = $persona[$i]['id_persona'];        
        $lista[$i]['cod_tipo_documento'] = $persona[$i]['cod_tipo_documento'];
        $lista[$i]['num_documento'] = $persona[$i]['num_documento'];
        $lista[$i]['cod_pais_emisor_documento'] = $persona[$i]['cod_pais_emisor_documento'];

        $lista[$i]['fecha_nacimiento'] = getFechaPatron($persona[$i]['fecha_nacimiento'], "d/m/Y");
        $lista[$i]['apellido_paterno'] = $persona[$i]['apellido_paterno'];
        $lista[$i]['apellido_materno'] = $persona[$i]['apellido_materno'];
        $lista[$i]['nombres'] = $persona[$i]['nombres'];
        $lista[$i]['sexo'] = $persona[$i]['sexo'];
        $lista[$i]['cod_nacionalidad'] = $persona[$i]['cod_nacionalidad'];
        $lista[$i]['cod_telefono_codigo_nacional'] = $persona[$i]['cod_telefono_codigo_nacional'];
        $lista[$i]['telefono'] = $persona[$i]['telefono'];
        $lista[$i]['correo'] = $persona[$i]['correo'];

        for ($j = 1; $j <= 2; $j++) { //direccion
            //cod_tipo_documento
            $daox = new PersonaDireccionDao();
            // Direccion 01 Y Direccion 02
            //funcione Reutilizada
            $ddata = $daox->buscarPersonaDireccionEstructura_01($persona[$i]['id_persona'], $j);
            //$lista[$i]['direccion_'.$j] = $ddata['ubigeo_departamento'];
            $lista[$i]['cod_via_' . $j] = $ddata['cod_via'];
            $lista[$i]['nombre_via_' . $j] = $ddata['nombre_via'];
            $lista[$i]['numero_via_' . $j] = $ddata['numero_via'];

            $lista[$i]['departamento_' . $j] = $ddata['departamento'];
            $lista[$i]['interior_' . $j] = $ddata['interior'];
            $lista[$i]['manzana_' . $j] = $ddata['manzana'];
            $lista[$i]['lote_' . $j] = $ddata['lote'];
            $lista[$i]['manzana_' . $j] = $ddata['manzana'];
            $lista[$i]['kilometro_' . $j] = $ddata['kilometro'];
            $lista[$i]['block_' . $j] = $ddata['block'];
            $lista[$i]['etapa_' . $j] = $ddata['etapa'];

            $lista[$i]['cod_zona_' . $j] = $ddata['cod_zona'];
            $lista[$i]['nombre_zona_' . $j] = $ddata['nombre_zona'];
            $lista[$i]['referencia_' . $j] = $ddata['referencia'];
            $lista[$i]['cod_ubigeo_reniec_' . $j] = $ddata['cod_ubigeo_reniec'];
            $lista[$i]['referente_essalud_' . $j] = $ddata['referente_essalud'];
        }//ENDFOR
    }//ENDFOR
    //--------------------------------------------------------------------------
    //CONVERTIENDO A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($lista); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $lista[$i]['cod_tipo_documento'];
            $new[$i][$j + 1] = $lista[$i]['num_documento'];
            $new[$i][$j + 2] = $lista[$i]['cod_pais_emisor_documento'];

            $new[$i][$j + 3] = $lista[$i]['fecha_nacimiento'];
            $new[$i][$j + 4] = $lista[$i]['apellido_paterno'];
            $new[$i][$j + 5] = $lista[$i]['apellido_materno'];
            $new[$i][$j + 6] = $lista[$i]['nombres'];
            $new[$i][$j + 7] = $lista[$i]['sexo'];
            $new[$i][$j + 8] = $lista[$i]['cod_nacionalidad'];
            $new[$i][$j + 9] = $lista[$i]['cod_telefono_codigo_nacional'];
            $new[$i][$j + 10] = $lista[$i]['telefono'];
            $new[$i][$j + 11] = $lista[$i]['correo'];

            //direccion 01
            $new[$i][$j + 12] = $lista[$i]['cod_via_1'];
            $new[$i][$j + 13] = $lista[$i]['nombre_via_1'];
            $new[$i][$j + 14] = $lista[$i]['numero_via_1'];
            $new[$i][$j + 15] = $lista[$i]['departamento_1'];
            $new[$i][$j + 16] = $lista[$i]['interior_1'];
            $new[$i][$j + 17] = $lista[$i]['manzana_1'];
            $new[$i][$j + 18] = $lista[$i]['lote_1'];
            $new[$i][$j + 19] = $lista[$i]['kilometro_1'];
            $new[$i][$j + 20] = $lista[$i]['block_1'];
            $new[$i][$j + 21] = $lista[$i]['etapa_1'];
            $new[$i][$j + 22] = $lista[$i]['cod_zona_1'];
            $new[$i][$j + 23] = $lista[$i]['nombre_zona_1'];
            $new[$i][$j + 24] = $lista[$i]['referencia_1'];
            $new[$i][$j + 25] = $lista[$i]['cod_ubigeo_reniec_1'];

            //direccion 02
            $new[$i][$j + 26] = $lista[$i]['cod_via_2'];
            $new[$i][$j + 27] = $lista[$i]['nombre_via_2'];
            $new[$i][$j + 28] = $lista[$i]['numero_via_2'];
            $new[$i][$j + 29] = $lista[$i]['departamento_2'];
            $new[$i][$j + 30] = $lista[$i]['interior_2'];
            $new[$i][$j + 31] = $lista[$i]['manzana_2'];
            $new[$i][$j + 32] = $lista[$i]['lote_2'];
            $new[$i][$j + 33] = $lista[$i]['kilometro_2'];
            $new[$i][$j + 34] = $lista[$i]['block_2'];
            $new[$i][$j + 35] = $lista[$i]['etapa_2'];
            $new[$i][$j + 36] = $lista[$i]['cod_zona_2'];
            $new[$i][$j + 37] = $lista[$i]['nombre_zona_2'];
            $new[$i][$j + 38] = $lista[$i]['referencia_2'];
            $new[$i][$j + 39] = $lista[$i]['cod_ubigeo_reniec_2'];

            // La dirección que seleccione será tomada por el EsSalud 
            // 1 O 2

            $direccion = "";
            if ($lista[$i]['referente_essalud_1'] == '1') {
                $direccion = 1;
            } else if ($lista[$i]['referente_essalud_2'] == '1') {
                $direccion = 2;
            }

            $new[$i][$j + 40] = $direccion;
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo
    $file_name = 'RP_' . RUC . '.ide';
    generarEstructura($file_name, $new);

    return $file_name;

    //$dao->buscarPersonaDireccionPorId($id);
}

function estructura_5($ids = '') {

    // PASO 01  Listar
    $dao = new TrabajadorDao();

    if (empty($ids)) {
        $ids = $dao->listarTrabajadoresSoloID_E5(/* 1 */ ID_EMPLEADOR_MAESTRO);
    }

    // PASO 02  Listar      
    $persona = array();
    for ($i = 0; $i < count($ids); $i++) {
        $persona[] = $dao->buscarTrabajadorPorId_E5($ids[$i]['id_trabajador']);
    }

//    echo "<pre>";
//    print_r($ids);
//    echo "<pre>";
    // PASO 03  Listar direccion 1 y 2
    $lista = array();

    $counteo = count($persona);
    for ($i = 0; $i < $counteo; $i++) { //persona
        //$lista[$i]['id_persona'] = $persona[$i]['id_persona'];
        $lista[$i]['cod_tipo_documento'] = $persona[$i]['cod_tipo_documento'];

        $lista[$i]['num_documento'] = $persona[$i]['num_documento'];
        // condicion TD = 07 anexo-3
        $lista[$i]['cod_pais_emisor_documento'] = ($persona[$i]['cod_tipo_documento'] == '07') ? $persona[$i]['cod_pais_emisor_documento'] : null;

        $lista[$i]['cod_regimen_laboral'] = $persona[$i]['cod_regimen_laboral'];

        $lista[$i]['cod_nivel_educativo'] = $persona[$i]['cod_nivel_educativo'];

        $lista[$i]['cod_ocupacion_p'] = $persona[$i]['cod_ocupacion_p'];

        $lista[$i]['discapacitado'] = $persona[$i]['discapacitado'];

        $lista[$i]['cuspp'] = $persona[$i]['cuspp'];

        // SCTR Pensión Falta Implementar ---------------------------------------------------------- OJO :D
        $lista[$i]['sctr_pension'] = null;

        $lista[$i]['cod_tipo_contrato'] = $persona[$i]['cod_tipo_contrato'];

        $lista[$i]['cod_tipo_contrato'] = $persona[$i]['cod_tipo_contrato'];

        /**
         * Array Jornada Laboral 
         */
        $arreglo = preg_split("/[,]/", $persona[$i]['jornada_laboral']);
        $counteoa = count($arreglo); //4

        $bandera_1 = 0;
        $bandera_2 = 0;
        $bandera_3 = 0;

        for ($a = 0; $a < $counteoa; $a++) {
            if ($arreglo[$a] == 'j-trabajo-maximo') {
                $bandera_1 = 1;
                break;
            }
        }

        for ($a = 0; $a < $counteoa; $a++) {
            if ($arreglo[$a] == 'j-atipica-acumulativa') {
                $bandera_2 = 1;
                break;
            }
        }

        for ($a = 0; $a < $counteoa; $a++) {
            if ($arreglo[$a] == 'trabajo-horario-nocturno') {
                $bandera_3 = 1;
                break;
            }
        }

        $lista[$i]['j-trabajo-maximo'] = $bandera_1;
        $lista[$i]['j-atipica-acumulativa'] = $bandera_2;
        $lista[$i]['trabajo-horario-nocturno'] = $bandera_3;

        /**
         * Array Jornada Laboral 
         */
        $lista[$i]['sindicalizado'] = $persona[$i]['sindicalizado'];

        $lista[$i]['cod_periodo_remuneracion'] = $persona[$i]['cod_periodo_remuneracion'];

        $lista[$i]['monto_remuneracion'] = $persona[$i]['monto_remuneracion'];

        $lista[$i]['cod_situacion'] = $persona[$i]['cod_situacion'];

        $lista[$i]['percibe_renta_5ta_exonerada'] = $persona[$i]['percibe_renta_5ta_exonerada'];

        $lista[$i]['situacion_especial'] = $persona[$i]['situacion_especial'];

        $lista[$i]['cod_tipo_pago'] = $persona[$i]['cod_tipo_pago'];

        $lista[$i]['cod_categorias_ocupacionales'] = $persona[$i]['cod_categorias_ocupacionales'];

        $lista[$i]['cod_convenio'] = $persona[$i]['cod_convenio'];

        //  ruca CAS
        $lista[$i]['ruc_cas'] = null;
    }//ENDFOR
    //--------------------------------------------------------------------------
    //CONVIERTE A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($lista); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $lista[$i]['cod_tipo_documento'];
            $new[$i][$j + 1] = $lista[$i]['num_documento'];
            $new[$i][$j + 2] = $lista[$i]['cod_pais_emisor_documento'];
            $new[$i][$j + 3] = $lista[$i]['cod_regimen_laboral'];
            $new[$i][$j + 4] = $lista[$i]['cod_nivel_educativo'];
            $new[$i][$j + 5] = $lista[$i]['cod_ocupacion_p'];
            $new[$i][$j + 6] = $lista[$i]['discapacitado'];
            $new[$i][$j + 7] = $lista[$i]['cuspp'];
            $new[$i][$j + 8] = $lista[$i]['sctr_pension']; // 0 default
            $new[$i][$j + 9] = $lista[$i]['cod_tipo_contrato'];
            $new[$i][$j + 10] = $lista[$i]['j-trabajo-maximo'];
            $new[$i][$j + 11] = $lista[$i]['j-atipica-acumulativa'];
            $new[$i][$j + 12] = $lista[$i]['trabajo-horario-nocturno'];
            $new[$i][$j + 13] = $lista[$i]['sindicalizado'];
            $new[$i][$j + 14] = $lista[$i]['cod_periodo_remuneracion'];
            $new[$i][$j + 15] = $lista[$i]['monto_remuneracion'];
            $new[$i][$j + 16] = $lista[$i]['cod_situacion'];
            $new[$i][$j + 17] = $lista[$i]['percibe_renta_5ta_exonerada'];
            $new[$i][$j + 18] = $lista[$i]['situacion_especial'];
            $new[$i][$j + 19] = $lista[$i]['cod_tipo_pago'];
            $new[$i][$j + 20] = $lista[$i]['cod_categorias_ocupacionales'];
            $new[$i][$j + 21] = $lista[$i]['cod_convenio'];
            $new[$i][$j + 22] = $lista[$i]['ruc_cas'];
        }
    }

    //----- Archivo
    //Nombre del archivo :  RP_###########.tra
    $file_name = 'RP_' . RUC . '.tra';
    generarEstructura($file_name, $new);

    return $file_name;
}

/**
 * PENSIONISTA 
 */
function estructura_6() {
    // PASO 01  
    $dao = new PensionistaDao();
    $ids = $dao->listarPensionistaSoloID_E6(/* 1 */ ID_EMPLEADOR_MAESTRO);

    // PASO 02 
    $data = array();
    for ($i = 0; $i < count($ids); $i++) {
        $data[] = $dao->buscarPensionistaPorId_E6($ids[$i]['id_pensionista']);
    }
    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($data); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $data[$i][$j]['cod_tipo_documento'];
            $new[$i][$j + 1] = $data[$i][$j]['num_documento'];
            $new[$i][$j + 2] = $data[$i][$j]['cod_pais_emisor_documento'];
            $new[$i][$j + 3] = $data[$i][$j]['cod_tipo_trabajador'];
            $new[$i][$j + 4] = $data[$i][$j]['cod_regimen_pensionario'];
            $new[$i][$j + 5] = $data[$i][$j]['cuspp'];
            $new[$i][$j + 6] = $data[$i][$j]['cod_tipo_pago'];
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo RP_###########.pen
    $file_name = 'RP_' . RUC . '.pen';
    generarEstructura($file_name, $new);

//    echo "<pre>";
//    print_r($data);
//    echo "<hr>";
//    print_r($new);
//    echo "</pre>";
}

function estructura_11a($ids = '') {

    // PASO 01  Listar Id
    $dao = new TrabajadorDao();
    if (empty($ids)) {
        $ids = $dao->listarTrabajadoresSoloID_E11(/* 1 */ ID_EMPLEADOR_MAESTRO);
    }

    // PASO 02  Listar Datos
    //$dao = new TrabajadorDao();
    $persona = array();
    for ($i = 0; $i < count($ids); $i++) {//#trabajadore
        for ($j = 0; $j < 4; $j++) {
            $id = $j + 1;
            $FUNCTION = 'buscarTrabajadorPorID_tipo_registro_' . $id . '_E11';  //Structura COLM 5
            $persona[$i][$j] = $dao->$FUNCTION($ids[$i]['id_trabajador']);
        }
    }

    $lista = array();
    $counteo = count($persona);
    for ($i = 0; $i < $counteo; $i++) { //persona
        //tipos de registro
        $counteox = count($persona[$i]); //always 4

        for ($j = 0; $j < $counteox; $j++) {

            $lista[$i][$j]['cod_tipo_documento'] = $persona[$i][$j]['cod_tipo_documento'];
            $lista[$i][$j]['num_documento'] = $persona[$i][$j]['num_documento'];
            $lista[$i][$j]['cod_pais_emisor_documento'] = $persona[$i][$j]['cod_pais_emisor_documento'];

            //Categoria:
            // 1 : Trabajador
            // 2 : Pensionista
            // 4 : Personal de terceros
            // 5 : Personal en Formacion
            //Tipo de Registro: Segun el Secuencia de datos :OJO: $FUNCTION()
            // 1 : Periodo Laboral
            // 2 : Tipo Trabajador
            // 3 : Regimen d Aseguramiento Salud
            // 4 : Regimen Pensionario
            // 5 : SCTR salud  : NOT IMPLEMENT.

            $categoria = null;
            $tipo_registro = null;
            $codigo_registro_detalle = null;
            $cod_eps = null;

            if ($j == 0) {
                $categoria = 1;
                $tipo_registro = 1;
                $codigo_registro_detalle = null; /* $persona[$i][$j]['cod_motivo_baja_registro']; */
            } else if ($j == 1) {
                $categoria = 1;
                $tipo_registro = 2;
                $codigo_registro_detalle = $persona[$i][$j]['cod_tipo_trabajador'];
            } else if ($j == 2) {
                $categoria = 1;
                $tipo_registro = 3;
                $codigo_registro_detalle = $persona[$i][$j]['cod_regimen_aseguramiento_salud'];
                $cod_eps = $persona[$i][$j]['cod_eps'];
            } else if ($j == 3) {
                $categoria = 1;
                $tipo_registro = 4;
                $codigo_registro_detalle = $persona[$i][$j]['cod_regimen_pensionario'];
            } else {
                $categoria = 'ERRROR';
                $tipo_registro = 'Error SCRT ?.';
                $codigo_registro_detalle = "Errror Error php";
            }

            $lista[$i][$j]['categoria'] = $categoria;
            $lista[$i][$j]['tipo_de_registro'] = $tipo_registro;
            $lista[$i][$j]['fecha_inicio'] = $persona[$i][$j]['fecha_inicio'];
            $lista[$i][$j]['fecha_fin'] = null; /* $persona[$i][$j]['fecha_fin']; */

            $lista[$i][$j]['tipo_registro'] = $codigo_registro_detalle;
            $lista[$i][$j]['cod_eps'] = $cod_eps;
        }
        //Catergoria 1 = trabajadores
        // $lista[$i][''] = $persona[$i]
    }
    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    $counteoi = count($lista);
    for ($i = 0; $i < $counteoi; $i++) {

        for ($x = 0; $x < count($lista[$i]); $x++) { //SIEMPRE 4
            for ($j = 0; $j < 1; $j++) {
                $new[$i][$x][$j] = $lista[$i][$x]['cod_tipo_documento'];
                $new[$i][$x][$j + 1] = $lista[$i][$x]['num_documento'];
                $new[$i][$x][$j + 2] = $lista[$i][$x]['cod_pais_emisor_documento'];
                $new[$i][$x][$j + 3] = $lista[$i][$x]['categoria'];
                $new[$i][$x][$j + 4] = $lista[$i][$x]['tipo_de_registro'];
                $new[$i][$x][$j + 5] = $lista[$i][$x]['fecha_inicio'];
                $new[$i][$x][$j + 6] = $lista[$i][$x]['fecha_fin'];
                $new[$i][$x][$j + 7] = $lista[$i][$x]['tipo_registro'];
                $new[$i][$x][$j + 8] = $lista[$i][$x]['cod_eps'];
            }
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo RP_###########.pen
    $file_name = 'RP_' . RUC . '.per';
    generarEstructura_11($file_name, $new);

//    echo "<pre>";
//    print_r($ids);
//    echo "</pre>";
//    echo "<hr>";
//    echo "<hr>";
//    echo "<pre> PERSONA DATA";
//    print_r($persona);
//    echo "</pre>";
//    echo "<br>";
//    echo "<hr>";
//    echo "<pre>LISTA ";
//    print_r($lista);
//    echo "</pre>";
//    echo "<br>";
//    echo "<hr>";
//    echo "<pre>LISTA ";
//    print_r($new);
//    echo "</pre>";

    return $file_name;
}

function estructura_11b($ids = '') {

    // PASO 01  Listar Id de Personas
    $dao = new TrabajadorDao();
    if (empty($ids)) {
        $ids = $dao->listarTrabajadoresSoloID_E11(/* 1 */ ID_EMPLEADOR_MAESTRO);
    }
    // PASO 02  Listar Datos de Personas 
    $persona = array();
    for ($i = 0; $i < count($ids); $i++) {//#trabajadore
        for ($j = 0; $j < 4; $j++) {
            $id = $j + 1;
            $FUNCTION = 'buscarTrabajadorPorID_tipo_registro_' . $id . '_E11';  //Structura COLM 5
            $persona[$i][$j] = $dao->$FUNCTION($ids[$i]['id_trabajador']);
        }
    }

//    echo "<pre>";
//    print_r($ids);
//    echo "</pre>";
//    echo "<pre>persona";
//    print_r($persona);
//    echo "</pre>";

    $lista = array();
    $counteo = count($persona);
    for ($i = 0; $i < $counteo; $i++) { //persona
        //tipos de registro
        $counteox = count($persona[$i]); //always 4
        /*         * *
         *  UNO NUEVA REGLA SUNAT:
         *  solo es necesario que se envie 1 registro:
         *  =periodo laboral OK
         *  ELSE
         *  Descomentar para mostrar 4 filas x trabajador
         */
        for ($j = 0; $j < 1 /* $counteox */; $j++) {

            $lista[$i][$j]['cod_tipo_documento'] = $persona[$i][$j]['cod_tipo_documento'];
            $lista[$i][$j]['num_documento'] = $persona[$i][$j]['num_documento'];
            $lista[$i][$j]['cod_pais_emisor_documento'] = $persona[$i][$j]['cod_pais_emisor_documento'];

            //Categoria:
            // 1 : Trabajador
            // 2 : Pensionista
            // 4 : Personal de terceros
            // 5 : Personal en Formacion
            //Tipo de Registro: Segun el Secuencia de datos :OJO: $FUNCTION()
            // 1 : Periodo Laboral
            // 2 : Tipo Trabajador
            // 3 : Regimen d Aseguramiento Salud
            // 4 : Regimen Pensionario
            // 5 : SCTR salud  : NOT IMPLEMENT.

            $categoria = null;
            $tipo_registro = null;
            $codigo_registro_detalle = null;
            $cod_eps = null;

            if ($j == 0) {
                $categoria = 1;
                $tipo_registro = 1;
                $codigo_registro_detalle = $persona[$i][$j]['cod_motivo_baja_registro'];
            } else if ($j == 1) {
                $categoria = 1;
                $tipo_registro = 2;
                $codigo_registro_detalle = $persona[$i][$j]['cod_tipo_trabajador'];
            } else if ($j == 2) {
                $categoria = 1;
                $tipo_registro = 3;
                $codigo_registro_detalle = $persona[$i][$j]['cod_regimen_aseguramiento_salud'];
                $cod_eps = $persona[$i][$j]['cod_eps'];
            } else if ($j == 3) {
                $categoria = 1;
                $tipo_registro = 4;
                $codigo_registro_detalle = $persona[$i][$j]['cod_regimen_pensionario'];
            } else {
                $categoria = 'ERRROR';
                $tipo_registro = 'Error SCRT ?.';
                $codigo_registro_detalle = "Errror Error php";
            }

            $lista[$i][$j]['categoria'] = $categoria;
            $lista[$i][$j]['tipo_de_registro'] = $tipo_registro;
            $lista[$i][$j]['fecha_inicio'] = null; /* $persona[$i][$j]['fecha_inicio'] */;
            $lista[$i][$j]['fecha_fin'] = $persona[$i][$j]['fecha_fin'];

            $lista[$i][$j]['tipo_registro'] = $codigo_registro_detalle;
            $lista[$i][$j]['cod_eps'] = $cod_eps;
        }
        //Catergoria 1 = trabajadores
        // $lista[$i][''] = $persona[$i]
    }

    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    $counteoi = count($lista);
    for ($i = 0; $i < $counteoi; $i++) {

        for ($x = 0; $x < count($lista[$i]); $x++) { //SIEMPRE 4
            for ($j = 0; $j < 1; $j++) {
                $new[$i][$x][$j] = $lista[$i][$x]['cod_tipo_documento'];
                $new[$i][$x][$j + 1] = $lista[$i][$x]['num_documento'];
                $new[$i][$x][$j + 2] = $lista[$i][$x]['cod_pais_emisor_documento'];
                $new[$i][$x][$j + 3] = $lista[$i][$x]['categoria'];
                $new[$i][$x][$j + 4] = $lista[$i][$x]['tipo_de_registro'];
                $new[$i][$x][$j + 5] = $lista[$i][$x]['fecha_inicio'];
                $new[$i][$x][$j + 6] = $lista[$i][$x]['fecha_fin'];
                $new[$i][$x][$j + 7] = $lista[$i][$x]['tipo_registro'];
                $new[$i][$x][$j + 8] = $lista[$i][$x]['cod_eps'];
            }
        }
    }

    //--------------------------------------------------------------------------
    //----- Archivo RP_###########.pen
    $file_name = 'RP_' . RUC . '.per';
    generarEstructura_11($file_name, $new);


//    echo "<pre>";
//    print_r($ids);
//    echo "</pre>";
//    echo "<hr>";
//    echo "<hr>";
//    echo "<pre> PERSONA DATA";
//    print_r($persona);
//    echo "</pre>";
//    echo "<br>";
//    echo "<hr>";
//    echo "<pre>LISTA ";
//    print_r($lista);
//    echo "</pre>";
//    echo "<br>";
//    echo "<hr>";
//    echo "<pre>LISTA ";
//    print_r($new);
//    echo "</pre>";
//    //echo count($persona[0][1]);


    return $file_name;
}

function estructura_17($ids = '') {

    // PASO 01
    $dao = new TrabajadorDao();
    if (empty($ids)) {
        $ids = $dao->listarTrabajadoresSoloID_E17(/* 1 */ ID_EMPLEADOR_MAESTRO);
    }
    // PASO 02 
    $data = array();
    for ($i = 0; $i < count($ids); $i++) {
        $data[] = $dao->buscarTrabajadorPorId_E17($ids[$i]['id_trabajador']);
    }
    //--------------------------------------------------------------------------
    //CONVIRTIENDO A DATO LINEAL
    $new = array();
    for ($i = 0; $i < count($data); $i++) {
        for ($j = 0; $j < 1; $j++) {
            $new[$i][$j] = $data[$i]['cod_tipo_documento'];
            $new[$i][$j + 1] = $data[$i]['num_documento'];
            $new[$i][$j + 2] = $data[$i]['cod_pais_emisor_documento'];
            $new[$i][$j + 3] = $data[$i]['ruc'];
            $new[$i][$j + 4] = $data[$i]['cod_establecimiento'];
        }
    }
    //--------------------------------------------------------------------------
    //----- Archivo RP_###########.est
    $file_name = 'RP_' . RUC . '.est';
    generarEstructura($file_name, $new);


//    echo "<pre>LISTA ";
//    print_r($ids);
//    echo "</pre>";
//    echo "<br>";
//    echo "<hr>";
//    echo "<pre>new ";
//    print_r($data);
//    echo "</pre>";

    return $file_name;
}

function generarEstructura($file_name, $arreglo) {

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

function generarEstructuraError($file_name, $arreglo) {

    $fp = fopen($file_name, 'w');

    $counteo = count($arreglo);
    for ($i = 0; $i < $counteo; $i++) {

        for ($j = 0; $j < count($arreglo[$i]); $j++) {
            fwrite($fp, $arreglo[$i][$j]);
            //fwrite($fp, $SEPADADOR);
            fwrite($fp, chr(13) . chr(10));
        }//ENDFOR
        fwrite($fp, chr(13));
        fwrite($fp, "--------------------------------------------------------");
        fwrite($fp, chr(13));
    }//ENDFOR
    fclose($fp);
}

//ENDFN
//generarEstructura();
//$arreglo[0][1] = "MEnsaje erroror 01";
//$arreglo[0][2] = "MEnsaje erroror 02";
//$arreglo[0][3] = "MEnsaje erroror 03";
//$arreglo[0][4] = "MEnsaje erroror 04";
//$arreglo[1][0] = "MEnsaje erroror 10";
//$arreglo[1][1] = "MEnsaje erroror 11";
//$arreglo[2][0] = "MEnsaje erroror 10";
//$arreglo[2][1] = "MEnsaje erroror 11";

//generarEstructuraError("error.txt",$arreglo);
//estructura_4();
//estructura_5();
//estructura_11a();
//estructura_6();

//estructura_17();