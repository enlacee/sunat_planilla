<?php

class VacacionDao extends AbstractDao {

    function vacacionPeriodo($id_empleador, $periodo,$WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }
        
        $query = "
        SELECT 
                p.id_persona,	
                p.num_documento,
                p.apellido_paterno,
                p.apellido_materno,
                p.nombres,
                t.id_trabajador,
                t.cod_situacion,
                v.fecha_programada

        FROM personas AS p 
        INNER JOIN empleadores AS e
        ON p.id_empleador = e.id_empleador

        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        LEFT JOIN vacaciones AS v
        ON t.id_trabajador = v.id_trabajador

        WHERE p.id_empleador = ?
        AND v.fecha_programada = ?
        $cadena;
            
-- lista a los trabajadores que tienen vacacion en este periodo 
-- en este select se duplican personas xq tienen muchas vacaciones
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);        
        $stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    function vacacionPeriodoCount($id_empleador, $periodo,$WHERE) {
        $query = "
        SELECT 
        COUNT(p.id_persona)as counteo
        
        FROM personas AS p 
        INNER JOIN empleadores AS e
        ON p.id_empleador = e.id_empleador

        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona

        LEFT JOIN vacaciones AS v
        ON t.id_trabajador = v.id_trabajador
        WHERE p.id_empleador = ?
        AND v.fecha_programada = ? 
        $WHERE
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);        
        $stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]['counteo'];
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

    // UTIL en 1era y 2da quincena!.. UTILIZADO DE PRUEBA
    public function listaIdsTraVacacionesFProgramada($mes_inicio, $mes_fin) {

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
            for ($i = 0; $i < count($lista); $i++):
                $array[] = $lista[$i]['id_trabajador'];
            endfor;
            return $array;
        endif;
    }

    public function listaIdsTraVacacionesFProgramadaFin($mes_inicio, $mes_fin) {

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
            for ($i = 0; $i < count($lista); $i++):
                $array[] = $lista[$i]['id_trabajador'];
            endfor;
            return $array;
        endif;
    }

    public function listarVacacionesEnRango($id_trabajador) { /* ,$mes_inicio,$mes_fin,$mes_fin_sgte_mes */

        $query = "
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


        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }

    public function add($obj) {

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