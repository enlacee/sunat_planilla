<?php

class ConfAfpDao extends AbstractDao {
    //put your code here
    public function registrar($ap_obligatorio, $comision,$prima_seguro,$fecha_vigencia) {
        $query = "
        INSERT INTO conf_afp
                    (
                    aporte_obligatorio,
                    comision,
                    prima_seguro,
                    fecha,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?);       
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ap_obligatorio);
        $stm->bindValue(2, $comision);
        $stm->bindValue(3, $prima_seguro);
        $stm->bindValue(4, $fecha_vigencia);
        $stm->bindValue(5, date("Y-m-d")); 
   
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($id, $ap_obligatorio, $comision,$prima_seguro,$fecha_vigencia) {

        $query = "
        UPDATE conf_afp
        SET 
        aporte_obligatorio = ?,
        comision = ?,
        prima_seguro = ?,
        fecha = ?
        WHERE id_conf_afp = ?;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ap_obligatorio);
        $stm->bindValue(2, $comision);
        $stm->bindValue(3, $prima_seguro);
        $stm->bindValue(4, $fecha_vigencia);
        
        $stm->bindValue(5, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function eliminar($id) {
        $query = "
        DELETE
        FROM conf_afp
        WHERE id_conf_afp = ?;      
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        return true;
    }

    public function listar() {

        $query = "
        SELECT
            id_conf_afp,
            aporte_obligatorio,
            comision,
            prima_seguro,
            fecha,
            fecha_creacion
        FROM conf_afp
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
