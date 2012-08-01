<?php

class PperiodoLaboralDao extends AbstractDao{
    //put your code here
 
        public function registrar($obj) {
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
        $stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getFecha_fin());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }
    
    
}

?>
