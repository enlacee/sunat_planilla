<?php

class TrabajadorPdeclaracionDao extends AbstractDao {

    function buscar_IDOject($id){        
        $query = "
        SELECT
          id_trabajador_pdeclaracion,
          id_pdeclaracion,
          id_trabajador,
          dia_laborado,
          dia_total,
          ordinario_hora,
          ordinario_min,
          sobretiempo_hora,
          sobretiempo_min,
          sueldo,
          sueldo_base,
          fecha_creacion,
          cod_tipo_trabajador,
          cod_regimen_pensionario,
          cod_regimen_aseguramiento_salud,
          cod_situacion

        FROM trabajadores_pdeclaraciones
        WHERE id_trabajador_pdeclaracion = ?
      ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        $data = $lista[0];
        
        $obj = new TrabajadorPdeclaracion();
        $obj->setId_trabajador_pdeclaracion($data['id_trabajador_pdeclaracion']);
        $obj->setId_pdeclaracion($data['id_pdeclaracion']);
        $obj->setId_trabajador($data['id_trabajador']);
        $obj->setDia_laborado($data['dia_laborado']);
        $obj->setDia_total($data['dia_total']);
        $obj->setOrdinario_hora($data['ordinario_hora']);
        $obj->setOrdinario_min($data['ordinario_min']);
        $obj->setSobretiempo_hora($data['sobretiempo_hora']);
        $obj->setSobretiempo_min($data['sobretiempo_min']);
        $obj->setSueldo($data['sueldo']);
        $obj->setSueldo_base($data['sueldo_base']);
        $obj->setFecha_creacion($data['fecha_creacion']);
        $obj->setCod_tipo_trabajador($data['cod_tipo_trabajador']);
        $obj->setCod_regimen_pensionario($data['cod_regimen_pensionario']);
        $obj->setCod_regimen_aseguramiento_salud($data['cod_regimen_aseguramiento_salud']);
        $obj->setCod_situacion($data['cod_situacion']);
        
        return $obj;
        
        
    }
    
    
    public function registrar($obj) {


        try {
            $query = "
            INSERT INTO trabajadores_pdeclaraciones
                        (
                    id_pdeclaracion,
                    id_trabajador,
                    dia_laborado,
                    dia_total,
                    ordinario_hora,
                    ordinario_min,
                    sueldo,
                    sueldo_base,                    
                    fecha_creacion,                                       
                    cod_tipo_trabajador,
                    cod_regimen_pensionario,
                    cod_regimen_aseguramiento_salud,
                    cod_situacion,
                    cod_ocupacion_p,
                    id_empresa_centro_costo
                         
                            )
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
                    ?,
                    ?,
                    ?
                    ); ";
            //Inicia transaccion
            $model = new TrabajadorPdeclaracion();
            $model = $obj;
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_pdeclaracion());
            $stm->bindValue(2, $model->getId_trabajador());
            $stm->bindValue(3, $model->getDia_laborado());
            $stm->bindValue(4, $model->getDia_total());
            $stm->bindValue(5, $model->getOrdinario_hora());
            $stm->bindValue(6, $model->getOrdinario_min());
            $stm->bindValue(7, $model->getSueldo());
            $stm->bindValue(8, $model->getSueldo_base());            
            $stm->bindValue(9, $model->getFecha_creacion());
                        
            $stm->bindValue(10, $model->getCod_tipo_trabajador());
            $stm->bindValue(11, $model->getCod_regimen_pensionario());
            $stm->bindValue(12, $model->getCod_regimen_aseguramiento_salud());
            $stm->bindValue(13, $model->getCod_situacion());

            $stm->bindValue(14, $model->getCod_ocupacion_p());
            $stm->bindValue(15, $model->getId_empresa_centro_costo());
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
   
    //
    public function actualizar($obj) {

        $query = "
    UPDATE trabajadores_pdeclaraciones
    SET 
      dia_total = ?,
      dia_laborado = ?,  
      ordinario_hora = ?,
      ordinario_min = ?,
      sobretiempo_hora = ?,
      sobretiempo_min = ?,
      sueldo = ?,
      sueldo_base = ?,  
      fecha_modificacion = ?
    WHERE id_trabajador_pdeclaracion = ?;
      ";
        $model = new TrabajadorPdeclaracion();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getDia_total());
        $stm->bindValue(2, $model->getDia_laborado());
        $stm->bindValue(3, $model->getOrdinario_hora());
        $stm->bindValue(4, $model->getOrdinario_min());
        $stm->bindValue(5, $model->getSobretiempo_hora());
        $stm->bindValue(6, $model->getSobretiempo_min());
        $stm->bindValue(7, $model->getSueldo());
        $stm->bindValue(8, $model->getSueldo_base());        
        $stm->bindValue(10, $model->getId_trabajador_pdeclaracion());

