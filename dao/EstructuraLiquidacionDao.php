<?php
class EstructuraLiquidacionDao extends AbstractDao {
    
    
    function dataBase($anio){
        
    $query = "
	SELECT  -- ok solo funciona id_persona xq es UNICO
	p.id_persona,
	p.num_documento,
	p.apellido_paterno,
	p.apellido_materno,
	p.nombres	
	-- dpl.fecha_inicio,
	-- dpl.fecha_fin

	FROM personas AS p
	INNER JOIN trabajadores AS t
	ON t.id_persona = p.id_persona

	INNER JOIN detalle_periodos_laborales AS dpl
	ON dpl.id_trabajador = t.id_trabajador

	WHERE ( YEAR(dpl.fecha_inicio) <= '$anio' )
	AND ( YEAR(dpl.fecha_fin) = '$anio' OR dpl.fecha_fin IS NULL )
	GROUP BY (p.id_persona) 
    ";

    $stm = $this->pdo->prepare($query);
    //$stm->bindValue(1, $anio);    
    $stm->execute();
    $lista = $stm->fetchAll();
    $stm = null;

    return $lista;
    }
    
    

    
    //-----------------------------------------------------------------------//
    // - Funcion para reporte Liquidacion Anual
    //-----------------------------------------------------------------------//
    function data_ONP($id_pdeclaracion, $id_persona){ // 0607
        $query = "
        -- LA PERSONA - TRABAJADOR ES UNICO POR 
        -- DECLARACION FULL OK
        
        SELECT
        t.id_persona,
        tpd.id_trabajador,
        drp.cuspp,
        (ddc.monto_pagado) AS monto_pagado
        FROM trabajadores_pdeclaraciones AS tpd
	INNER JOIN trabajadores AS t
	ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN declaraciones_dconceptos AS ddc
        ON tpd.id_trabajador_pdeclaracion = ddc.id_trabajador_pdeclaracion
        
	INNER JOIN detalle_regimenes_pensionarios AS drp
	ON t.id_trabajador = drp.id_trabajador
        
        WHERE ddc.cod_detalle_concepto IN (607)
        AND tpd.id_pdeclaracion = ?
        AND t.id_persona = ?
        -- AND tpd.id_trabajador = 14 , 15
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;        
        return $lista;
    }
    
    function dataAFP($id_pdeclaracion, $id_persona){ // 0601-0606-0608
        
        $query = "
        -- LA PERSONA - TRABAJADOR ES UNICO POR 
        -- DECLARACION FULL OK
        
        SELECT
        t.id_persona,
        tpd.id_trabajador,
        drp.cuspp,
        SUM(ddc.monto_pagado) AS monto_pagado
        FROM trabajadores_pdeclaraciones AS tpd
	INNER JOIN trabajadores AS t
	ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN declaraciones_dconceptos AS ddc
        ON tpd.id_trabajador_pdeclaracion = ddc.id_trabajador_pdeclaracion
        
	INNER JOIN detalle_regimenes_pensionarios AS drp
	ON t.id_trabajador = drp.id_trabajador
        
        WHERE ddc.cod_detalle_concepto IN (0601,0606,0608)
        AND tpd.id_pdeclaracion = ?
        AND t.id_persona = ?            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;        
        return $lista;
        
    }  
    
    function dataEssalud($id_pdeclaracion, $id_persona){
        
        
        $query ="
        SELECT
        t.id_persona,
        tpd.id_trabajador,        
        SUM(ddc.monto_pagado) AS monto_pagado
        FROM trabajadores_pdeclaraciones AS tpd
	INNER JOIN trabajadores AS t
	ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN declaraciones_dconceptos AS ddc
        ON tpd.id_trabajador_pdeclaracion = ddc.id_trabajador_pdeclaracion
        
	INNER JOIN detalle_regimenes_pensionarios AS drp
	ON t.id_trabajador = drp.id_trabajador
        
        WHERE ddc.cod_detalle_concepto IN (0804)
        AND tpd.id_pdeclaracion = ?
        AND t.id_persona = ?      
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;        
        return $lista;
        
    }
    
    
/*    
    function dataAFP_DELETE($id_pdeclaracion, $id_trabajador){ // 0601-0606-0608
        
        $query = "
        SELECT 
        SUM(ddc.monto_pagado) AS monto_pagado
        FROM trabajadores_pdeclaraciones AS tpd

        INNER JOIN pdeclaraciones AS pd
        ON tpd.id_pdeclaracion = pd.id_pdeclaracion

        INNER JOIN declaraciones_dconceptos AS ddc
        ON tpd.id_trabajador_pdeclaracion = ddc.id_trabajador_pdeclaracion
        
        WHERE ddc.cod_detalle_concepto IN (0601,0606,0608)
        AND tpd.id_pdeclaracion = ? 
        AND tpd.id_trabajador = ?               
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;        
        return $lista[0]['monto_pagado'];        
    }
*/
    
    
}

?>
