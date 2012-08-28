<?php

function listar_concepto_calc_ID_TrabajadorPdeclaracion($id_trabajador_pdeclaracion) {

    $dao = new DeclaracionDconceptoDao();
    return $dao->buscar_ID_TrabajadorPdeclaracion($id_trabajador_pdeclaracion);
}

?>
