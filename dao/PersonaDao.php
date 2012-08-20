<?php

class PersonaDao extends AbstractDao {

//END FUNCION

    public function registrarPersona($obj_persona) {

        $per = new Persona();
        $per = $obj_persona;

        try {
            $query = "
        INSERT INTO personas
                    (
                    id_empleador,
                    cod_pais_emisor_documento,
                    cod_tipo_documento,
                    cod_nacionalidad,
                    num_documento,
                    fecha_nacimiento,
                    apellido_paterno,
                    apellido_materno,
                    nombres,
                    sexo,
                    cod_telefono_codigo_nacional,
                    telefono,
                    id_estado_civil,
                    correo,
                    tabla_trabajador,
                    tabla_pensionista,
                    tabla_personal_formacion_laboral,
                    tabla_personal_terceros,
                    estado,
                    fecha_creacion)
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
		?
                );
            ";
            //Inicia transaccion
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $per->getId_empleador());
            $stm->bindValue(2, $per->getCod_pais_emisor_documentos());
            $stm->bindValue(3, $per->getCod_tipo_documento());
            $stm->bindValue(4, $per->getCod_nacionalidad());
            $stm->bindValue(5, $per->getNum_documento());
            $stm->bindValue(6, $per->getFecha_nacimiento());
            $stm->bindValue(7, $per->getApellido_paterno());
            $stm->bindValue(8, $per->getApellido_materno());
            $stm->bindValue(9, $per->getNombres());
            $stm->bindValue(10, $per->getSexo());
            $stm->bindvalue(11, $per->getCod_telefono_codigo_nacional());
            $stm->bindvalue(12, $per->getTelefono());
            $stm->bindvalue(13, $per->getId_estado_civil());
            $stm->bindvalue(14, $per->getCorreo());
            $stm->bindvalue(15, $per->getTabla_trabajador());
            $stm->bindvalue(16, $per->getTabla_pensionista());
            $stm->bindvalue(17, $per->getTabla_personal_formacion_laboral());
            $stm->bindvalue(18, $per->getTabla_personal_terceros());
            $stm->bindvalue(19, $per->getEstado());
            $stm->bindvalue(20, $per->getFecha_creacion());

            $stm->execute();

            // id Persona
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

    //
    public function actualizarPersona($obj_persona) {

        $com = new Persona();
        $com = $obj_persona;


        $query = "
          UPDATE personas
          SET
            cod_pais_emisor_documento = ?,
            cod_tipo_documento = ?,
            cod_nacionalidad = ?,
            num_documento = ?,
            fecha_nacimiento = ?,
            apellido_paterno = ?,
            apellido_materno = ?,
            nombres = ?,
            sexo = ?,
            id_estado_civil = ?,
            cod_telefono_codigo_nacional = ?,
            telefono = ?,
            correo = ?,
            fecha_modificacion = ?
          WHERE id_persona = ?;
          ";


        //Inicia transaccion
        $stm = $this->pdo->prepare($query);

        $stm->bindValue(1, $com->getCod_pais_emisor_documentos());
        $stm->bindValue(2, $com->getCod_tipo_documento());
        $stm->bindValue(3, $com->getCod_nacionalidad());
        $stm->bindValue(4, $com->getNum_documento());
        $stm->bindValue(5, $com->getFecha_nacimiento());
        $stm->bindValue(6, $com->getApellido_paterno());
        $stm->bindValue(7, $com->getApellido_materno());
        $stm->bindValue(8, $com->getNombres());
        $stm->bindValue(9, $com->getSexo());
        $stm->bindValue(10, $com->getId_estado_civil());
        $stm->bindValue(11, $com->getCod_telefono_codigo_nacional());
        $stm->bindValue(12, $com->getTelefono());
        $stm->bindValue(13, $com->getCorreo());
        $stm->bindValue(14, $com->getFecha_modificacion());
        $stm->bindValue(15, $com->getId_persona());

        $stm->execute();

        //echo "<br>".$query;
        //$stm = null;

        return true;
    }

