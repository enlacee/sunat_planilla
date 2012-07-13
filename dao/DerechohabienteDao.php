<?php

class DerechohabienteDao extends AbstractDao {

//END FUNCION
//Registrar Producto and Imagen
    public function registrarDerechohabiente($obj_derecho_habiente) {

        $em = new Derechohabiente();
        $em = $obj_derecho_habiente;

        try {
            $query = "
            INSERT INTO derechohabientes
            (
             id_persona,
             cod_tipo_documento,
             cod_pais_emisor_documento,
             cod_vinculo_familiar,
             cod_documento_vinculo_familiar,
             num_documento,
             fecha_nacimiento,
             apellido_paterno,
             apellido_materno,
             nombres,
             sexo,
             id_estado_civil,
             vf_num_documento,
             vf_mes_concepcion,
             cod_telefono_codigo_nacional,
             telefono,
             correo,
             cod_motivo_baja_derechohabiente,
             cod_situacion,
             estado,
             fecha_creacion
             )
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
        ?,
        ?
        );
";
            //Inicia transaccion
            $this->pdo->beginTransaction();

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $em->getId_persona());
            $stm->bindValue(2, $em->getCod_tipo_documento());
            $stm->bindValue(3, $em->getCod_pais_emisor_documentos());
            $stm->bindValue(4, $em->getCod_vinculo_familiar());
            $stm->bindValue(5, $em->getCod_documento_vinculo_familiar());
            $stm->bindValue(6, $em->getNum_documento());
            $stm->bindValue(7, $em->getFecha_nacimiento());
            $stm->bindValue(8, $em->getApellido_paterno());
            $stm->bindValue(9, $em->getApellido_materno());
            $stm->bindValue(10, $em->getNombres());
            $stm->bindvalue(11, $em->getSexo());
            $stm->bindvalue(12, $em->getId_estado_civil());
            $stm->bindvalue(13, $em->getVf_num_documento());
            $stm->bindvalue(14, $em->getVf_mes_concepcion());
            $stm->bindvalue(15, $em->getCod_telefono_codigo_nacional());
            $stm->bindvalue(16, $em->getTelefono());
            $stm->bindvalue(17, $em->getCorreo());
            $stm->bindvalue(18, $em->getCod_motivo_baja_derechohabiente());
            $stm->bindvalue(19, $em->getCod_situacion());
            $stm->bindvalue(20, $em->getEstado());
            $stm->bindvalue(21, $em->getFecha_creacion());
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

    //
    public function actualizar($obj_derechohabiente) {

        $com = new Derechohabiente();
        $com = $obj_derechohabiente;

        $query = "
        UPDATE derechohabientes
        SET 
        id_persona = ?,
        cod_tipo_documento = ?,
        num_documento = ?,
        cod_pais_emisor_documento = ?,
        fecha_nacimiento = ?,
        apellido_paterno = ?,
        apellido_materno = ?,
        nombres = ?,
        sexo = ?,
        id_estado_civil = ?,
        cod_vinculo_familiar = ?,
        cod_documento_vinculo_familiar = ?,
        vf_num_documento = ?,
        vf_mes_concepcion = ?,
        cod_telefono_codigo_nacional = ?,
        telefono = ?,
        correo = ?        
        WHERE id_derechohabiente = ?;
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $com->getId_persona());
        $stm->bindValue(2, $com->getCod_tipo_documento());
        $stm->bindValue(3, $com->getNum_documento());
        $stm->bindValue(4, $com->getCod_pais_emisor_documentos());
        $stm->bindValue(5, $com->getFecha_nacimiento());
        $stm->bindValue(6, $com->getApellido_paterno());
        $stm->bindValue(7, $com->getApellido_materno());
        $stm->bindValue(8, $com->getNombres());
        $stm->bindValue(9, $com->getSexo());
        $stm->bindValue(10, $com->getId_estado_civil());
        $stm->bindValue(11, $com->getCod_vinculo_familiar());
        $stm->bindValue(12, $com->getCod_documento_vinculo_familiar());
        $stm->bindValue(13, $com->getVf_num_documento());
        $stm->bindValue(14, $com->getVf_mes_concepcion());
        $stm->bindValue(15, $com->getCod_telefono_codigo_nacional());
        $stm->bindValue(16, $com->getTelefono());
        $stm->bindValue(17, $com->getCorreo());
        $stm->bindValue(18, $com->getId_derechohabiente());

