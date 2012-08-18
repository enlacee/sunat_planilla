<?php

class PeriodoAdelantoDao extends AbstractDao {

    //put your code here
    public function registrar($obj) {
        $model = new PeriodoAdelanto();
        $model = $obj;

        $query = "
INSERT INTO periodos_adelantos
            (
             id_trabajador,
             id_pdeclaracion,
             id_periodo_pago,
             dia_laborado,
             dia_total,
             sueldo_base,
             sueldo,
             descuento,
             sueldo_neto,
             fecha_inicio,
             fecha_fin,
             ordinario_hora,
             ordinario_min,
             sobretiempo_hora,
             sobretiempo_min,
             estado,
             descripcion,
             id_etapa_mes)
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
        ?);
        ";
        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getId_pdeclaracion());
        $stm->bindValue(3, $model->getId_periodo_pago());
        $stm->bindValue(4, $model->getDia_laborado());
        $stm->bindValue(5, $model->getDia_total());
        $stm->bindValue(6, $model->getSueldo_base());
        $stm->bindValue(7, $model->getSueldo());
        $stm->bindValue(8, $model->getDescuento());
        $stm->bindValue(9, $model->getSueldo_neto());

        $stm->bindValue(10, $model->getFecha_inicio());
        $stm->bindValue(11, $model->getFecha_fin());
        $stm->bindValue(12, $model->getOrdinario_hora());
        $stm->bindValue(13, $model->getOrdinario_min());
        $stm->bindValue(14, $model->getSobretiempo_hora());
        $stm->bindValue(15, $model->getSobretiempo_min());
        $stm->bindValue(16, $model->getEstado());
        $stm->bindValue(17, $model->getDescripcion());
        $stm->bindValue(18, $model->getId_etapa_mes());

        $stm->execute();

        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        $stm = null;

        return $lista[0]['id'];
    }

    public function actualizar($obj) {
        
    }
/**
 *
*funcion depende registarr  quincena y 2 quincena
 */
    public function buscarPeriodoAdelanto($id_declaracion/*, $id_periodo_pago*/) {
        /*    $query = "
          SELECT
          id_periodo_adelanto
          FROM periodos_adelantos

          WHERE (id_pdeclaracion= ? AND id_periodo_pago = ?)
          "; */

        $query = "
        SELECT *FROM periodos_adelantos 
        WHERE (id_pdeclaracion = ?)
        GROUP BY id_etapa_mes          
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_declaracion);
        //$stm->bindValue(2, $id_periodo_pago);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function listar($id_pdeclaracion) {
        /*      $query = "
          SELECT
          ep.id_etapa_pago,
          ep.id_pdeclaracion,
          ep.cod_periodo_remuneracion,
          ep.fecha_inicio,
          ep.fecha_fin,
          ep.fecha_creacion,
          ep.tipo,
          ep.glosa,
          pr.descripcion
          FROM etapas_pagos AS ep
          INNER JOIN periodos_remuneraciones AS pr
          ON ep.cod_periodo_remuneracion = pr.cod_periodo_remuneracion
          WHERE (id_pdeclaracion= ?)
          "; */
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
      public function buscar_ID($id_etapa_pago, $op=null) {
      $query = "
      SELECT
      ep.id_etapa_pago,
      ep.id_pdeclaracion,
      ep.cod_periodo_remuneracion,
      ep.fecha_inicio,
      ep.fecha_fin,
      ep.fecha_creacion,
      ep.tipo,
      ep.glosa
      FROM etapas_pagos AS ep
      WHERE (ep.id_etapa_pago= ?)
      ";
      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_etapa_pago);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      if ($op == "data") {
      return $lista;
      } else {
      return $lista[0];
      }
      }

      public function buscarEtapaPago($id_declaracion, $cod_periodo_remuneracion) {
      $query = "
      SELECT
      id_etapa_pago
      FROM etapas_pagos
      WHERE (id_pdeclaracion= ? AND cod_periodo_remuneracion = ?)
      ";
      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_declaracion);
      $stm->bindValue(2, $cod_periodo_remuneracion);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      return $lista;
      }

      public function buscarEtapaPago2($id_declaracion, $cod_periodo_remuneracion, $tipo) {
      $query = "
      SELECT
      id_etapa_pago
      FROM etapas_pagos
      WHERE (id_pdeclaracion= ? AND cod_periodo_remuneracion = ?)
      AND tipo = ?;
      ";
      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_declaracion);
      $stm->bindValue(2, $cod_periodo_remuneracion);
      $stm->bindValue(3, $tipo);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      return $lista[0]['id_etapa_pago'];
      }
     */
}

?>
