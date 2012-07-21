<?php

class Dcem_Ptributo_aporteDao  extends AbstractDao{
    //put your code here
    public function registrar($obj) {

        $model = new Dcem_Ptributo_aporte();
        $model = $obj;

        $query = "
        INSERT INTO dcem_ptributos_aportes
                    (
                    id_detalle_concepto_empleador_maestro,
                    id_ptrabajador,
                    monto)
        VALUES (
                ?,
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_detalle_concepto_empleador_maestro());
        $stm->bindValue(2, $model->getId_ptrabajador());
        $stm->bindValue(3, $model->getMonto());

        $stm->execute();
        //$lista = $stm->fetchAll();
        return true;
    }

    public function actualizar($obj) {

        $model = new Dcem_Ptributo_aporte();
        $model = $obj;

        $query = "
        UPDATE dcem_ptributos_aportes
            SET 
            monto = ?
        WHERE id_dcem_ptributo_aporte = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getMonto());
        $stm->bindValue(2, $model->getId_dcem_ptributo_aporte());
        
        $stm->execute();
        //$lista = $stm->fetchAll();
        return true;
    }
}

?>
