<?php

function buscarDetallePeriodoLaboralPensionista($id_PEN){
	$dao = new DetallePeriodoLaboralPensionistaDao();
	/*buscaTrabajadorPorIdPersona*/
	$data = $dao->buscarDetallePeriodoLaboralPensionista($id_PEN);	
	//echo "CONTROLLER DAO";
	//echo "<pre>";
	//print_r($data);
	//echo "</pre>";
	$model = new DetallePeriodoLaboralPensionista();		
	$model->setId_detalle_periodo_laboral_pensionista($data['id_detalle_periodo_laboral_pensionista']);
	$model->setId_pensionista($data['id_pensionista']);
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
