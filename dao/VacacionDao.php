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
    
    
    public function fechaVacacionProgramada($id_trabajador, $anio,$mes){
        
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
        AND YEAR(fecha_programada) = $anio  
        AND MONTH(fecha_programada) = $mes;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        //$stm->bindValue(2, $anio);
        //$stm->bindValue(3, $mes);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        
        return $lista[0];        
    }
    
    // UTIL en 1era y 2da quincena!..
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

    public function del($id){
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