<?php

class PlameConceptoDao extends AbstractDao{

    //put your code here
    public function cantidad() {
        $query = "
            SELECT COUNT(*) AS numfilas
            FROM conceptos ";

        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista[0]["numfilas"];
        $stm = null;
    }

    public function listar( $start, $limit, $sidx, $sord) {

        $query = "
        SELECT *FROM conceptos        
        ORDER BY  $sidx $sord LIMIT $start,  $limit
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }
    
    
    //public function listarSector
    
    
    // No utilizado PERO Podria LLegar a Usarlo
    public function buscarID($cod_concepto){        
        $query = "
        SELECT *FROM conceptos    
        WHERE cod_concepto = ?        
        ";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$cod_concepto);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }
    
    

}

?>
