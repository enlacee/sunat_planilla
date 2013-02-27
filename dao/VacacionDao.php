<?php

class VacacionDao extends AbstractDao {

    function add($id_trabajador, $id_pdeclaracion) {
        //$model = new Vacacion();
        //$model = $obj;
        $query = "
        INSERT INTO vacaciones
                    (
                     id_trabajador,
                     id_pdeclaracion)
        VALUES (
                ?,
                ?);
        ";
        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();
        $this->pdo->commit();
        $stm = null;
        return $lista[0]['id'];
    }

    function del($id) {
        $query = "       

        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    function listar($ID_EMPLEADOR_MAESTRO, $id_pdeclaracion, $WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }
        $query = "
	SELECT  v.id_vacacion,
		t.id_trabajador,
		td.descripcion_abreviada AS nombre_tipo_documento,
		p.num_documento,
		p.apellido_paterno,
		p.apellido_materno,
		p.nombres		
        FROM personas AS p        
        INNER JOIN tipos_documentos AS td
        ON p.cod_tipo_documento = td.cod_tipo_documento
        INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona        
        INNER JOIN vacaciones AS v
        ON t.id_trabajador = v.id_trabajador        
        WHERE em.id_empleador_maestro = ?
	AND v.id_pdeclaracion = ?
        $cadena
        ";

        try {
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $ID_EMPLEADOR_MAESTRO);
            $stm->bindValue(2, $id_pdeclaracion);
            $stm->execute();
            $lista = $stm->fetchAll();
            return $lista;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function listarCount($ID_EMPLEADOR_MAESTRO, $id_pdeclaracion, $WHERE) {
        $query = "
	SELECT
            count(v.id_vacacion) as counteo		
        FROM personas AS p        
        INNER JOIN tipos_documentos AS td
        ON p.cod_tipo_documento = td.cod_tipo_documento
        INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona        
        INNER JOIN vacaciones AS v
        ON t.id_trabajador = v.id_trabajador        
        WHERE em.id_empleador_maestro = ?
	AND v.id_pdeclaracion = ?
        $WHERE
        ";

        try {
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $ID_EMPLEADOR_MAESTRO);
            $stm->bindValue(2, $id_pdeclaracion);
            $stm->execute();
            $lista = $stm->fetchAll();
            return $lista['counteo'];
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // Este dato es unico X año.
    function getPdeclaracionBase($id_trabajador,$anio){
        $query = "
        SELECT
        v.id_vacacion,
        v.id_pdeclaracion,       
        pd.periodo
        FROM vacaciones AS v
        INNER JOIN pdeclaraciones AS pd
        ON v.id_pdeclaracion = pd.id_pdeclaracion        
        WHERE id_trabajador = ?
        AND YEAR(pd.periodo) = ?   
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['id_pdeclaracion'];        
    }
    //--------------------------------------------------------------------------
    // funcion Consulta si existe trabajador registrado en periodo 01-01-2013
    // este trabajador solo debe estar registrado en 1 solo periodo de este año 2013.
    // anio = id_pdeclaracion (alternativa.)
    function searchTrabajadorPorAnio($id_trabajdor, $anio) {
        $query = "
        SELECT
        v.id_vacacion,
        v.id_pdeclaracion,
        pd.periodo
        FROM vacaciones AS v
        INNER JOIN pdeclaraciones AS pd
        ON v.id_pdeclaracion = pd.id_pdeclaracion
        WHERE v.id_trabajador = ? 
        AND YEAR(pd.periodo) = ?     
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajdor);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();

        if (!is_null($lista)) {
            return $lista[0];
        } else {
            return false;
        }
    }
    
    //data para generar vacacion
    function trabajadoresConVacacion($id_empleador_maestro,$anio){
        $query = "
        SELECT 
        v.id_vacacion,
        v.id_trabajador,
        -- pd.periodo,
        t.monto_remuneracion

        FROM vacaciones AS v
        LEFT JOIN pdeclaraciones AS pd
        ON v.id_pdeclaracion = pd.id_pdeclaracion
        -- 
        INNER JOIN trabajadores AS t
        ON v.id_trabajador = t.id_trabajador
        -- 
        INNER JOIN detalle_periodos_laborales AS dpl
        ON v.id_trabajador = dpl.id_trabajador
        -- 17 = No se inicio relacion laboral
        WHERE dpl.cod_motivo_baja_registro <> '17'
        AND pd.id_empleador_maestro = ?
        AND YEAR(pd.periodo)= ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;        
        
    }
    //--------------------------------------------------------------------------
    // funcion pregunta por fechas de vacaciones en vacacion_detalle
    function fechasDevacacionesTrabajador($id_trabajador, $id_pdeclaracion) {
        $query = "
        SELECT 
        vd.fecha_inicio,
        vd.fecha_fin
        FROM vacaciones AS v
        INNER JOIN vacaciones_detalles AS vd
        ON v.id_vacacion = vd.id_vacacion
        WHERE id_trabajador = ?
        AND v.id_pdeclaracion = ?  
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>