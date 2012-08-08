<?php

class PeriodoRemuneracionDao extends AbstractDao {

    // public function registrar($obj) {
    // }

    public function actualizar($obj) {

        $model = new PeriodoRemuneracion();
        $model = $obj;
        $query = "
        UPDATE periodos_remuneraciones
        SET         
        tasa_pago = ?
        WHERE cod_periodo_remuneracion = ?;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getTasa_pago());
        $stm->bindValue(2, $model->getCod_periodo_remuneracion());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }


    public function listar($WHERE=null) {
        $query = "
        SELECT
        cod_periodo_remuneracion,
        descripcion,
        tasa_pago,
        estado
        FROM periodos_remuneraciones
        WHERE estado = 1
        $WHERE
            ";
        
        
        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $id_pjoranada_laboral);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
