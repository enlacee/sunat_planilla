<?php

class PpagoDao extends AbstractDao {

    //put your code here

    public function add($obj) {
        $model = new Ppago();
        $model = $obj;
        $query = "
        INSERT INTO ppagos
                    (
                     id_prestamo,
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
        $stm->bindValue(1, $model->getId_prestamo());
        $stm->bindValue(2, $model->getId_pdeclaracion());
        $stm->bindValue(3, $model->getValor());
        $stm->bindValue(4, $model->getFecha());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

// Elimina pagos segun el  mes!!
    public function delInnerPdeclaracion($id_pdeclaracion,$id_trabajador) {

        $query = "
        DELETE ppagos
        FROM prestamos
        INNER JOIN ppagos
        ON ppagos.id_prestamo = prestamos.id_prestamo

        WHERE ppagos.id_pdeclaracion=  ? 
        AND prestamos.id_trabajador = ?
    ";
//        DELETE prestamos
//        FROM ppagos
//        INNER JOIN prestamos
//        ON ppagos.id_prestamo = prestamos.id_prestamo
//        WHERE ppagos.id_pdeclaracion=  ? 
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        
        $stm->execute();
        $stm = null;
        return true;
    }
    

    /*
     * Sumtoria de todos los pagos....!!!!
     * del prestamo establecido.
     */
    public function sum_Id_Prestamo($id_prestamo) {
        $query = "
        SELECT
        SUM(valor) AS sum_valor
        FROM ppagos
        WHERE id_prestamo = ?            
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_prestamo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['sum_valor'];
    }

    public function del_idpdeclaracion($id_pdeclaracion) {
        $query = "
            DELETE
            FROM ppagos
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
