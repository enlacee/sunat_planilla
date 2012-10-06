<?php

class PtfPagoDao extends AbstractDao {

    public function add($obj) {
        $model = new PtfPago();
        $model = $obj;
        $query = "
        INSERT INTO ptf_pagos
                    (
                    id_para_ti_familia,
                    id_pdeclaracion,
                    valor,
                    fecha)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_para_ti_familia());
        $stm->bindValue(2, $model->getId_pdeclaracion());
        $stm->bindValue(3, $model->getValor());
        $stm->bindValue(4, $model->getFecha());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function del_idpdeclaracion($id_pdeclaracion){
        $query ="
        DELETE
        FROM ptf_pagos
        WHERE id_pdeclaracion = ?     
        ";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        
        $stm = null;
        return true;
    }
    
    
    
}

?>
