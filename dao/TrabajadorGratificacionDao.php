<?php

class TrabajadorGratificacionDao extends AbstractDao {

    //put your code here

    function add($obj) {
        $model = new TrabajadorGratificacion();
        $model = $obj;
        $query = "
        INSERT INTO trabajadores_gratifiaciones
                    (
                     id_trabajador,
                     fecha,
                     fecha_creacion)
        VALUES (
                ?,
                ?,
                ?);
      ";

        try {
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_trabajador());
            $stm->bindValue(2, $model->getFecha());
            $stm->bindValue(3, $model->getFecha_creacion());

            $stm->execute();
            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();

            $this->pdo->commit();
            $stm = null;
            return $lista[0]['id'];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    function existePersonaGratificacion($id_trabajador, $fecha_gratificacion){
        $query = "
        SELECT 
        id_trabajador_grati,
        id_trabajador
        FROM trabajadores_gratifiaciones
        WHERE id_trabajador = ?
        AND fecha = ?
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $fecha_gratificacion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];        
    }
    
    function del() {
        
    }

    function listar_2($periodo, $id_establecimiento, $id_empresa_centro_costo) {
        $query = "
        SELECT
          gt.id_trabajador_grati,      
          gt.id_trabajador,
          -- persona
          p.id_persona, 
          P.apellido_materno,
          p.apellido_paterno,
          p.nombres,
          p.num_documento,
          -- ocupacion
          op.nombre AS nombre_ocupacion,
          -- regimen pensionario          
          rp.descripcion_abreviada AS nombre_afp,
          -- centro costo
          ecc.descripcion AS nombre_centro_costo

        FROM trabajadores_gratifiaciones AS gt -- gt
        INNER JOIN trabajadores AS t
        ON gt.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona

        LEFT JOIN ocupaciones_p AS  op
        ON t.cod_ocupacion_p = op.cod_ocupacion_p
-- new	
	INNER JOIN detalle_regimenes_pensionarios  AS drp
	ON t.id_trabajador = drp.id_trabajador
	
        INNER JOIN regimenes_pensionarios AS rp
        ON drp.cod_regimen_pensionario = rp.cod_regimen_pensionario -- t.cod_regimen_ gt.cod_regimen_pensionario = rp.cod_regimen_pensionario
-- new
        LEFT JOIN empresa_centro_costo  AS ecc
        ON t.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE gt.fecha = ?        
        AND t.id_establecimiento = ?
        AND ecc.id_empresa_centro_costo = ?           
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $periodo);
        $stm->bindValue(2, $id_establecimiento);
        $stm->bindValue(3, $id_empresa_centro_costo);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;        
        return $lista;
    }

    function buscarConceptos($periodo,$id_trabajador) { // ????????????????

        $query = "
        SELECT 
	ddg.cod_detalle_concepto,
	ddg.monto_pagado,
	dc.descripcion,
	dc.descripcion_abreviada	
        FROM trabajadores_gratifiaciones AS tg
        
        INNER JOIN declaraciones_dconceptos_grati AS ddg  
        ON tg.id_trabajador_grati = ddg.id_trabajador_grati
        INNER JOIN detalles_conceptos AS dc
        ON ddg.cod_detalle_concepto = dc.cod_detalle_concepto

        WHERE tg.fecha = ?
        AND tg.id_trabajador = ?         
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $periodo);
        $stm->bindValue(2, $id_trabajador);        

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
    
    //buscar ID
    function buscarIdTrabajadorGrati($id_trabajador,$periodo){
        
        $query = "
        SELECT 
        id_trabajador_grati
        FROM trabajadores_gratifiaciones
        WHERE id_trabajador = ?
        AND fecha = ?  
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $periodo);        

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]['id_trabajador_grati'];
        
    }
    
}

?>
