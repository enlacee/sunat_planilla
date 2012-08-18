<?php

class PeriodoPagoDao extends AbstractDao {

    public function buscar_ID($id) {

        $query = "
        SELECT
        id_periodo_pago,
        valor,
        dia
        FROM periodos_pagos WHERE id_periodo_pago =?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

?>
