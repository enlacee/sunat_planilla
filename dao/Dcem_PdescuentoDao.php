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

    //view
    public function listar_ID_Ptrabajador($id_ptrabajador, $seleccionado) {
        $query = "
        SELECT 
        dcem_pd.id_pdcem_pdescuento,
        dcem_pd.id_ptrabajador,
        dcem_pd.monto,
        -- detalle concepto empleador maestro
        dcem.cod_detalle_concepto,
        dcem.seleccionado,

        -- detalle descripcion
        dc.descripcion


        FROM dcem_pdescuentos AS dcem_pd
        INNER JOIN detalles_conceptos_empleadores_maestros AS dcem
        ON dcem_pd.id_detalle_concepto_empleador_maestro = dcem.id_detalle_concepto_empleador_maestro

        INNER JOIN detalles_conceptos AS dc
        ON dcem.cod_detalle_concepto = dc.cod_detalle_concepto

        WHERE (dcem_pd.id_ptrabajador = ? AND dcem.seleccionado =?)
        
         ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_ptrabajador);
        $stm->bindValue(2, $seleccionado);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

}

?>
