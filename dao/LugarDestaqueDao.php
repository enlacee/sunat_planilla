<?php


class LugarDestaqueDao  extends AbstractDao{
    //put your code here
    
        public function registrar($obj){
        $query = "
        INSERT INTO lugares_destaques
                    (
                     id_personal_tercero,
                     id_establecimiento)
        VALUES (
                ?,
                ?);           
        ";
        
        $em = new LugarDestaque();
        $em = $obj;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getId_personal_tercero());
        $stm->bindValue(2, $em->getId_establecimiento());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;

    }
    
    
    public function actualizar($obj){
        $query = "
        UPDATE lugares_destaques
        SET 
          id_personal_tercero = ?,
          id_establecimiento = ?
        WHERE id_lugar_destaque = ?;      
        ";
        
        $em = new LugarDestaque();
        $em = $obj;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getId_personal_tercero());
        $stm->bindValue(2, $em->getId_establecimiento());
        $stm->bindValue(3, $em->getId_lugar_destaque());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;        
    }
    
    //buscar
   public function buscarLugarDestaque($id_PERSONA_TERCERO){
        $query = "
        SELECT
          id_lugar_destaque,
          id_personal_tercero,
          id_establecimiento
        FROM lugares_destaques

        WHERE id_personal_tercero = ?        
        ";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_PERSONA_TERCERO);
        $stm->execute();
        $data = $stm->fetchAll();
        $stm = null;
        return $data[0];     
    }
    

    
    
}

?>
