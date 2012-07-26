<?php
function listarDcem_PtributoAporte($id_ptrabajador) {

    $dao = new Dcem_PtributoAporteDao();
    $data = $dao->listar_ID_Ptrabajador($id_ptrabajador, "1");
    return $data; 
}
?>
