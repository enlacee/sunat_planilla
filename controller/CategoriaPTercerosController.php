<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");
$op = $_REQUEST["oper"];
if ($op) {

    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    
    require_once '../model/PersonaTercero.php';
    require_once '../dao/PersonaTerceroDao.php';

    // sub 01 - Periodo
    require_once '../model/PeriodoDestaque.php';
    require_once '../dao/PeriodoDestaqueDao.php';
    
    // sub 02 - Lugar Destaque
    require_once '../model/LugarDestaque.php';
    require_once '../dao/LugarDestaqueDao.php';
    
    //sub 03 - Cobertura Salud
    require_once '../model/CoberturaSalud.php';
    require_once '../dao/CoberturaSaludDao.php';
    
  }
  

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /** *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "edit"){

    $responce = editarPTerceros();
} else {

    //echo "-_-";
}

echo json_encode($responce);

//function nuevoPFormacion($ID_PERSONA) { }

function editarPTerceros() {

    //Identificador
    $ID_PERSONA_TERCERO = $_REQUEST['id_personal_tercero'];

    $pen = new PersonaTercero();
    $pen->setId_personal_tercero($ID_PERSONA_TERCERO);
    //$pen->setId_persona($id_persona);
    $pen->setId_empleador_destaque_yoursef($_REQUEST['cbo_empleadores_destaques_yourself']);    
    $pen->setCobertura_pension($_REQUEST['rbtn_cobertura_pension']);
    
    // --> GUARDAR    
    $dao = new PersonaTerceroDao();
    $dao->actualizar($pen); 
    
    // sub 01 - Periodos Destaque -------------------------------------
    $obj1 = new PeriodoDestaque();
    $obj1->setId_periodo_destaque($_REQUEST['id_periodo_destaque']);
    $obj1->setId_personal_tercero($ID_PERSONA_TERCERO);
    $obj1->setFecha_inicio(getFechaPatron($_REQUEST['txt_pdestaque_finicio_base'],"Y-m-d"));
    $obj1->setFecha_fin(getFechaPatron( $_REQUEST['txt_pdestaque_ffin_base'],"Y-m-d"));

    // Guardar
    $dao1 = new PeriodoDestaqueDao();
    $dao1->actualizar($obj1);

    // sub 02 - Lugares Destaques -------------------------------------
    $obj2 = new LugarDestaque();
    $obj2->setId_lugar_destaque($_REQUEST['id_lugar_destaque']);
    $obj2->setId_personal_tercero($ID_PERSONA_TERCERO);
    $obj2->setId_establecimiento($_REQUEST['txt_id_establecimiento3']);
//    echo "<pre>";
//    print_r($obj2);
//    echo "</pre>";
    // Guardar
    $dao2 = new LugarDestaqueDao();
    $dao2->actualizar($obj2);
    
    // sub 03 - Cobertura Salud -------------------------------------
    $obj3 = new CoberturaSalud();
    $obj3->setId_cobertura_salud($_REQUEST['id_cobertura_salud']);
    $obj3->setNombre_cobertura($_REQUEST['rbtn_cobertura_salud']);
    $obj3->setFecha_inicio(getFechaPatron($_REQUEST['txt_csalud_finicio_base'],"Y-m-d"));
    $obj3->setFecha_fin(getFechaPatron($_REQUEST['txt_csalud_ffin_base'],"Y-m-d"));
    
    //Guardar
    $dao3 = new CoberturaSaludDao();
    $dao3->actualizar($obj3);

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