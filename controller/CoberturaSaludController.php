<?php

function buscarCoberturaSaludPtercero($ID_PERSONA_TERCERO) {

    $dao = new CoberturaSaludDao();
    /* buscaTrabajadorPorIdPersona */
    $data = $dao->buscarCoberturaSalud($ID_PERSONA_TERCERO);
    //echo "CONTROLLER DAO";
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    $model = new CoberturaSalud();
    $model->setId_cobertura_salud($data['id_cobertura_salud']);
    $model->setId_personal_tercero($data['id_personal_tercero']);
    $model->setNombre_cobertura($data['nombre_cobertura']);
    
    $model->setFecha_inicio($data['fecha_inicio']);
    $model->setFecha_fin($data['fecha_fin']);
//var_dump();

    return $model;
}

?>