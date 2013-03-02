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
    
   
    function buscar_idPadre($id){        
        $query = "
        SELECT
          id_prestamo_cutoa,  
          monto,                  
          fecha_calc,
          fecha_pago,          
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
