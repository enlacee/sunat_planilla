<?php

class PlameDao extends AbstractDao {
    //put your code here

    /**
     *
     * @param type $id_EM
     * @return boolean 
     * 
     * Lista Trabajadores que estan dentro de un periodo mes/anio
     * sunat proporciona el PLAME desde 2011-11-01 hasta hoy.
     */
    public function listarTrabajadoresPorPeriodo($id_EM, $mes_fin) {
         $fecha_inicio_sunat = '2011-11-01';
        
        $query = "		
        SELECT 
        -- detalle
        
        p.cod_tipo_documento,
        p.num_documento,
        p.nombres,
        p.apellido_materno,
        p.apellido_paterno,

        -- detalle
        p.id_persona,
        t.id_trabajador,
        dpl.fecha_inicio,
        dpl.fecha_fin

        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador

        INNER JOIN detalle_periodos_laborales AS dpl
        ON t.id_trabajador = dpl.id_trabajador

        WHERE (t.cod_situacion = 1 AND em.id_empleador_maestro = ? )

        AND dpl.fecha_inicio >= ?  -- fecha inicio SUNAT PLANILLA

        -- fecha periodo
        AND dpl.fecha_inicio <= ?  -- fin periodo
        -- fecha periodo
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $fecha_inicio_sunat);
        $stm->bindValue(3, $mes_fin);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function cantidadTrabajadoresPorPeriodo($id_EM, $mes_fin) {
        
       $fecha_inicio_sunat = '2011-11-01';
       
       
        $query = "		
        SELECT 
        COUNT(*) AS  numfilas
        
        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador

        INNER JOIN detalle_periodos_laborales AS dpl
        ON t.id_trabajador = dpl.id_trabajador

        WHERE (t.cod_situacion = 1 AND em.id_empleador_maestro = ? )

        AND dpl.fecha_inicio >= ?  -- fecha inicio SUNAT PLANILLA

        -- fecha periodo
        AND dpl.fecha_inicio <= ?  -- fin periodo
        -- fecha periodo
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $fecha_inicio_sunat);
        $stm->bindValue(3, $mes_fin);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]['numfilas'];
    }

}

?>
