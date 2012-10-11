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
    /*
      public function listarTrabajadoresPorPeriodo($id_EM, $mes_inicio, $mes_fin) {

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

      -- NO EXISTE QUINCENA THIS IS MONTH'S

      ORDER BY t.id_trabajador,dpl.fecha_inicio DESC

      ";
      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_EM);
      $stm->bindValue(2, $mes_fin);
      $stm->bindValue(3, $mes_inicio);

      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;

      return $lista;
      }
     */
    public function listarTrabajadoresPorPeriodo_global_grid_Count($id_EM, $mes_inicio, $mes_fin, $WHERE) {
        $query = "		
        SELECT 
        count(*) AS counteo

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
        $WHERE
	";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $mes_fin);
        $stm->bindValue(3, $mes_inicio);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }

    public function listarTrabajadoresPorPeriodo_global_grid($id_EM, $mes_inicio, $mes_fin, $WHERE, $start, $limit, $sidx, $sord) {

        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

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
        
        $cadena
	";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $mes_fin);
        $stm->bindValue(3, $mes_inicio);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    
    
    /**
     * Segundo caso Listado de trabajadores por mes:
     *  debido ah que solo tiene que encontrarse a trabajadores dentro
     *  del mes  = 01/01/2012 a 31/01/2012 
     *  lista de trabajadores en Enero 31.
     * =listarTrabajadoresPorPeriodo_global_grid_Mes=
     */
    public function listarTrabajadoresPorPeriodo_global_grid_Mes($id_EM, $mes_inicio, $mes_fin, $WHERE, $start, $limit, $sidx, $sord) {

        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

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
        
        $cadena
	";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $mes_fin);
        $stm->bindValue(3, $mes_inicio);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function listarTrabajadoresPorPeriodo_global_grid_Mes_Count($id_EM, $mes_inicio, $mes_fin, $WHERE) {
        $query = "		
        SELECT 
        count(*) AS counteo

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
	AND (dpl.fecha_inicio <= ? )
	AND (dpl.fecha_fin >= ? OR dpl.fecha_fin IS NULL )        
        $WHERE
	";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $mes_fin);
        $stm->bindValue(3, $mes_inicio);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }    
    
    
    
    
    // ADELANTO QUINCENAL
    //before = listarTrabajadoresPorPeriodo_15 , 7
    public function listarTrabajadoresPorPeriodo_global($id_EM, $mes_inicio, $mes_fin, $WHERE = null/* , $cod_periodo_remuneracion */) {

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
        t.monto_devengado,
        
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
        
        $WHERE

	ORDER BY t.id_trabajador,dpl.fecha_inicio DESC
        
	";
        // --  NO TOMA EN CUENTA A TRABAJADORES POR [ AND t.cod_periodo_remuneracion = ? -- 2 = quincena

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $mes_fin);
        $stm->bindValue(3, $mes_inicio);
        //$stm->bindValue(4, $cod_periodo_remuneracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function editMontoDevengadoTrabajador($id_trabajador,$monto_devengado){
        $query="
        UPDATE trabajadores
        SET 
        monto_devengado = ?
        WHERE id_trabajador = ?; 
    ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $monto_devengado);
        $stm->bindValue(2, $id_trabajador); 
        $stm->execute();
        //$lista = $stm->fetchAll();
        //$stm = null;
        return true;        
                
    }
    
    //new 10/09/2012
    public function listarTrabajadorPeriodo($id_empleador_maestro, $id_trabajador) {

        $query = "
          SELECT
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
	AND t.id_trabajador = ?           
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $id_trabajador);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

    /*
     *   CONSISTENCIA DE DATOS SE CREA HERE LA FUNCION 
     *  de periodos laborales dentro del MES (inicio,fin)
     *  this Sin Periodo Remunerativo XQ 
     *  buscarPeriodosTrabajador_ID_persona:: Solo se utiliza para el PDT-PLAME =mensual
     */

    public function buscarPeriodosTrabajador_ID_persona_MENSUAL($id_EM, $mes_inicio, $mes_fin, $ID_PERSONA) {

        $query = "
        SELECT 
        -- detalle
        p.id_persona,
        -- detalle        
        t.id_trabajador,
	t.cod_periodo_remuneracion,
	t.monto_remuneracion,    
        
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
	WHERE ( dpl.cod_motivo_baja_registro <> '17' AND em.id_empleador_maestro = 2  AND p.id_persona= ?)

	-- fecha periodo
	AND (dpl.fecha_inicio <= ? )  -- >fin_periodo PRIMER FILTRO SI Aprueba estar en Perido 01/2012
	AND (dpl.fecha_fin >= ? OR dpl.fecha_fin IS NULL )
        
	ORDER BY t.id_trabajador,dpl.fecha_inicio DESC    
";


        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_EM);
        $stm->bindValue(2, $ID_PERSONA);
        $stm->bindValue(3, $mes_fin);
        $stm->bindValue(4, $mes_inicio);
        //$stm->bindValue(4, $cod_periodo_remuneracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

}

?>
