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
    
    
    
    
}

?>
