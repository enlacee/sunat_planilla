<?php

class VacacionDao extends AbstractDao {

    // Contenedor Instancia de la Clase
    private static $instance;

    // A private constructor; previene creación de objetos vía new

    /*
      // EL método singleton
      public static function singleton() {
      if (!isset(self::$instance)) {
      $c = __CLASS__;
      self::$instance = new $c;
      }

      return self::$instance;
      }
     */
    /*
     * 
     */

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
        estado,
        fecha_creacion
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
    
    
    public function fechaVacacionProgramada($id_trabajador, $anio){
        
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
        AND YEAR(fecha_programada) = ?       
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        
        return $lista[0];        
    }
    

    function add($id_trabajador, $fecha, $fecha_programada) {


        $query = "
        INSERT INTO vacaciones
                    (
                    id_trabajador,
                    fecha,
                    fecha_programada,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?);      
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $fecha);
        $stm->bindValue(3, $fecha_programada);
        $stm->bindValue(4, date("Y-m-d"));
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



}

?>