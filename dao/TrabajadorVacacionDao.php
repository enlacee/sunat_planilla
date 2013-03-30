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
                         id_empresa_centro_costo,
                         id_establecimiento,
                         cod_ocupacion_p,
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
            $stm->bindValue(10, $model->getId_empresa_centro_costo());
            $stm->bindValue(11, $model->getId_establecimiento());
            $stm->bindValue(12, $model->getCod_ocupacion_p());
            $stm->bindValue(13, $model->getFecha_creacion());

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

    public function update($obj) {

        $query = "
            UPDATE trabajadores_vacaciones
            SET 
              fecha_lineal = ?,
              dia = ?,
              sueldo = ?,
              sueldo_base = ?,
              proceso_porcentaje = ?,
              cod_regimen_pensionario = ?,
              cod_regimen_aseguramiento_salud = ?,
              id_empresa_centro_costo = ?,
              id_establecimiento = ?,
              cod_ocupacion_p = ?,
              fecha_actualizacion = ?
            WHERE id_trabajador_vacacion = ?;
";
        //Inicia transaccion
        $model = new TrabajadorVacacion();
        $model = $obj;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getFecha_lineal());
        $stm->bindValue(2, $model->getDia());
        $stm->bindValue(3, $model->getSueldo());
        $stm->bindValue(4, $model->getSueldo_base());
        $stm->bindValue(5, $model->getProceso_porcentaje());
        $stm->bindValue(6, $model->getCod_regimen_pensionario());
        $stm->bindValue(7, $model->getCod_regimen_aseguramiento_salud());
        $stm->bindValue(8, $model->getId_empresa_centro_costo());
        $stm->bindValue(9, $model->getId_establecimiento());
        $stm->bindValue(10, $model->getCod_ocupacion_p());
        $stm->bindValue(11, $model->getFecha_actualizacion());
        $stm->bindValue(12, $model->getId_trabajador_vacacion());
        $stm->execute();
        $stm = null;  
        //echo "\n model->getId_trabajador_vacacion() = ".$model->getId_trabajador_vacacion();
        return true;       

    }

    function existe($id_pdeclaracion, $id_trabajador) {
        $query = "
        SELECT 
        id_trabajador_vacacion
        FROM trabajadores_vacaciones
        WHERE 1=1
        AND id_pdeclaracion = ?
        AND id_trabajador = ?           
        ";
        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $this->pdo->commit();
        $stm = null;
        return $lista[0]['id_trabajador_vacacion'];
    }

    // new function usado en reporte afp
    function buscarAttr($id_trabajador_vacacion,$attributo){
        $query ="
        SELECT 
        $attributo
        FROM trabajadores_vacaciones
        WHERE id_trabajador_vacacion = ?;         
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_vacacion);        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0][$attributo];        
        
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
        //echo "\nListar";
        //echoo($query);
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
        return $lista[0]['counteo'];
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

        //echoo($query);
       // echo "id = " . $id_pdeclaracion;
        return true;
    }
    
    // reporte vacacion
    function listarReporteVacacion($id_pdeclaracion,$id_establecimiento,$id_empresa_centro_costo){
      
        $query = "
    SELECT 
    tv.id_trabajador_vacacion,
    tv.id_trabajador,
    tv.fecha_lineal,
    tv.dia,
    tv.cod_regimen_pensionario,
    tv.cod_regimen_aseguramiento_salud,
    tv.fecha_creacion,
    tv.fecha_actualizacion,
    -- persona
    p.id_persona,
    p.apellido_materno,
    p.apellido_paterno,
    p.nombres ,
    p.num_documento,
    -- ocupacion
    op.nombre AS nombre_ocupacion,
    -- regimen pensionario
    rp.descripcion_abreviada AS nombre_afp,
    -- centro costo
    ecc.descripcion AS nombre_centro_costo

    FROM trabajadores_vacaciones AS  tv     
    INNER JOIN trabajadores AS t
    ON tv.id_trabajador = t.id_trabajador
    INNER JOIN personas AS p
    ON t.id_persona = p.id_persona
    LEFT JOIN ocupaciones_p AS  op
    ON tv.cod_ocupacion_p = op.cod_ocupacion_p
    LEFT JOIN regimenes_pensionarios AS rp
    ON tv.cod_regimen_pensionario = rp.cod_regimen_pensionario
    LEFT JOIN empresa_centro_costo  AS ecc
    ON tv.id_empresa_centro_costo = ecc.id_empresa_centro_costo

    WHERE tv.id_pdeclaracion = ?
    AND tv.id_establecimiento = ?
    AND tv.id_empresa_centro_costo = ?
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_establecimiento);
        $stm->bindValue(3, $id_empresa_centro_costo);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;        
        
    }
  
    // reporte planilla 'vacacion'
    function listarPlanilla($id_pdeclaracion){
        $query ="
        SELECT
          tpdv.id_trabajador_vacacion,          
          tpdv.id_trabajador,
          tpdv.dia,
          p.id_persona,
          p.apellido_materno,
          p.apellido_paterno,
          p.nombres ,
          p.num_documento
        FROM trabajadores_vacaciones AS tpdv
        LEFT JOIN trabajadores AS t
        ON tpdv.id_trabajador = t.id_trabajador
        LEFT JOIN personas AS p
        ON t.id_persona = p.id_persona
        WHERE tpdv.id_pdeclaracion = ?            
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;        
        
        
    }
    
}

/*
  $dao = new TrabajadorVacacionDao();
  $data = $dao->getDiaVacacion(24, 5);
  echoo($data);
 */
?>
