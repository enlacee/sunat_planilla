<?php

class Dcem_PingresoDao extends AbstractDao {

    //put your code here
    public function registrar($obj) {

        $model = new Dcem_Pingreso();
        $model = $obj;

        $query = "
        INSERT INTO dcem_pingresos
                    (
                    id_ptrabajador,
                    id_detalle_concepto_empleador_maestro,
                    devengado,
                    pagado)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(2, $model->getId_detalle_concepto_empleador_maestro());
        $stm->bindValue(3, $model->getDevengado());
        $stm->bindValue(4, $model->getPagado());


        $stm->execute();
        //$lista = $stm->fetchAll();
        return true;
        
    }

    public function actualizar($obj) {
        
        $model = new Dcem_Pingreso();
        $model = $obj;
        
        $query = "
        UPDATE dcem_pingresos
        SET 
        devengado = ?,
        pagado = ?
        WHERE id_dcem_pingreso = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getDevengado());
        $stm->bindValue(2, $model->getPagado());
        $stm->bindValue(3, $model->getId_dcem_pingreso());

        $stm->execute();
        //$lista = $stm->fetchAll();
        return true;
    }

    //view
    public function listar_ID_Ptrabajador($id_ptrabajador, $seleccionado){
        $query = "
        SELECT 
        dcem_pi.id_dcem_pingreso,
        dcem_pi.id_ptrabajador,
        dcem_pi.devengado,
        dcem_pi.pagado,

        -- detalle concepto empleador maestro
        dcem.id_detalle_concepto_empleador_maestro,
        dcem.cod_detalle_concepto,
        dcem.seleccionado

        FROM dcem_pingresos AS dcem_pi
        INNER JOIN detalles_conceptos_empleadores_maestros AS dcem
        ON dcem_pi.id_detalle_concepto_empleador_maestro = dcem.id_detalle_concepto_empleador_maestro

        WHERE (dcem_pi.id_ptrabajador = ? AND dcem.seleccionado = ?)
        
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
