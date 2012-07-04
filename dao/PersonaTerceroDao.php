<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonaTercero
 *
 * @author Anibal
 */
class PersonaTerceroDao extends AbstractDao {

    //put your code here

    public function registrar($obj) {


        try {
            $query = "
            INSERT INTO personales_terceros
                        (
                         id_persona,
                         id_empleador_destaque_yoursef,
                         cobertura_pension,
                         cod_situacion,
                         estado)
            VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?);
                ";

            //Inicia transaccion
            $em = new personaTercero();
            $em = $obj;

            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $em->getId_persona());
            $stm->bindValue(2, $em->getId_empleador_destaque_yoursef());
            $stm->bindValue(3, $em->getCobertura_pension());
            $stm->bindValue(4, $em->getCod_situacion());
            $stm->bindValue(5, $em->getEstado());

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

    public function actualizar($obj) {

        $query = "
        UPDATE personales_terceros
        SET           
          id_empleador_destaque_yoursef = ?,
          cobertura_pension = ?
        WHERE id_personal_tercero = ?;          
        ";
        $com = new personaTercero();
        $com = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $com->getId_empleador_destaque_yoursef());
        $stm->bindValue(2, $com->getCobertura_pension());
        $stm->bindValue(3, $com->getId_personal_tercero());
        $stm->execute();
        $stm = null;

        return true;
    }

    public function buscaPersonaTerceroPorIdPersona($id_persona) {//OK
        $query = "
        SELECT
          pt.id_personal_tercero,
          pt.id_persona,
          pt.id_empleador_destaque_yoursef,
          pt.cobertura_pension,
          pt.cod_situacion,
          pt.estado,
          s.cod_situacion,
          s.descripcion_abreviada
        FROM personales_terceros AS pt
        INNER JOIN situaciones AS s
        ON pt.cod_situacion = s.cod_situacion

        WHERE pt.id_persona = ?
        AND pt.estado = 'ACTIVO'            
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];


    }

}

?>
