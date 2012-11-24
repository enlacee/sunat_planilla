<?php

class ConfDao extends AbstractDao {
    
    public function conf_valor_SB() {
        $query = "
        SELECT 
        fecha,
        valor,
        fecha_creacion FROM conf_sueldo_basico 
        ORDER BY fecha DESC
        LIMIT 1
        ";
        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $valor);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['valor'];
    }

    public function conf_tasa_onp() {
        $query = "
        SELECT fecha,tasa,fecha_creacion FROM conf_onp 
        ORDER BY fecha DESC
        LIMIT 1
        ";
        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $valor);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['tasa'];
    }

    public function conf_tasa_uit() {
        $query = "
        SELECT fecha,valor,fecha_creacion FROM conf_uit 
        ORDER BY fecha DESC
        LIMIT 1
        ";
        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $valor);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['valor'];
    }

    public function conf_tasa_aFamiliar() {
        $query = "
        SELECT fecha,tasa,fecha_creacion FROM conf_asignacion_familiar 
        ORDER BY fecha DESC
        LIMIT 1
        ";
        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $valor);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['tasa'];
    }
    
    
       public function conf_tasa_essalud() {
        $query = "
        SELECT fecha,tasa,fecha_creacion FROM conf_essalud 
        ORDER BY fecha DESC
        LIMIT 1
        ";
        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $valor);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['tasa'];
    } 

}

?>
