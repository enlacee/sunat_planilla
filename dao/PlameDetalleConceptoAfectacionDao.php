<?php

class PlameDetalleConceptoAfectacionDao extends AbstractDao {

    // USANDO PARA CARGA ONLY DEVELOPER usando ??? FUCK!
    public function registrar($cod_detalle_concepto, $cod_afectacion) {

        $query = "
        INSERT INTO detalle_conceptos_afectaciones
                    (
                    cod_detalle_concepto,
                    cod_afectacion
                    )
        VALUES (
                ?,
                ?
                );         
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_detalle_concepto);
        $stm->bindValue(2, $cod_afectacion);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function actualizar($id_detalle_concepto_afectacion, $estado) {

        $query = "
        UPDATE detalle_conceptos_afectaciones
        SET 
        afecto = ?
        WHERE id_detalle_concepto_afectacion = ?;      
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $estado);
        $stm->bindValue(2, $id_detalle_concepto_afectacion);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function listar($cod_detalle_concepto) { //listar  = configuracion
        $query = "
        SELECT 
        dca.id_detalle_concepto_afectacion,
        dca.afecto,
        descripcion
        FROM detalle_conceptos_afectaciones AS dca
        INNER JOIN afectaciones AS af
        ON dca.cod_afectacion = af.cod_afectacion

        WHERE dca.cod_detalle_concepto = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_detalle_concepto);
        //$stm->bindValue(2, $cod_afectacion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    //------------------------------------------------------------------------
    //      Funciones Afectos o no afectos 03/09/2012
    //------------------------------------------------------------------------

    /**
     * Afecto a Renta de 5ta Categoria
     * $cod_afectacion
     * <br/>
     *  10 = renta 5ta categoria;
     * <br/>
     *  9  = sistema privado pensiones;
     */
    public function conceptosAfecto_a($cod_afectacion, $afecto=1) {

        $query = "
        SELECT 
        -- id_detalle_concepto_afectacion,
        -- cod_afectacion,
        -- afecto,
         cod_detalle_concepto  

        FROM detalle_conceptos_afectaciones
        WHERE cod_afectacion = ?
        AND  afecto = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_afectacion);
        $stm->bindValue(2, $afecto);
        $stm->execute();
        $lista = $stm->fetchAll(); //$stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
