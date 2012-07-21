<?php

class PjoranadaLaboralDao extends AbstractDao{

    //put your code here

    public function registrar($obj) {
        $model = new PjornadaLaboral();
        $model = $obj;

        $query = "
        INSERT INTO pjornadas_laborales
                    (
                    id_ptrabajador,
                    dia_laborado,
                    dia_subsidiado,
                    dia_nolaborado_nosubsidiado,
                    hora_ordinaria_hh,
                    hora_ordinaria_mm,
                    hora_sobretiempo_hh,
                    hora_sobretiempo_mm)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(2, $model->getDia_laborado());
        $stm->bindValue(3, $model->getDia_subsidiado());
        $stm->bindValue(4, $model->getDia_nolaborado_nosubsidiado());
        $stm->bindValue(5, $model->getHora_ordinaria_hh());
        $stm->bindValue(6, $model->getHora_ordinaria_mm());
        $stm->bindValue(7, $model->getHora_sobretiempo_hh());
        $stm->bindValue(8, $model->getHora_sobretiempo_mm());

        $stm->execute();
        // $lista = $stm->fetchAll();
        return true;
    }

    public function actualizar($obj) {
        $model = new PjornadaLaboral();
        $model = $obj;
        
        $query = "
        UPDATE pjornadas_laborales
        SET 
        id_ptrabajador = ?,
        dia_laborado = ?,
        dia_subsidiado = ?,
        dia_nolaborado_nosubsidiado = ?,
        hora_ordinaria_hh = ?,
        hora_ordinaria_mm = ?,
        hora_sobretiempo_hh = ?,
        hora_sobretiempo_mm = ?
        WHERE id_pjornada_laboral = ?;            
        ";


        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(2, $model->getDia_laborado());
        $stm->bindValue(3, $model->getDia_subsidiado());
        $stm->bindValue(4, $model->getDia_nolaborado_nosubsidiado());
        $stm->bindValue(5, $model->getHora_ordinaria_hh());
        $stm->bindValue(6, $model->getHora_ordinaria_mm());
        $stm->bindValue(7, $model->getHora_sobretiempo_hh());
        $stm->bindValue(8, $model->getHora_sobretiempo_mm());
        $stm->bindValue(9, $model->getId_pjornada_laboral());
        
        $stm->execute();
        //$lista = $stm->fetchAll();
        return true;
    }

}

?>
