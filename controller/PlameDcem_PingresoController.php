<?php

//class PlameDcem_PingresoController{}
// ---- view
function listarDcem_Pingreso($id_ptrabajador) {

    $dao = new Dcem_PingresoDao();
    $data = $dao->listar_ID_Ptrabajador($id_ptrabajador, "1");

    /*$arreglo = array();
    $model = new Dcem_Pingreso();    
    
    for ($i = 0; $i < count($data); $i++) {
        $model->setId_dcem_pingreso($data[$i]['id_dcem_pingreso']);
        $model->setId_ptrabajador($data[$i]['id_ptrabajador']);
        $model->setId_detalle_concepto_empleador_maestro($data[$i]['id_detalle_concepto_empleador_maestro']);
        $model->setDevengado($data[$i]['devengado']);
        $arreglo[] = $model;
    }*/

    return $data; 
}

?>
