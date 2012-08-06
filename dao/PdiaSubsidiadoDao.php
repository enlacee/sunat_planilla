<?php

class PdiaSubsidiadoDao extends AbstractDao {

//put your code here

    public function registrar($obj) {
        $model = new PdiaSubsidiado();
        $model = $obj;
        $query = "
        INSERT INTO pdias_subsidiados
                    (
                     id_pjornada_laboral,
                     cantidad_dia,
                     cod_tipo_suspen_relacion_laboral)
        VALUES (
                ?,
                ?,
                ?);            
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_pjornada_laboral());
        $stm->bindValue(2, $model->getCantidad_dia());
        $stm->bindValue(3, $model->getCod_tipo_suspen_relacion_laboral());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {

        $model = new PdiaSubsidiado();
        $model = $obj;
        $query = "
        UPDATE pdias_subsidiados
        SET         
          cantidad_dia = ?,
          cod_tipo_suspen_relacion_laboral = ?
        WHERE id_pdia_subsidiado = ?;           
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getCantidad_dia());
        $stm->bindValue(2, $model->getCod_tipo_suspen_relacion_laboral());
        $stm->bindValue(3, $model->getId_pdia_subsidiado());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function eliminar($id_pdia_subsidiado) {

        $query = "
        DELETE
        FROM pdias_subsidiados
        WHERE id_pdia_subsidiado = ?;       
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdia_subsidiado);

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function busacar_IdPjorandaLaboral($id_pjoranada_laboral) {

        $query = "
        SELECT *FROM pdias_subsidiados
        WHERE id_pjornada_laboral = ?         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pjoranada_laboral);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
