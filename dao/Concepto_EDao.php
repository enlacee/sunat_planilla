<?php

class Concepto_EDao extends AbstractDao {

    public function listar() {
        $query = "
            SELECT *FROM conceptos_e 
            ";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function buscar_ID($id) {
        $query = "
        SELECT
          id_concepto_e,
          descripcion
        FROM conceptos_e 
        WHERE id_concepto_e = ?;
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
