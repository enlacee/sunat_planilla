<?php

class PrestamoCuotaDao extends AbstractDao {

    function add($obj) {
        //$model = new PrestamoCuota();
        $model = $obj;

        $query = "
        INSERT INTO prestamos_cuotas
                    (
                     id_prestamo,
                     monto,
                     fecha_calc,
                     fecha_pago,
                     estado
                     )
        VALUES (
                ?,
                ?,
                ?,                
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_prestamo());
        $stm->bindValue(2, $model->getMonto());
        $stm->bindValue(3, $model->getFecha_calc());
        $stm->bindValue(4, $model->getFecha_pago());
        $stm->bindValue(5, $model->getEstado());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }
    
    //Solo para Cambiar estado de Pago
    function pagarPrestamoCuota($obj){
        
        //$periodo = (is_null($periodo_pago)) ? null : $periodo_pago;
        $model = new PrestamoCuota();
        echo "\n\nEN pagarPrestamoCuota";
        echoo($obj);
        
        $model = $obj;
        $query = "
        UPDATE prestamos_cuotas
        SET          
          estado = ?,
          fecha_pago = ?,
          monto_pagado = ?
        WHERE id_prestamo_cutoa = ?;        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getEstado());
        $stm->bindValue(2, $model->getFecha_pago());
        $stm->bindValue(3, $model->getMonto_pagado());
        $stm->bindValue(4, $model->getId_prestamo_cutoa());
        $stm->execute();
        $stm = null;
        return true;
    }
    
    //Solo para Cambiar estado de Pago x Periodo
    //pagarPrestamoCuota_Periodo
    function bajaPrestamoCuota($periodo){
        $fecha_pago = null;
        $query = "
        UPDATE prestamos_cuotas
        SET          
          estado = ?,
          fecha_pago = ?
        WHERE fecha_calc = ?        
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, 0);
        $stm->bindValue(2, $fecha_pago);        
        $stm->bindValue(3, $periodo);
        $stm->execute();
        $stm = null;
        return true;
    }    
    
    
    function edit($obj) {
        //$model = new PrestamoCuota();
        $model = $obj;

        $query = "
        UPDATE prestamos_cuotas
        SET          
          monto_variable = ?,
          fecha_calc = ?
        WHERE id_prestamo_cutoa = ?;         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getMonto_variable());
        $stm->bindValue(2, $model->getFecha_calc());        
        $stm->bindValue(3, $model->getId_prestamo_cutoa());
        $stm->execute();
        $stm = null;
        //$lista = $stm->fetchAll();
        return true;
 
    }
    
    
    function buscar_idPadre($id){        
        $query = "
        SELECT
          id_prestamo_cutoa,  
          monto,
          monto_variable,
          monto_pagado,
          fecha_calc,
          fecha_pago,
          cubodin,
          estado
        FROM prestamos_cuotas
        WHERE id_prestamo = ?            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
        
    }
    
}

?>
