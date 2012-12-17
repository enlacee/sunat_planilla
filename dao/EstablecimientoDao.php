<?php

class EstablecimientoDao extends AbstractDao {

//END FUNCION
//Registrar Producto and Imagen
    public function registrarEstablecimiento($obj_establecimiento) {

        try {
            $query = "
            INSERT INTO establecimientos
                (
		cod_establecimiento,
                id_empleador,
                id_tipo_establecimiento,
                fecha_creacion)
            VALUES (?,
                    ?,
                    ?,
                    ?)
            ";
            //Inicia transaccion 

            $em = new Establecimiento();
            $em = $obj_establecimiento;

            $this->pdo->beginTransaction();

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $em->getCod_establecimiento());
            $stm->bindValue(2, $em->getId_empleador());
            $stm->bindValue(3, $em->getId_tipo_establecimiento());
            $stm->bindValue(4, $em->getFecha_creacion());
            $stm->execute();

            //  id Comerico
            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();
            $this->pdo->commit();
            //finaliza transaccion
            //$id_establecimiento = $lista[0]['id'];

            return $lista[0]['id'];

//			return true;

            $stm = null;
        } catch (Exception $e) {
            //  Util::rigistrarLog( $e, $query );
            $this->pdo->rollBack();
            throw $e;
        }
    }

    //-----------------------------------------------------------------------

    public function actualizarEstablecimiento($objto) {

        $obj = new Establecimiento();
        $obj = $objto;

        $query = "
        UPDATE establecimientos
        SET 
          id_empleador = ?,
          id_tipo_establecimiento = ?,
          cod_establecimiento = ?,
          fecha_creacion = ?
        WHERE id_establecimiento = ?;            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $obj->getId_empleador());
        $stm->bindValue(2, $obj->getId_tipo_establecimiento());
        $stm->bindValue(3, $obj->getCod_establecimiento());
        $stm->bindValue(4, $obj->getFecha_creacion());
        $stm->bindValue(5, $obj->getId_establecimiento());
        $stm->execute();
        //$lista = $stm->fetchAll();
        
        return true;
    }
	
/**
* USADO PARA JQGRID
**/
function actualizarEstablecimiento_2($id,$actividad_de_riesgo){
        $query = "
		UPDATE establecimientos  
		SET realizaran_actividad_riesgo = '$actividad_de_riesgo'
		WHERE id_establecimiento = '$id'";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $stm = null;
	//echo $query;
        return true;
	
}

