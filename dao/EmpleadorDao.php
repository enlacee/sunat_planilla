<?php

class EmpleadorDao extends AbstractDao {

//END FUNCION
//Registrar Producto and Imagen
    public function registrarEmpleador($obj_empleador) {

        try {
            $query = "
			INSERT INTO empleadores
				( 
				  id_tipo_empleador ,
				  cod_telefono_codigo_nacional ,
				  ruc ,
				  razon_social ,
				  id_tipo_sociedad_comercial ,
				  nombre_comercial ,
				  cod_tipo_actividad ,
				  telefono ,
				  correo ,
				  empresa_dedica ,
				  senati ,
				  remype ,
				  remype_tipo_empresa,
				  trabajador_sin_rp ,
				  actividad_riesgo_sctr ,
				  trabajadores_sctr ,
				  persona_discapacidad ,
				  agencia_empleo ,
				  desplaza_personal ,
				  terceros_desplaza_usted ,
				  estado_empleador ,
				  fecha_creacion )
			VALUES (
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ? ,
			 ?);
			";
            //Inicia transaccion 

            $em = new Empleador();
            $em = $obj_empleador;


            $this->pdo->beginTransaction();

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $em->getId_tipo_empleador());
            $stm->bindValue(2, $em->getCod_telefono_codigo_nacional());
            $stm->bindValue(3, $em->getRuc());
            $stm->bindValue(4, $em->getRazon_social());
            $stm->bindValue(5, $em->getId_tipo_sociedad_comercial());

            $stm->bindValue(6, $em->getNombre_comercial());
            $stm->bindValue(7, $em->getCod_tipo_actividad());
            $stm->bindValue(8, $em->getTelefono());
            $stm->bindValue(9, $em->getCorreo());
            $stm->bindValue(10, $em->getEmpresa_dedica());

            $stm->bindValue(11, $em->getSenati());
            $stm->bindValue(12, $em->getRemype());
            $stm->bindValue(13, $em->getRemype_tipo_empresa());
            $stm->bindvalue(14, $em->getTrabajador_sin_rp());
            $stm->bindvalue(15, $em->getactividad_riesgo_sctr());
            $stm->bindvalue(16, $em->getTrabajadores_sctr());
            $stm->bindvalue(17, $em->getPersona_discapacidad());

            $stm->bindvalue(18, $em->getAgencia_empleo());
            $stm->bindvalue(19, $em->getDesplaza_personal());
            $stm->bindvalue(20, $em->getTerceros_desplaza_usted());
            $stm->bindValue(21, $em->getEstado_empleador());
            $stm->bindValue(22, $em->getFecha_creacion());

            $stm->execute();

            // id Comerico
            /*            $query2 = "select last_insert_id() as id";
              $stm = $this->pdo->prepare($query2);
              $stm->execute();
              $lista = $stm->fetchAll();
             */
            $this->pdo->commit();
            //finaliza transaccion
            return true;
            //return $lista[0]['id'];
            $stm = null;
        } catch (Exception $e) {
            //  Util::rigistrarLog( $e, $query );
            $this->pdo->rollBack();
            throw $e;
        }
    }

    //
    public function actualizarEmpleador($obj_empleador) {

        $com = new Empleador();
        $com = $obj_empleador;


        $query = "
		UPDATE empleadores
		SET
		  id_tipo_empleador = ?,
		  cod_telefono_codigo_nacional = ?,
		  ruc = ?,
		  razon_social = ?,
		  id_tipo_sociedad_comercial = ?,
		  nombre_comercial = ?,
		  cod_tipo_actividad = ?,
		  telefono = ?,
		  correo = ?,
		  empresa_dedica = ?,
		  senati = ?,
		  remype = ?,
		  remype_tipo_empresa =?,
		  trabajador_sin_rp = ?,
		  actividad_riesgo_sctr = ?,
		  trabajadores_sctr = ?,
		  persona_discapacidad = ?,
		  agencia_empleo = ?,
		  desplaza_personal = ?,
		  terceros_desplaza_usted = ?
		WHERE id_empleador = ?;
		";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $com->getId_tipo_empleador());
        $stm->bindValue(2, $com->getCod_telefono_codigo_nacional());
        $stm->bindValue(3, $com->getRuc());
        $stm->bindValue(4, $com->getRazon_social());
        $stm->bindValue(5, $com->getId_tipo_sociedad_comercial());
        $stm->bindValue(6, $com->getNombre_comercial());
        $stm->bindValue(7, $com->getCod_tipo_actividad());
        $stm->bindValue(8, $com->getTelefono());
        $stm->bindValue(9, $com->getCorreo());
        $stm->bindValue(10, $com->getEmpresa_dedica());
        $stm->bindValue(11, $com->getSenati());
        $stm->bindValue(12, $com->getRemype());
        $stm->bindValue(13, $com->getRemype_tipo_empresa());
        $stm->bindValue(14, $com->getTrabajador_sin_rp());
        $stm->bindValue(15, $com->getActividad_riesgo_sctr());
        $stm->bindValue(16, $com->getTrabajadores_sctr());
        $stm->bindValue(17, $com->getPersona_discapacidad());
        $stm->bindValue(18, $com->getAgencia_empleo());
        $stm->bindValue(19, $com->getDesplaza_personal());
        $stm->bindValue(20, $com->getTerceros_desplaza_usted());
        $stm->bindValue(21, $com->getId_empleador());

        $stm->execute();
        $stm = null;

        return true;
    }

