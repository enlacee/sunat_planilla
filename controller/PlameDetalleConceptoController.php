<?php
//class PlameDetalleConceptoController {}



function buscar_detalle_concepto_id( $cod_detalle_concepto ) {

    $dao = new PlameDetalleConceptoDao();
    return $dao->buscarID($cod_detalle_concepto);
    
}

?>
