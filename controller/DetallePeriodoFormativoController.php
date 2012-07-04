<?php

function buscarDetallePeriodoFormativo($id) {

    $dao = new DetallePeriodoFormativoDao();

    $data = $dao->buscarDetallePeriodoFormativo($id);
    //echo "CONTROLLER DAO";
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    $model = new DetallePeriodoFormativo();
    $model->setId_detalle_periodo_formativo($data['id_detalle_periodo_formativo']);
    //$model->setId_personal_formacion_laboral(  );
    $model->setFecha_inicio($data['fecha_inicio']);
    $model->setFecha_fin($data['fecha_fin']);
//	var_dump($model);	
    return $model;
}

?>
