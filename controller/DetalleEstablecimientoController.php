<?php

function buscarDetalleEstablecimiento($id_TRA) {
    $dao = new DetalleEstablecimientoDao();
    $data = $dao->buscarDetalleEstablecimiento($id_TRA);

    $model = new DetalleEstablecimiento();
    $model->setId_detalle_establecimiento($data['id_detalle_establecimiento']);
    $model->setId_trabajador($data['id_trabajador']);
    $model->setId_establecimiento($data['id_establecimiento']);

    return $model;
}

?>
