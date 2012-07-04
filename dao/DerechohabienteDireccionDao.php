<?php

final class DerechohabienteDireccionDao extends AbstractDao {

//END FUNCION

    public function registrarDerechohabienteDireccion($obj_dh_direccion) {

        $per_d = new DerechohabienteDireccion();
        $per_d = $obj_dh_direccion;

        try {
            $query = "
        INSERT INTO derechohabientes_direcciones
                    (
                    id_derechohabiente,
                    cod_ubigeo_reniec,
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
            $stm->bindValue(1, $per_d->getId_derechohabiente());
            $stm->bindValue(2, $per_d->getCod_ubigeo_reinec());            
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

            // id Persona
            /*$query2 = "select last_insert_id() as id";
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
    public function actualizarDerechohabienteDireccion($obj_per_direccion) {

        $com = new DerechohabienteDireccion();
        $com = $obj_per_direccion;


        $query = "
        UPDATE derechohabientes_direcciones
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
        
        WHERE id_derechohabiente_direccion = ?; ";

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
        $stm->bindValue(16, $com->getId_derechohabiente_direccion());
        
        $stm->execute();
        $stm = null;

        return true;
    }


    public function listarDerechohabienteDirecciones($id_derechohabiente,$WHERE, $start, $limit, $sidx, $sord) {

        // $query = "SELECT *FROM personas $WHERE  ORDER BY $sidx $sord LIMIT $start $limit";

        $query = "
SELECT
d.id_derechohabiente,
dd.id_derechohabiente_direccion,
ud.descripcion AS ubigeo_departamento,
up.descripcion AS ubigeo_provincia,
ur.descripcion  AS ubigeo_distrito,
dd.cod_via,
v.descripcion AS ubigeo_nombre_via,
dd.nombre_via,
dd.numero_via,
dd.departamento,
dd.interior,
dd.manzana,
dd.lote,
dd.kilometro,
dd.block,
dd.etapa,
dd.cod_zona,
z.descripcion AS ubigeo_nombre_zona,
dd.nombre_zona,
referencia,
referente_essalud,
estado_direccion
FROM derechohabientes AS d
INNER JOIN  derechohabientes_direcciones AS dd
ON d.id_derechohabiente = dd.id_derechohabiente

INNER JOIN vias AS v
ON dd.cod_via = v.cod_via

INNER JOIN ubigeo_reniec AS  ur
ON dd.cod_ubigeo_reniec = ur.cod_ubigeo_reniec


INNER JOIN ubigeo_provincias AS  up
ON ur.cod_provincia = up.cod_provincia

INNER JOIN ubigeo_departamentos AS ud
ON ur.cod_departamento = ud.cod_departamento

INNER JOIN zonas AS z
ON dd.cod_zona = z.cod_zona


WHERE d.id_derechohabiente = ?		        
		$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit ";
        // echo $query;
        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_derechohabiente);
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
	
	
    public function cantidadDerechohabienteDireccionesConIdPersona($id_derechohabiente) {
		//OK
        $query = "
		
		SELECT
		COUNT(d.id_derechohabiente) AS numfilas
		
		FROM derechohabientes AS d
		INNER JOIN derechohabientes_direcciones AS dd
		ON d.id_derechohabiente = dd.id_derechohabiente
		
		INNER JOIN vias AS v
		ON dd.cod_via = v.cod_via
		
		INNER JOIN ubigeo_reniec AS  ur
		ON dd.cod_ubigeo_reniec = ur.cod_ubigeo_reniec
		
		INNER JOIN zonas AS z
		ON dd.cod_zona = z.cod_zona
		
		WHERE d.id_derechohabiente = ?
			
		";

        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1,$id_derechohabiente);
            $stm->execute();

            $lista = $stm->fetchAll();
            return $lista[0]["numfilas"];
            $stm = null;
        } catch (Exception $e) {

            throw $e;
        }
    }




/**/
    public function buscarDerechohabienteDireccionPorId($id) {
        $query = "SELECT *FROM derechohabientes_direcciones WHERE id_derechohabiente_direccion = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }






    
}

//End Class
?>