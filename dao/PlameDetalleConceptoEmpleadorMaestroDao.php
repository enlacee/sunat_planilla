<?php

class PlameDetalleConceptoEmpleadorMaestroDao extends AbstractDao {

    //put your code here
    public function registrar($id_empleador_maestro, $cod_detalle_concepto) {

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

        //--------------
        try {
            //Inicia transaccion
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_empleador_maestro);
            $stm->bindValue(2, $cod_detalle_concepto);
            $stm->execute();

            // id Persona
            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();

            $this->pdo->commit();
            //finaliza transaccion
            //return true;
            $stm = null;
            return $lista[0]['id'];
            
        } catch (Exception $e) {
            //  Util::rigistrarLog( $e, $query );
            $this->pdo->rollBack();
            throw $e;
        }
        //-------------



        return true;
    }

    public function buscarID_EmpleadorRegistrado($id_empleador_maestro) {

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

    //public function buscar

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

    public function listar($cod_concepto, $id_empleador_maestro) {

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

    public function cantidad($cod_concepto, $id_empleador_maestro) {
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
    public function actualizar($id_detalle_conceptoEM, $estado) {

        $query = "
        UPDATE detalles_conceptos_empleadores_maestros
        SET 
        seleccionado = ?
        WHERE id_detalle_concepto_empleador_maestro = ? ;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $estado);
        $stm->bindValue(2, $id_detalle_conceptoEM);
        $stm->execute();
        $stm = null;

        return true;
    }

    public function estado($id_detalle_conceptoEM, $estado) {

        $query = "
        UPDATE detalles_conceptos_empleadores_maestros
        SET 
        seleccionado = ?
        WHERE id_detalle_concepto_empleador_maestro = ? ;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $estado);
        $stm->bindValue(2, $id_detalle_conceptoEM);
        $stm->execute();
        $stm = null;

        return true;
    }

}

?>