        $stm->execute();
        $stm = null;

        return true;
    }

    public function cantidadDH($id_persona, $WHERE) {//OK
        $query = "
		SELECT 
		
		COUNT(d.id_derechohabiente) AS numfilas
		
		FROM derechohabientes AS d	
		
		WHERE d.id_persona = ?	
		
		$WHERE 
	  ";

        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_persona);
            $stm->execute();

            $lista = $stm->fetchAll();

            return $lista[0]["numfilas"];

            $stm = null;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function listarDH($id_persona, $WHERE, $start, $limit, $sidx, $sord) {

        $query = "		
		SELECT 		
		d.id_derechohabiente,
		d.id_persona,
		td.descripcion_abreviada AS nombre_documento,
		d.num_documento,
		d.apellido_paterno,
		d.apellido_materno,
		d.nombres,
		d.fecha_nacimiento,
		vf.descripcion_abreviada AS nombre_vinculo_familiar,
		s.cod_situacion,
		s.descripcion_abreviada AS nombre_situacion
		
		FROM derechohabientes AS d
		INNER JOIN tipos_documentos AS td
		ON d.cod_tipo_documento = td.cod_tipo_documento
		
		INNER JOIN vinculos_familiares AS vf
		ON d.cod_vinculo_familiar = vf.cod_vinculo_familiar
		
		INNER JOIN situaciones AS s
		ON d.cod_situacion = s.cod_situacion		
		
		WHERE d.id_persona = ?
				
		 $WHERE  
		
		ORDER BY $sidx $sord LIMIT $start,  $limit
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();

        if (count($lista) == 0) {
            return false;
        }

        return $lista;
    }

    //---
    public function buscarDerechohabientePorId($id) {


        $query = "
        SELECT
            id_derechohabiente,
            id_persona,
            cod_tipo_documento,
            cod_pais_emisor_documento,
            cod_vinculo_familiar,
            cod_documento_vinculo_familiar,
            num_documento,
            fecha_nacimiento,
            apellido_paterno,
            apellido_materno,
            nombres,
            sexo,
            id_estado_civil,
            vf_num_documento,
            vf_mes_concepcion,
            cod_telefono_codigo_nacional,
            telefono,
            correo,
            cod_motivo_baja_derechohabiente,
            cod_situacion,
            estado,
            fecha_baja
        FROM derechohabientes
        WHERE id_derechohabiente = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";

        return $data[0];
    }

    public function eliminaDH($id) {

        $query = "DELETE FROM derechohabientes WHERE id_derechohabiente = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }
	
	public function bajaDH($id) {
        $query = "
		UPDATE derechohabientes
		SET cod_situacion = 0		
		WHERE id_derechohabiente = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }
//----------------------------------------------------

	public function autocomplete($id_empleador,$term){
		
        $query = "
		SELECT 
			p.id_persona AS id,
			p.apellido_paterno,
			p.apellido_materno,
			p.nombres,
			td.descripcion_abreviada,
			CONCAT(td.descripcion_abreviada,'-',p.num_documento,'',p.apellido_paterno,' ',p.apellido_materno) AS label,
			p.num_documento AS value		
		FROM personas AS p
		INNER JOIN tipos_documentos AS td
		ON p.cod_tipo_documento = td.cod_tipo_documento
		
		WHERE  p.num_documento   LIKE '%$term%'
		AND p.id_empleador = ?
		
		";
		//-- AND p.cod_tipo_documento =?
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$id_empleador);
		//$stm->bindValue(2,$tipodoc);
        $stm->execute();
        $data = $stm->fetchAll();
        $stm = null;
        return $data;
	
	}
        
        
        
        public function buscarDerechoHabienteNumDocumento($tipodoc, $id_empleador_maestro, $num_documento ){
            
            $query = "
            SELECT 
            p.num_documento,
            p.id_persona,
            p.id_empleador

            FROM  personas AS p

            INNER JOIN empleadores_maestros AS em
            ON p.id_empleador = em.id_empleador

             WHERE p.num_documento = ?
            AND p.cod_tipo_documento = ?
            AND em.id_empleador_maestro = ?
            ";
            
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1,$num_documento);
            $stm->bindValue(2,$tipodoc);
            $stm->bindValue(3,$id_empleador_maestro);
            $stm->execute();
            $data = $stm->fetchAll();
            $stm = null;
            
            return $data[0];
            
        }
	
	

}

//End Class
?>