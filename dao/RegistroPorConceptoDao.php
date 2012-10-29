<?php

class RegistroPorConceptoDao extends AbstractDao {

    //put your code here

    function add($obj) {

        $query = "
        INSERT INTO registros_por_conceptos
                    (
                    id_pdeclaracion,
                    id_trabajador,
                    cod_detalle_concepto,
                    valor,
                    estado,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
                );            
        ";
        $model = new RegistroPorConcepto();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_pdeclaracion());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getCod_detalle_concepto());
        $stm->bindValue(4, $model->getValor());
        $stm->bindValue(5, $model->getEstado());
        $stm->bindValue(6, $model->getFecha_creacion());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }



    function edit($obj) {

        $query = "
        UPDATE registros_por_conceptos
        SET  
        valor = ?       
        WHERE id_registro_por_concepto = ?;         
        ";
        $model = new RegistroPorConcepto();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getValor());
        $stm->bindValue(2, $model->getId_registro_por_concepto());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    // -- funcion lista trabajadores por concepto para realizar calculo segun
    // corresponda. usando 04/10/2012 ONLY EN DAO...
    //
    function buscar_RPC_PorTrabajador($id_pdeclaracion,$id_trabajador, $cod_detalle_concepto, $estado = 1) {

        if (is_array($cod_detalle_concepto) && count($cod_detalle_concepto) >= 1) {
            $sql = " ";
            for ($i = 0; $i < count($cod_detalle_concepto); $i++) {
                $sql .= $cod_detalle_concepto[$i];
                if ($i == (count($cod_detalle_concepto)) - 1) {
                    //null                    
                } else {
                    $sql .= ",";
                }
            }
        } else {
            $sql = $cod_detalle_concepto;
        }


        $query = "
        SELECT
        -- id_registro_por_concepto,
        cod_detalle_concepto,
        id_trabajador,
        valor,
        estado
        FROM registros_por_conceptos
        WHERE id_pdeclaracion = ?
        AND id_trabajador = ?        
        AND cod_detalle_concepto in ($sql)        
        AND estado = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->bindValue(3, $estado);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista[0];
    }

    // -- sin uso   04/09/2012
    function listar() {
        $query = "
        SELECT
        id_registro_por_concepto,
        id_trabajador,
        cod_detalle_concepto,
        valor,
        estado,
        fecha_creacion
        FROM registros_por_conceptos
        -- WHERE cod_detalle_concepto = X; 
        ";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $cod_detalle_concepto);
        $stm->execute();

        $lista = $stm->fetchAll();

        return $lista;
    }

    function listar2_count($cod_detalle_concepto, $id_pdeclaracion, $WHERE) {
        $query = "
        SELECT
        count(*) AS counteo
        
        FROM registros_por_conceptos AS rpc
        INNER JOIN trabajadores AS t
        ON rpc.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona 

        WHERE rpc.cod_detalle_concepto = ?
        AND rpc.id_pdeclaracion = ?
        $WHERE
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_detalle_concepto);
        $stm->bindValue(2, $id_pdeclaracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista[0]['counteo'];
    }

    function listar2($cod_detalle_concepto, $id_pdeclaracion, $WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
        SELECT
        rpc.id_registro_por_concepto,
        rpc.id_trabajador,
        rpc.cod_detalle_concepto,
        rpc.valor,
        rpc.estado,
        rpc.fecha_creacion,
        --   
        per.cod_tipo_documento,
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres 
        
        FROM registros_por_conceptos AS rpc
        INNER JOIN trabajadores AS t
        ON rpc.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona 

        WHERE rpc.cod_detalle_concepto = ?
        AND rpc.id_pdeclaracion = ?
        $cadena
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_detalle_concepto);
        $stm->bindValue(2, $id_pdeclaracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    /**
     *
     * pregunta si el trabajador esta registrado en:
     * - misma declaracion = id_pdeclaracion
     * - con el mismo codigo = cod_detalle_concepto
     */
    function buscar_ID_trabajador($id_trabajador, $id_pdeclaracion, $cod_detalle_concepto) {
        $query = "
        SELECT 
        rpc.id_registro_por_concepto,
        rpc.id_trabajador
        FROM registros_por_conceptos AS rpc        
        WHERE rpc.id_trabajador = ?
        AND rpc.id_pdeclaracion = ?
        AND rpc.cod_detalle_concepto = '$cod_detalle_concepto'
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista[0];
    }

    function edit_estado($obj) {
        $query = "
        UPDATE registros_por_conceptos
        SET  
        estado = ?       
        WHERE id_registro_por_concepto = ?;         
        ";
        $model = new RegistroPorConcepto();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getEstado());
        $stm->bindValue(2, $model->getId_registro_por_concepto());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    /**
     *
     * @param type $codigo Situacion 0 OR 1
     * @return boolean 
     */
    function baja($id_trabajador,$id_pdeclaracion,$cod_detalle_concepto) {
        
        $query = "
        UPDATE registros_por_conceptos
        SET  
        estado = 0
        
        WHERE id_trabajador = ?
        AND id_pdeclaracion = ?
        AND cod_detalle_concepto = ?
        ";

        $stm = $this->pdo->prepare($query);        
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->bindValue(3, $cod_detalle_concepto);

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    //-------------------------------------------------------------------------
    public function listarTrabajador($id_pdeclaracion, $cod_detalle_concepto) {

        $query = "
    SELECT
    id_pdeclaracion,
    id_trabajador,
    cod_detalle_concepto,
    valor,
    estado

    FROM registros_por_conceptos
    WHERE id_pdeclaracion = ?
    AND cod_detalle_concepto = '$cod_detalle_concepto'
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
