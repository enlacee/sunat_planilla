<?php

class PagoDao extends AbstractDao {
   // singleton
    private static $instancia = null;
    
    static public function anb_dosQuincenasEnBoleta($id_pdeclaracion, $id_trabajador) {
        $data = null;        
        if (self::$instancia == null) {
            try {
                self::$instancia = new PagoDao();               
            } catch (Exception $e) {
                throw $e;
            }
        }
        
        $data = self::$instancia->dosQuincenasEnBoleta($id_pdeclaracion, $id_trabajador);
        
        return $data;
    }

    public function registrar($obj) {

        $model = new Pago();
        $model = $obj;
        $query = "
INSERT INTO pagos
            (
             id_trabajador,
             id_etapa_pago,
             dia_laborado,
             dia_nosubsidiado,
             dia_total,
             sueldo_base,
             sueldo,             
             sueldo_vacacion,
             descuento,
             sueldo_neto,
             ordinario_hora,
             ordinario_min,
             sobretiempo_hora,
             sobretiempo_min,
             estado,
             descripcion,
             fecha_creacion,
             id_empresa_centro_costo,
             fecha_fin_15,
             monto_devengado,
             sueldo_porcentaje
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
        ?,
        ?,
        ?,
        ?,
        ?);
                ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getId_etapa_pago());
        $stm->bindValue(3, $model->getDia_laborado());
        $stm->bindValue(4, $model->getDia_nosubsidiado());
        $stm->bindValue(5, $model->getDia_total());
        $stm->bindValue(6, $model->getSueldo_base());
        $stm->bindValue(7, $model->getSueldo());
        $stm->bindValue(8, $model->getSueldo_vacacion());
        $stm->bindValue(9, $model->getDescuento());
        $stm->bindValue(10, $model->getSueldo_neto());
        $stm->bindValue(11, $model->getOrdinario_hora());
        $stm->bindValue(12, $model->getOrdinario_min());
        $stm->bindValue(13, $model->getSobretiempo_hora());
        $stm->bindValue(14, $model->getSobretiempo_min());

        $stm->bindValue(15, $model->getEstado());
        $stm->bindValue(16, $model->getDescripcion());
        $stm->bindValue(17, $model->getFecha_creacion());
        $stm->bindValue(18, $model->getId_empresa_centro_costo());
        $stm->bindValue(19, $model->getFecha_fin_15());
        $stm->bindValue(20, $model->getMonto_devengado());
        $stm->bindValue(21,$model->getSueldo_porcentaje());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {

        $model = new Pago();
        $model = $obj;
        //-- dia_laborado = ?,
        $query = "
        UPDATE pagos
        SET           
          descuento = ?,
          sueldo_neto = ?,
          ordinario_hora = ?,
          ordinario_min = ?,
          sobretiempo_hora = ?,
          sobretiempo_min = ?, 
          descripcion = ?,
          fecha_modificacion = ?

        WHERE id_pago = ?;
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getDescuento());
        $stm->bindValue(2, $model->getSueldo_neto());
        $stm->bindValue(3, $model->getOrdinario_hora());
        $stm->bindValue(4, $model->getOrdinario_min());
        $stm->bindValue(5, $model->getSobretiempo_hora());
        $stm->bindValue(6, $model->getSobretiempo_min());
        $stm->bindValue(7, $model->getDescripcion());
        $stm->bindValue(8, $model->getFecha_modificacion());
        $stm->bindValue(9, $model->getId_pago());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    //USO ALTERNATIVA PARA FILTRAR EN QUINCENAS!
    public function listar_HIJO($id_etapa_pago) {

        $query = "
        SELECT
            p.id_pago,
            p.id_trabajador

        FROM pagos AS p
        INNER JOIN trabajadores AS t
        ON p.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona

        INNER JOIN empresa_centro_costo AS ecc
        ON p.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE p.id_etapa_pago = ?     
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    //USO ESCLUSIVO PARA GRID
    public function listarCount($id_etapa_pago, $WHERE) {

        $query = "
        SELECT
        count(*) as counteo
        FROM pagos AS p

        INNER JOIN trabajadores AS t
        ON p.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona

        INNER JOIN empresa_centro_costo AS ecc
        ON p.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE p.id_etapa_pago = ?
        $WHERE
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }

    //new OK!
    function buscar_what($id_etapa_pago,$id_trabajador,$atributo){
        $query = "
        SELECT
            $atributo
        FROM pagos AS p
        WHERE p.id_etapa_pago = ?
        AND id_trabajador = ?
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]["$atributo"];
        
    }
    
    //USO ESCLUSIVO PARA GRID
    public function listar($id_etapa_pago, $WHERE, $start, $limit, $sidx, $sord) {

        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
        SELECT
            p.id_pago,
            p.id_trabajador,
            p.sueldo,            
            p.descuento,            
            p.dia_laborado,          
            p.estado,
            p.fecha_creacion,
            p.id_empresa_centro_costo,	  
            per.cod_tipo_documento,
            per.num_documento,
            per.apellido_paterno,
            per.apellido_materno,
            per.nombres,
            ecc.descripcion AS ccosto
        FROM pagos AS p

        INNER JOIN trabajadores AS t
        ON p.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona

        INNER JOIN empresa_centro_costo AS ecc
        ON p.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE p.id_etapa_pago = ?
        $cadena
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        /*
          if ($op == 'id_trabajador') { // ['id_trabajador']
          $new = array();
          for ($i = 0; $i < count($lista); $i++) {
          $new[]['id_trabajador'] = $lista[$i]['id_trabajador'];
          }
          return $new;
          } */
        return $lista;
    }

