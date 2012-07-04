<?php

class EmpleadorDestaqueYourSelfDao extends AbstractDao {

    public function registrar($id_empleador, $id_empleador_maestro) {

        $query = "
        INSERT INTO empleadores_destaques_yourself
                    (
                     id_empleador,
                     id_empleador_maestro,
                     estado)
        VALUES (
                ?,
                ?,
                ?);          
        ";
       // $obj = new EmpleadorDestaqueYourSelf();
       // $obj = $empleador_destaque_yourself;

        try {
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_empleador);
            $stm->bindValue(2, $id_empleador_maestro);
            $stm->bindValue(3, 'ACTIVO');
            $stm->execute();

            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();
            $this->pdo->commit();

            $stm = null;
            return $lista[0]['id'];
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $this->pdo->rollBack();
            //throw $e;
        }
    }

    public function baja($id) {

        $query = "
        UPDATE empleadores_destaques_yourself
        SET estado = 'INACTIVO'
        WHERE id_empleador_destaque_yoursef = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function alta($id) {

        $query = "
        UPDATE empleadores_destaques_yourself
        SET estado = 'ACTIVO'
        WHERE id_empleador_destaque_yoursef = ?;            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id); //BOOL
        $stm->execute();
        $stm = null;
        return true;
    }

    public function cantidad($id_empleador_maestro, $WHERE) {//OK
        $query = "
        SELECT COUNT(*) AS numfilas
        FROM empleadores_destaques_yourself
        WHERE id_empleador_maestro = ? 

        $WHERE           
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]["numfilas"];
    }

    //---------------
    public function listar($id_empleador_maestro, $WHERE, $start, $limit, $sidx, $sord) {

        $query = "
        SELECT 
        edy.id_empleador_destaque_yoursef,
        edy.id_empleador,
        edy.id_empleador_maestro,
        e.razon_social,
        e.ruc,
        edy.estado

        FROM empleadores_destaques_yourself AS edy

        INNER JOIN empleadores AS e
        ON edy.id_empleador = e.id_empleador

        WHERE edy.id_empleador_maestro=?   
        
        $WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
        ";
        // echo $query;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    //-- ojo ok
    public function buscarIdEmpleadorDestaqueYourself($id_empleador_maestro,$id_empleador) {
        $query = "
        SELECT
        edy.id_empleador_destaque_yoursef
        FROM empleadores_destaques_yourself AS edy
        WHERE edy.id_empleador_maestro= ? 
        AND edy.id_empleador = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2,$id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();

        return $lista[0]['id_empleador_destaque_yoursef'];
    }

    // --- 
    public function existeRelacionCreada($id_empleador, $id_empleador_maestro) {
        $query = "
        SELECT id_empleador_destaque_yoursef 
        FROM empleadores_destaques_yourself
        WHERE id_empleador = ?
        AND id_empleador_maestro = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();

        return $lista[0]['id_empleador_destaque_yoursef'];
    }
    
    
    
       /*
     * Lista IDS
     * Empleadores que Destaco Personal YourSelf
     */

    public function listaIDEmpleador($id_empleador_maestro) {
        $query = "
        SELECT 
        id_empleador,
        id_empleador_destaque_yoursef
        
        FROM empleadores_destaques_yourself  
        WHERE id_empleador_maestro = ?
        AND estado = 'ACTIVO';
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        //-------------
        for ($i = 0; $i < count($lista); $i++) {
            $data[$i]['id_empleador_destaque_yoursef'] = $lista[$i]['id_empleador_destaque_yoursef'];
            $data[$i]['id_empleador'] = $lista[$i]['id_empleador'];
        }

        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";
        return $data;
    }

}

?>
