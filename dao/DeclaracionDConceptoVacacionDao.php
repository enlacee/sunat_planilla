<?php

class DeclaracionDConceptoVacacionDao extends AbstractDao{
   
    
    function add($obj){
        
        $model = new DeclaracionDConceptoVacacion();
        $model = $obj;
        $query = "
        INSERT INTO declaraciones_dconceptos_vacaciones
                    (
                     id_trabajador_vacacion,
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
           // echoo($model);
            
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_trabajador_vacacion());
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
   
    // listar conceptos x vacacion
    public function listarTrabajadorPorDeclaracion($id_trabajador,$id_pdeclaracion) {//OK FUCK !! NOOO
        $query = "
        SELECT 
        ddcv.cod_detalle_concepto,
        ddcv.monto_pagado,
        ddcv.monto_devengado
        FROM trabajadores_vacaciones AS tv
        INNER JOIN declaraciones_dconceptos_vacaciones AS ddcv
        ON tv.id_trabajador_vacacion = ddcv.id_trabajador_vacacion
        WHERE tv.id_trabajador = ?
        AND tv.id_pdeclaracion = ?         
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
