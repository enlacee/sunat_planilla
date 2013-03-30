<?php

class PlameDetalleConceptoDao extends AbstractDao {

    //put your code here

    public function listar() {
        $query = "SELECT *FROM detalles_conceptos";

        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    // usado para los conceptos 1000
    public function update($cod_detalle_concepto, $descripcion) {

        $query = "
        UPDATE detalles_conceptos
        SET
          descripcion = ?
        WHERE cod_detalle_concepto = ?;        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $descripcion);
        $stm->bindValue(2, $cod_detalle_concepto);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

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

    //USANDO
    public function buscarID($cod_detalle_concepto) {

        $query = "
        SELECT *FROM detalles_conceptos
        WHERE cod_detalle_concepto = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_detalle_concepto);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

    //-------------------------------------------------------------------------



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

    // Usado para Cargar Conceptoo X ID_EMPLEADOR_MAESTRO //FUCK
    public function listarConceptos() {
        $query = "
      SELECT *FROM detalles_conceptos

      ";
        // -- ok 135 la cantidad SI NO
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    //  para tabla detalle_concepto_afectaciones
    // RE IMPORTANTEEEEEEEEEEEEEEEEE!!!!
    //utilizadoooo
    //Para registrar empleadores con sus propios conceptos
    public function listarXXX() { //los que no TIENEN SI Y NO  okkkK Funcionando
        $query = "
        SELECT *FROM detalles_conceptos
        WHERE (cod_concepto != '2000'
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

    public function listarXXXSinAfeccion() { //los que no TIENEN SI Y NO  okkkK Funcionando
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
