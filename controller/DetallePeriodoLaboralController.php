<?php

function buscarDetallePeriodoLaboral($id_TRA){
	$dao = new DetallePeriodoLaboralDao();
	/*buscaTrabajadorPorIdPersona*/
	$data = $dao->buscarDetallePeriodoLaboral($id_TRA);	
	//echo "CONTROLLER DAO";
	//echo "<pre>";
	//print_r($data);
	//echo "</pre>";
	$model = new DetallePeriodoLaboral();		
	$model->setId_detalle_periodo_laboral($data['id_detalle_periodo_laboral']);
	$model->setId_trabajador($data['id_trabajador']);
	$model->setCod_motivo_baja_registro($data['cod_motivo_baja_registro']);
	$model->setFecha_inicio($data['fecha_inicio']);
        $model->setFecha_fin($data['fecha_fin']);	
//	var_dump($model);	
	return $model;
		
}















//------------------------------------------------------------------------------
// --------------------- FUNCIONES PARA cargar_tabla ---------------------------
//------------------------------------------------------------------------------
function contar() {
    $data = unserialize($_SESSION['data_01']);
    $counteo = count($data);
    return $counteo;
}

function listar(){
   $data = unserialize($_SESSION['data_01']);
   return $data;
}








?>
