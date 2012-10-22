<?php

class VacacionDao extends AbstractDao {

    function buscarVacacion() {
        
    }

    /**
     *
     * @param type $id_trabajador
     * @return type 
     */
    public function listar($id_trabajador) {
        $query = "
        SELECT
        id_vacacion,
        id_trabajador,
        fecha,
        fecha_programada,
        fecha_programada_fin,
        estado,
        fecha_creacion,
        tipo_vacacion
        FROM vacaciones
        WHERE id_trabajador = ?        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function fechaVacacionProgramada($id_trabajador, $anio, $mes) {

        $query = "
        SELECT
        -- id_vacacion,
        id_trabajador,
        fecha,
        fecha_programada,
        estado,
        fecha_creacion
        FROM vacaciones
        WHERE id_trabajador = ?
        WHERE (fecha_programada <= '$mes_fin')        
        AND (fecha_programada >= '$mes_inicio' );
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }
    
    
    
    public function fechaVacacionProgramadaFin($id_trabajador, $anio, $mes) {

        $query = "
        SELECT
        -- id_vacacion,
        id_trabajador,
        fecha,
        fecha_programada,
        estado,
        fecha_creacion
        FROM vacaciones
        WHERE id_trabajador = ?
        WHERE (fecha_programada_fin <= '$mes_fin')        
        AND (fecha_programada_fin >= '$mes_inicio' );
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    } 

    /* // UTIL en 1era y 2da quincena!.. UTILIZADO DE PRUEBA
      function listaIdsTraVacaciones($periodo){

      $anio = getFechaPatron($periodo, "Y");
      $mes = getFechaPatron($periodo, "m");

      $query = "
      SELECT
      id_trabajador
      FROM vacaciones
      WHERE YEAR(fecha_programada) = ?
      AND MONTH(fecha_programada) = ?;
      ";
      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $anio);
      $stm->bindValue(2, $mes);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;

      return $lista;

      }
     */

    // UTIL en 1era y 2da quincena!.. UTILIZADO DE PRUEBA
    function listaIdsTraVacacionesFProgramada($mes_inicio, $mes_fin) {

        $query = "
        SELECT        
        id_trabajador        
        FROM vacaciones        
        WHERE (fecha_programada <= '$mes_fin')        
        AND (fecha_programada >= '$mes_inicio' ); 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        
        if (is_null($lista)):
            $array = array();
            return $array;
        else:
            $array = array();
            for($i=0;$i<count($lista);$i++):
                $array[] = $lista[$i]['id_trabajador'];
            endfor;            
            return $array;
        endif;
    }

    function listaIdsTraVacacionesFProgramadaFin($mes_inicio, $mes_fin) {

        $query = "
        SELECT        
        id_trabajador        
        FROM vacaciones        
        WHERE (fecha_programada_fin <= '$mes_fin')        
        AND (fecha_programada_fin >= '$mes_inicio' ); 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = array();
        $lista = $stm->fetchAll();
        $stm = null;

        if (is_null($lista)):
            $array = array();
            return $array;
        else:
            $array = array();
            for($i=0;$i<count($lista);$i++):
                $array[] = $lista[$i]['id_trabajador'];
            endfor;            
            return $array;
        endif;
        
    }

    
      function listarVacacionesEnRango($id_trabajador/*,$mes_inicio,$mes_fin,$mes_fin_sgte_mes*/){

      $query ="
      SELECT
      id_trabajador,
      fecha,
      fecha_programada,
      fecha_programada_fin,
      tipo_vacacion,
      fecha_creacion
      FROM vacaciones
      WHERE id_trabajador = ?
      ";
      
      /*-- this mes y ASK proximo Mes maximo
      AND (fecha_programada <= '$mes_fin' OR fecha_programada_fin <= '$mes_fin_sgte_mes' )
      -- Mes minimo
      AND (fecha_programada >= '$mes_inicio' );
      */
      

      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_trabajador);

      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;

      return $lista[0];

      }
     

    function add($obj/* $id_trabajador, $fecha, $fecha_programada,$f_programado_fin,$tipo_vacacion */) {

        $model = new Vacacion();
        $model = $obj;
        $query = "
        INSERT INTO vacaciones
                    (
                    id_trabajador,
                    fecha,
                    fecha_programada,
                    fecha_programada_fin,
                    fecha_creacion,
                    tipo_vacacion
                    )
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);      
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getFecha());
        $stm->bindValue(3, $model->getFecha_programada());
        $stm->bindValue(4, $model->getFecha_prograda_fin());
        $stm->bindValue(5, date("Y-m-d"));
        $stm->bindValue(6, $model->getTipo_vacacion());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;

        return true;
    }

    public function listarUltimaFechaVacacion($id_trabajador) {
        $query = "
        SELECT
        id_vacacion,
        id_trabajador,
        fecha,
        fecha_programada,
        estado,
        fecha_creacion
        FROM vacaciones
        WHERE id_trabajador = ?
        ORDER BY fecha DESC
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }

    public function del($id) {
        $query = "        
        DELETE
        FROM vacaciones
        WHERE id_vacacion = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

}

?>