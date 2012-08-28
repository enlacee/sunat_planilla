<?php

class TrabajadorPdeclaracionDao extends AbstractDao {

    public function registrar($obj) {


        try {
            $query = "
            INSERT INTO trabajadores_pdeclaraciones
                        (
                    id_pdeclaracion,
                    id_trabajador,
                    dia_laborado,
                    dia_total,
                    ordinario_hora,
                    ordinario_min,
                    sueldo,
                    sueldo_neto,
                    estado,
                    fecha_creacion,                         
                    ingreso_5ta_categoria,
                    cod_tipo_trabajador,
                    cod_regimen_pensionario,
                    cod_regimen_aseguramiento_salud,
                    cod_situacion                         
                         
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
                    ); ";
            //Inicia transaccion
            $model = new TrabajadorPdeclaracion();
            $model = $obj;
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_pdeclaracion());
            $stm->bindValue(2, $model->getId_trabajador());
            $stm->bindValue(3, $model->getDia_laborado());
            $stm->bindValue(4, $model->getDia_total());
            $stm->bindValue(5, $model->getOrdinario_hora());
            $stm->bindValue(6, $model->getOrdinario_min());
            $stm->bindValue(7, $model->getSueldo());
            $stm->bindValue(8, $model->getSueldo_neto());
            $stm->bindValue(9, $model->getEstado());
            $stm->bindValue(10, $model->getFecha_creacion());
            $stm->bindValue(11, $model->getIngreso_5ta_categoria());
            $stm->bindValue(12, $model->getCod_tipo_trabajador());
            $stm->bindValue(13, $model->getCod_regimen_pensionario());
            $stm->bindValue(14, $model->getCod_regimen_aseguramiento_salud());
            $stm->bindValue(15, $model->getCod_situacion());
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
    UPDATE trabajadores_pdeclaraciones
    SET 
      dia_total = ?,
      dia_laborado = ?,  
      ordinario_hora = ?,
      ordinario_min = ?,
      sobretiempo_hora = ?,
      sobretiempo_min = ?,
      sueldo = ?,
      sueldo_neto = ?,  
      fecha_modificacion = ?
    WHERE id_trabajador_pdeclaracion = ?;
      ";
        $model = new TrabajadorPdeclaracion();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getDia_total());
        $stm->bindValue(2, $model->getDia_laborado());
        $stm->bindValue(3, $model->getOrdinario_hora());
        $stm->bindValue(4, $model->getOrdinario_min());
        $stm->bindValue(5, $model->getSobretiempo_hora());
        $stm->bindValue(6, $model->getSobretiempo_min());
        $stm->bindValue(7, $model->getSueldo());
        $stm->bindValue(8, $model->getSueldo_neto());
        $stm->bindValue(9, $model->getFecha_modificacion());
        $stm->bindValue(10, $model->getId_trabajador_pdeclaracion());

        $stm->execute();
        $stm = null;

        return true;
    }

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

    public function buscar_ID_trabajador($id_trabajador) {

        $query = "
            SELECT 
                t.id_trabajador,
                t.monto_remuneracion,
                t.cod_situacion,
                -- p.nombres,
                -- drs.id_detalle_regimen_salud,
                drs.cod_regimen_aseguramiento_salud,
                -- drp.id_detalle_regimen_pensionario,
                drp.cod_regimen_pensionario,
                dtt.cod_tipo_trabajador,
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
            -- new
            INNER JOIN detalle_tipos_trabajadores AS dtt
            ON t.id_trabajador = dtt.id_trabajador
            
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

    
      public function buscar_ID($id) {
      $query = "
        SELECT
          id_trabajador_pdeclaracion,
          id_pdeclaracion,
          id_trabajador,
          dia_laborado,
          dia_total,
          ordinario_hora,
          ordinario_min,
          sobretiempo_hora,
          sobretiempo_min,
          sueldo,
          sueldo_neto,
          estado,
          descripcion,
          fecha_creacion,
          fecha_modificacion
        FROM trabajadores_pdeclaraciones
        WHERE id_trabajador_pdeclaracion = ?
      ";
      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      return $lista[0];
      }
     

    function listar($id_pdeclaracion, $op=null, $WHERE=null) {

        $query = "
        SELECT
          tpd.id_trabajador_pdeclaracion,
          tpd.id_trabajador,
          tpd.dia_laborado,
          tpd.dia_total,
          tpd.ordinario_hora,
          tpd.ordinario_min,
          tpd.sobretiempo_hora,
          tpd.sobretiempo_min,
          tpd.sueldo,
          tpd.sueldo_neto,
        per.cod_tipo_documento,
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres 

        FROM  trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona
        
        $WHERE

        WHERE tpd.id_pdeclaracion = ?            
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        if ($op == 'id_trabajador') { // ['id_trabajador']
            $new = array();
            for ($i = 0; $i < count($lista); $i++) {
                $new[]['id_trabajador'] = $lista[$i]['id_trabajador'];
            }
            return $new;
        }

        return $lista;
    }

    public function buscar_ID_GRID_LINEAL($id_trabajadorpdeclaracion) {

        $query = "
        SELECT
          tpd.id_trabajador_pdeclaracion,
          tpd.id_trabajador,
          tpd.dia_laborado,
          tpd.dia_total,
          tpd.ordinario_hora,
          tpd.ordinario_min,
          tpd.sobretiempo_hora,
          tpd.sobretiempo_min,
          tpd.sueldo,
          tpd.sueldo_neto,
          tpd.estado,
        per.cod_tipo_documento,
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres 

        FROM  trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona


        WHERE id_trabajador_pdeclaracion = ?       
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajadorpdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
       
    }

}

?>
