<?php

//require_once '../dao/AbstractDao.php';;

class EmpleadorDestaqueDao extends AbstractDao {

    public function registrar($id_empleador, $id_empleador_maestro) {

        $query = "
        INSERT INTO empleadores_destaques
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
        UPDATE empleadores_destaques
        SET estado = 'INACTIVO'
        WHERE id_empleador_destaque = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function alta($id) {

        $query = "
        UPDATE empleadores_destaques
        SET estado = 'ACTIVO'
        WHERE id_empleador_destaque = ?;            
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
        FROM empleadores_destaques
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
        ed.id_empleador_destaque,
        ed.id_empleador,
        ed.id_empleador_maestro,
        e.razon_social,
        e.ruc,
        ed.estado

        FROM empleadores_destaques AS ed

        INNER JOIN empleadores AS e
        ON ed.id_empleador = e.id_empleador

        WHERE ed.id_empleador_maestro=?   
        
        $WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
        ";
        // echo $query;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    //-- ojo ok
    public function buscarIdEmpleadorDestaque($id_empleador_maestro, $id_empleador) {
        $query = "
        SELECT
        ed.id_empleador_destaque
        FROM empleadores_destaques AS ed
        WHERE ed.id_empleador_maestro= ? 
        AND ed.id_empleador = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['id_empleador_destaque'];
    }

    // --- 
    public function existeRelacionCreada($id_empleador, $id_empleador_maestro) {
        $query = "
        SELECT id_empleador_destaque 
        FROM empleadores_destaques
        WHERE id_empleador = ?
        AND id_empleador_maestro = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['id_empleador_destaque'];
    }

    /*
     * Lista IDS
     * Empleadores que Destaco Personal
     */

    public function listaIDEmpleador($id_empleador_maestro) {
        $query = "
        SELECT 
        id_empleador
        FROM empleadores_destaques 
        WHERE id_empleador_maestro = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        // --- query 02
        $query2 = "
        SELECT 
        e.id_empleador
        FROM empleadores AS e
        INNER JOIN empleadores_maestros AS em
        ON e.id_empleador = em.id_empleador
        WHERE em.id_empleador_maestro = ?        
        ";
        $stm = $this->pdo->prepare($query2);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista2 = $stm->fetchAll();
        $stm = null;


        //-------------
        $data = array();
        for ($i = 0; $i < count($lista2); $i++) {
            $data[] = $lista2[$i]['id_empleador'];
        }

        for ($i = 0; $i < count($lista); $i++) {
            $data[] = $lista[$i]['id_empleador'];
        }

        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";
        return $data;
    }

}

//$dao = new EmpleadorDestaqueDao();
//$dao->listarIDEmpleadores(1);
?>
