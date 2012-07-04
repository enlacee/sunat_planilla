<?php


class CoberturaSaludDao extends AbstractDao {
    //put your code here

     public function registrar($obj){
        $query = "
        INSERT INTO coberturas_salud
                    (
                     id_personal_tercero,
                     nombre_cobertura,
                     fecha_inicio,
                     fecha_fin)
        VALUES (
                ?,
                ?,
                ?,
                ?);           
        ";
        
        $em = new CoberturaSalud();
        $em = $obj;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getId_personal_tercero());
        $stm->bindValue(2, $em->getNombre_cobertura());
        $stm->bindValue(3, $em->getFecha_inicio());
        $stm->bindValue(4, $em->getFecha_fin());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;

    }
    
    
    public function actualizar($obj){
        $query = "
        UPDATE coberturas_salud
        SET 
          
          nombre_cobertura = ?,
          fecha_inicio = ?,
          fecha_fin = ?
        WHERE id_cobertura_salud = ?;      
        ";
        
        $em = new CoberturaSalud();
        $em = $obj;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getNombre_cobertura());
        $stm->bindValue(2, $em->getFecha_inicio());
        $stm->bindValue(3, $em->getFecha_fin());
        $stm->bindValue(4, $em->getId_cobertura_salud());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;        
    }
    
    //buscar
   public function buscarCoberturaSalud($ID_PERSONAL_TERCERO){
        $query = "
        SELECT
          id_cobertura_salud,
          id_personal_tercero,
          nombre_cobertura,
          fecha_inicio,
          fecha_fin
        FROM coberturas_salud
        WHERE id_personal_tercero = ?        
        ";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_PERSONAL_TERCERO);
        $stm->execute();
        $data = $stm->fetchAll();
        $stm = null;
        return $data[0];     
    }
    
    
    
}



?>
