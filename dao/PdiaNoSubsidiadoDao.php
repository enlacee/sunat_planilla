<?php

class PdiaNoSubsidiadoDao extends AbstractDao {

    //put your code here
    public function registrar($obj) {

        $model = new PdiaNoSubsidiado();
        $model = $obj;
        $query = "
        INSERT INTO pdias_nosubsidiados
                    (
                    id_pago,
                    cantidad_dia,
                    cod_tipo_suspen_relacion_laboral)
        VALUES (
                ?,
                ?,
                ?);          
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_pago());
        $stm->bindValue(2, $model->getCantidad_dia());
        $stm->bindValue(3, $model->getCod_tipo_suspen_relacion_laboral());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {

        $model = new PdiaNoSubsidiado();
        $model = $obj;
        $query = "
        UPDATE pdias_nosubsidiados
        SET 
        cantidad_dia = ?,
        cod_tipo_suspen_relacion_laboral = ?
        WHERE id_pdia_nosubsidiado = ?;      
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getCantidad_dia());
        $stm->bindValue(2, $model->getCod_tipo_suspen_relacion_laboral());
        $stm->bindValue(3, $model->getId_pdia_nosubsidiado());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function eliminar($id_pdia_nosubsidiado) {

        $query = "
        DELETE
        FROM pdias_nosubsidiados
        WHERE id_pdia_nosubsidiado = ?;      
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdia_nosubsidiado);

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function buscar_IdPago($id_pago,$SUM=null) {

        $query1 = "
        SELECT *FROM pdias_nosubsidiados
        WHERE id_pago = ?         
        ";
        $query2 = "
        SELECT        
        SUM(cantidad_dia) AS cantidad_dia
        FROM pdias_nosubsidiados
        WHERE id_pago = ?          
        ";
        if ($SUM == "SUMA") {
            $query = $query2;
        } else {
            $query = $query1;
        }
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pago);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        if ($SUM == "SUMA") {
            return $lista[0]['cantidad_dia'];
        } else {
            return $lista;;
        }
        
    }

}

?>
