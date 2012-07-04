<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VinculoFamiliarDao
 *
 * @author conta 1
 */
class VinculoFamiliarDao  extends AbstractDao{
    //put your code here


    public function cantidadPesonas() {//OK
        $query = "
            SELECT COUNT(*) AS numfilas
            FROM personas";

        try {

            $stm = $this->pdo->prepare($query);

            $stm->execute();

            $lista = $stm->fetchAll();

            return $lista[0]["numfilas"];

            $stm = null;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function listarPersonas($WHERE, $start, $limit, $sidx, $sord) {

        // $query = "SELECT *FROM personas $WHERE  ORDER BY $sidx $sord LIMIT $start $limit";

        $query = "
	SELECT 
		p.id_persona,
		td.descripcion_abreviada AS nombre_tipo_documento,
		p.num_documento,
		p.apellido_paterno,
		p.apellido_materno,
		p.nombres,
		p.fecha_nacimiento,
		p.sexo,
		p.estado, -- RELACIONADO COD_SITUACION TRABAJADOR U OTRA CATEGORIA.
		
		IF (p.tabla_trabajador = 1,'TRA','0') AS categoria_1,
		IF (p.tabla_pensionista = 1,'PEN','0') AS categoria_2,
		IF (p.tabla_personal_formacion_laboral = 1,'PFOR','0') AS categoria_3,
		IF (p.tabla_personal_terceros = 1,'PTER','0') AS categoria_4	
		
	 FROM personas AS p        
         INNER JOIN tipos_documentos AS td
	 ON p.cod_tipo_documento = td.cod_tipo_documento
        
         WHERE p.estado='ACTIVO'
        
        $WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit
        ";
        // echo $query;
        try {

            $stm = $this->pdo->prepare($query);
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

    public function buscarPersonaPorId($id_persona) {

        //$query ='SELECT *FROM personas WHERE id_persona = ?';
        $query = "
	SELECT 
	id_persona,
	id_empleador,
	cod_pais_emisor_documento,
	cod_tipo_documento,
	cod_nacionalidad,
	num_documento,
        fecha_nacimiento,
	apellido_paterno,
	apellido_materno,
	nombres,
        sexo,
        id_estado_civil,
        cod_telefono_codigo_nacional,
        telefono,
        correo,
        tabla_trabajador,
        tabla_pensionista,
        tabla_personal_formacion_laboral,
        tabla_personal_terceros,
        estado,
        fecha_creacion,
        fecha_modificacion,
        fecha_baja    

	FROM personas 	
	WHERE id_persona = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_persona);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";

        return $data[0];
    }
	
	
	
	public function eliminarPersona($id){
	
		$estado = 'INACTIVO';
		
        $query = "
        UPDATE personas
        SET estado = ?
        WHERE id_persona = ?
        ";
		
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $estado);
	$stm->bindValue(2, $id);
        $stm->execute();
		           
        return true;       

	}
	
    
}

?>
