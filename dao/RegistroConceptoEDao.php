<?php

class RegistroConceptoEDao extends AbstractDao {

    function add($obj) {

        $query = "
        INSERT INTO registros_conceptos_e
                    (
                     id_concepto_e_empleador,
                     id_trabajador,
                     valor,
                     estado,
                     fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?);          
        ";
        $model = new RegistroConceptoE();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_concepto_e_empleador());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getValor());
        $stm->bindValue(4, $model->getEstado());
        $stm->bindValue(5, $model->getFecha_creacion());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }



    function edit($obj) {

        $query = "
        UPDATE registros_conceptos_e
        SET   
          valor = ?
        WHERE id_registro_concepto_e = ?;         
        ";
        $model = new RegistroConceptoE();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getValor());
        $stm->bindValue(2, $model->getId_registro_concepto_e());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    // -- funcion lista trabajadores por concepto para realizar calculo segun
    // corresponda. usando 04/10/2012 ONLY EN DAO...
    // SOLO USADO EN CONTROLLER calc. !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    //   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
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


    function listar($id_concepto_e_empleador) {
        $query = "
        SELECT
        rpce.id_registro_concepto_e,
        rpce.id_concepto_e_empleador,
        rpce.id_trabajador,
        rpce.valor,
        rpce.estado,        
        cee.id_concepto_e_empleador,
        --   
        per.cod_tipo_documento,
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres  
        FROM registros_conceptos_e AS rpce
	INNER JOIN conceptos_e_empleadores AS cee
	ON rpce.id_concepto_e_empleador = cee.id_concepto_e_empleador
        --         
        INNER JOIN trabajadores AS t
        ON rpce.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona
        INNER JOIN empleadores AS emp
        ON per.id_empleador = emp.id_empleador 

        WHERE rpce.id_concepto_e_empleador  = ?
        AND t.cod_situacion = 1;
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_concepto_e_empleador);
        //$stm->bindValue(2, $id_empleador);
        
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }
    
    

    
    // fulllllllllll

    function buscar_ID_trabajador($id_trabajador, $id_concepto_e_empleador) {
        $query = "
	SELECT 
	rpce.id_registro_concepto_e,
	rpce.id_trabajador
	FROM registros_conceptos_e AS rpce        
	WHERE rpce.id_trabajador = ?
	AND rpce.id_concepto_e_empleador = ?; ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_concepto_e_empleador);
        
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista[0];
    }

    function edit_estado($obj) {
        $query = "
        UPDATE registros_conceptos_e
        SET  
        estado = ?       
        WHERE id_registro_concepto_e = ?;         
        ";
        $model = new RegistroConceptoE();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getEstado());
        $stm->bindValue(2, $model->getId_registro_concepto_e());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    
    

    
}

?>
