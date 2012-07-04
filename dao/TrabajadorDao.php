<?php

class TrabajadorDao extends AbstractDao {

//END FUNCION
//Registrar Producto and Imagen
    public function registrarTrabajador($obj_trabajador) {


        try {
            $query = "
        INSERT INTO trabajadores
                    (
                     id_persona,
                     cod_regimen_laboral,
                     cod_nivel_educativo,
		     cod_categorias_ocupacionales,
                     id_ocupacion_2,
                     cod_ocupacion_p,
                     cod_tipo_contrato,
                     cod_tipo_pago,
                     cod_periodo_remuneracion,
                     id_monto_remuneracion,
                     id_establecimiento,
                     jornada_laboral,
                     situacion_especial,
                     discapacitado,
                     sindicalizado,
                     percibe_renta_5ta_exonerada,
                     aplicar_convenio_doble_inposicion,
                     cod_convenio,
                     cod_situacion,
                     estado)
        VALUES (
                ?,
		?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?); ";
            //Inicia transaccion 

            $em = new Trabajador();
            $em = $obj_trabajador;

//echo "<h3>";
//print_r($em);
//echo "</h3>";
            $this->pdo->beginTransaction();

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $em->getId_persona());
            $stm->bindValue(2, $em->getCod_regimen_laboral());
            $stm->bindValue(3, $em->getCod_nivel_educativo());
            $stm->bindValue(4, $em->getCod_categorias_ocupacionales());
            $stm->bindValue(5, $em->getId_ocupacion_2());
            $stm->bindValue(6, $em->getCod_ocupacion());
            $stm->bindValue(7, $em->getCod_tipo_contrato());
            $stm->bindValue(8, $em->getCod_tipo_pago());

            $stm->bindValue(9, $em->getCod_periodo_remuneracion());
            $stm->bindValue(10, $em->getId_monto_remuneracion());
            $stm->bindValue(11, $em->getId_establecimiento());
            $stm->bindValue(12, $em->getJornada_laboral());
            $stm->bindValue(13, $em->getSituacion_especial());

            $stm->bindValue(14, $em->getDiscapacitado());
            $stm->bindValue(15, $em->getSindicalizado());
            $stm->bindValue(16, $em->getPercibe_renta_5ta_exonerada());
            $stm->bindvalue(17, $em->getAplicar_convenio_doble_inposicion());
            $stm->bindvalue(18, $em->getCod_convenio());
            $stm->bindvalue(19, $em->getCod_situacion());
            $stm->bindvalue(20, $em->getEstado());

            $stm->execute();

            // id Comerico
            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();

            $this->pdo->commit();
            //finaliza transaccion
            //return true;
            return $lista[0]['id'];
            $stm = null;
        } catch (Exception $e) {
            //  Util::rigistrarLog( $e, $query );
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function actualizarTrabajador($obj_trabajador) {

        $com = new Trabajador();
        $com = $obj_trabajador;

        $query = "
        UPDATE trabajadores
        SET 
          cod_regimen_laboral = ?,
          cod_nivel_educativo = ?,
	  cod_categorias_ocupacionales=?,
          id_ocupacion_2 = ?,
	  cod_ocupacion_p=?,
          cod_tipo_contrato = ?,
          cod_tipo_pago = ?,
          cod_periodo_remuneracion = ?,
	  monto_remuneracion =?,
          id_monto_remuneracion = ?,
          id_establecimiento = ?,
          jornada_laboral = ?,
          situacion_especial = ?,
          discapacitado = ?,
          sindicalizado = ?,
          percibe_renta_5ta_exonerada = ?,
          aplicar_convenio_doble_inposicion = ?,
          cod_convenio = ?,
          cod_situacion = ?,
          estado = ?
        WHERE id_trabajador = ?;
	";

        //echo "<pre>en DAO";
        //print_r($com);
        //echo "</pre>";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $com->getCod_regimen_laboral());
        $stm->bindValue(2, $com->getCod_nivel_educativo());
        $stm->bindValue(3, $com->getCod_categorias_ocupacionales());
        $stm->bindValue(4, $com->getId_ocupacion_2());
        $stm->bindValue(5, $com->getCod_ocupacion());
        $stm->bindValue(6, $com->getCod_tipo_contrato());
        $stm->bindValue(7, $com->getCod_tipo_pago());
        $stm->bindValue(8, $com->getCod_periodo_remuneracion());
        $stm->bindValue(9, $com->getMonto_remuneracion());
        $stm->bindValue(10, $com->getId_monto_remuneracion());
        $stm->bindValue(11, $com->getId_establecimiento());
        $stm->bindValue(12, $com->getJornada_laboral());
        $stm->bindValue(13, $com->getSituacion_especial());
        $stm->bindValue(14, $com->getDiscapacitado());
        $stm->bindValue(15, $com->getSindicalizado());
        $stm->bindValue(16, $com->getPercibe_renta_5ta_exonerada());
        $stm->bindValue(17, $com->getAplicar_convenio_doble_inposicion());
        $stm->bindValue(18, $com->getCod_convenio());
        $stm->bindValue(19, $com->getCod_situacion());
        $stm->bindValue(20, $com->getEstado());
        $stm->bindValue(21, $com->getId_trabajador());
        $stm->execute();
        $stm = null;
        return true;
    }

    public function listarTrabajador($ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE, $start, $limit, $sidx, $sord) {

        $query = "
	SELECT 
		t.id_trabajador,
		td.descripcion_abreviada AS nombre_tipo_documento,
		p.num_documento,
		p.apellido_paterno,
		p.apellido_materno,
		p.nombres,
		p.fecha_nacimiento,
		p.sexo,
		-- t.cod_situacion,
		s.descripcion_abreviada AS estado,
                t.estado  AS reporte,
		
		IF (p.tabla_trabajador = 1,'TRA','0') AS categoria_1,
		IF (p.tabla_pensionista = 1,'PEN','0') AS categoria_2,
		IF (p.tabla_personal_formacion_laboral = 1,'PFOR','0') AS categoria_3,
		IF (p.tabla_personal_terceros = 1,'PTER','0') AS categoria_4	
		
        FROM personas AS p        
        INNER JOIN tipos_documentos AS td
        ON p.cod_tipo_documento = td.cod_tipo_documento

        INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador

        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona
        
        INNER JOIN situaciones AS s
        ON t.cod_situacion = s.cod_situacion
        
        WHERE em.id_empleador_maestro = ?
        AND T.cod_situacion= ? 
        
        $WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
        ";

        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1,$ID_EMPLEADOR_MAESTRO);
            $stm->bindValue(2, $ESTADO);
            $stm->execute();
            $lista = $stm->fetchAll();

            if (count($lista) == 0) {
                return false;
            }

            return $lista;
        } catch (PDOException $e) {

            throw $e;
        }
    }

    public function cantidadTrabajador($ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE) {//OK
        $query = "
        SELECT COUNT(*) AS numfilas
        FROM trabajadores AS t
        
        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona
        
        INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador

        WHERE em.id_empleador_maestro = ?
        AND t.estado = ?        
        -- $WHERE
            ;";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$ID_EMPLEADOR_MAESTRO);
        $stm->bindValue(2, $ESTADO);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista[0]["numfilas"];
        $stm = null;
    }

    public function Baja($id) {
        //DELETE FROM empleadores WHERE id_empleador = ?

        $query = "		
		UPDATE trabajadores 
		SET estado = ?
		WHERE id_trabajador = ?
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, 'BSI');
        $stm->bindValue(2, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function Alta($id) {

        $query = "		
		UPDATE trabajadores  
		SET estado = ?
		WHERE id_trabajador = ?
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, "ASI");
        $stm->bindValue(2, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    /**
     *
     *  OK FULLL "Categoria Trabajador.php"
     */
    public function buscaTrabajadorPorIdPersona($id_persona) {//OK
        $query = "
        SELECT *FROM trabajadores AS t

        INNER JOIN situaciones AS s
        ON t.cod_situacion = s.cod_situacion
        WHERE id_persona = ? 
        -- AND t.cod_situacion = 1                    
        ";


        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];

    }

    //-----------------------------E4---------------------------------------
    public function listarTrabajadoresSoloID_E4($ID_EMPLEADOR_MAESTRO) {
        $query = "
        SELECT 
        t.id_trabajador
          
        FROM personas AS p
        INNER JOIN empleadores AS emp
        ON p.id_empleador = emp.id_empleador
		
        INNER JOIN empleadores_maestros AS em
        ON emp.id_empleador = em.id_empleador
        
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona
	
	WHERE (p.cod_tipo_documento='01' OR p.cod_tipo_documento='04' OR p.cod_tipo_documento='07' OR p.cod_tipo_documento='11')
        AND em.id_empleador_maestro = ?
        AND t.cod_situacion = 1
        -- AND t.estado = 'ACTIVO';             
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_EMPLEADOR_MAESTRO);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    // OJO USADO EN REPORTES
    public function buscarTrabajadorPorId_E4($id_trabajador) {

        //$query ='SELECT *FROM personas WHERE id_persona = ?';
        $query = "
        SELECT 
        -- t.id_trabajador,
        p.id_persona,
        p.id_empleador,
        p.cod_pais_emisor_documento,
        p.cod_tipo_documento,
        p.cod_nacionalidad,
        p.num_documento,
        p.fecha_nacimiento,
        p.apellido_paterno,
        p.apellido_materno,
        p.nombres,
        p.sexo,
        p.id_estado_civil,
        p.cod_telefono_codigo_nacional,
        p.telefono,
        p.correo,
        p.estado,
        td.descripcion_abreviada,
        CONCAT(td.descripcion_abreviada,'-',  p.num_documento) AS documento

        FROM personas p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona
		
		INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador
        
        INNER JOIN tipos_documentos AS td
        ON td.cod_tipo_documento = p.cod_tipo_documento         

        WHERE (p.cod_tipo_documento='01' OR p.cod_tipo_documento='04' OR p.cod_tipo_documento='07' OR p.cod_tipo_documento='11')
        
        AND t.id_trabajador = ?
        AND t.cod_situacion = 1
        -- AND t.estado = 'ACTIVO';
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";

        return $data[0];
    }

    //------------------------------E5----------------------------------------
    // OJO usado en Reporte buscarTrabajadorPorId_E4
    //antes   ; ListarPersonasSoloID_E5
    public function listarTrabajadoresSoloID_E5($ID_EMPLEADOR_MAESTRO) {
        $query = "
        SELECT
        t.id_trabajador    
        FROM personas AS p      

        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona		
        
        INNER JOIN empleadores AS emp
        ON p.id_empleador = emp.id_empleador
		
        INNER JOIN empleadores_maestros AS em
        ON emp.id_empleador = em.id_empleador
        
	
	WHERE (p.cod_tipo_documento='01' OR p.cod_tipo_documento='04' OR p.cod_tipo_documento='07')
        AND em.id_empleador_maestro = ?
        AND t.cod_situacion = 1        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_EMPLEADOR_MAESTRO);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    // OJO usado en Reporte


    public function buscarTrabajadorPorId_E5($id_trabajador) {

        $query = "
        SELECT 
        -- datos prestador de servicio
        p.id_persona,
        p.cod_tipo_documento,
        p.num_documento,
        p.cod_pais_emisor_documento,
        t.cod_regimen_laboral,
        t.cod_nivel_educativo,
        t.cod_ocupacion_p, -- S. Privado
        t.discapacitado,
        drp.cuspp,
        -- SCTR Pension = 0
        t.cod_tipo_contrato,
        t.jornada_laboral, -- array
        t.sindicalizado,
        t.cod_periodo_remuneracion,
        t.monto_remuneracion,
        t.cod_situacion,
        t.percibe_renta_5ta_exonerada,
        t.situacion_especial,
        t.cod_tipo_pago,
        t.cod_categorias_ocupacionales,
        t.cod_convenio
        -- ruca CAS
        
        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN detalle_regimenes_pensionarios  AS drp
        ON t.id_trabajador = drp.id_trabajador
        -- Inico IDE Empleador
        INNER JOIN empleadores AS emp
        ON p.id_empleador = emp.id_empleador
        INNER JOIN empleadores_maestros AS em
        ON emp.id_empleador = em.id_empleador
        -- Final IDE Empleador
        -- WHERE em.id_empleador_maestro = 1
        WHERE t.id_trabajador = ?
        AND t.cod_situacion =1        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";

        return $data[0];
    }

    //------------------------------E6----------------------------------------
    //
    //
    //
    //-----------------------------E11---------------------------------------
    // OJO usado en Reporte
    /**
     * observacion :
     * 01: DNI; 04: Carné de extranjería;
     * 07: Pasaporte y 11 Partida de nacimiento. ='XQ = personal en formacion'
     */
    public function listarTrabajadoresSoloID_E11($ID_EMPLEADOR_MAESTRO) {
        $query = "
        SELECT 
        t.id_trabajador
          
        FROM personas AS p
        INNER JOIN empleadores AS emp
        ON p.id_empleador = emp.id_empleador
		
        INNER JOIN empleadores_maestros AS em
        ON emp.id_empleador = em.id_empleador
		
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona
	
	WHERE (p.cod_tipo_documento='01' OR p.cod_tipo_documento='04' OR p.cod_tipo_documento='07' OR p.cod_tipo_documento='11')
        AND em.id_empleador_maestro = ?
        AND t.cod_situacion = 1
        -- AND t.estado = 'ACTIVO'
        -- order by t.id_trabajador desc
        -- LIMIT 1
              
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_EMPLEADOR_MAESTRO);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

//---
// SOLO TRABAJADORES  
// Categoria = 1

    /**
     * TIPO DE PERIODO
     * 1: Período: De vínculo (trabajador),
     *  de pensionista, de formación (Personal en Formación modalidad formativa 
     */
    public function buscarTrabajadorPorID_tipo_registro_1_E11($id_trabajador) {
        $query = "
        SELECT 
        -- datos prestador de servicio
	t.id_trabajador,
        p.id_persona,
        p.cod_tipo_documento,
        p.num_documento,
        p.cod_pais_emisor_documento,
        -- 
	-- dpl.id_detalle_periodo_laboral,
        DATE_FORMAT(dpl.fecha_inicio, '%d/%m/%Y') AS fecha_inicio,
        DATE_FORMAT(dpl.fecha_fin, '%d/%m/%Y') AS fecha_fin,
	dpl.cod_motivo_baja_registro
        
        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN detalle_periodos_laborales  AS dpl
        ON t.id_trabajador = dpl.id_trabajador
        
        WHERE t.id_trabajador = ?
       ;
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";

        return $data[0];
    }

    /**
     *   tipo Trabajador E 11
     *   2 = Tipo Trabajador
     */
    public function buscarTrabajadorPorID_tipo_registro_2_E11($id_trabajador) {

        $query = "
        SELECT 
	t.id_trabajador,
        p.id_persona,
        p.cod_tipo_documento,
        p.num_documento,
        p.cod_pais_emisor_documento,
        DATE_FORMAT(dtt.fecha_inicio, '%d/%m/%Y') AS fecha_inicio,
        DATE_FORMAT(dtt.fecha_fin, '%d/%m/%Y') AS fecha_fin,
	dtt.cod_tipo_trabajador
        
        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN detalle_tipos_trabajadores  AS dtt
        ON t.id_trabajador = dtt.id_trabajador
        
        WHERE t.id_trabajador = ?         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();

        return $data[0];
    }

    /**
     *   3: Régimen de Aseguramiento de Salud. E 11
     */
    public function buscarTrabajadorPorID_tipo_registro_3_E11($id_trabajador) {
        $query = "
        SELECT 
	t.id_trabajador,
        p.id_persona,
        p.cod_tipo_documento,
        p.num_documento,
        p.cod_pais_emisor_documento,
        DATE_FORMAT(drs.fecha_inicio, '%d/%m/%Y') AS fecha_inicio,
        DATE_FORMAT(drs.fecha_fin, '%d/%m/%Y') AS fecha_fin,
	drs.cod_regimen_aseguramiento_salud,
        IF(drs.cod_eps=0,'',drs.cod_eps) AS cod_eps
        	
        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN detalle_regimenes_salud  AS drs
        ON t.id_trabajador = drs.id_trabajador
        
        WHERE t.id_trabajador = ?        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();

        return $data[0];
    }

    /**
     *   4: Régimen pensionario. E 11
     */
    public function buscarTrabajadorPorID_tipo_registro_4_E11($id_trabajador) {
        $query = "
        SELECT 
        -- datos prestador de servicio
	t.id_trabajador,
        p.id_persona,
        p.cod_tipo_documento,
        p.num_documento,
        p.cod_pais_emisor_documento,
        -- 
	-- dpl.id_detalle_periodo_laboral,
        DATE_FORMAT(drp.fecha_inicio, '%d/%m/%Y') AS fecha_inicio,
        DATE_FORMAT(drp.fecha_fin, '%d/%m/%Y') AS fecha_fin,        
	drp.cod_regimen_pensionario
        
        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN detalle_regimenes_pensionarios  AS drp
        ON t.id_trabajador = drp.id_trabajador
        
        WHERE t.id_trabajador = ?      
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();

        return $data[0];
    }

    //-----------------------------E17---------------------------------------

    public function listarTrabajadoresSoloID_E17($ID_EMPLEADOR_MAESTRO) {
        $query = "
        SELECT 
        t.id_trabajador
          
        FROM personas AS p
        INNER JOIN empleadores AS emp
        ON p.id_empleador = emp.id_empleador
		
        INNER JOIN empleadores_maestros AS em
        ON emp.id_empleador = em.id_empleador
		
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona
	
		WHERE (p.cod_tipo_documento='01' OR p.cod_tipo_documento='04' OR p.cod_tipo_documento='07')
        AND em.id_empleador_maestro = ?
        AND t.cod_situacion = 1
        AND t.estado = 'ACTIVO'
        -- order by t.id_trabajador desc
        -- LIMIT 1
              
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_EMPLEADOR_MAESTRO);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    public function buscarTrabajadorPorId_E17($id_trabajador) {
        $query = "
        SELECT 
        t.id_trabajador,
        p.id_persona,
        p.cod_tipo_documento,
        p.num_documento,
        p.cod_pais_emisor_documento,
        -- detalle establecimiento
        dest.id_detalle_establecimiento,
        est.cod_establecimiento,
        -- empleador ruc
        emp.id_empleador,
        emp.ruc,
        emp.razon_social

        FROM personas AS p
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        INNER JOIN detalle_establecimiento AS dest
        ON dest.id_trabajador = t.id_trabajador

        INNER JOIN establecimientos AS est
        ON dest.id_establecimiento = est.id_establecimiento

        INNER JOIN empleadores AS emp
        ON est.id_empleador = emp.id_empleador

        WHERE t.id_trabajador = ?         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0];
    }

//------------------------------------------------------------------------------
//---
// SOLO PENSIONISTAS  
// Categoria = 2
//------------------------------------------------------------------------------    
//---
// SOLO PERSONAL DE TERCEROS  
// Categoria = 4
//------------------------------------------------------------------------------    
//---
// SOLO Personal en Formación-modalidad formativa laboral.
// Categoria = 5
//------------------------------------------------------------------------------    
}

//End Class
?>