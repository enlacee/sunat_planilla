<?php

class PrestamoCuotaDao extends AbstractDao {

    public function add($obj) {
        $model = new PrestamoCuota();
        $model = $obj;
        $query = "
        INSERT INTO prestamos_cuotas
                    (
                     id_prestamo,
                     monto,
                     monto_variable,
                     fecha_calc,                     
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
        $stm->bindValue(3, $model->getMonto_variable());
        $stm->bindValue(4, $model->getFecha_calc());
        $stm->bindValue(5, $model->getEstado());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    public function update($obj) {
        $model = new PrestamoCuota();
        $model = $obj;
        $query = "
        UPDATE prestamos_cuotas
        SET   
          monto_variable = ?  
        WHERE id_prestamo_cutoa = ?         
           ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getMonto_variable());
        $stm->bindValue(2, $model->getId_prestamo_cutoa());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function buscar_idPadre($id) {
        $query = "
        SELECT
          id_prestamo_cutoa,  
          monto,
          monto_variable,
          fecha_calc,                   
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
