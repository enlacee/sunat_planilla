<?php

class PlameDetalleConceptoDao extends AbstractDao {

    //put your code here

    public function cantidad($cod_concepto) {
        $query = "
            SELECT COUNT(*) AS numfilas
            FROM detalles_conceptos
            WHERE cod_concepto = ?
            ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_concepto);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]["numfilas"];
    }

/*    
    //uso para el GRID no se utilizara
    public function listar($cod_concepto) {

        $query = "
        SELECT *FROM detalles_conceptos
        WHERE cod_concepto = ?

        -- ORDER BY $sidx $sord LIMIT $start,  $limit
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_concepto);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
*/
    
    // Usado para Cargar Conceptoo X ID_EMPLEADOR_MAESTRO
      public function listarConceptos() {
      $query = "
      SELECT *FROM detalles_conceptos
     /* WHERE (cod_concepto != '1000'
      AND cod_concepto != '0600'
      AND cod_concepto != '0700'
      AND cod_concepto != '0800');*/
      ";
      // -- ok 135 la cantidad SI NO
      $stm = $this->pdo->prepare($query);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;

      return $lista;
      } 

    //  para tabla detalle_concepto_afectaciones
    public function listarX() { //los que no TIENEN SI Y NO
        $query = "
        SELECT *FROM detalles_conceptos
        WHERE (cod_concepto != '0600' 
        AND cod_concepto != '0700' 
        AND cod_concepto != '0800' 
        )
        ORDER BY cod_detalle_concepto ASC;
        ;
        ";
        // -- ok 135 la cantidad SI NO
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

}

?>
