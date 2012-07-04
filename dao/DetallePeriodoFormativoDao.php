<?php


class DetallePeriodoFormativoDao extends AbstractDao {

    //put your code here

    public function registrar($obj) {
        $query = "
        INSERT INTO detalle_periodos_formativos
                    (
                    id_personal_formacion_laboral,
                    fecha_inicio,
                    fecha_fin)
        VALUES (
                ?,
                ?,
                ?);
        ";

        $model = new DetallePeriodoFormativo();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_personal_formacion_laboral());
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getFecha_fin());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    public function actualizar($obj) {
        $query = "            
        UPDATE detalle_periodos_formativos
        SET 
        id_personal_formacion_laboral = ?,
        fecha_inicio = ?,
        fecha_fin = ?
        WHERE id_detalle_periodo_formativo = ?;       
        ";
        $model = new DetallePeriodoFormativo();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_personal_formacion_laboral());
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getFecha_fin());
        $stm->bindValue(4, $model->getId_detalle_periodo_formativo());
        $stm->execute();
        return true;
    }


/*
    public function listar($id_trabajador) {

        $query = "???????";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data;
    }
*/
    //------- buscador

    function buscarDetallePeriodoFormativo($id_PForm) {
        $query = "SELECT *FROM detalle_periodos_formativos WHERE id_personal_formacion_laboral = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_PForm);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0];
    }

}

?>
