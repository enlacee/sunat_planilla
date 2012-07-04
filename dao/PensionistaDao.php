<?php

class PensionistaDao extends AbstractDao {

//END FUNCION
//Registrar Producto and Imagen
    public function registrar($obj_pensionista) {


        try {
            $query = "
            INSERT INTO pensionistas
                        (
                         id_persona,
                         cod_tipo_trabajador,
                         cod_regimen_pensionario,
                         cod_tipo_pago,
                         estado,
                         cod_situacion)
            VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?); ";
            //Inicia transaccion
            $em = new Pensionista();
            $em = $obj_pensionista;

            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $em->getId_persona());
            $stm->bindValue(2, $em->getCod_tipo_trabajador());
            $stm->bindValue(3, $em->getCod_regimen_pensionario());
            $stm->bindValue(4, $em->getCod_tipo_pago());
            $stm->bindValue(5, $em->getEstado());
            $stm->bindValue(6, $em->getCod_situacion());
            $stm->execute();

            // id Comerico
            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();

            $this->pdo->commit();
            //finaliza transaccion
            //return true;
            $stm = null;
            return $lista[0]['id'];
        } catch (Exception $e) {
            //  Util::rigistrarLog( $e, $query );
            $this->pdo->rollBack();
            throw $e;
        }
    }

    //
    public function actualizar($obj) {

        $query = "
        UPDATE pensionistas
        SET 
        cod_tipo_trabajador = ?,
        cod_regimen_pensionario = ?,
        cuspp = ?,
        cod_tipo_pago = ?
        WHERE id_pensionista = ?;            
        ";
        $com = new Pensionista();
        $com = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $com->getCod_tipo_trabajador());
        $stm->bindValue(2, $com->getCod_regimen_pensionario());
        $stm->bindValue(3, $com->getCuspp());
        $stm->bindValue(4, $com->getCod_tipo_pago());
        $stm->bindValue(5, $com->getId_pensionista());
        $stm->execute();
        $stm = null;

        return true;
    }

    public function baja($id_pensionista) {

        $query = "		
        UPDATE pensionistas
        SET 
          estado = estado,
          cod_situacion = cod_situacion
        WHERE id_pensionista = ?;
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, 'INACTIVO');
        $stm->bindValue(2, $id_pensionista);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function buscaPensionistaPorIdPersona($id_persona) {//OK
        $query = "
        SELECT *FROM pensionistas AS p
        INNER JOIN situaciones AS s
        ON p.cod_situacion = s.cod_situacion
        WHERE id_persona = ?
        AND p.cod_situacion = 1
        -- AND estado = 'ACTIVO';            
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];

        //echo "<pre>HOLAAAAAAAAAAAAAAA DAO";
        //print_r($lista[0]);
        //echo "</pre>";
    }

    //-----------------------------E6---------------------------------------
    public function listarPensionistaSoloID_E6($ID_EMPLEADOR_MAESTRO) {
        $query = "
        SELECT 
        pen.id_pensionista
        
        FROM personas AS p
        INNER JOIN empleadores AS emp
        ON p.id_empleador = emp.id_empleador
        INNER JOIN empleadores_maestros AS em
        ON emp.id_empleador = em.id_empleador
        
        INNER JOIN pensionistas AS pen
        ON p.id_persona = pen.id_persona
	
	WHERE (p.cod_tipo_documento='01' OR p.cod_tipo_documento='04' OR p.cod_tipo_documento='07')
        AND em.id_empleador_maestro = ?
        AND pen.cod_situacion = 1
        AND pen.estado = 'ACTIVO'     
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_EMPLEADOR_MAESTRO);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

    public function buscarPensionistaPorId_E6($id_pensionista) {
         
        $query = "
        SELECT 
        pen.id_pensionista,
        p.cod_tipo_documento,
        p.num_documento,
        p.cod_pais_emisor_documento,
        pen.cod_tipo_trabajador,
        pen.cod_regimen_pensionario,
        pen.cuspp,
        pen.cod_tipo_pago
        
        FROM personas AS p
        INNER JOIN pensionistas AS pen
        ON p.id_persona = pen.id_persona

        WHERE pen.id_pensionista = ?
        AND pen.cod_situacion = 1
        AND pen.estado = 'ACTIVO'   
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pensionista);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;        
        
        
        
    }

    
    
    
}

//End Class
?>