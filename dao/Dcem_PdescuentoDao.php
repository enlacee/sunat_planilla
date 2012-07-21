<?php

class Dcem_PdescuentoDao extends AbstractDao {
//put your code here
    public function registrar($obj) {
        
        $model = new Dcem_Pdescuento();
        $model = $obj;

        $query = "
        INSERT INTO dcem_pdescuentos
                    (
                    id_ptrabajador,
                    id_detalle_concepto_empleador_maestro,
                    monto)
        VALUES (
                ?,
                ?,
                ?);
        ";
       
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(2, $model->getId_detalle_concepto_empleador_maestro());
        $stm->bindValue(3, $model->getMonto());

        $stm->execute();
        $stm = null;
        //$lista = $stm->fetchAll();
        return true;
    }

    public function actualizar($obj) {

        $model = new Dcem_Pdescuento();
        $model = $obj;

        $query = "
        UPDATE dcem_pdescuentos
        SET 
        monto = ?
        WHERE id_pdcem_pdescuento = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getMonto());
        $stm->bindValue(2, $model->getId_dcem_pdescuento());
        $stm->execute();
        $stm = null;
        //$lista = $stm->fetchAll();
        return true;
    }

}

?>
