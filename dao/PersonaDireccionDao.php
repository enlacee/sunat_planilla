<?php

class PersonaDireccionDao extends AbstractDao {

//END FUNCION

    public function registrarPersonaDireccion($obj_per_direccion) {

        $per_d = new PersonaDireccion();
        $per_d = $obj_per_direccion;

        try {
            $query = "
			INSERT INTO personas_direcciones
			(
				cod_ubigeo_reniec,
				id_persona,
				cod_via,                                
				nombre_via,
                                numero_via,
				departamento,
				interior,
				manzana,
				lote,
				kilometro,
				block,
				etapa,
				cod_zona,
				nombre_zona,
				referencia,
				referente_essalud,
				estado_direccion)
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
				?);
			";

            //Inicia transaccion
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $per_d->getCod_ubigeo_reinec());
            $stm->bindValue(2, $per_d->getId_persona());
            $stm->bindValue(3, $per_d->getCod_via());
            $stm->bindValue(4, $per_d->getNombre_via());
            $stm->bindValue(5, $per_d->getNumero_via());
            $stm->bindValue(6, $per_d->getDepartamento());
            $stm->bindValue(7, $per_d->getInterior());
            $stm->bindValue(8, $per_d->getManzanza());
            $stm->bindValue(9, $per_d->getLote());
            $stm->bindvalue(10, $per_d->getKilometro());
            $stm->bindvalue(11, $per_d->getBlock());
            $stm->bindvalue(12, $per_d->getEstapa());
            $stm->bindvalue(13, $per_d->getCod_zona());
            $stm->bindvalue(14, $per_d->getNombre_zona());
            $stm->bindvalue(15, $per_d->getReferencia());
            $stm->bindvalue(16, $per_d->getReferente_essalud());
            $stm->bindvalue(17, $per_d->getEstado_direccion());

            $stm->execute();
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

    public function actualizarPersonaDireccion($obj_per_direccion) {

        $com = new PersonaDireccion();
        $com = $obj_per_direccion;


        $query = "
        UPDATE personas_direcciones
        SET         
        cod_ubigeo_reniec = ?,
        cod_via = ?,
        nombre_via = ?,
        numero_via = ?,
        departamento = ?,
        interior = ?,
        manzana = ?,
        lote = ?,
        kilometro = ?,
        block = ?,
        etapa = ?,
        cod_zona = ?,
        nombre_zona = ?,
        referencia = ?,
        referente_essalud = ?
        
        WHERE id_persona_direccion = ?; ";

        $stm = $this->pdo->prepare($query);

        $stm->bindValue(1, $com->getCod_ubigeo_reinec());
        $stm->bindValue(2, $com->getCod_via());
        $stm->bindValue(3, $com->getNombre_via());
        $stm->bindValue(4, $com->getNumero_via());
        $stm->bindValue(5, $com->getDepartamento());
        $stm->bindValue(6, $com->getInterior());
        $stm->bindValue(7, $com->getManzanza());
        $stm->bindValue(8, $com->getLote());
        $stm->bindValue(9, $com->getKilometro());
        $stm->bindValue(10, $com->getBlock());
        $stm->bindValue(11, $com->getEstapa());
        $stm->bindValue(12, $com->getCod_zona());
        $stm->bindValue(13, $com->getNombre_zona());
        $stm->bindValue(14, $com->getReferencia());
        $stm->bindValue(15, $com->getReferente_essalud());
        $stm->bindValue(16, $com->getId_persona_direccion());



        $stm->execute();
        $stm = null;

        return true;
    }

