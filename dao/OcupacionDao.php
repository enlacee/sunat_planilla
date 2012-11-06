<?php

class OcupacionDao extends AbstractDao {

    // $cod_categorias_ocupacionales
    //
    public function listar() {
        $query = "
SELECT 
coop.id_c_ocupacion_ocupacion_p,
coop.cod_categorias_ocupacionales,
coop.cod_ocupacion_p
FROM c_ocupaciones_ocupaciones_p AS coop
INNER JOIN ocupacion_2 AS o2
ON coop.cod_ocupacion_p = o2.id_ocupacion_2
WHERE coop.cod_categorias_ocupacionales = ?
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $cod_categorias_ocupacionales);
?????
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

}

?>