    // 06/09/2012 ->new uso para reportes
    public function listar_2($id_etapa_pago, $id_establecimiento, $id_empresa_centro_costo) {

        $query = "
        SELECT
            p.id_pago,
            p.id_trabajador,
            p.sueldo,
            p.descuento,
            p.sueldo_neto, -- Calculado y guardado
            p.dia_total,          
            p.estado,
            p.fecha_creacion,
            p.id_empresa_centro_costo,	  
            per.cod_tipo_documento,
            per.num_documento,
            per.apellido_paterno,
            per.apellido_materno,
            per.nombres,
            ecc.descripcion AS ccosto
	    
        FROM pagos AS p

        INNER JOIN trabajadores AS t
        ON p.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona

        INNER JOIN empresa_centro_costo AS ecc
        ON p.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE p.id_etapa_pago = ? 
        AND t.id_establecimiento = ?
        AND ecc.id_empresa_centro_costo = ?
        
        AND p.sueldo > 0
        AND p.fecha_fin_15 IS NULL
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);
        $stm->bindValue(2, $id_establecimiento);
        $stm->bindValue(3, $id_empresa_centro_costo);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function buscar_ID($id, $op = null) {
        $query = "
        SELECT
          id_pago,
          id_etapa_pago,
          id_trabajador,                   
          sueldo,
          descuento,
          sueldo_neto,
          sueldo_base,
          descripcion,
          dia_total,          
          dia_laborado,
          ordinario_hora,
          ordinario_min,
          sobretiempo_hora,
          sobretiempo_min,
          estado,
          id_empresa_centro_costo, 
          fecha_modificacion
        FROM pagos
        WHERE id_pago = ?            
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        if ($op == "data") {
            return $lista;
        } else {
            return $lista[0];
        }
    }

    public function buscar_ID_GRID_LINEAL($id_pago) {

        $query = "
        SELECT
            p.id_pago,
            p.id_trabajador,
            per.cod_tipo_documento,
            per.num_documento,
            per.apellido_paterno,
            per.apellido_materno,
            per.nombres,
            p.dia_total,
            p.sueldo,
            p.descuento,
            p.sueldo_neto,            
            p.estado 
        FROM pagos AS p

        INNER JOIN trabajadores AS t
        ON p.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona

        INNER JOIN empresa_centro_costo AS ecc
        ON p.id_empresa_centro_costo = ecc.id_empresa_centro_costo

        WHERE p.id_pago = ?           
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pago);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function del($id) {

        $query = "
        DELETE
        FROM pagos
        WHERE id_pago = ?;        
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    public function dosQuincenas($id_pdeclaracion, $id_trabajador) {
        $query = "
        SELECT 
        -- pago
        pg.id_trabajador,
        SUM(pg.sueldo) AS sueldo,
        -- SUM(pg.sueldo_vacacion) AS sueldo_vacacion,
        SUM(pg.dia_total) AS dia_total,
        SUM(pg.dia_laborado) AS dia_laborado ,
        SUM(pg.dia_nosubsidiado) AS dia_nosubsidiado,
        
        SUM(pg.ordinario_hora) AS ordinario_hora,
        SUM(pg.ordinario_min) AS ordinario_min,
        SUM(pg.sobretiempo_hora) AS sobretiempo_hora,
        SUM(pg.sobretiempo_min) AS sobretiempo_min,
        -- pago
        -- persona
        p.nombres
        -- persona

        FROM pdeclaraciones AS pd
        INNER JOIN etapas_pagos AS ep
        ON pd.id_pdeclaracion = ep.id_pdeclaracion
        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago
        -- tra
        INNER JOIN trabajadores AS t
        ON pg.id_trabajador = t.id_trabajador
        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona
        -- tra
        WHERE pd.id_pdeclaracion = ?
        AND t.id_trabajador  = ?        
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }

    public function dosQuincenasEnBoleta($id_pdeclaracion, $id_trabajador) {
        $query = "
        SELECT 
        SUM(pg.sueldo) AS sueldo,
        SUM(pg.sueldo_vacacion) AS sueldo_vacacion,        
        SUM(pg.dia_laborado) AS dia_laborado ,
        SUM(pg.dia_nosubsidiado) AS dia_nosubsidiado

        FROM pdeclaraciones AS pd
        INNER JOIN etapas_pagos AS ep
        ON pd.id_pdeclaracion = ep.id_pdeclaracion
        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago
        -- tra
        INNER JOIN trabajadores AS t
        ON pg.id_trabajador = t.id_trabajador
        
        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona
        -- tra
        WHERE pd.id_pdeclaracion = ?
        AND t.id_trabajador  = ?        
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }

    //Elimado en casacada..
    public function eliminar_idEtapaPago($id_etapa_pago, $id_trabajador) {
        $query = "
        DELETE 
        FROM pagos
        WHERE id_etapa_pago = ?
        AND id_trabajador = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $stm = null;
        return true;
    }

}

?>