    public function actualizarEstadoReferenteEsalud($id_persona,$num_direccion, $estado_referencia) {
        $query = "

        UPDATE personas_direcciones
        SET           
          referente_essalud = ?
        WHERE (id_persona = ?)
        AND estado_direccion = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $estado_referencia);
        $stm->bindValue(2, $id_persona);        
        $stm->bindValue(3, $num_direccion);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function listarPersonaDirecciones($id_persona, $WHERE, $start, $limit, $sidx, $sord) {

        // $query = "SELECT *FROM personas $WHERE  ORDER BY $sidx $sord LIMIT $start $limit";

        $query = "
		SELECT
		p.id_persona,
		pd.id_persona_direccion,
		pd.cod_ubigeo_reniec,
		ud.descripcion AS ubigeo_departamento,
		up.descripcion AS ubigeo_provincia,
		ur.descripcion  AS ubigeo_distrito,
		pd.cod_via,
		v.descripcion AS ubigeo_nombre_via,
		pd.nombre_via,
		pd.cod_via,
		pd.numero_via,
		pd.departamento,
		pd.interior,
		pd.manzana,
		pd.lote,
		pd.kilometro,
		pd.block,
		pd.etapa,
		pd.cod_zona,
		z.descripcion AS ubigeo_nombre_zona,
		pd.nombre_zona,
		referencia,
		referente_essalud,
		estado_direccion
		
		FROM personas AS p
		INNER JOIN personas_direcciones AS  pd
		ON p.id_persona = pd.id_persona
		
		INNER JOIN vias AS v
		ON pd.cod_via = v.cod_via
		
		INNER JOIN ubigeo_reniec AS  ur
		ON pd.cod_ubigeo_reniec = ur.cod_ubigeo_reniec
		
		
		INNER JOIN ubigeo_provincias AS  up
		ON ur.cod_provincia = up.cod_provincia
		
		INNER JOIN ubigeo_departamentos AS ud
		ON ur.cod_departamento = ud.cod_departamento
		
		INNER JOIN zonas AS z
		ON pd.cod_zona = z.cod_zona
		
		WHERE p.id_persona = ?        
		$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
	 
        ";
        // echo $query;
        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_persona);
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

    public function cantidadPesonaDireccionesConIdPersona($id_persona) {//OK
        $query = "
		
		SELECT
		COUNT(p.id_persona) AS numfilas
		
		FROM personas AS p
		INNER JOIN personas_direcciones AS  pd
		ON p.id_persona = pd.id_persona
		
		INNER JOIN vias AS v
		ON pd.cod_via = v.cod_via
		
		INNER JOIN ubigeo_reniec AS  ur
		ON pd.cod_ubigeo_reniec = ur.cod_ubigeo_reniec
		
		INNER JOIN zonas AS z
		ON pd.cod_zona = z.cod_zona
		
		WHERE pd.id_persona = ?
			
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

    public function buscarPersonaDireccionPorId($id) {
        $query = "SELECT *FROM personas_direcciones WHERE id_persona_direccion = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

    /**
     * UTILIZADO EN REPORTES 
     * planilla_sunat/controller/controller Estructura_01TrabajadorController.php
     * 
     * */
    public function buscarPersonaDireccionEstructura_01($id_persona, $num) {

        $query = "
        SELECT 
        p.id_persona,
        -- p.num_documento,
        -- inicio Direccion Lineal
		IF( pd.cod_ubigeo_reniec = '0','',pd.cod_ubigeo_reniec ) AS cod_ubigeo_reniec,
                ud.descripcion AS ubigeo_departamento,
                up.descripcion AS ubigeo_provincia,
                ur.descripcion  AS ubigeo_distrito,
                v.descripcion AS ubigeo_nombre_via,	
                pd.nombre_via,
                IF( pd.cod_via = '0','',pd.cod_via ) AS cod_via,
                pd.numero_via,
                pd.departamento,
                pd.interior,
                pd.manzana,
                pd.lote,
                pd.kilometro,
                pd.block,
                pd.etapa,
                IF( pd.cod_zona = '0','',pd.cod_zona ) AS cod_zona,
                z.descripcion AS ubigeo_nombre_zona,
                pd.nombre_zona,
                pd.referencia,
                pd.referente_essalud
        -- finall Direccion Lineal

        FROM personas AS p
        INNER JOIN personas_direcciones AS pd
        ON p.id_persona = pd.id_persona

        -- inicio Direccion Lineal

                INNER JOIN vias AS v
                ON pd.cod_via = v.cod_via

                INNER JOIN ubigeo_reniec AS  ur
                ON pd.cod_ubigeo_reniec = ur.cod_ubigeo_reniec


                INNER JOIN ubigeo_provincias AS  up
                ON ur.cod_provincia = up.cod_provincia

                INNER JOIN ubigeo_departamentos AS ud
                ON ur.cod_departamento = ud.cod_departamento

                INNER JOIN zonas AS z
                ON pd.cod_zona = z.cod_zona

        -- finall Direccion Lineal	

        WHERE p.id_persona = ?
        and pd.estado_direccion = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
        $stm->bindValue(2, $num);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

//End Class
?>