<?php

/**
 * CONTROLLER USADO 
 * *
 * */
function listarServiciosPrestados($id_empleador_maestro, $id_empleador) {

    // (01) ->Buscar Id Empleador Destaque Yourself
    $dao = new EmpleadorDestaqueDao();
    $id_emp_destaque = $dao->buscarIdEmpleadorDestaque($id_empleador_maestro, $id_empleador);

    // (02) ->Listar Servicios
    if (!empty($id_emp_destaque)) {

        $dao = new ServicioPrestadoDao();
        $data = $dao->listar($id_emp_destaque);
    }
    return $data;
}

?>
