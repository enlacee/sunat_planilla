<?php

class EpagoTrabajadorDao extends AbstractDao {

    //put your code here
    //put your code here
    public function registrar($id_trabajador, $id_etapa_pago) {

        $query = "
        INSERT INTO epagos_trabajadores
                    (
                     id_trabajador,
                     id_etapa_pago)
        VALUES (
                ?,
                ?);         
        ";
        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_etapa_pago);
        $stm->execute();
        // id 
        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        //finaliza transaccion
        //return true;
        return $lista[0]['id'];
        $stm = null;
    }

    public function eliminar($id_epago_trabajador) {

        $query = "
        DELETE
        FROM epagos_trabajadores
        WHERE id_epago_trabajador = ?;     
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_epago_trabajador);

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }
    
    
    
    
    public function listarTrabajadores(){
    
        
        
        
    }
    
    

}

?>
