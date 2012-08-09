<?php

class AdelantoDao extends AbstractDao {

    public function registrar($obj) {
        $model = new Adelanto();
        $model = $obj;
        $query = "
        INSERT INTO adelantos
                    (
                    id_trabajador,
                    cod_periodo_remuneracion,
                    dia_total,
                    dia_nolaborado,
                    valor,
                    fecha_inicio,
                    fecha_fin,
                    fecha_creacion)
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
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getCod_periodo_remuneracion());
        $stm->bindValue(3, $model->getDia_total());
        $stm->bindValue(4, $model->getDia_nolaborado());
        $stm->bindValue(5, $model->getValor());
        $stm->bindValue(6, $model->getFecha_inicio());
        $stm->bindValue(7, $model->getFecha_fin());
        $stm->bindValue(8, $model->getFecha_creacion());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($id_adelanto, $dia_nolaborado) {

        $model = new Adelanto();
        $model = $obj;
        $query = "
        UPDATE adelantos
        SET 
        dia_nolaborado = ?
        WHERE id_adelanto = ?;        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $dia_nolaborado);
        $stm->bindValue(2, $id_adelanto);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function listar() {
        $query = "";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pjoranada_laboral);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function buscar_IDtrabajador($id) {
        $query = "
        SELECT
            id_adelanto,
            id_trabajador,
            cod_periodo_remuneracion,
            dia_total,
            dia_laborado_FUCK,
            dia_nolaborado,
            valor,
            fecha_inicio,
            fecha_fin,
            fecha_creacion
        FROM adelantos
        WHERE id_trabajador = ?
     ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
