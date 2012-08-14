<?php

class AdelantoDao extends AbstractDao {

    public function registrar($obj) {
        $model = new Adelanto();
        $model = $obj;
        $query = "
        INSERT INTO adelantos
                    (
                    id_pdeclaracion,
                    id_trabajador,
                    cod_periodo_remuneracion,                 
                    valor,
                    fecha_inicio,
                    fecha_fin,
                    fecha_creacion,
                    id_empresa_centro_costo)
        VALUES (
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
        $stm->bindValue(1, $model->getId_declaracion());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getCod_periodo_remuneracion());
        $stm->bindValue(4, $model->getValor());
        $stm->bindValue(5, $model->getFecha_inicio());
        $stm->bindValue(6, $model->getFecha_fin());
        $stm->bindValue(7, $model->getFecha_creacion());
        $stm->bindValue(8, $model->getId_empresa_centro_costo());

        $stm->execute();

        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        $stm = null;

        return $lista[0]['id'];
    }

    public function actualizar($id_adelanto, $valor) {

        $model = new Adelanto();
        $model = $obj;
        $query = "
        UPDATE adelantos
        SET 
        valor = ?
        WHERE id_adelanto = ?;    
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $id_adelanto);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function listar($id_pdeclaracion) {
        $query = "
        SELECT
            id_adelanto,
            id_pdeclaracion,
            id_trabajador,
            cod_periodo_remuneracion,
            glosa,
            valor,
            fecha_inicio,
            fecha_fin,
            fecha_creacion,
            id_empresa_centro_costo
        FROM adelantos
        WHERE id_pdeclaracion = ?
        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /* public function buscar_IDtrabajador($id) {
      $query = "
      SELECT
      id_adelanto,
      id_trabajador,
      cod_periodo_remuneracion,
      dia_total,
      dia_laborado_FUCK,
      dia_nolaborado,
      valor,
      fecha_inicio,
      fecha_fin,
      fecha_creacion
      FROM adelantos
      WHERE id_trabajador = ?
      ";

      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      return $lista;
      } */
}

?>
