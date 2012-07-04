<?php

function buscarDetalleRegimenSalud($id_TRA) {
    $dao = new DetalleRegimenSaludDao();
    $data = $dao->buscarDetalleRegimenSalud($id_TRA);

	
    $model = new DetalleRegimenSalud();
    $model->setId_detalle_regimen_salud($data['id_detalle_regimen_salud']);
    $model->setId_trabajador($data['id_trabajador']);
    $model->setCod_regimen_aseguramiento_salud($data['cod_regimen_aseguramiento_salud']);
    $model->setFecha_inicio($data['fecha_inicio']);
    $model->setFecha_fin($data['fecha_fin']);
    $model->setCod_eps($data['cod_eps']);
    return $model;
}

?>