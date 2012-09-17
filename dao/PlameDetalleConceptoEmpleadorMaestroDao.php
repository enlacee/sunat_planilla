<?php

class PlameDetalleConceptoEmpleadorMaestroDao extends AbstractDao {

    //put your code here
    public function registrar($id_empleador_maestro, $cod_detalle_concepto, $seleccionado) {

        $query = "
        INSERT INTO detalles_conceptos_empleadores_maestros
                    (
                    id_empleador_maestro,
                    cod_detalle_concepto,
                    seleccionado
                    )
        VALUES (
                ?,
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
            $stm->bindValue(3, $seleccionado);

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
        LIMIT 1
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
        dcem.descripcion_1000,
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

    public function actualizarConceptoDescripcion1000($id_detalle_concepto_empleador_maestro, $descripcion) {

        $query = "
        UPDATE detalles_conceptos_empleadores_maestros
        SET 
          descripcion_1000 = ?
        WHERE id_detalle_concepto_empleador_maestro = ?;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $descripcion);
        $stm->bindValue(2, $id_detalle_concepto_empleador_maestro);
        $stm->execute();
        $stm = null;
        return true;
    }

    /*
     * Utilizando EN concepto 1000 JAVASCRIPT 
     * /view-plame/detalle/view_detalle_concepto_1000.php
     * Function busca ID PARA CHECK Segun empleador Maestro
     */

    public function buscarID_CheckConcepto1000_EM($id_empleador_maestro, $cod_detalle_concepto) {

        $query = "
        SELECT 
            id_detalle_concepto_empleador_maestro 
        FROM detalles_conceptos_empleadores_maestros
        WHERE id_empleador_maestro = ?
        AND cod_detalle_concepto = ?   
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $cod_detalle_concepto);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]['id_detalle_concepto_empleador_maestro'];
    }

    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------
    //  LISTAR datos para tablas
    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------

    public function listar_dcem_pingresos($id_empleador_maestro) {
        $query = "
        SELECT 
        dcem.id_detalle_concepto_empleador_maestro,
        dcem.id_empleador_maestro,
        dcem.cod_detalle_concepto

        FROM detalles_conceptos AS dc
        INNER JOIN detalles_conceptos_empleadores_maestros AS dcem
        ON dc.cod_detalle_concepto = dcem.cod_detalle_concepto

        WHERE (dc.cod_concepto ='0100' 
        OR dc.cod_concepto ='0200'
        OR dc.cod_concepto ='0300'
        OR dc.cod_concepto ='1000')
        AND dcem.id_empleador_maestro = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function listar_dcem_pdescuentos($id_empleador_maestro) {
        $query = "
        SELECT 
        dcem.id_detalle_concepto_empleador_maestro,
        dcem.id_empleador_maestro,
        dcem.cod_detalle_concepto

        FROM detalles_conceptos AS dc
        INNER JOIN detalles_conceptos_empleadores_maestros AS dcem
        ON dc.cod_detalle_concepto = dcem.cod_detalle_concepto

        WHERE (dc.cod_concepto ='0700')
        AND dcem.id_empleador_maestro = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function listar_dcem_ptributos_aportes($id_empleador_maestro) {

        $query = "
        SELECT 
        dcem.id_detalle_concepto_empleador_maestro,
        dcem.id_empleador_maestro,
        dcem.cod_detalle_concepto

        FROM detalles_conceptos AS dc
        INNER JOIN detalles_conceptos_empleadores_maestros AS dcem
        ON dc.cod_detalle_concepto = dcem.cod_detalle_concepto

        WHERE (dc.cod_concepto ='0600'
        OR dc.cod_concepto ='0800')
        AND dcem.id_empleador_maestro = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

//----------------------------------------------------------------------------//
//............................................................................//
//----------------------------------------------------------------------------//
// view-plame 27/08/2012

    public function view_listarConcepto($id_empleador_maestro, $cod_concepto, $seleccionado=1) {

        if (is_array($cod_concepto) && count($cod_concepto) >= 1) {
            $sql = " ";
            for ($i = 0; $i < count($cod_concepto); $i++) {
                $sql .= $cod_concepto[$i];
                if ($i == (count($cod_concepto)) - 1) {
                    //null                    
                } else {
                    $sql .= ",";
                }
            }
        }else{
            $sql = $cod_concepto;
        }
        
        //echo "sql   ".$sql;

        $query = "
        SELECT
          dce.id_detalle_concepto_empleador_maestro,
          -- dce.id_empleador_maestro,
          dce.cod_detalle_concepto,
          dce.seleccionado,
          dce.descripcion_1000,
          dc.descripcion,
          c.cod_concepto  
        FROM detalles_conceptos_empleadores_maestros AS dce
        INNER JOIN detalles_conceptos AS dc
        ON dce.cod_detalle_concepto = dc.cod_detalle_concepto
        INNER JOIN conceptos AS c
        ON dc.cod_concepto = c.cod_concepto

        WHERE (dce.id_empleador_maestro = ?)
        AND c.cod_concepto in ($sql)        
        AND dce.seleccionado = $seleccionado
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        //$stm->bindValue(2, '$cod_concepto');
        // $stm->bindValue(3, $seleccionado);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
       /* echo "<pre>$sql y estado $seleccionado  ";
        print_r($lista);
        echo "</pre>";
        */
        return $lista;
    }

}

?>
