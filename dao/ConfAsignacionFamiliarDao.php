<?php

class ConfAsignacionFamiliarDao extends AbstractDao {

    //put your code here
    public function registrar($valor, $fecha_vigencia) {
        $query = "
        INSERT INTO conf_asignacion_familiar
                    (
                    tasa,
                    fecha,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?);   
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $fecha_vigencia);
        $stm->bindValue(3, date("Y-m-d"));
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($id, $valor, $fecha_vigencia) {

        $query = "
        UPDATE conf_asignacion_familiar
        SET 
        tasa = ?,
        fecha = ?
        WHERE id_conf_asignacion_familiar = ?;       
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $fecha_vigencia);
        $stm->bindValue(3, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function eliminar($id) {
        $query = "
        DELETE
        FROM conf_asignacion_familiar
        WHERE id_conf_asignacion_familiar = ?; 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        return true;
    }

    public function listar() {

        $query = "
        SELECT
            id_conf_asignacion_familiar,
            tasa,
            fecha,
            fecha_creacion
        FROM conf_asignacion_familiar    
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function vigente() {
        $query = "
        SELECT
            id_conf_asignacion_familiar,
            tasa,
            fecha      
        FROM conf_asignacion_familiar
        ORDER BY fecha DESC        
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['tasa'];
    }

    public function vigenteAux($periodo) {
        $query = "
        SELECT
            id_conf_asignacion_familiar,
            tasa,
            fecha      
        FROM conf_asignacion_familiar
        WHERE fecha <='$periodo'
        ORDER BY fecha DESC      
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['tasa'];
    }

}

?>
