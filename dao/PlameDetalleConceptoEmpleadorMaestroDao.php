<?php

class PlameDetalleConceptoEmpleadorMaestroDao extends AbstractDao {

    //put your code here
    function registrar($id_empleador_maestro, $cod_detalle_concepto) {

        $query = "
        INSERT INTO detalles_conceptos_empleadores_maestros
                    (
                    id_empleador_maestro,
                    cod_detalle_concepto
                    )
        VALUES (
                ?,
                ?
                );         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $cod_detalle_concepto);
        $stm->execute();
        $stm = null;

        return true;
    }

    function buscarID($id_empleador_maestro) {

        $query = "
        SELECT 
        id_detalle_concepto_empleador_maestro,
        id_empleador_maestro 
        FROM detalles_conceptos_empleadores_maestros
        WHERE id_empleador_maestro = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }

    /*
      function actualizar(){

      $query = "
      SELECT
      id_detalle_concepto_empleador_maestro,
      id_empleador_maestro
      FROM detalles_conceptos_empleadores_maestros
      WHERE id_empleador_maestro = ?
      ";

      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_empleador_maestro);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;

      }

     */

    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------

    function listar($cod_concepto, $id_empleador_maestro) {

        $query = "
        SELECT 
        dcem.id_detalle_concepto_empleador_maestro,
        dcem.id_empleador_maestro,
        dcem.seleccionado,
        dc.cod_concepto,
        dc.cod_detalle_concepto,
        dc.descripcion
        FROM detalles_conceptos_empleadores_maestros AS dcem
        INNER JOIN detalles_conceptos AS dc
        ON dcem.cod_detalle_concepto = dc.cod_detalle_concepto

        INNER JOIN empleadores_maestros AS em
        ON dcem.id_empleador_maestro = em.id_empleador_maestro

        WHERE dc.cod_concepto = ?
        AND dcem.id_empleador_maestro = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_concepto);
        $stm->bindValue(2, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    function cantidad($cod_concepto, $id_empleador_maestro) {
        $query = "
        SELECT 
        COUNT(*) AS numfilas
        FROM detalles_conceptos_empleadores_maestros AS dcem
        INNER JOIN detalles_conceptos AS dc
        ON dcem.cod_detalle_concepto = dc.cod_detalle_concepto

        INNER JOIN empleadores_maestros AS em
        ON dcem.id_empleador_maestro = em.id_empleador_maestro

        WHERE dc.cod_concepto = ?
        AND dcem.id_empleador_maestro = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_concepto);
        $stm->bindValue(2, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]["numfilas"];
    }

    
    // Marcando = TRUE
    function actualizar($id_detalle_conceptoEM){

        $query = "
        UPDATE detalles_conceptos_empleadores_maestros
        SET 
        seleccionado = 1
        WHERE id_detalle_concepto_empleador_maestro = ? ;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_detalle_conceptoEM );
        //$stm->bindValue(2, $id_detalle_conceptoEM );
        $stm->execute();
        $stm = null;

        return true;
    }

}

?>
