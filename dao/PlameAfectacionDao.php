<?php

class PlameAfectacionDao extends AbstractDao {
   
    
    public function listar() {
        $query = "
        SELECT *FROM afectaciones
        ";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
    
}

?>
