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
    public function listarTrabajadoresPorPeriodo($id_EM, $mes_inicio, $mes_fin) {
         $fecha_inicio_sunat = '2011-11-01';
      /*   echo "<pre>";
         echo "fecha_inicio_sunat = ".$fecha_inicio_sunat;
         echo "EME = ".$id_EM."<BR>";
         echo "mes_fin = ".$mes_fin."<BR>";
         echo "mes_inicio = ".$mes_inicio."<BR>";
         echo "</pre>";                  
        */
        $query = "		
        SELECT 
        -- detalle
        p.id_persona,
        t.cod_situacion,
        p.cod_tipo_documento,
        p.num_documento,
        p.nombres,
        p.apellido_materno,
        p.apellido_paterno,

        -- detalle        
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

	-- 17 = No se inicio relacion laboral
        WHERE ( dpl.cod_motivo_baja_registro <> '17' AND em.id_empleador_maestro = ? )

        -- fecha periodo
        AND (dpl.fecha_inicio <= ? )  -- >fin_periodo PRIMER FILTRO SI Aprueba estar en Perido 01/2012
        AND (dpl.fecha_fin >= ? OR dpl.fecha_fin IS NULL )
        ORDER BY t.id_trabajador,dpl.fecha_inicio DESC
        
	";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $mes_fin);
        $stm->bindValue(3, $mes_inicio);
        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        /*echo "<pre>";
        print_r($lista);
        echo "</pre>";
        */
        return $lista;
    }

  /*  public function cantidadTrabajadoresPorPeriodo($id_EM, $mes_fin) {
        
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
*/
    
    
    // ADELANTO QUINCENAL
    //before = listarTrabajadoresPorPeriodo_15 , 7
    public function listarTrabajadoresPorPeriodo_global($id_EM, $mes_inicio, $mes_fin,$cod_periodo_remuneracion) {

        $query = "		
        SELECT 
        -- detalle
        p.id_persona,
        t.cod_situacion,
        p.cod_tipo_documento,
        p.num_documento,
        p.nombres,
        p.apellido_materno,
        p.apellido_paterno,

        -- detalle        
        t.id_trabajador,
	t.cod_periodo_remuneracion,
	t.monto_remuneracion,    
        
        dpl.fecha_inicio,
        dpl.fecha_fin,
        -- centro costo
        ecc.id_empresa_centro_costo,
        ecc.descripcion

        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona
        -- Centro costo
	INNER JOIN empresa_centro_costo AS ecc
	ON t.id_empresa_centro_costo = ecc.id_empresa_centro_costo        
	-- Centro Costo
        INNER JOIN empleadores_maestros AS em
	ON p.id_empleador = em.id_empleador

	INNER JOIN detalle_periodos_laborales AS dpl
	ON t.id_trabajador = dpl.id_trabajador

	-- 17 = No se inicio relacion laboral
	WHERE ( dpl.cod_motivo_baja_registro <> '17' AND em.id_empleador_maestro = ? )

	-- fecha periodo
	AND (dpl.fecha_inicio <= ? )  -- >fin_periodo PRIMER FILTRO SI Aprueba estar en Perido 01/2012
	AND (dpl.fecha_fin >= ? OR dpl.fecha_fin IS NULL )
        
        AND t.cod_periodo_remuneracion = ? -- 2 = quincena
        
	ORDER BY t.id_trabajador,dpl.fecha_inicio DESC
        
	";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $mes_fin);
        $stm->bindValue(3, $mes_inicio);
        $stm->bindValue(4, $cod_periodo_remuneracion);
        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        /*echo "<pre>";
        print_r($lista);
        echo "</pre>";
        */
        return $lista;
    }    
    
    
    
    
}

?>
