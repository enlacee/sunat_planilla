<?php

/*
  require_once '../util/funciones.php';
  require_once '../dao/AbstractDao.php';
 */

class TrabajadorVacacionDao extends AbstractDao {

    function add($obj) {
        try {
            $query = "
            INSERT INTO trabajadores_vacaciones
                        (
                         id_pdeclaracion,
                         id_trabajador,
                         fecha_lineal,
                         dia,
                         sueldo,
                         sueldo_base,
                         proceso_porcentaje,
                         cod_regimen_pensionario,
                         cod_regimen_aseguramiento_salud,
                         fecha_creacion)
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
                    ?);
                    ";
            //Inicia transaccion
            $model = new TrabajadorVacacion();
            $model = $obj;
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_pdeclaracion());
            $stm->bindValue(2, $model->getId_trabajador());
            $stm->bindValue(3, $model->getFecha_lineal());
            $stm->bindValue(4, $model->getDia());
            $stm->bindValue(5, $model->getSueldo());
            $stm->bindValue(6, $model->getSueldo_base());
            $stm->bindValue(7, $model->getProceso_porcentaje());
            $stm->bindValue(8, $model->getCod_regimen_pensionario());
            $stm->bindValue(9, $model->getCod_regimen_aseguramiento_salud());
            $stm->bindValue(10, $model->getFecha_creacion());

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

    // unico registro pero posible 2 a mas en el mismo mes.
    function listarTv($id_pdeclaracion, $id_trabajador) {
        $query = "
        SELECT
          id_trabajador_vacacion,
          dia,
          proceso_porcentaje
        FROM trabajadores_vacaciones
        WHERE id_pdeclaracion = ?
        AND id_trabajador = ?          
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

    // new para planilla vacacion 
    function listar_HIJO($id_pdeclaracion) {

        $query = "
        SELECT 
	id_trabajador	
        FROM trabajadores_vacaciones
	WHERE id_pdeclaracion = ?          
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    function listar($id_pdeclaracion, $WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }
        $query = "
	SELECT  tv.id_trabajador_vacacion,
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
        INNER JOIN trabajadores_vacaciones AS tv
        ON t.id_trabajador = tv.id_trabajador
	WHERE tv.id_pdeclaracion = ?
        $cadena
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    function listarCount($id_pdeclaracion, $WHERE) {


        $query = "
	SELECT  
        count(*) as counteo		
        FROM personas AS p        
        INNER JOIN tipos_documentos AS td
        ON p.cod_tipo_documento = td.cod_tipo_documento
        INNER JOIN empleadores_maestros AS em
        ON p.id_empleador = em.id_empleador
        INNER JOIN trabajadores AS t
        ON p.id_persona = t.id_persona        
        INNER JOIN trabajadores_vacaciones AS tv
        ON t.id_trabajador = tv.id_trabajador
	WHERE tv.id_pdeclaracion = ?
        $WHERE
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista['counteo'];
    }

    function eliminar($id) {

        $query = "
        DELETE
        FROM trabajadores_vacaciones
        WHERE id_trabajador_vacacion = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        return true;
    }

    function eliminarAll($id_pdeclaracion) {
        $query = "
        DELETE
        FROM trabajadores_vacaciones
        WHERE id_pdeclaracion = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        
        echoo($query);
        echo "id = ".$id_pdeclaracion;        
        return true;
    }

}

/*
  $dao = new TrabajadorVacacionDao();
  $data = $dao->getDiaVacacion(24, 5);
  echoo($data);
 */
?>
