<?php

class PperiodoLaboralDao extends AbstractDao {

    //put your code here

    public function registrar($obj,$ID_PTRABAJADOR) {
        $model = new PperiodoLaboral();
        $model = $obj;

        $query = "
        INSERT INTO pperiodos_laborales
                    (
                    id_ptrabajador,
                    fecha_inicio,
                    fecha_fin)
        VALUES (
                ?,
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_PTRABAJADOR);
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getFecha_fin());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    public function buscarPorIdPtrabajador($id_ptrabajador) {

        $query = "
        SELECT
        id_pperiodo_laboral,
        id_ptrabajador,
        fecha_inicio,
        fecha_fin
        FROM pperiodos_laborales
        WHERE id_ptrabajador = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_ptrabajador);

        $stm->execute();        
        $lista = $stm->fetchAll();
        $stm = null;        
        return $lista;
    }

}

?>
