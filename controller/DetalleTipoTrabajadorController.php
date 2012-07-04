<?php


function buscarDetalleTipoTrabajador($id_TRA){
	$dao = new DetalleTipoTrabajadorDao();
	$data = $dao->buscarDetalleTipoTrabajador($id_TRA);
        
	$model = new DetalleTipoTrabajador();		
	$model->setId_detalle_tipo_trabajador($data['id_detalle_tipo_trabajador']);
	$model->setId_trabajador($data['id_trabajador']);
	$model->setCod_tipo_trabajador($data['cod_tipo_trabajador']);
	$model->setFecha_inicio($data['fecha_inicio']);
        $model->setFecha_fin($data['fecha_fin']);	
//	var_dump($model);
	
	return $model;
		
}






















?>
