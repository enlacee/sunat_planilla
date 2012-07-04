<?php

class EmpleadorMaestroDao extends AbstractDao {

    public function buscarIdEmpleadorMaestroPorIDEMPLEADOR($id_empleador) {

        $query = "
        SELECT 
        e.id_empleador,
        e.ruc,
        e.razon_social,
	em.id_empleador_maestro,
        IF(e.id_empleador = em.id_empleador,'MAESTRO','OTRO') AS tipo
	
        FROM empleadores AS e        
        LEFT JOIN empleadores_maestros AS em
        ON e.id_empleador = em.id_empleador

        WHERE e.id_empleador = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $data = $stm->fetchAll();
        $stm = null;
        return $data[0];
    }

}

?>