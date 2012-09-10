<?php

class RegistroPorConceptoDao extends AbstractDao {

    //put your code here

    function add($obj) {

        $query = "
        INSERT INTO registros_por_conceptos
                    (
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
                ?
                );            
        ";
        $model = new RegistroPorConcepto();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getCod_detalle_concepto());
        $stm->bindValue(3, $model->getValor());
        $stm->bindValue(4, $model->getEstado());
        $stm->bindValue(5, $model->getFecha_creacion());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    function del() {
        
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
    function buscar_RPC_PorTrabajador($id_trabajador, $cod_detalle_concepto, $estado = 1) {
        
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

        //echo "sql   ".$sql;

        $query = "
        SELECT
        -- id_registro_por_concepto,
        cod_detalle_concepto,
        id_trabajador,
        valor,
        estado
        FROM registros_por_conceptos
        WHERE (
        id_trabajador = ?
        AND cod_detalle_concepto in ($sql)
        )
        AND estado = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $estado);
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

    function listar2($cod_detalle_concepto,$id_empleador) {
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
        INNER JOIN empleadores AS emp
        ON per.id_empleador = emp.id_empleador 

        WHERE cod_detalle_concepto = ?
        AND emp.id_empleador = ?
        AND t.cod_situacion = 1
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_detalle_concepto);
        $stm->bindValue(2, $id_empleador);
        
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    function buscar_ID_trabajador($id_trabajador, $cod_detalle_concepto) {
        $query = "
        SELECT 
        rpc.id_registro_por_concepto,
        rpc.id_trabajador
        FROM registros_por_conceptos AS rpc        
        WHERE rpc.id_trabajador = ?
        AND rpc.cod_detalle_concepto = $cod_detalle_concepto


";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
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

}

?>
