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
    
    public function actualizar($id){
        
        $query = "
        UPDATE detalle_conceptos_afectaciones
        SET 
        afecto = afecto
        WHERE id_detalle_concepto_afectacion = ?;      
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        //$stm->bindValue(2, $cod_afectacion);
        $stm->execute();
        $stm = null;
        return true;
        
    }
    
    

    
    /**
     *
     * @param type $cod_detalle_concepto
     * @return type 
     * <br>Usado para listar para CONFIGURACION
     */
    public function listar($cod_detalle_concepto) { //listar  = configuracion

        $query = "
        SELECT 
        dca.id_detalle_concepto_afectacion,
        dca.afecto,
        descripcion
        FROM detalle_conceptos_afectaciones AS dca
        INNER JOIN afectaciones AS af
        ON dca.cod_afectacion = af.cod_afectacion

        WHERE cod_detalle_concepto = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_detalle_concepto);
        //$stm->bindValue(2, $cod_afectacion);
        $stm->execute();        
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
        
    }
    


}

?>