    public function cantidadPesonas($estado, $ID_EMPLEADOR_MAESTRO, $WHERE) {//OK
        $query = "
        SELECT 
                COUNT(*) AS numfilas
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
        AND t.cod_situacion = ?
            -- $WHERE ";

        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1,$ID_EMPLEADOR_MAESTRO);
            $stm->bindValue(2,$estado);

            $stm->execute();

            $lista = $stm->fetchAll();

            return $lista[0]["numfilas"];

            $stm = null;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function listarPersonas($estado, $ID_EMPLEADOR_MAESTRO, $WHERE, $start, $limit, $sidx, $sord) {
        $query = "
	SELECT 
		p.id_persona,
                t.id_trabajador,
		td.descripcion_abreviada AS nombre_tipo_documento,
		p.num_documento,
		p.apellido_paterno,
		p.apellido_materno,
		p.nombres,
		p.fecha_nacimiento,
		p.sexo,
		t.cod_situacion,
		s.descripcion_abreviada AS estado,
		
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
        AND t.cod_situacion = ?
        
       -- WHERE p.estado='ACTIVO'
        
        $WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
        ";

        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1,$ID_EMPLEADOR_MAESTRO);
            $stm->bindValue(2,$estado);
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

    public function eliminarPersona($id) {

        $query = "
        DELETE
        FROM personas
        WHERE id_persona = ?;
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();

        return true;
    }

    /**
     *   -----------------------------------------------------------------------------------------
     * 	FUNCIONES COMBO_BOX
     * 	-----------------------------------------------------------------------------------------
     * */

    /**
     * tabla  tipos_documentos
     *
     */
    public function comboTipoDocumento() {

        $query = "SELECT *FROM tipos_documentos ORDER BY cod_tipo_documento ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function existePersonaRegistrada($id_empleador,$num_doc,$cod_tipo_documento) {
        $query = "
        SELECT 
        id_persona,
        cod_tipo_documento,
        num_documento        
        
        FROM personas AS p
        INNER JOIN empleadores AS e
        ON p.id_empleador = e.id_empleador
	
	WHERE (e.id_empleador = ? 
	AND p.num_documento = ? 
	AND p.cod_tipo_documento = ? )
        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $num_doc);
        $stm->bindValue(3, $cod_tipo_documento);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        
        return $lista[0]['id_persona'];
    }
    //VER SI ES USADO ???
    public function listarTrabajadoresPor_ID_Persona($id_persona){
        
        $query ="
        SELECT 
        t.id_trabajador,
        t.cod_situacion,
        dpl.fecha_inicio,
        dpl.fecha_fin
        
        FROM personas AS p	
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona
        
        INNER JOIN detalle_periodos_laborales AS dpl
	ON t.id_trabajador = dpl.id_trabajador
	
	WHERE p.id_persona = ?

                
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
       // $stm->bindValue(2, $mes_inicio);
       // $stm->bindValue(3, $mes_fin);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        
        return $lista;
        
    }
    
    
    
    

    // OJO USADO EN REPORTES
    public function buscarPersonaPorId($id_persona) {

        //$query ='SELECT *FROM personas WHERE id_persona = ?';
        $query = "
        SELECT 
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
        INNER JOIN tipos_documentos AS td
        ON td.cod_tipo_documento = p.cod_tipo_documento 	
        WHERE p.id_persona = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";

        return $data[0];
    }

    /**
     * UTILIZADO EN REPORTES 
     * planilla_sunat/controller/controller Estructura_01TrabajadorController.php
     * 
     * */
    public function listarPersonasSoloID($id_empleador_maestro) {
        $query = "
        SELECT 
        p.id_persona
        FROM personas AS p
        INNER JOIN empleadores AS emp
        ON p.id_empleador = emp.id_empleador
        INNER JOIN empleadores_maestros AS em
        ON emp.id_empleador = em.id_empleador

        WHERE em.id_empleador_maestro = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    // OJO usado en Reporte
    public function ListarPersonasSoloID_E5($id_empleador_maestro) {
        $query = "
        SELECT 
        p.id_persona   
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
        AND t.estado = 'ACTIVO'";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

}

//End Class
?>