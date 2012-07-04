<?php

function buscarLugarDestaquePtercero($ID_PERSONA_TERCERO) {

    $dao = new LugarDestaqueDao();
    /* buscaTrabajadorPorIdPersona */
    $data = $dao->buscarLugarDestaque($ID_PERSONA_TERCERO);
    //echo "CONTROLLER DAO";

    $model = new LugarDestaque();
    $model->setId_lugar_destaque($data['id_lugar_destaque']);
    $model->setId_personal_tercero($data['id_personal_tercero']);
    $model->setId_establecimiento($data['id_establecimiento']);

    //echo "<pre>";
    //print_r($model);
    //echo "</pre>";

    return $model;
}

?>