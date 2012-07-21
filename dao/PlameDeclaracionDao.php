<?php

class PlameDeclaracionDao extends AbstractDao {

    public function registrar($id_empleador_maestro, $periodo) {
        $query = "
        INSERT INTO pdeclaraciones
                    (
                    id_empleador_maestro,
                    periodo)
        VALUES (
                ?,
                ?);
        ";
        $this->pdo->beginTransaction();
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $periodo);
        $stm->execute();

        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        $stm = null;



        return $lista[0]['id'];
        //$lista = $stm->fetchAll();
    }

    /**
     *
     * @param type $id_empleador_maestro
     * @param type $periodo
     * @return type 
     * Pregunta si existe periodo registrado.
     */
    public function existeDeclaracion($id_empleador_maestro, $periodo) {

        $query = "
        SELECT
            COUNT(*) as nunfilas
        FROM pdeclaraciones
        WHERE (id_empleador_maestro = ? AND periodo = '$periodo' )
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        //$stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['nunfilas'];
    }

}

?>
