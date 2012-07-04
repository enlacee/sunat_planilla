<?php

function buscarPeriodoDestaquePtercero($ID_PERSONA_TERCERO) {

    $dao = new PeriodoDestaqueDao();
    /* buscaTrabajadorPorIdPersona */
    $data = $dao->buscarPeriodoDestaque($ID_PERSONA_TERCERO);
    //echo "CONTROLLER DAO";
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    $model = new PeriodoDestaque();
    $model->setId_periodo_destaque($data['id_periodo_destaque']);
    $model->setId_personal_tercero($data['id_personal_tercero']);
    $model->setFecha_inicio($data['fecha_inicio']);
    $model->setFecha_fin($data['fecha_fin']);

//var_dump($model);

    return $model;
}

?>