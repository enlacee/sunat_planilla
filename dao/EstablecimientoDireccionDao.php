<?php

class EstablecimientoDireccionDao extends AbstractDao {


    //END FUNCION
    
    public function registrarEstablecimientoDireccion($obj){
          $per_d = new EstablecimientoDireccion();
        $per_d = $obj;

        try {
            $query = "
        INSERT INTO establecimientos_direcciones
                    (
                    id_establecimiento,
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
                    referencia
                    
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
                ?
                );
	";

            //Inicia transaccion
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $per_d->getId_establecimiento());
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

    
      public function buscarEstablecimientoDireccionPorId($id) {
        $query = "SELECT *FROM establecimientos_direcciones WHERE id_establecimiento = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }
    
    
    public function actualizarEstablecimientoDireccion($o){
        
        $obj = new EstablecimientoDireccion();
        $obj =$o;
        
        $query = "
        UPDATE establecimientos_direcciones
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
          referencia = ?
        WHERE id_establecimiento = ?   ";
        $stm = $this->pdo->prepare($query);        
        
        $stm->bindValue(1, $obj->getCod_ubigeo_reinec());
        $stm->bindValue(2, $obj->getCod_via());
        $stm->bindValue(3, $obj->getNombre_via());
        $stm->bindValue(4, $obj->getNumero_via());
        $stm->bindValue(5, $obj->getDepartamento());
        $stm->bindValue(6, $obj->getInterior());
        $stm->bindValue(7, $obj->getManzanza());
        $stm->bindValue(8, $obj->getLote());        
        $stm->bindValue(9, $obj->getKilometro());
        $stm->bindValue(10, $obj->getBlock());
        $stm->bindValue(11, $obj->getEstapa());
        $stm->bindValue(12, $obj->getCod_zona());
        $stm->bindValue(13, $obj->getNombre_zona());
        $stm->bindValue(14, $obj->getReferencia());
        $stm->bindValue(15, $obj->getId_establecimiento());
        $stm->execute();
        //$lista = $stm->fetchAll();   
        
        return true;
        
    }

}

//End Class
?>