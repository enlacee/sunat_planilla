<?php

class PlameDeclaracionDao extends AbstractDao {

    public function registrar($id_empleador_maestro, $periodo) {

        $date = date("Y-m-d");
        $query = "
        INSERT INTO pdeclaraciones
                    (
                    id_empleador_maestro,
                    periodo,
                    fecha_creacion,
                    fecha_modificacion)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";
        $this->pdo->beginTransaction();

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $periodo);
        $stm->bindValue(3, $date);
        $stm->bindValue(4, $date);
        $stm->execute();

        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        $stm = null;



        return $lista[0]['id'];
        //$lista = $stm->fetchAll();
    }

    public function actualizar($id_pdeclaracion, $fecha_modificacion) {

        $query = "
        UPDATE pdeclaraciones
        SET
          fecha_modificacion = ?
        WHERE (id_pdeclaracion = ? );            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $fecha_modificacion);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $stm = null;
        return true;
    }

    /**
     *
     * @param type $id_empleador_maestro
     * @param type $periodo
     * @return type 
     * Pregunta si existe periodo registrado.
     */
    public function existeDeclaracion($id_empleador_maestro, $periodo) { //paso 01
        $query = "
        SELECT
            COUNT(*) as nunfilas
        FROM pdeclaraciones
        WHERE (id_empleador_maestro = ? AND periodo = '$periodo' )
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        //$stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['nunfilas'];
    }

    /*
      public function contarTrabajadoresEnPeriodo($id_empleador_maestro,$periodo){//paso 02

      $query = "
      SELECT
      COUNT(*) AS numfilas

      FROM pdeclaraciones AS pd
      INNER JOIN empleadores_maestros AS em
      ON pd.id_empleador_maestro = em.id_empleador_maestro

      INNER JOIN ptrabajadores AS pt
      ON pd.id_pdeclaracion = pt.id_pdeclaracion

      INNER JOIN trabajadores AS tra
      ON pt.id_trabajador = tra.id_trabajador

      INNER JOIN personas AS p
      ON tra.id_persona = p.id_persona


      INNER JOIN pjornadas_laborales AS pjl
      ON pt.id_ptrabajador = pjl.id_ptrabajador

      WHERE (em.id_empleador_maestro = ? AND  pd.periodo = ?)
      ";

      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_empleador_maestro);
      $stm->bindValue(2, $periodo);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      return $lista[0]['numfilas'];


      }

     */
/*
    public function listar($id_empleador_maestro) {
        $query = "
        SELECT 
        id_pdeclaracion,
        periodo,
        fecha_modificacion

        FROM pdeclaraciones AS pd
        INNER JOIN empleadores_maestros AS em
        ON pd.id_empleador_maestro = em.id_empleador_maestro

        WHERE(em.id_empleador_maestro= ?)
        ORDER BY periodo ASC
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        //$stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
*/
    public function listar($id_empleador_maestro,$anio) {
        $query = "
        SELECT 
        id_pdeclaracion,
        periodo,
        fecha_modificacion

        FROM pdeclaraciones AS pd
        INNER JOIN empleadores_maestros AS em
        ON pd.id_empleador_maestro = em.id_empleador_maestro

        WHERE(em.id_empleador_maestro= ?AND YEAR(pd.periodo) = ?)
        ORDER BY periodo ASC
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
