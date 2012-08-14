<?php

class JornadaLaboralDao extends AbstractDao {
    //put your code here
    
      public function registrar($obj) {
        $model = new JornadaLaboral();
        $model = $obj;
        $query = "
        INSERT jornadas_laborales
                    (
                    id_adelanto,
                    dia_total,
                    dia_nosubsidiado,
                    ordinario_hora,
                    ordinario_min,
                    sobretiempo_hora,
                    sobretiempo_min)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_adelanto());
        $stm->bindValue(2, $model->getDia_total());
        $stm->bindValue(3, $model->getDia_nosubsidiado());        
        $stm->bindValue(4, $model->getOrdinario_hora());
        $stm->bindValue(5, $model->getOrdinario_min());
        $stm->bindValue(6, $model->getSobretiempo_hora());
        $stm->bindValue(7, $model->getSobretiempo_min());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {

        $model = new JornadaLaboral();
        $model = $obj;
        $query = "
        UPDATE jornadas_laborales
        SET  
          dia_nosubsidiado = ?,
          ordinario_hora = ?,
          ordinario_min = ?,
          sobretiempo_hora = ?,
          sobretiempo_min = ?
        WHERE id_jornada_laboral = ?;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getDia_nosubsidiado());
        $stm->bindValue(2, $model->getOrdinario_hora());
        $stm->bindValue(3, $model->getOrdinario_min());
        $stm->bindValue(4, $model->getSobretiempo_hora());
        $stm->bindValue(5, $model->getSobretiempo_min());
        $stm->bindValue(6, $model->getId_joranada_laboral());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }
    
}

?>
