<?php

class PtrabajadorDao extends AbstractDao {

    //put your code here


    public function registrar($objeto) {
        $model = new PTrabajador();
        $model = $objeto;

        $query = "
        INSERT INTO ptrabajadores
            (
             id_trabajador,
             asignacion_familiar,
             para_ti_familia,
             para_ti_familia_op,
             aporta_essalud_vida,
             aporta_asegura_tu_pension,
             domiciliado)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);
    ";
        try {
            $this->pdo->beginTransaction();

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_trabajador());
            $stm->bindValue(2, $model->getAsignacion_familiar());
            $stm->bindValue(3, $model->getPara_ti_familia());
            $stm->bindValue(4, $model->getPara_ti_familia_op());
            $stm->bindValue(5, $model->getAporta_essalud_vida());
            $stm->bindValue(6, $model->getAporta_asegura_tu_pension());
            $stm->bindValue(7, $model->getDomiciliado());

            $stm->execute();

            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();

            $this->pdo->commit();
            return $lista[0]['id'];

            //finaliza transaccion
        } catch (Exception $e) {

            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function actualizar($objeto) {
        $obj = new PTrabajador();
        $obj = $objeto;

        $query = "
        UPDATE ptrabajadores
        SET
          asignacion_familiar = ?,
          para_ti_familia = ?,
          para_ti_familia_op = ?,
          aporta_essalud_vida = ?,
          aporta_asegura_tu_pension = ?,
          domiciliado = ?
        WHERE id_ptrabajador = ?;
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $obj->getAsignacion_familiar());
        $stm->bindValue(2, $obj->getPara_ti_familia());
        $stm->bindValue(3, $obj->getPara_ti_familia_op());
        $stm->bindValue(4, $obj->getAporta_essalud_vida());
        $stm->bindValue(5, $obj->getAporta_asegura_tu_pension());
        $stm->bindValue(6, $obj->getDomiciliado());
        $stm->bindValue(7, $obj->getId_ptrabajador());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    public function buscar_ID($id_ptrabajador) {

        $query = "
        SELECT
         asignacion_familiar,
          para_ti_familia,
          para_ti_familia_op,
          id_ptrabajador,
          id_trabajador,
          aporta_essalud_vida,
          aporta_asegura_tu_pension,
          domiciliado
        FROM ptrabajadores
        WHERE id_ptrabajador = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_ptrabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0];
    }

    function exite_ID_TRABAJADOR($id_trabajador) {

        $query = "
        SELECT
          id_ptrabajador
        FROM ptrabajadores
        WHERE id_trabajador = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0]['id_ptrabajador'];
    }

}

?>