        $stm->execute();
        $stm = null;

        return true;
    }


    public function eliminar($id_trabajador_pdeclaracion) {

        $query = "		
        DELETE
        FROM trabajadores_pdeclaraciones
        WHERE id_trabajador_pdeclaracion = ?;
	";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        $stm = null;
        return true;
    }



    function buscar_what($id_pdeclaracion,$id_trabajador,$atributo){
        $query = "
        SELECT          
            $atributo
        FROM trabajadores_pdeclaraciones
        WHERE id_pdeclaracion = ?
        AND id_trabajador = ?
      ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]["$atributo"];        
        
    }
    public function buscar_ID($id) {
        $query = "
        SELECT
          id_trabajador_pdeclaracion,
          id_pdeclaracion,
          id_trabajador,
          dia_laborado,
          dia_total,
          ordinario_hora,
          ordinario_min,
          sobretiempo_hora,
          sobretiempo_min,
          sueldo,
          sueldo_base,          
          fecha_creacion,
          cod_tipo_trabajador,
          cod_regimen_pensionario,
          cod_regimen_aseguramiento_salud,
          cod_situacion

        FROM trabajadores_pdeclaraciones
        WHERE id_trabajador_pdeclaracion = ?
      ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

    function listarCount($id_pdeclaracion, $WHERE) {

        $query = "
        SELECT
        count(*) as counteo

        FROM  trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona        
        $WHERE

        WHERE tpd.id_pdeclaracion = ?            
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }

    function listar($id_pdeclaracion, $WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }


        $query = "
        SELECT
          tpd.id_trabajador_pdeclaracion,
          tpd.id_trabajador,
          tpd.dia_laborado,
          tpd.dia_total,
          tpd.ordinario_hora,
          tpd.ordinario_min,
          tpd.sobretiempo_hora,
          tpd.sobretiempo_min,
          tpd.sueldo,
          tpd.sueldo_base,
        per.cod_tipo_documento,
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres 

        FROM  trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona
        WHERE tpd.id_pdeclaracion = ?
        $cadena
        
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        if ($op == 'id_trabajador') { // ['id_trabajador']
            $new = array();
            for ($i = 0; $i < count($lista); $i++) {
                $new[]['id_trabajador'] = $lista[$i]['id_trabajador'];
            }
            return $new;
        }

        return $lista;
    }

    function listar_HIJO($id_pdeclaracion) {

        $query = "
        SELECT
          tpd.id_trabajador_pdeclaracion,
          tpd.id_trabajador
          
        FROM  trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona
        WHERE tpd.id_pdeclaracion = ?            
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }


    
    
    public function buscar_ID_GRID_LINEAL($id_trabajadorpdeclaracion) {

        $query = "
        SELECT
          tpd.id_trabajador_pdeclaracion,
          tpd.id_trabajador,
          tpd.dia_laborado,
          tpd.dia_total,
          tpd.ordinario_hora,
          tpd.ordinario_min,
          tpd.sobretiempo_hora,
          tpd.sobretiempo_min,
          tpd.sueldo,
          tpd.sueldo_base,
        per.cod_tipo_documento,
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres 

        FROM  trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona


        WHERE id_trabajador_pdeclaracion = ?       
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajadorpdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    //->new uso para reportes    
    public function listar_2($id_pdeclaracion, $id_establecimiento, $id_empresa_centro_costo) {

        $query = "
        SELECT
          tpd.id_trabajador_pdeclaracion,
          tpd.id_pdeclaracion,
          tpd.id_trabajador,
          tpd.dia_laborado,
          tpd.ordinario_hora,
          tpd.sueldo_base,
          tpd.cod_tipo_trabajador,
          tpd.cod_regimen_pensionario,
          tpd.cod_regimen_aseguramiento_salud,
          tpd.cod_situacion,
          tpd.id_empresa_centro_costo,
          tpd.cod_ocupacion_p,
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

        FROM trabajadores_pdeclaraciones AS tpd
        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona

        LEFT JOIN ocupaciones_p AS  op
        ON tpd.cod_ocupacion_p = op.cod_ocupacion_p

        LEFT JOIN regimenes_pensionarios AS rp
        ON tpd.cod_regimen_pensionario = rp.cod_regimen_pensionario

        LEFT JOIN empresa_centro_costo  AS ecc
        ON tpd.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE tpd.id_pdeclaracion = ?
        AND t.id_establecimiento = ?
        AND ecc.id_empresa_centro_costo = ?              
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

    //->new uso para reportes    
    public function listar_3($id_pdeclaracion) {

        $query = "
        SELECT
          tpd.id_trabajador_pdeclaracion,          
          tpd.id_trabajador,
          tpd.dia_laborado,
          tpd.ordinario_hora,
          tpd.sobretiempo_hora,        
          -- persona
          p.id_persona,
          p.apellido_materno,
          p.apellido_paterno,
          p.nombres ,
          p.num_documento

        FROM trabajadores_pdeclaraciones AS tpd
        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona        

        WHERE tpd.id_pdeclaracion = ?
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    //------------------------------------------------------------------------//
    // ------------- FUNCION ESTRUTURAS---------------------------------------//
    // USADO en Estructuras
    public function listar_ID_Trabajador($id_pdeclaracion) {

        $query = "
        SELECT
        id_trabajador_pdeclaracion,        
        id_trabajador
        FROM trabajadores_pdeclaraciones
        WHERE id_pdeclaracion = ?
    ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function estrutura_14($id_pdeclaracion, $id_trabajador) {

        $query = "
        SELECT  
        tpd.ordinario_hora,
        tpd.ordinario_min,
        tpd.sobretiempo_hora,
        tpd.sobretiempo_min,
        p.cod_tipo_documento,
        p.num_documento


        FROM trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona
        WHERE tpd.id_pdeclaracion = ?
        AND tpd.id_trabajador = ?
    ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }

    public function estrutura_15_a($id_pdeclaracion, $id_trabajador) {

        $query = "
        SELECT 
        ds.cod_tipo_suspen_relacion_laboral,
        ds.cantidad_dia,
        p.cod_tipo_documento,
        p.num_documento

        FROM trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona

        INNER JOIN dias_subsidiados AS ds
        ON tpd.id_trabajador_pdeclaracion = ds.id_trabajador_pdeclaracion
        WHERE tpd.id_pdeclaracion = ?
        AND tpd.id_trabajador = ?
    ";

        // OJO 
        // INNER JOIN dias_subsidiados AS ds  = LEFT JOIN
        // Si quires imprimir Vacios  OPcional revisar SQL. ARRIVA.    

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function estrutura_15_b($id_pdeclaracion, $id_trabajador) {

        $query = "
        SELECT 
        dns.cod_tipo_suspen_relacion_laboral,
        dns.cantidad_dia,
        p.cod_tipo_documento,
        p.num_documento

        FROM trabajadores_pdeclaraciones AS tpd

        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona

        INNER JOIN dias_nosubsidiados AS dns
        ON tpd.id_trabajador_pdeclaracion = dns.id_trabajador_pdeclaracion
        WHERE tpd.id_pdeclaracion = ?
        AND tpd.id_trabajador = ?
    ";

        // OJO 
        // INNER JOIN dias_nosubsidiados AS dns  = LEFT JOIN
        // Si quires imprimir Vacios  OPcional revisar SQL. ARRIVA.

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function estrutura_18($id_pdeclaracion, $id_trabajador) {

        $query = "
        SELECT 
	tpd.id_trabajador_pdeclaracion,
	tpd.id_pdeclaracion,
	tpd.id_trabajador,
	ddc.cod_detalle_concepto,
	ddc.monto_devengado,
	ddc.monto_pagado,
        p.cod_tipo_documento,
        p.num_documento

        FROM trabajadores_pdeclaraciones AS tpd	
	INNER JOIN declaraciones_dconceptos AS ddc
	ON tpd.id_trabajador_pdeclaracion = ddc.id_trabajador_pdeclaracion
        
        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona

        WHERE tpd.id_pdeclaracion = ?
        AND tpd.id_trabajador = ?
        -- ojo plame sale error
        AND (ddc.cod_detalle_concepto != '0804')
        AND (ddc.cod_detalle_concepto != '0607');
    ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    //Utilizado para limpiar periodo del mes. utilizado full 2 tablas 27/09/2012
    //
    public function eliminarDatosMes($id_pdeclaracion) {
        //SELECT *FROM etapas_pagos
        //SELECT *FROM trabajadores_pdeclaraciones        
        $query = "
        DELETE FROM trabajadores_pdeclaraciones        
        WHERE id_pdeclaracion = ?
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $count = $stm->rowCount();
        $stm = null;
        return $count;
    }

    public function del_idpdeclaracion($id_pdeclaracion) {
        $query = "
            DELETE
            FROM trabajadores_pdeclaraciones
            WHERE id_pdeclaracion = ?;  
    ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        //$lista = $stm->fetchAll();
        //$count = $stm->rowCount();
        $stm = null;
        return true;
    }

    public function eliminar_idPdeclaracion($id_pdeclaracion, $id_trabajador){
        $query = "
        DELETE
        FROM trabajadores_pdeclaraciones
        WHERE id_pdeclaracion = ? 
        AND id_trabajador = ?
    ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;

        return true;
    }
    
    
    
    
}

?>
