<?php

class PrestamoCuotaDao extends AbstractDao {

    function add($obj) {
        $model = new PrestamoCuota();
        $model = $obj;

        $query = "
        INSERT INTO prestamos_cuotas
                    (
                     id_prestamo,
                     monto,
                     fecha_calc,
                     fecha_pago,
                     estado,
                     descripcion)
        VALUES (
                ?,
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
        $stm->bindValue(4,$model->getFecha_pago());
        $stm->bindValue(5,$model->getEstado());
        $stm->bindValue(6,$model->getDescripcion());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    function edit() {
        $model = new PrestamoCuota();
        $model = $obj;

        $query = "
        UPDATE prestamos_cuotas
        SET
          monto = ?,
          fecha_calc = ?,
          fecha_pago = ?,
          estado = ?,
          descripcion = ?
        WHERE id_prestamo_cutoa = ?;         
        ";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(1, $model->getMonto());
        $stm->bindValue(2, $model->getFecha_calc());
        $stm->bindValue(3, $model->getFecha_pago());
        $stm->bindValue(4, $model->getEstado());
        $stm->bindValue(5, $model->getDescripcion());
        $stm->bindValue(6, $model->getId_prestamo_cutoa());
        $stm->execute();
        $stm = null;
        //$lista = $stm->fetchAll();
        return true;
 
    }

}

?>
