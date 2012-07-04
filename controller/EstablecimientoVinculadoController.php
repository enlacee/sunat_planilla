<?php

/**
 * CONTROLLER USADO Cargar Lista de tipo de Servicio OK
 * *
 * */
//UNION UNICA = id_empleador_maestro_empleado = 

function listarDetalleEstablecimientoVinculado($id_empleador_maestro, $id_empleador) {

    // (01) ->Buscar Id Empleador Destaque 
    $dao = new EmpleadorDestaqueDao();
    $id_empleador_destaque = $dao->buscarIdEmpleadorDestaque($id_empleador_maestro, $id_empleador);

    // (02) ->Listar Servicios
    if (!empty($id_empleador_destaque)) {

        $dao = new EstablecimientoVinculadoDao();
        $data = $dao->listar($id_empleador_destaque);
    }
    return $data;
}

?>
