<?php

class EmpresaCentroCostoDao extends AbstractDao {
    // NO UTILIZADO
    public function registrar($descripcion) {

        $query = "
        INSERT INTO empresa_centro_costo
                    (descripcion)
        VALUES (?); 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $descripcion);
        $stm->execute();
        $stm = null;
        return true;
    }
    
    // NO UTILIZADO
    public function actualizar($id,$descripcion){
        
        $query = "
        UPDATE empresa_centro_costo
        SET   descripcion = ?
        WHERE id_empresa_centro_costo = ?;     
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $descripcion);
        $stm->bindValue(2, $id);
        $stm->execute();
        $stm = null;
        return true;
        
    }
    //OK   
    public function listar(){
        
        $query ="SELECT *FROM empresa_centro_costo";        
        $stm= $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
        
    }
}

?>
