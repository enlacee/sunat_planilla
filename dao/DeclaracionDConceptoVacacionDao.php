<?php

class DeclaracionDConceptoVacacionDao extends AbstractDao {

    function add($obj) {

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

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador_vacacion());
        $stm->bindValue(2, $model->getCod_detalle_concepto());
        $stm->bindValue(3, $model->getMonto_devengado());
        $stm->bindValue(4, $model->getMonto_pagado());
        $stm->execute();
        $stm = null;
        return true;
    }

    function limpiar($id_trabajador_vacacion) {
        $query = "
        DELETE
        FROM declaraciones_dconceptos_vacaciones
        WHERE id_trabajador_vacacion = ?";
        try {
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_trabajador_vacacion);
            //$lista = $stm->fetchAll();
            $stm->execute();
            $stm = null;
            return true;
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            return false;
        }
    }

    // listar conceptos x vacacion
    public function listarTrabajadorPorDeclaracion($id_trabajador, $id_pdeclaracion) {//OK FUCK !! NOOO
        $query = "
        SELECT 
        ddcv.cod_detalle_concepto,
        ddcv.monto_pagado,
        ddcv.monto_devengado
        FROM trabajadores_vacaciones AS tv
        INNER JOIN declaraciones_dconceptos_vacaciones AS ddcv
        ON tv.id_trabajador_vacacion = ddcv.id_trabajador_vacacion
        WHERE tv.id_trabajador = ?
        AND tv.id_pdeclaracion = ?         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    // listar for reporte vacaciones (list conceptos)
    public function buscar_ID_TrabajadorVacacionPorConceptos($id_trabajador_vacacion) {
        $query = "
        SELECT
            ddcv.id_declaracion_dconcepto_vacacion,         
            ddcv.cod_detalle_concepto,            
            ddcv.monto_pagado,
            ddcv.monto_devengado,            
            dc.descripcion,
            dc.descripcion_abreviada
        FROM declaraciones_dconceptos_vacaciones AS ddcv
        INNER JOIN detalles_conceptos AS dc
        ON   ddcv.cod_detalle_concepto = dc.cod_detalle_concepto   
        WHERE ddcv.id_trabajador_vacacion = ?;      
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_vacacion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
