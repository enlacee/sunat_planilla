<?php
//class PlameJornadaLaboralController {}
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PersonaDao.php';
    
    require_once '../model/PjornadaLaboral.php';
    require_once '../dao/PjoranadaLaboralDao.php';
}


$response = NULL;

if ($op == "edit") {
    $response = editarPjornadaLaboral();
} else if ($op == "") {
    
}


echo (!empty($response)) ? json_encode($response) : '';

/**
 * 04/08/2012
 * @return type Editar Jornada Laboral
 */
function editarPjornadaLaboral() {

    /*$id_pjornada_laboral = $_REQUEST['id_pjornada_laboral'];

    $model = new PjornadaLaboral();
    $model->setId_pjornada_laboral($id_pjornada_laboral);
    $model->setDia_subsidiado($_REQUEST['dia_subsidiado']);
    $model->setDia_nosubsidiado($_REQUEST['dia__nosubsidiado']);

    $model->setHora_ordinaria_hh($_REQUEST['hora_ordinaria_hh']);
    $model->setHora_ordinaria_mm($_REQUEST['hora_ordinaria_mm']);
    $model->setHora_sobretiempo_hh($_REQUEST['hora_sobretiempo_hh']);
    $model->setHora_sobretiempo_mm($_REQUEST['hora_sobretiempo_mm']);

    //calculados

    $dao = new PjoranadaLaboralDao();
    $dao->actualizar($model);
    return true;*/
}

function buscarPjornadaLaboral_Id($id_pjoranada_laboral) {
    $dao = new PjoranadaLaboralDao();

    $data = $dao->buscar_ID($id_pjoranada_laboral);
    
    
    $model = new PjornadaLaboral();
    $model->setId_pjornada_laboral($data['id_pjornada_laboral']);
    $model->setId_ptrabajador($data['id_ptrabajador']);
    $model->setDia_laborado($data['dia_laborado']);
    $model->setDia_subsidiado($data['dia_subsidiado']);
    $model->setDia_nosubsidiado($data['dia_nosubsidiado']);
    //$model->($data['']);
    $model->setHora_ordinaria_hh($data['hora_ordinaria_hh']);
    $model->setHora_ordinaria_mm($data['hora_ordinaria_mm']);
    $model->setHora_sobretiempo_hh($data['hora_sobretiempo_hh']);
    $model->setHora_sobretiempo_mm($data['hora_sobretiempo_mm']);
    
    return $model;
    
}


function buscarPjornadaLaboral_IdPtrabajdor($id_ptrabajador) {
    $dao = new PjoranadaLaboralDao();

    $data = $dao->buscar_ID_ptrabajador($id_ptrabajador);

    $model = new PjornadaLaboral();
    $model->setId_pjornada_laboral($data['id_pjornada_laboral']);
    $model->setId_ptrabajador($data['id_ptrabajador']);
    $model->setDia_laborado($data['dia_laborado']);
    $model->setDia_subsidiado($data['dia_subsidiado']);
    $model->setDia_nosubsidiado($data['dia_nosubsidiado']);
    $model->setDia_total($data['dia_total']);
    $model->setHora_ordinaria_hh($data['hora_ordinaria_hh']);
    $model->setHora_ordinaria_mm($data['hora_ordinaria_mm']);
    $model->setHora_sobretiempo_hh($data['hora_sobretiempo_hh']);
    $model->setHora_sobretiempo_mm($data['hora_sobretiempo_mm']);
    
    //var_dump($data);
    //var_dump($model);
    
    return $model;
    
}

?>
