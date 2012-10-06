<?php

class ComboCategoriaDao extends AbstractDao {
    /**
     *   -----------------------------------------------------------------------------------------
     * 	FUNCIONES COMBO_BOX
     * 	-----------------------------------------------------------------------------------------
     * */

    /**
     * tabla  motivos_bajas_registro
     * id_tipo_empleador:
     * 1 = sector privado
     * 2 = sector publico
     * 3 = otros
     */
    public function comboTipoTrabajadorPorIdTipoEmpleador($id_tipo_empleador) {

        $query = "
		SELECT 
			-- te.id_tipo_empleador,
			-- tt_te.id_t_trabajador_t_empleado,
			tt.cod_tipo_trabajador,
			tt.descripcion,
			tt.descripcion_abreviada
			 
		FROM tipos_empleadores AS te
		
		INNER JOIN t_trabajadores_t_empleadores AS tt_te
		ON te.id_tipo_empleador = tt_te.id_tipo_empleador
		
		INNER JOIN tipos_trabajadores AS tt
		ON tt.cod_tipo_trabajador = tt_te.cod_tipo_trabajador
		
		WHERE tt_te.id_tipo_empleador = ?
		ORDER BY tt.cod_tipo_trabajador ASC	
		";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_tipo_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * 	Motivos Bajas Registros = motivos_bajas_registros
     * 	Filtro para La Categoria Trabajador
     * */
    public function comboMotivoBajaRegistro() {
        $query = "SELECT *FROM motivos_bajas_registros ORDER BY cod_motivo_baja_registro ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /**
     * 	 tb_regimenes_laborales
     * id_tipo_empleador:
     * 1 = sector privado
     * 2 = sector publico
     * 3 = otros
     * */
    public function comboRegimenLaboralPorTipoEmpleador($id_tipo_empleador) {
        $query = "		
		SELECT 
		 -- te.descripcion,
		 rl.cod_regimen_laboral,
		 rl.descripcion,
		 rl.descripcion_abreviada
		FROM tipos_empleadores AS te
		
		INNER JOIN t_empleadores_t_r_laborales  AS te_trl
		ON te.id_tipo_empleador = te_trl.id_tipo_empleador
		
		INNER JOIN regimenes_laborales AS rl
		ON te_trl.cod_regimen_laboral = rl.cod_regimen_laboral
		
		WHERE te.id_tipo_empleador = ? 
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_tipo_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /**
     * 	tb_categorias_ocupacionales
     * 1 = sector privado
     * 2 = sector publico
     * 3 = otros
     * */
    public function comboCategoriaOcupacionalPorTipoEmpleador($id_tipo_empleador) {
        $query = "
		SELECT 
		 -- te.descripcion,
		 co.cod_categorias_ocupacionales,
		 co.descripcion
		
		FROM tipos_empleadores AS te
		INNER JOIN t_empleadores_c_ocupacionales AS te_co
		ON te.id_tipo_empleador = te_co.id_tipo_empleador
		
		INNER JOIN categorias_ocupacionales AS co
		ON te_co.cod_categorias_ocupacionales = co.cod_categorias_ocupacionales
		
		WHERE te.id_tipo_empleador = ?
		
		ORDER BY co.cod_categorias_ocupacionales ASC
		";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_tipo_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

//

    /*
     * * tb niveles_educativos
     */

    public function comboNivelEducativo() {

        $query = "
	SELECT 
	cod_nivel_educativo,
	descripcion_abreviada,
	descripcion
	FROM niveles_educativos ORDER BY cod_nivel_educativo ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     *
     * tb ocupaciones_p
     */

    public function comboOcupacionPorIdCategoriaOcupacional($id_categoria_ocupacional) {
        /* 	-- ocupacion por cod_categoria_ocupacional
          -- 01 EJECUTIVO
          -- 02 OBRERO
          -- 03 EMPLEADO
         */
        $query = "
		SELECT
		co.descripcion, 
		o.cod_ocupacion_p,
		o.nombre
		FROM ocupaciones_p AS o
		INNER JOIN c_ocupaciones_ocupaciones_p AS  co_op
		ON o.cod_ocupacion_p = co_op.cod_ocupacion_p
		
		INNER JOIN categorias_ocupacionales AS co
		ON co_op.cod_categorias_ocupacionales = co.cod_categorias_ocupacionales
		
		WHERE co.cod_categorias_ocupacionales = '$id_categoria_ocupacional'
		
		ORDER BY o.cod_ocupacion_p	";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1,$id_categoria_ocupacional);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /**
     * *
     * * OCUPACION POR TIPO DE TRABAJADOR
     * *
     * *
     * */
    /*
     * 
     * tb tipos_contratos
     */
    public function comboTiposContrato() {
        $query = "SELECT *FROM tipos_contratos ORDER BY cod_tipo_contrato ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     *
     * tb tipos_pagos
     */

    public function comboTipoPago() {
        $query = "SELECT *FROM tipos_pagos ORDER BY cod_tipo_pago ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     *
     * tb tipos_pagos
     */

    public function comboPeriodoRemuneracion() {
        $query = "SELECT *FROM periodos_remuneraciones ORDER BY cod_periodo_remuneracion ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     * ****************************************************************************	
     * INICIO ADMINISTRACION
     * **************************************************************************** 
     */

    public function comboMontoRemuneracion() {
        $query = "SELECT *FROM montos_remuneraciones ORDER BY cantidad ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     * ****************************************************************************	
     * FINAL ADMINISTRACION
     * **************************************************************************** 
     */





    /*
     * 	TRABAJADOR
     * Datos de Seguridad Social 
     * tb regimenes_aseguramientos_salud
     */

    public function comboRegimenAseguramientoSalud() {
        $query = "SELECT *FROM regimenes_aseguramientos_salud ORDER BY cod_regimen_aseguramiento_salud ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*     * **********************************************************************************
     * 	COMBO PARA EMPLEADOR
     * ************************************************************************************
     */

    public function comboTipoEmpleador() {
        $query = "SELECT *FROM tipos_empleadores ORDER BY id_tipo_empleador ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboTipoSociedadComercial() {
        $query = "SELECT *FROM tipos_sociedades_comerciales ORDER BY id_tipo_sociedad_comercial ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboTipoActividad() {
        $query = "SELECT *FROM tipos_actividades ORDER BY cod_tipo_actividad ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboRegimenSalud() {
        $query = "SELECT *FROM regimenes_aseguramientos_salud ORDER BY cod_regimen_aseguramiento_salud ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboRegimenPensionario() {

        $query = "SELECT *FROM regimenes_pensionarios ORDER BY cod_regimen_pensionario ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboConvenio() {
        $query = "SELECT *FROM convenios ORDER BY cod_convenio ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     * ***************************************************************************************
     * 		Combo Establecimientos en linea
     * ***************************************************************************************
     */

    public function comboEstablecimientoLineal($id_empleador) {

        $query = "
	SELECT 
	e.id_empleador,
	es.cod_establecimiento,
	es.id_establecimiento,
	
	esd.cod_ubigeo_reniec,
	ud.descripcion AS ubigeo_departamento,
	up.descripcion AS ubigeo_provincia,
	ur.descripcion  AS ubigeo_distrito,
	esd.cod_via,
	v.descripcion AS ubigeo_nombre_via,	
	esd.nombre_via,
	esd.cod_via,
	esd.numero_via,
	esd.departamento,
	esd.interior,
	esd.manzana,
	esd.lote,
	esd.kilometro,
	esd.block,
	esd.etapa,
	esd.cod_zona,
	z.descripcion AS ubigeo_nombre_zona,
	esd.nombre_zona,
	esd.referencia
	
	
	FROM empleadores AS e
	INNER JOIN establecimientos AS es
	ON  e.id_empleador = es.id_empleador
	
	INNER JOIN  establecimientos_direcciones AS esd
	ON es.id_establecimiento = esd.id_establecimiento
	
	INNER JOIN vias AS v
	ON esd.cod_via = v.cod_via
	
	INNER JOIN ubigeo_reniec AS  ur
	ON esd.cod_ubigeo_reniec = ur.cod_ubigeo_reniec
	
	
	INNER JOIN ubigeo_provincias AS  up
	ON ur.cod_provincia = up.cod_provincia
	
	INNER JOIN ubigeo_departamentos AS ud
	ON ur.cod_departamento = ud.cod_departamento
	
	INNER JOIN zonas AS z
	ON esd.cod_zona = z.cod_zona
	
	WHERE e.id_empleador = ?
	ORDER BY es.id_establecimiento ASC ;
	";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     * ***************************************************************************************
     * 		Empleador
     * ***************************************************************************************
     */


    /*     * **********************************************************************************
     * 	COMBO Persona en Formacion
     * ************************************************************************************
     */

    public function comboModalidadFormativa() {
        $query = "
	SELECT 
	id_modalidad_formativa,
	descripcion_abreviada
	FROM modalidades_formativas
	ORDER BY id_modalidad_formativa ASC
	";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboOcupacionALLPformacion() {
        $query = "
        SELECT 
        cod_ocupacion_p AS id,
        nombre AS descripcion
        FROM ocupaciones_p ";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboeps() {
        $query = "
    SELECT 
        cod_eps,
        ruc,
        descripcion
    FROM eps
    ORDER BY cod_eps asc;
    ";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /**
     * 	SITUACIONES -> cod_situacion
     * */
    public function comboSituacion() {
        $query = "
        SELECT 
        cod_situacion,
        descripcion,
        descripcion_abreviada
        FROM situaciones ORDER BY cod_situacion        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function comboSuspencionLaboral() {

        $query = "
        SELECT 
            cod_tipo_suspen_relacion_laboral,
            descripcion_abreviada
        FROM tipos_suspensiones_relaciones_laborales        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
        public function comboCentroCosto() {

        $query = "
        SELECT 
        id_empresa_centro_costo,
        descripcion
        FROM empresa_centro_costo      
        ";

        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    
    public function comboTipoParaTiFamilia(){
        
        $query = "
        SELECT
        id_tipo_para_ti_familia,
        descripcion,
        valor
        FROM tipo_para_ti_familia        
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;     
        
    }

}

//End Class
?>