<?php

class PagoQuincenaDao extends AbstractDao {

    function add($obj) {
        $model = new PagoQuincena();
        $model = $obj;
        $query = "
        INSERT INTO pagos_quincenas
                    (
                     id_pdeclaracion,
                     id_trabajador,
                     dia_laborado,
                     sueldo_base,
                     sueldo_porcentaje,
                     sueldo,
                     devengado,
                     id_empresa_centro_costo,
                     fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);
                ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_pdeclaracion());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getDia_laborado());
        $stm->bindValue(4, $model->getSueldo_base());
        $stm->bindValue(5, $model->getSueldo_porcentaje());
        $stm->bindValue(6, $model->getSueldo());
        $stm->bindValue(7, $model->getDevengado());
        $stm->bindValue(8, $model->getId_empresa_centro_costo());
        $stm->bindValue(9, $model->getFecha_creacion());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    // INIT GRID QUINCENA //
    function listar($ID_PDECLARACION,$WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }
        $query = "
        SELECT 
        pq.id_pago_quincena,
        pq.id_trabajador,
        pq.sueldo,       
        pq.sueldo_base,            
        pq.dia_laborado,
        pq.id_empresa_centro_costo,

        per.cod_tipo_documento,
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres,
        ecc.descripcion AS ccosto
        FROM pagos_quincenas AS pq
        INNER JOIN trabajadores AS t
        ON pq.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona

        INNER JOIN empresa_centro_costo AS ecc
        ON pq.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE pq.id_pdeclaracion = ?
        $cadena
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_PDECLARACION);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;        
    }
    
        //USO ESCLUSIVO PARA GRID
    public function listarCount($ID_PDECLARACION, $WHERE) {

        $query = "
        SELECT 
        count(*) as counteo
        FROM pagos_quincenas AS pq
        INNER JOIN trabajadores AS t
        ON pq.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona

        INNER JOIN empresa_centro_costo AS ecc
        ON pq.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE pq.id_pdeclaracion = ?
        $WHERE
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_PDECLARACION);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }
    // END GRID QUINCENA //
    
    
    
    public function listarReporte($ID_PDECLARACION,$id_establecimiento,$id_empresa_centro_costo) {
        $query = "
        SELECT
          pq.id_pago_quincena,
          pq.id_trabajador,
          pq.sueldo,
          pq.dia_laborado,
          pq.id_empresa_centro_costo,
          pq.fecha_creacion,
          per.num_documento,
          per.apellido_paterno,
          per.apellido_materno,
          per.nombres,
          ecc.descripcion            AS ccosto
        FROM pagos_quincenas AS pq
          INNER JOIN trabajadores AS t
            ON pq.id_trabajador = t.id_trabajador
          INNER JOIN personas AS per
            ON t.id_persona = per.id_persona
          INNER JOIN empresa_centro_costo AS ecc
            ON pq.id_empresa_centro_costo = ecc.id_empresa_centro_costo
        WHERE pq.id_pdeclaracion = ?
            AND t.id_establecimiento = ?
            AND ecc.id_empresa_centro_costo = ?
        $cadena
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_PDECLARACION);
        $stm->bindValue(2, $id_establecimiento);
        $stm->bindValue(3, $id_empresa_centro_costo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;        
    }    
    
    
     public function del($id) {

        $query = "
        DELETE
        FROM pagos_quincenas
        WHERE id_pago_quincena = ?;    
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function delAll($id_pdeclaracion){
        $query = "
        DELETE
        FROM pagos_quincenas
        WHERE  id_pdeclaracion = ?;    
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $stm = null;
        return true;
    }
    
    
    // lista ids
    function listarPorPdeclaracion($id_pdeclaracion) {
            $query = "
        SELECT 
        id_trabajador
        FROM pagos_quincenas
        WHERE id_pdeclaracion = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }
    
    function listarPorPdeclaracionTrabajador($id_pdeclaracion,$id_trabajador){
        
        $query = "
        SELECT
          id_pago_quincena,
          dia_laborado,
          sueldo_base,
          sueldo_porcentaje,
          sueldo,
          devengado,
          id_empresa_centro_costo
        FROM pagos_quincenas
        WHERE id_pdeclaracion = ?
        AND id_trabajador = ?
        ";  
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;        
        
    }

}

?>