//
    public function cantidadEstablecimiento($id) {//OK
        $query = "
            SELECT COUNT(*) AS numfilas
            FROM establecimientos
            WHERE id_empleador = ?            
            ";

        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id);
            $stm->execute();

            $lista = $stm->fetchAll();

            return $lista[0]["numfilas"];

            $stm = null;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function eliminarEstablecimiento($id) {

        $query = "DELETE FROM establecimientos WHERE id_establecimiento = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function listarEstablecimientos($id_empleador, $WHERE, $start, $limit, $sidx, $sord) {

        // $query = "SELECT *FROM empleadores $WHERE  ORDER BY $sidx $sord LIMIT $start $limit";

        $query = "
        SELECT
          e.id_establecimiento,
          e.cod_establecimiento,
          e.id_empleador,
          e.id_tipo_establecimiento,
          e.realizaran_actividad_riesgo,
          e.fecha_creacion,
          emp.ruc AS ruc_empleador,
          te.cod_tipo_establecimiento AS codigo_establecimiento,
          te.descripcion AS nombre_establecimiento
        FROM establecimientos AS e
        INNER JOIN empleadores AS emp
        ON e.id_empleador = emp.id_empleador

        INNER JOIN tipos_establecimientos AS te
        ON e.id_tipo_establecimiento = te.id_tipo_establecimiento
        
        WHERE e.id_empleador = ?
        
        $WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
        ";
        // echo $query;
        try {

            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_empleador);
            $stm->execute();
            $lista = $stm->fetchAll();

            if (count($lista) == 0) {
                return false;
            }

            return $lista;
        } catch (PDOException $e) {

            throw $e;
        }
    }
    
    //new
    function domicilioFiscal($id_empleador){
    
    $query ="
    SELECT
    ud.descripcion AS ubigeo_departamento,
    up.descripcion AS ubigeo_provincia,
    ur.descripcion  AS ubigeo_distrito,
    v.descripcion AS ubigeo_nombre_via,
    ed.nombre_via,
    ed.numero_via,
    ed.departamento,
    ed.interior,
    ed.manzana,
    ed.lote,
    ed.kilometro,
    ed.block,
    ed.etapa,
    z.descripcion AS ubigeo_nombre_zona,
    ed.nombre_zona

    FROM establecimientos AS est
    INNER JOIN establecimientos_direcciones AS  ed
    ON est.id_establecimiento = ed.id_establecimiento

    INNER JOIN vias AS v
    ON ed.cod_via = v.cod_via

    INNER JOIN ubigeo_reniec AS  ur
    ON ed.cod_ubigeo_reniec = ur.cod_ubigeo_reniec

    INNER JOIN ubigeo_provincias AS  up
    ON ur.cod_provincia = up.cod_provincia

    INNER JOIN ubigeo_departamentos AS ud
    ON ur.cod_departamento = ud.cod_departamento

    INNER JOIN zonas AS z
    ON ed.cod_zona = z.cod_zona

    WHERE est.id_empleador = ?
    AND est.id_tipo_establecimiento = 1;            
";
    $stm = $this->pdo->prepare($query);
    $stm->bindValue(1,$id_empleador);
    $stm->execute();
    $lista = $stm->fetchAll();
    $cadena = $lista[0]['nombre_via'].' '.$lista[0]['numero_via'].' - '.$lista[0]['ubigeo_distrito']; 
    return $cadena;      
    
    }
    
    //edit 06/09/2012 ->Usado en Planilla
    function listar_Ids_Establecimientos($id_empleador){
        $query ="
        SELECT 
        e.ruc,
        est.id_establecimiento
        FROM empleadores AS e
        INNER JOIN establecimientos AS est
        ON e.id_empleador = est.id_empleador
        WHERE e.id_empleador = ?";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        
        return $lista;       
        
    }



    public function listarEstablecimientos2($id_empleador, $WHERE, $start, $limit, $sidx, $sord) {

        $query = "
SELECT 
emp.id_empleador,
est.id_establecimiento,
est.cod_establecimiento,
est.realizaran_actividad_riesgo,
est_d.cod_ubigeo_reniec,
ud.descripcion AS ubigeo_departamento,
up.descripcion AS ubigeo_provincia,
ur.descripcion  AS ubigeo_distrito,
est_d.cod_via, v.descripcion AS nombre_via,
est_d.cod_via,
est_d.numero_via,
est_d.departamento,
est_d.interior,
est_d.manzana,
est_d.lote,
est_d.kilometro,
est_d.block,
est_d.etapa,
est_d.cod_zona,
z.descripcion AS nombre_zona,
est_d.nombre_zona,
est_d.referencia


FROM empleadores AS emp
INNER JOIN establecimientos AS est
ON emp.id_empleador = est.id_empleador

INNER JOIN establecimientos_direcciones AS est_d
ON est.id_establecimiento =est_d.id_establecimiento

INNER JOIN vias AS v
ON est_d.cod_via = v.cod_via

INNER JOIN ubigeo_reniec AS  ur
ON est_d.cod_ubigeo_reniec = ur.cod_ubigeo_reniec


INNER JOIN ubigeo_provincias AS  up
ON ur.cod_provincia = up.cod_provincia

INNER JOIN ubigeo_departamentos AS ud
ON ur.cod_departamento = ud.cod_departamento

INNER JOIN zonas AS z
ON est_d.cod_zona = z.cod_zona

WHERE emp.id_empleador = ?
	
$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
		$stm = $this->pdo->prepare($query);
		$stm->bindValue(1, $id_empleador);
		$stm->execute();
		$lista = $stm->fetchAll();		
		return  $lista;
		$stm=null;
    }
	//----------------------------------------------------------------------
    //----------------------------------------------------------------------
    //--------  OTRAS CONSULTAS SQL
    /**
     * OJO ver Tabla tipos de Establecimiento 
     * registros ingresados:::: id_tipo_establecimiento ::::
     * 1 = DOMICILIO FISCAL  !!
     * 2 = SUCRUSAL 
     */
    public function numeroDeEstablecimientoFISCAL($id_empleador) {
        $query = "
		SELECT 
		COUNT(*) AS numfilas
		FROM empleadores AS e
		INNER JOIN establecimientos AS est
		ON e.id_empleador = est.id_empleador
		
		INNER JOIN tipos_establecimientos AS te
		ON est.id_tipo_establecimiento = te.id_tipo_establecimiento
		
		WHERE e.id_empleador = ? AND est.id_tipo_establecimiento = 1
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]["numfilas"];
    }

    /*
     *
     * ValidarCodigoEsTablecimiento : 0001, 0002 ...
     *
     */

    public function numeroDeCodigoEstablecimientoPorIdEmpleador($id_empleador, $cod_establecimiento) {
        $query = "
		SELECT 
		-- est.id_establecimiento,
		-- est.cod_establecimiento,
		COUNT(est.cod_establecimiento) AS numfilas
		
		FROM empleadores AS e
		INNER JOIN establecimientos AS est
		ON e.id_empleador = est.id_empleador
		
		INNER JOIN tipos_establecimientos AS te
		ON est.id_tipo_establecimiento = te.id_tipo_establecimiento
		
		WHERE e.id_empleador = ?
		AND est.cod_establecimiento = ? ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $cod_establecimiento);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]["numfilas"];
    }

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//--------------------FUNCION EXCLUIDA OK---------------------------
//----------------------------------------------------------------------
//----------------------------------------------------------------------
    public function buscarEstablecimientoPorId($id) {
        $query = "SELECT *FROM establecimientos WHERE id_establecimiento = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

//End Class
?>