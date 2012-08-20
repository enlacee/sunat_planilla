<?php
class DeclaracionDconceptoDao  extends AbstractDao{
    //put your code here
    
    
    public function registrar($obj) {
        $model = new DeclaracionDconcepto();
        $model = $obj;
        $query = "
INSERT INTO declaraciones_dconceptos
            (
             id_trabajador_pdeclaracion,
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
        $stm->bindValue(1, $model->getId_trabajador_pdeclaracion());
        $stm->bindValue(2, $model->getCod_detalle_concepto());
        $stm->bindValue(3, $model->getMonto_devengado());
        $stm->bindValue(4, $model->getMonto_pagado());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {

        $model = new DeclaracionDconcepto();
        $model = $obj;
        $query = "
        UPDATE declaraciones_dconceptos
        SET 
          id_trabajador_pdeclaracion = ?,
          cod_detalle_concepto = ?,
          monto_devengado = ?,
          monto_pagado = ?
        WHERE id_declaracion_dconcepto = ?;          
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador_pdeclaracion());
        $stm->bindValue(2, $model->getCod_detalle_concepto());
        $stm->bindValue(3, $model->getMonto_devengado());
        $stm->bindValue(4, $model->getMonto_pagado());
        $stm->bindValue(5, $model->getId_declaracion_dconcepto());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

 /*   public function eliminar($id_pdia_subsidiado) {

        $query = "      
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdia_subsidiado);

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }*/

}

?>
