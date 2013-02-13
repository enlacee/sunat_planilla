<?php

class ParatiFamiliaDao extends AbstractDao {

    public function add($obj) {
        $model = new ParatiFamilia();
        $model = $obj;
        $query = "
        INSERT INTO para_ti_familia
                    (
                    id_empleador,
                    id_trabajador,
                    id_tipo_para_ti_familia,
                    estado,
                    fecha_inicio,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getId_tipo_para_ti_familia());
        $stm->bindValue(4, $model->getEstado());
        $stm->bindValue(5, $model->getFecha_inicio());
        $stm->bindValue(6, $model->getFecha_creacion());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function edit($obj) {
        $model = new ParatiFamilia();
        $model = $obj;
        $query = "
        UPDATE para_ti_familia
        SET 
        id_tipo_para_ti_familia = ?,
        fecha_inicio =  ?
        WHERE id_para_ti_familia = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_tipo_para_ti_familia());
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getId_para_ti_familia());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    function listarPTFPeriodo($id_empleador,$periodo, $WHERE, $start = null, $limit = null, $sidx = null, $sord = null){
        
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
        SELECT
        ptf.id_para_ti_familia,        
        ptf.id_trabajador,
	tptf.descripcion,        
	p.num_documento,
	p.apellido_paterno,
	p.apellido_materno,
	p.nombres	
        
        FROM para_ti_familia AS ptf
	INNER JOIN tipo_para_ti_familia AS tptf
	ON ptf.id_tipo_para_ti_familia = tptf.id_tipo_para_ti_familia        
        
        INNER JOIN trabajadores AS t
	ON ptf.id_trabajador = t.id_trabajador
	
	INNER JOIN personas AS p
	ON p.id_persona = t.id_persona
        WHERE ptf.id_empleador = ?
        AND ptf.fecha_inicio <='$periodo'        
        AND t.cod_situacion = 1
        $cadena
        ";
        //echo " id_empleador =". $id_empleador;
        //echo $query;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista; 
        
    }

    function listarPTFCount($id_empleador,$periodo, $WHERE){
                
        $query ="
        SELECT
	COUNT(ptf.id_para_ti_familia)AS counteo
        
        FROM para_ti_familia AS ptf
	INNER JOIN tipo_para_ti_familia AS tptf
	ON ptf.id_tipo_para_ti_familia = tptf.id_tipo_para_ti_familia        
        
        INNER JOIN trabajadores AS t
	ON ptf.id_trabajador = t.id_trabajador
	
	INNER JOIN personas AS p
	ON p.id_persona = t.id_persona
        WHERE ptf.id_empleador = ?
        AND ptf.fecha_inicio <='$periodo'        
        AND t.cod_situacion = 1
        $WHERE;
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
        
    }

// para validar y for
    public function listarTrabajador_Registrados($id_empleador) {

        $query = "
        SELECT
        ptf.id_para_ti_familia,
        ptf.id_empleador,
        ptf.id_trabajador	
        
        FROM para_ti_familia AS ptf  
        
        INNER JOIN trabajadores AS t
	ON ptf.id_trabajador = t.id_trabajador
	
	INNER JOIN personas AS p
	ON p.id_persona = t.id_persona
              
        WHERE ptf.id_empleador = ?
        
        AND ptf.estado = 1  
	AND t.cod_situacion = 1 
        ";

        // Cosulta de trabajadores
        // THIS TABLA VINCULADA!!

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    //FUCK DELETE!!! VINCULO COn TRABAJADOR 
    public function baja($id) {

        $query = "
        UPDATE para_ti_familia
        SET
        estado = 0 
        WHERE id_para_ti_familia = ?;      
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }
    
    public function del($id){
        $query = "
        DELETE
        FROM para_ti_familia
        WHERE id_para_ti_familia = ?;
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();        
        $stm = null;
        return true;   
        
    }
    public function buscar_data_id($id) {

        $query = "
        SELECT
        ptf.id_para_ti_familia,
        ptf.id_empleador,
        ptf.id_trabajador,
        ptf.id_tipo_para_ti_familia,
        ptf.estado,
        ptf.fecha_inicio,
        ptf.fecha_creacion,
        p.cod_tipo_documento,
        p.num_documento,
        p.apellido_paterno,
        p.apellido_materno,
        p.nombres

        FROM para_ti_familia AS ptf
        INNER JOIN trabajadores AS t
        ON ptf.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON p.id_persona = t.id_persona
        WHERE ptf.id_para_ti_familia =  ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }
    
    
        // UTILIZADO EN trabajadorPdeclaracionController.php
    public function buscar_idTrabajador($id_trabajador, $periodo) {

        $query = "
        SELECT 
        ptf.id_para_ti_familia,
        tptf.valor
        FROM para_ti_familia AS ptf
	INNER JOIN tipo_para_ti_familia AS tptf
	ON ptf.id_tipo_para_ti_familia = tptf.id_tipo_para_ti_familia
        
        INNER JOIN trabajadores AS t
	ON ptf.id_trabajador = t.id_trabajador

        WHERE ptf.id_trabajador = ?
        AND ptf.fecha_inicio <='$periodo'                   
        ";
        // AND t.cod_situacion = 1; 
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }
    
  
    // Utilizado para reporte mensual !!!!! == funcion gemela
    public function getPagoCuotaPorPeriodo_Reporte($id_pdeclaracion,$id_trabajador){
        
        $query = "
        SELECT
        ptfp.valor  

        FROM para_ti_familia AS ptf
        INNER JOIN ptf_pagos AS ptfp
        ON ptf.id_para_ti_familia = ptfp.id_para_ti_familia
        WHERE ptf.id_trabajador = ?
        AND ptfp.id_pdeclaracion = ?
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['valor'];      
        
    }
    
    
 

}

?>
