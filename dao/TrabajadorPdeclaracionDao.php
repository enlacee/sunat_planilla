<?php

class TrabajadorPdeclaracionDao extends AbstractDao {

    public function registrar($id_pdeclaracion, $id_trabajador) {


        try {
            $query = "
        INSERT INTO trabajadores_pdeclaraciones
                    (
                     id_pdeclaracion,
                     id_trabajador)
        VALUES (
                ?,
                ?); ";
            //Inicia transaccion
            //$em = new TrabajadorPdeclaracionDao()
            //$em = $obj_pensionista;

            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_pdeclaracion);
            $stm->bindValue(2, $id_trabajador);

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
    /* public function actualizar($obj) {

      $query = "

      ";
      $com = new Pensionista();
      $com = $obj;

      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $com->getCod_tipo_trabajador());
      $stm->execute();
      $stm = null;

      return true;
      } */

    public function eliminar($id_trabajador_pdeclaracion) {

        $query = "		
        DELETE
        FROM trabajadores_pdeclaraciones
        WHERE id_trabajador_pdeclaracion = ?;
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        $stm = null;
        return true;
    }
    
    /*
     * Ayuda y 
     * Busca informacion para ayudar con la generacion de 
     * Conceptos.
     */
    public function buscar_ID_trabajador($id_trabajador){
        
        $query ="
            SELECT 
                t.id_trabajador,
                -- p.nombres,
                -- drs.id_detalle_regimen_salud,
                drs.cod_regimen_aseguramiento_salud,
                -- drp.id_detalle_regimen_pensionario,
                drp.cod_regimen_pensionario,
                -- ptrabajador
                pt.aporta_essalud_vida,
                pt.aporta_asegura_tu_pension
            FROM trabajadores AS t
            INNER JOIN personas AS p
            ON t.id_persona = p.id_persona

            INNER JOIN detalle_regimenes_salud AS drs
            ON drs.id_trabajador = t.id_trabajador

            INNER JOIN detalle_regimenes_pensionarios AS drp
            ON drp.id_trabajador = t.id_trabajador

            LEFT JOIN ptrabajadores AS pt
            ON t.id_trabajador = pt.id_trabajador

            WHERE t.id_trabajador = ?        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        
        return $lista[0];       
        
    }
/*
    public function buscar_ID() {
        $query = "		

		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        $stm = null;
        return true;
    }
    */

}

?>
