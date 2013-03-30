<?php

class OcupacionDao extends AbstractDao {

    // $cod_categorias_ocupacionales
    //
    
    public function listar($cod_categorias_ocupacional,$WHERE, $start = null, $limit = null, $sidx = null, $sord = null) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }        
        $query = "
        SELECT 
        -- coop.id_c_ocupacion_ocupacion_p,
        coop.cod_ocupacion_p,
        o2.descripcion
        FROM c_ocupaciones_ocupaciones_p AS coop
        INNER JOIN ocupacion_2 AS o2
        ON coop.cod_ocupacion_p = o2.id_ocupacion_2
        WHERE coop.cod_categorias_ocupacionales = ?
        $cadena
";
        $stm = $this->pdo->prepare($query);        
        $stm->bindValue(1, $cod_categorias_ocupacional);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
        // lista de prestamos por Prestamos count
    function listarCount($cod_categorias_ocupacional, $WHERE){
        $query = "
        SELECT 
        COUNT(*) AS counteo
        FROM c_ocupaciones_ocupaciones_p AS coop
        INNER JOIN ocupacion_2 AS o2
        ON coop.cod_ocupacion_p = o2.id_ocupacion_2
        WHERE coop.cod_categorias_ocupacionales = ?
        $WHERE
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_categorias_ocupacional);        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];    
        
    }

}

?>
