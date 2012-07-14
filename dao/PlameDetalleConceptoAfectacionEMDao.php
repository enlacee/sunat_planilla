<?php

class PlameDetalleConceptoAfectacionEMDao extends AbstractDao {

    //put your code here


    function registrar($ID_DCEM,$cod_afectacion) {
        $query = "
        INSERT INTO detalles_conceptos_afectaciones_em
                    (
                    id_detalle_concepto_empleador_maestro,
                    cod_afectacion

                    )
        VALUES (?,
                ?     
                );           
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_DCEM);
        $stm->bindValue(2, $cod_afectacion);
        $stm->execute();
        return true;
       // $lista = $stm->fetchAll();
    }

}

?>
