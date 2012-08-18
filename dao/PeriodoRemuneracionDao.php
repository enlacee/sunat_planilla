<?php

class PeriodoRemuneracionDao extends AbstractDao {

    //put your code here

    public function buscar_ID($ID) {

        $query = "
        SELECT
          cod_periodo_remuneracion,
          descripcion,
          tasa,
          dia,
          estado
        FROM periodos_remuneraciones
        WHERE cod_periodo_remuneracion = ?
        AND estado = 1        
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

?>
