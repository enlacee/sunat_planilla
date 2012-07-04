<?php

function buscarPTerceroPorIdPersona($ID_PERSONA) {

    $dao = new PersonaTerceroDao();
    /* buscaTrabajadorPorIdPersona */
    $data = $dao->buscaPersonaTerceroPorIdPersona($ID_PERSONA);
    //echo "CONTROLLER DAO";

    $model = new PersonaTercero();
    $model->setId_personal_tercero($data['id_personal_tercero']);
    $model->setId_persona($data['id_persona']);
    $model->setId_empleador_destaque_yoursef($data['id_empleador_destaque_yoursef']);
    $model->setCod_situacion($data['descripcion_abreviada']);
    $model->setCobertura_pension($data['cobertura_pension']);



    return $model;
}

?>
