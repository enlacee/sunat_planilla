<?php

class PeriodoDestaqueDao extends AbstractDao {

    //put your code here
    //Registrar Producto and Imagen
    public function registrar($obj) {
//        fuck
        $query = "
            INSERT INTO periodos_destaques
                        (
                         id_personal_tercero,
                         fecha_inicio,
                         fecha_fin)
            VALUES (
                    ?,
                    ?,
                    ?); ";

        //Inicia transaccion
        $em = new PeriodoDestaque();
        $em = $obj;
        //echo "<pre>";
        //print_r($em);
        //echo "</pre>";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getId_personal_tercero());
        $stm->bindValue(2, $em->getFecha_inicio());
        $stm->bindValue(3, $em->getFecha_fin());
        $stm->execute();

        $stm = null;
        return true;
    }

    public function actualizar($obj) {
        $query = "
        UPDATE periodos_destaques
        SET 
          id_personal_tercero = ?,
          fecha_inicio = ?,
          fecha_fin = ?
        WHERE id_periodo_destaque = ?;      
        ";

        $em = new PeriodoDestaque();
        $em = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getId_personal_tercero());
        $stm->bindValue(2, $em->getFecha_inicio());
        $stm->bindValue(3, $em->getFecha_fin());
        $stm->bindValue(4, $em->getId_periodo_destaque());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

        //buscar
   public function buscarPeriodoDestaque($ID_PERSONAL_TERCERO){
        $query = "
        SELECT
          id_periodo_destaque,
          id_personal_tercero,
          fecha_inicio,
          fecha_fin
        FROM periodos_destaques
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
