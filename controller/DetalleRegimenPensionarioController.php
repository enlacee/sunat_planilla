<?php

function buscarDetalleRegimenPensionario($id_TRA) {
	
    $dao = new DetalleRegimenPensionarioDao();    
    $data = $dao->buscarDetalleRegimenPensionario($id_TRA);

    $model = new DetalleRegimenPensionario();
    $model->setId_detalle_regimen_pensionario($data['id_detalle_regimen_pensionario']);
	$model->setCod_regimen_pensionario($data['cod_regimen_pensionario']);
    $model->setId_trabajador($data['id_trabajador']);
    $model->setCUSPP($data['cuspp']);
    $model->setFecha_inicio($data['fecha_inicio']);
    $model->setFecha_fin($data['fecha_fin']);
    
    return $model;
}

?>