//
    public function cantidadEmpleadores() {//OK
        $query = "
            SELECT COUNT(*) AS numfilas
            FROM empleadores";

        try {

            $stm = $this->pdo->prepare($query);

            $stm->execute();

            $lista = $stm->fetchAll();

            return $lista[0]["numfilas"];

            $stm = null;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function eliminarEmpleador($id) {
        //DELETE FROM empleadores WHERE id_empleador = ?

        $query = "		
		UPDATE empleadores AS 
		SET estado_empleador = ?
		WHERE id_empleador = ?
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, 'INACTIVO');
        $stm->bindValue(2, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function listarEmpleadores($WHERE, $start, $limit, $sidx, $sord) {

        // $query = "SELECT *FROM empleadores $WHERE  ORDER BY $sidx $sord LIMIT $start $limit";

        $query = "
        SELECT 
        e.id_empleador,
        e.ruc,
        e.razon_social,
        e.nombre_comercial,
        e.telefono,
        t_e.descripcion AS nombre_tipo_empleador,
		-- em.id_empleador,
        IF(e.id_empleador = em.id_empleador,'MAESTRO','OTRO') AS tipo
	
        FROM empleadores AS e

        INNER JOIN tipos_empleadores AS t_e
        ON e.id_tipo_empleador = t_e.id_tipo_empleador 
        
        LEFT JOIN empleadores_maestros AS em
        ON e.id_empleador = em.id_empleador
        WHERE e.estado_empleador = ?
		
        $WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
        ";
        // echo $query;
        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, 'ACTIVO');
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

    public function buscaEmpleadorPorRuc($ruc) {//OK
        $query = "
	SELECT 
	e.id_empleador,
	e.id_tipo_empleador,
	e.ruc,
	e.razon_social,
	e.id_tipo_sociedad_comercial,
	e.nombre_comercial,
	e.cod_tipo_actividad,
	e.telefono,
	e.correo,
	e.empresa_dedica,
	e.senati,
	e.remype,
	e.remype_tipo_empresa,
	e.trabajador_sin_rp,
	e.actividad_riesgo_sctr,
	e.trabajadores_sctr,
	e.persona_discapacidad,
	e.agencia_empleo,
	e.desplaza_personal,
	e.terceros_desplaza_usted,
	CONCAT(e.razon_social,' ',tsc.descripcion_abreviada) AS razon_social_concatenado
	FROM empleadores AS e
	INNER JOIN tipos_sociedades_comerciales AS tsc
	ON e.id_tipo_sociedad_comercial = tsc.id_tipo_sociedad_comercial

	WHERE ruc = ?		 
		";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ruc);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

    //no usa
    /*   public function buscaEmpleadorPorRuc2($ruc) {//OK
      $query = "
      SELECT
      e.id_empleador,
      e.ruc,
      CONCAT(e.razon_social,' ',tsc.descripcion_abreviada) AS razon_social_concatenado
      FROM empleadores AS e
      INNER JOIN tipos_sociedades_comerciales AS tsc
      ON e.id_tipo_sociedad_comercial = tsc.id_tipo_sociedad_comercial

      WHERE ruc = ?
      ";
      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $ruc);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      return $lista[0];
      }
     */
    //
    public function existeRucDuplicado($ruc) {
        $query = "SELECT *FROM empleadores WHERE ruc = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ruc);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        if (count($lista) >= 1) {
            return true;
        }
        return false;
    }

    /*
     * VISTA EDITAR EMPLEADOR
     * */

    /*
     * **********************************************************************
     * Funciones ALTERNAS para llenar el combo Empleador y su establecimiento
     * CategoriaEmpleadorController.php
     * ***********************************************************************
     */

    public function buscarEmpleadorPorId($id) {

        //$query ='SELECT *FROM personas WHERE id_persona = ?';
        $query = "SELECT *FROM empleadores WHERE id_empleador = ? ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();

        return $lista[0];
        $stm = null;
    }

    public function buscarEmpleadorPorId2($id) {

        //$query ='SELECT *FROM personas WHERE id_persona = ?';
        $query = "
        SELECT
        emp.id_empleador AS id,
        emp.ruc,
        -- emp.razon_social,
        -- tsc.descripcion_abreviada,
        CONCAT(emp.razon_social,' ',tsc.descripcion_abreviada,' ',emp.ruc) AS descripcion

        FROM empleadores AS emp
        INNER JOIN tipos_sociedades_comerciales AS tsc
        ON emp.id_tipo_sociedad_comercial = tsc.id_tipo_sociedad_comercial

        WHERE id_empleador= ?         
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();

        return $lista[0];
        $stm = null;
    }

    /*
     * -------- buscar ------- 00002
     */

    public function buscarEstablecimientoEmpleadorPorId($id_empleador) {

        $query = "
		SELECT 
		emp.id_empleador,
		est.id_establecimiento,
		est.cod_establecimiento,
		est.id_tipo_establecimiento,
		-- tipo establecimiento
		t_est.descripcion AS tipo_establecimiento_descripcion,
		-- inicio Direccion Lineal
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
		-- finall Direccion Lineal
		
		FROM empleadores AS emp
		INNER JOIN establecimientos AS est
		ON emp.id_empleador = est.id_empleador
		INNER JOIN tipos_establecimientos AS t_est
		ON est.id_tipo_establecimiento = t_est.id_tipo_establecimiento
		
		-- inicio Direccion Lineal
			INNER JOIN  establecimientos_direcciones AS esd
			ON est.id_establecimiento = esd.id_establecimiento
			
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
		
		-- finall Direccion Lineal	
		
		WHERE emp.id_empleador = ?

		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();

        return $lista;
        $stm = null;
    }

    public function existeEmpleadorBD($ruc) {

        $query = "SELECT  ruc FROM empleadores WHERE ruc = ? ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ruc);
        $stm->execute();
        $lista = $stm->fetchAll();

        $counteo = count($lista);

        $bandera = false;
        if ($counteo == 1) {
            $bandera = true;
        } else if ($counteo == 0) {
            $bandera = false;
        } else {
            $bandera = false; //Error Dulicado!
        }
//print_r("bandera ".$bandera);
        $stm = null;
        return $bandera;
    }

}

//End Class
?>