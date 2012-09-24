<?php

class ConfUitDao extends AbstractDao {

    //put your code here
    public function registrar($valor, $fecha_vigencia) {
        $query = "
        INSERT INTO conf_uit
                    (
                    valor,
                    fecha
                    )
        VALUES (
                ?,
                ?);        
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $fecha_vigencia);
        //$stm->bindValue(3, date(""));
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($id, $valor, $fecha_vigencia) {

        $query = "
        UPDATE conf_uit
        SET 
            valor = ?,
            fecha = ?            
        WHERE id_conf_uit = ?;            
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $fecha_vigencia);
        $stm->bindValue(3, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function eliminar($id_conf_uit) {
        $query = "
        DELETE
        FROM conf_uit
        WHERE id_conf_uit = ?;       
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_conf_uit);
        $stm->execute();
        return true;
    }

    public function listar() {

        $query = "
        SELECT
        id_conf_uit,
        valor,
        fecha,
        fecha_creacion
        FROM conf_uit           
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    
        public function vigenteAux($periodo){
       $query = "
        SELECT
        id_conf_uit,
        valor,
        fecha        
        FROM conf_uit
        WHERE fecha <='$periodo'
        ORDER BY fecha DESC       
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['valor']; 
        
    }
    
    
    
}

?>
