<?php

class PersonaFormacionLaboralDao extends AbstractDao {

//END FUNCION
//Registrar Producto and Imagen
    public function registrar($obj) {
//        fuck

        try {
            $query = "
INSERT INTO personales_formaciones_laborales
            (
             id_persona,
             cod_nivel_educativo,
             id_modalidad_formativa,
             id_ocupacion_2,
             centro_formacion,
             seguro_medico,
             presenta_discapacidad,
             horario_nocturno,
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
        ?
        ); ";

            //Inicia transaccion
            $em = new PersonaFormacionLaboral();
            $em = $obj;
            
            //echo "<pre>";
            //print_r($em);
            //echo "</pre>";
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $em->getId_persona());
            $stm->bindValue(2, $em->getCod_nivel_educativo());
            $stm->bindValue(3, $em->getId_modalidad_formativa());
            $stm->bindValue(4, $em->getId_ocupacion_2());
            $stm->bindValue(5, $em->getCentro_formacion());
            $stm->bindValue(6, $em->getSeguro_medico());
            $stm->bindValue(7, $em->getPresenta_discapacidad());
            $stm->bindValue(8, $em->getHorario_nocturno());
            $stm->bindValue(9, $em->getCod_situacion());
            $stm->bindValue(10,$em->getEstado());
            
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
        
        //-- id_persona = ?,
        $query = "
        UPDATE personales_formaciones_laborales
        SET 
        cod_nivel_educativo = ?,
        id_modalidad_formativa = ?,
        id_ocupacion_2 = ?,
        centro_formacion = ?,
        seguro_medico = ?,
        presenta_discapacidad = ?,
        horario_nocturno = ?
        
        WHERE id_personal_formacion_laboral = ?; 
        ";
        $com = new PersonaFormacionLaboral();
        $com = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $com->getCod_nivel_educativo());
        $stm->bindValue(2, $com->getId_modalidad_formativa());
        $stm->bindValue(3, $com->getId_ocupacion_2());
        $stm->bindValue(4, $com->getCentro_formacion());
        $stm->bindValue(5, $com->getSeguro_medico());        
        $stm->bindValue(6, $com->getPresenta_discapacidad());
        $stm->bindValue(7, $com->getHorario_nocturno());
        $stm->bindValue(8, $com->getId_personal_formacion_laboral());
        
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

    public function buscaPFormacionLaboralPorIdPersona($id_persona) {//OK
        $query = "
        SELECT *FROM personales_formaciones_laborales AS pfl
        INNER JOIN situaciones AS s
        ON pfl.cod_situacion = s.cod_situacion
        WHERE id_persona = ? 
        AND estado = 'ACTIVO';
                    
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
    
    

}

//End Class
?>


