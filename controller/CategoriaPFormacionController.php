<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");
$op = $_REQUEST["oper"];
if ($op) {
    //Empleador
    //require_once '../model/Empleador.php';
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    //require_once '../dao/EmpleadorDao.php';
    // Categoria Trabajador
    require_once '../model/PersonaFormacionLaboral.php';
    require_once '../dao/PersonaFormacionLaboralDao.php';

    //Periodo
    require_once '../model/DetallePeriodoFormativo.php';
    require_once '../dao/DetallePeriodoFormativoDao.php';

    //Establecimiento Formacion
    require_once '../model/DetalleEstablecimientoFormacion.php';
    require_once '../dao/DetalleEstablecimientoFormacionDao.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "edit") {

    $responce = editarPFormacion();
} else {

    //echo "-_-";
}

echo json_encode($responce);

//function nuevoPFormacion($ID_PERSONA) { }

function editarPFormacion() {

    //Identificador
    $ID_PERSONA_FORMACION = $_REQUEST['id_persona_formacion'];

    $pen = new PersonaFormacionLaboral();
    $pen->setId_personal_formacion_laboral($ID_PERSONA_FORMACION);

    //$pen->setId_persona(null);
    $pen->setSeguro_medico($_REQUEST['rbtn_seguro_medico']);

    $pen->setCod_nivel_educativo($_REQUEST['cbo_nivel_educativo']); //Pensionista o cesante

    $pen->setId_modalidad_formativa($_REQUEST['cbo_tipo_modalidad_formativa']);

    $pen->setId_ocupacion_2($_REQUEST['cbo_ocupacion2']);

    $pen->setCentro_formacion($_REQUEST['rbtn_cformacion']);

    $pen->setPresenta_discapacidad($_REQUEST['rbtn_pdiscapacidad']);

    $pen->setHorario_nocturno($_REQUEST['rbtn_hnocturno']);

    // --> GUARDAR    
    $dao = new PersonaFormacionLaboralDao();
    $dao->actualizar($pen); //return
    // sub Establecimiento de formacion
    $obj1 = new DetalleEstablecimientoFormacion();
    $obj1->setId_detalle_establecimiento_formacion($_REQUEST['id_detalle_establecimiento_formacion']);
    $obj1->setId_personal_formacion_laboral($ID_PERSONA_FORMACION);
    $obj1->setId_establecimiento($_REQUEST['txt_id_establecimiento2']);

//		echo "<pre>controller";
//		print_r($obj1);
//		echo "</pre>";
    // Guardar
    $dao1 = new DetalleEstablecimientoFormacionDao();
    $dao1->actualizar($obj1);

    // sub Periodo Formativo
    $obj2 = new DetallePeriodoFormativo();
    $obj2->setId_detalle_periodo_formativo($_REQUEST['id_detalle_perido_formativo']);
    $obj2->setId_personal_formacion_laboral($ID_PERSONA_FORMACION);
    $obj2->setFecha_inicio( getFechaPatron( $_REQUEST['txt_pformativo_fecha_inicio_base'],"Y-m-d"));
    $obj2->setFecha_fin(getFechaPatron ( $_REQUEST['txt_pformativo_fecha_fin_base'],"Y-m-d"));

    // Guardar
    $dao2 = new DetallePeriodoFormativoDao();
    $dao2->actualizar($obj2);

    return true;
}

/*
 * Funcion Persona Formacion
 */

function buscaPersonaFormacionPorIdPersona($id_persona) {

    $dao = new PersonaFormacionLaboralDao();
    /* buscaTrabajadorPorIdPersona */
    $data = $dao->buscaPFormacionLaboralPorIdPersona($id_persona);
    //echo "CONTROLLER DAO";
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    $model = new PersonaFormacionLaboral();
    $model->setId_personal_formacion_laboral($data['id_personal_formacion_laboral']);
    $model->setId_persona($data['id_persona']);
    $model->setCod_nivel_educativo($data['cod_nivel_educativo']);
    $model->setId_modalidad_formativa($data['id_modalidad_formativa']);
    $model->setId_ocupacion_2($data['id_ocupacion_2']);
    $model->setCentro_formacion($data['centro_formacion']);
    $model->setSeguro_medico($data['seguro_medico']);
    $model->setPresenta_discapacidad($data['presenta_discapacidad']);
    $model->setHorario_nocturno($data['horario_nocturno']);
    $model->setCod_situacion($data['descripcion_abreviada']);
    //var_dump($model);

    return $model;
}