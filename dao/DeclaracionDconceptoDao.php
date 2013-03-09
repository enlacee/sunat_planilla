<?php

class DeclaracionDconceptoDao extends AbstractDao {

    public function registrar($obj) {
        $model = new DeclaracionDconcepto();
        $model = $obj;
        $query = "
        INSERT INTO declaraciones_dconceptos
                    (
                    id_trabajador_pdeclaracion,
                    cod_detalle_concepto,
                    monto_devengado,
                    monto_pagado)
        VALUES (
                ?,
                ?,
                ?,
                ?);     
        ";
        try {

            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_trabajador_pdeclaracion());
            $stm->bindValue(2, $model->getCod_detalle_concepto());
            $stm->bindValue(3, $model->getMonto_devengado());
            $stm->bindValue(4, $model->getMonto_pagado());

            $stm->execute();
            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();

            $this->pdo->commit();
            $stm = null;
            return $lista[0]['id'];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function actualizar($obj) {

        $model = new DeclaracionDconcepto();
        $model = $obj;
        $query = "
        UPDATE declaraciones_dconceptos
        SET 
          id_trabajador_pdeclaracion = ?,
          cod_detalle_concepto = ?,
          monto_devengado = ?,
          monto_pagado = ?
        WHERE id_declaracion_dconcepto = ?;          
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador_pdeclaracion());
        $stm->bindValue(2, $model->getCod_detalle_concepto());
        $stm->bindValue(3, $model->getMonto_devengado());
        $stm->bindValue(4, $model->getMonto_pagado());
        $stm->bindValue(5, $model->getId_declaracion_dconcepto());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }


    public function limpiar($id_trabajador_pdeclaracion){
        
        $query = "
        DELETE
        FROM declaraciones_dconceptos
        WHERE id_trabajador_pdeclaracion = ?        
        ";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }


    /*
     * Lista todos los conceptos del trabajador Pagados o Emitidos
     * Nuevo ID del trabajador en el PLAME.
     * id_trabajador = id_trabajador_pdeclaracion 20/08/2012
     */

    public function buscar_ID_TrabajadorPdeclaracion($id_trabajador_pdeclaracion) {
        // id_trabajador_pdeclaracion

        $query = "
        SELECT
            id_declaracion_dconcepto,
            id_trabajador_pdeclaracion,
            cod_detalle_concepto,
            monto_devengado,
            monto_pagado
        FROM declaraciones_dconceptos        
        WHERE id_trabajador_pdeclaracion = ?            
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    //uSADO en reporte tabla txt.
 public function buscar_ID_TrabajadorPdeclaracion_2($id_trabajador_pdeclaracion) {
        $query = "
        SELECT
            ddc.id_declaracion_dconcepto,            
            ddc.cod_detalle_concepto,            
            ddc.monto_pagado,
            -- detalle
            dc.descripcion,
            dc.descripcion_abreviada
        FROM declaraciones_dconceptos AS ddc
        INNER JOIN detalles_conceptos AS dc
        ON   ddc.cod_detalle_concepto = dc.cod_detalle_concepto   
        WHERE id_trabajador_pdeclaracion = ?;        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    //uSADO en reporte empresa  txt.
 public function buscar_ID_TrabajadorPdeclaracion_3($id_trabajador_pdeclaracion) {
        // id_trabajador_pdeclaracion

        $query = "
        SELECT
            ddc.id_declaracion_dconcepto,            
            ddc.cod_detalle_concepto,            
            ddc.monto_pagado,
            -- detalle
            dc.descripcion            
        FROM declaraciones_dconceptos AS ddc

        INNER JOIN detalles_conceptos AS dc
        ON   ddc.cod_detalle_concepto = dc.cod_detalle_concepto   
        WHERE id_trabajador_pdeclaracion = ?          
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }    

    //---------------------------------------------------------------
    // Funcion utilizada x Renta 5ta Categoria - controller u ONP U ESSALUD ++
    //10/09/2012
    //---------------------------------------------------------------

    public function listarTrabajadorPorDeclaracion($id_trabajador,$id_pdeclaracion) {//OK FUCK !! NOOO
        $query = "
        SELECT 
        -- tpd.id_pdeclaracion,
        -- tpd.id_trabajador,
        ddc.cod_detalle_concepto,
        ddc.monto_pagado
        FROM trabajadores_pdeclaraciones AS tpd
        INNER JOIN declaraciones_dconceptos AS ddc
        ON tpd.id_trabajador_pdeclaracion = ddc.id_trabajador_pdeclaracion
        WHERE tpd.id_trabajador = ?
        AND tpd.id_pdeclaracion = ?          
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

      
}

?>
