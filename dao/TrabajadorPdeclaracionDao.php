<?php

class TrabajadorPdeclaracionDao extends AbstractDao {

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
                    sueldo_neto,
                    estado,
                    fecha_creacion,                         
                    ingreso_5ta_categoria,
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
            $stm->bindValue(8, $model->getSueldo_neto());
            $stm->bindValue(9, $model->getEstado());
            $stm->bindValue(10, $model->getFecha_creacion());
            $stm->bindValue(11, $model->getIngreso_5ta_categoria());
            $stm->bindValue(12, $model->getCod_tipo_trabajador());
            $stm->bindValue(13, $model->getCod_regimen_pensionario());
            $stm->bindValue(14, $model->getCod_regimen_aseguramiento_salud());
            $stm->bindValue(15, $model->getCod_situacion());
            
            $stm->bindValue(16, $model->getCod_ocupacion_p());
            $stm->bindValue(17, $model->getId_empresa_centro_costo());
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
      sueldo_neto = ?,  
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
        $stm->bindValue(8, $model->getSueldo_neto());
        $stm->bindValue(9, $model->getFecha_modificacion());
        $stm->bindValue(10, $model->getId_trabajador_pdeclaracion());

        $stm->execute();
        $stm = null;

        return true;
    }

    
    
    //actulizar horas y minutos sobretiempo 23/09/2012
    public function actualizarHoraMinuto($sobretiempo_hora,$sobretiempo_min,$id_trabajador_pdeclaracion){
        
        $query = "
    UPDATE trabajadores_pdeclaraciones
    SET       
      sobretiempo_hora = ?,
      sobretiempo_min = ?      
    WHERE id_trabajador_pdeclaracion = ?;
      ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $sobretiempo_hora);
        $stm->bindValue(2, $sobretiempo_min);
        $stm->bindValue(3, $id_trabajador_pdeclaracion);
        $stm->execute();
        $stm = null;

        return true;   
        
        
    }
    
    
    public function selectHoraMinuto($id_trabajador_pdeclaracion){
        $query = "
        SELECT 
        sobretiempo_hora,
        sobretiempo_min        
        FROM trabajadores_pdeclaraciones           
        WHERE id_trabajador_pdeclaracion = ?;
      ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
        
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

    /*
     * Ayuda y 
     * Busca informacion para ayudar con la generacion de 
     * Conceptos.
     */

    public function buscar_ID_trabajador($id_trabajador) {

        $query = "
            SELECT 
		-- trabajador
                t.id_trabajador,
                t.monto_remuneracion,
                t.cod_situacion,
                t.cod_ocupacion_p,
                t.id_empresa_centro_costo,
                -- p.nombres,
                -- drs.id_detalle_regimen_salud,
                drs.cod_regimen_aseguramiento_salud,
                -- drp.id_detalle_regimen_pensionario,
                drp.cod_regimen_pensionario,
                dtt.cod_tipo_trabajador
               
            FROM trabajadores AS t
            INNER JOIN personas AS p
            ON t.id_persona = p.id_persona

            INNER JOIN detalle_regimenes_salud AS drs
            ON drs.id_trabajador = t.id_trabajador

            INNER JOIN detalle_regimenes_pensionarios AS drp
            ON drp.id_trabajador = t.id_trabajador
            -- new
            INNER JOIN detalle_tipos_trabajadores AS dtt
            ON t.id_trabajador = dtt.id_trabajador
            
            WHERE t.id_trabajador = ?        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
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
          sueldo_neto,
          estado,
          descripcion,
          fecha_creacion,
          fecha_modificacion,
          ingreso_5ta_categoria,
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
     

    function listar($id_pdeclaracion, $op=null, $WHERE=null) {

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
          tpd.sueldo_neto,
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
        
        $WHERE

        WHERE tpd.id_pdeclaracion = ?            
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
          tpd.sueldo_neto,
          tpd.estado,
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
    public function listar_2($id_pdeclaracion, $id_establecimiento, $id_empresa_centro_costo){
        
        $query="
        SELECT
          tpd.id_trabajador_pdeclaracion,
          tpd.id_pdeclaracion,
          tpd.id_trabajador,
          tpd.dia_laborado,
          tpd.ordinario_hora,
          tpd.sueldo_neto,
          -- estado,
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
    

    //------------------------------------------------------------------------//
    // ------------- FUNCION ESTRUTURAS---------------------------------------//
    
    // USADO en Estructuras
    public function listar_ID_Trabajador($id_pdeclaracion){
       
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
    
    
    
     public function estrutura_14($id_pdeclaracion,$id_trabajador){
        
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
    

     public function estrutura_15_a($id_pdeclaracion,$id_trabajador){
        
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
    
    
    
     public function estrutura_15_b($id_pdeclaracion,$id_trabajador){
        
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
    
    
    
     public function estrutura_18($id_pdeclaracion,$id_trabajador){
        
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
        AND tpd.id_trabajador = ?;
    ";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
    
        return $lista;        
        
    }      
    
}

?>
