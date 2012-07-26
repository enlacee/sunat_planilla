<?php

class Dcem_PtributoAporteDao  extends AbstractDao{
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

    //view
    public function listar_ID_Ptrabajador($id_ptrabajador, $seleccionado){
        $query = "
        SELECT 
        dcem_pta.id_dcem_ptributo_aporte,
        dcem_pta.id_ptrabajador,
        dcem_pta.monto,

        -- detalle concepto empleador maestro
        dcem.id_detalle_concepto_empleador_maestro,
        dcem.cod_detalle_concepto,
        dcem.seleccionado,

        -- detalle descripcion
        dc.cod_concepto,
        dc.descripcion

        FROM dcem_ptributos_aportes AS dcem_pta
        INNER JOIN detalles_conceptos_empleadores_maestros AS dcem
        ON dcem_pta.id_detalle_concepto_empleador_maestro = dcem.id_detalle_concepto_empleador_maestro

        INNER JOIN detalles_conceptos AS dc
        ON dcem.cod_detalle_concepto = dc.cod_detalle_concepto

        WHERE (dcem_pta.id_ptrabajador = ? AND dcem.seleccionado = ?)
        
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
