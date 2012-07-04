<?php
/**
 *
 * @param type $id
 * @return \DetallePeriodoFormativo 
 * 
 * @tutorial || CATEGORIA Persona Formacion Laboral esta vinculado con
 *  la tabla detalle_establecimientos_formacion 
 * NEED
 */

function buscarDetalleEstablecimientoFormacion($id) {

    $dao = new DetalleEstablecimientoFormacionDao();

    $data = $dao->buscarDetalleEstablecimientoFormacion($id);
    //echo "CONTROLLER DAO";
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    $model = new DetalleEstablecimientoFormacion();
    $model->setId_detalle_establecimiento_formacion($data['id_detalle_establecimiento_formacion']);
    $model->setId_personal_formacion_laboral($data['id_personal_formacion_laboral']);
    $model->setId_establecimiento($data['id_establecimiento']);

//	var_dump($model);	
    return $model;
}

?>