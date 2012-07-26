<?php
// view
function listarDcem_Pdescuento($id_ptrabajador) {

    $dao = new Dcem_PdescuentoDao();
    $data = $dao->listar_ID_Ptrabajador($id_ptrabajador, "1");
    return $data; 
}



?>
