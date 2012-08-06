<?php

class PjoranadaLaboralDao extends AbstractDao {

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
                     dia_nosubsidiado,
                     dia_total,
                     hora_ordinaria_hh,
                     hora_ordinaria_mm,
                     hora_sobretiempo_hh,
                     hora_sobretiempo_mm
                    )
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
                );
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(2, $model->getDia_laborado());
        $stm->bindValue(3, $model->getDia_subsidiado());
        $stm->bindValue(4, $model->getDia_nosubsidiado());
        $stm->bindValue(5, $model->getDia_total());
        $stm->bindValue(6, $model->getHora_ordinaria_hh());
        $stm->bindValue(7, $model->getHora_ordinaria_mm());
        $stm->bindValue(8, $model->getHora_sobretiempo_hh());
        $stm->bindValue(9, $model->getHora_sobretiempo_mm());
        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    public function actualizar($obj) {
        $model = new PjornadaLaboral();
        $model = $obj;

        $query = "
        UPDATE pjornadas_laborales
        SET           
          dia_laborado = ?,
          dia_subsidiado = ?,
          dia_nosubsidiado = ?,
          hora_ordinaria_hh = ?,
          hora_ordinaria_mm = ?,
          hora_sobretiempo_hh = ?,
          hora_sobretiempo_mm = ?,
          total_hora_hh = ?,
          total_hora_mm = ?
        WHERE id_pjornada_laboral = ?;          
        ";


        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(1, $model->getDia_laborado());
        $stm->bindValue(2, $model->getDia_subsidiado());
        $stm->bindValue(3, $model->getDia_nosubsidiado());
        $stm->bindValue(4, $model->getHora_ordinaria_hh());
        $stm->bindValue(5, $model->getHora_ordinaria_mm());
        $stm->bindValue(6, $model->getHora_sobretiempo_hh());
        $stm->bindValue(7, $model->getHora_sobretiempo_mm());
        $stm->bindValue(8, $model->getTotal_hora_hh());
        $stm->bindValue(9, $model->getTotal_hora_mm());
        $stm->bindValue(10, $model->getId_pjornada_laboral());

        $stm->execute();
        $stm = null;
        //$lista = $stm->fetchAll();
        return true;
    }

    public function buscar_ID($id_pjoranada_laboral) {

        $query = "
            SELECT *FROM pjornadas_laborales
            WHERE id_pjoranada_laboral = ? 
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pjoranada_laboral);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function buscar_ID_ptrabajador($id_ptrabajador) {

        $query = "
            SELECT *FROM pjornadas_laborales
            WHERE id_ptrabajador = ? ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_ptrabajador);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

?>
