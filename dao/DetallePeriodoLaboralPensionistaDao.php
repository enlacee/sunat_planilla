<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleEstablecimientoVinculadoDao
 *
 * @author conta 1
 */
class DetallePeriodoLaboralPensionistaDao extends AbstractDao  {

    //put your code here

    public function registrar($obj) {
        $query = "
        INSERT INTO detalle_periodos_laborales_pensionistas
                    (
                    cod_motivo_baja_registro,
                    id_pensionista,
                    fecha_inicio,
                    fecha_fin)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";
//echo "<pre>";
//print_r($obj);
//echo "</pre>";
        $model = new DetallePeriodoLaboralPensionista();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getCod_motivo_baja_registro());
        $stm->bindValue(2, $model->getId_pensionista());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getFecha_fin());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    public function actualizar($obj) {
        $query = "            
        UPDATE detalle_periodos_laborales_pensionistas
        SET 
        cod_motivo_baja_registro = ?,
        id_pensionista = ?,
        fecha_inicio = ?,
        fecha_fin = ?
        WHERE id_detalle_periodo_laboral_pensionista = ?;       
        ";

        $model = new DetallePeriodoLaboralPensionista();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getCod_motivo_baja_registro());
        $stm->bindValue(2, $model->getId_pensionista());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getFecha_fin());
        $stm->bindValue(5, $model->getId_detalle_periodo_laboral_pensionista());
        $stm->execute();
        return true;
    }


    //------- buscador
    function buscarDetallePeriodoLaboralPensionista($id_PEN) {
        $query = "SELECT *FROM detalle_periodos_laborales_pensionistas WHERE id_pensionista = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_PEN);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0];
    }

}

?>
