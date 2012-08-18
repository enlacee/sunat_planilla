<?php

class PagoDao extends AbstractDao {

    //put your code here
    public function registrar($obj) {
        $model = new Pago();
        $model = $obj;
        $query = "
INSERT INTO pagos
            (
             id_trabajador,
             id_etapa_pago,
             dia_laborado,
             dia_total,
             sueldo_base,
             sueldo,
             descuento,
             sueldo_neto,
             ordinario_hora,
             ordinario_min,
             sobretiempo_hora,
             sobretiempo_min,
             estado,
             descripcion,
             id_empresa_centro_costo)
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
        ?);
                ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getId_estapa_pago());
        $stm->bindValue(3, $model->getDia_laborado());
        $stm->bindValue(4, $model->getDia_total());        
        $stm->bindValue(5, $model->getSueldo_base());        
        $stm->bindValue(6, $model->getSueldo());
        $stm->bindValue(7, $model->getDescuento());
        $stm->bindValue(8, $model->getSueldo_neto());
        $stm->bindValue(9, $model->getOrdinario_hora());
        $stm->bindValue(10, $model->getOrdinario_min());
        $stm->bindValue(11, $model->getSobretiempo_hora());
        $stm->bindValue(12, $model->getSobretiempo_min());

        $stm->bindValue(13, $model->getEstado());
        $stm->bindValue(14, $model->getDescripcion());
        $stm->bindValue(15, $model->getId_empresa_centro_costo());

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

    public function listar($id_etapa_pago) {

        $query = "
        SELECT
          p.id_pago,
          p.id_trabajador,
          p.sueldo,
          p.descuento,
          p.sueldo_neto, -- Calculado y guardado
          p.dia_total,          
          p.estado,
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
        ";
       
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function buscar_ID($id, $op=null) {
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

    /**
     *
     * @param type $id_pdeclaracion
     * @return type 
     * LISTADO DE TRABAJADORES DE VIEW-EMPRESA registrados (tabla pago)
     */
    public function listarTablaPago_PorDeclaracion($id_pdeclaracion) {

        $query = "
        SELECT 
        pd.id_pdeclaracion,
        pd.periodo,
        ep.glosa,
        pg.id_trabajador,
        pg.id_empresa_centro_costo,
        pg.valor,
        pg.descuento,
        pg.valor_total,
        pg.descripcion,
        pg.dia_total,
        pg.dia_nosubsidiado,
        pg.dia_laborado,
        pg.ordinario_hora,
        pg.ordinario_min,
        pg.sobretiempo_hora,
        pg.sobretiempo_min,
        pg.estado,

        t.id_persona

        FROM pdeclaraciones AS pd
        INNER JOIN etapas_pagos AS ep
        ON pd.id_pdeclaracion = ep.id_pdeclaracion

        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago

        INNER JOIN trabajadores AS t
        ON pg.id_trabajador = t.id_trabajador

        WHERE pd.id_pdeclaracion = ?        
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    //AGRUPADO
    public function listaGrup_Por_Persona($id_pdeclaracion,$id_persona) {
        $query = "
        SELECT 
            SUM(pg.dia_laborado) AS sum_dia_laborado,
            SUM(pg.dia_nosubsidiado) AS sum_dia_nosubsidiado, -- falta tb
            SUM(pg.dia_total) AS sum_dia_total,
            SUM(pg.ordinario_hora) AS sum_ordinario_hora,
            SUM(pg.ordinario_min) AS sum_ordinario_min,
            SUM(pg.sobretiempo_hora) AS sum_sobretiempo_hora,
            SUM(pg.sobretiempo_min) AS sum_sobretiempo_min,
            SUM(pg.descuento) AS sum_descuento,
            SUM(pg.valor) AS sum_valor,
            SUM(pg.valor_total) AS sum_valor_total,

            -- persona
            p.id_persona

        FROM etapas_pagos AS ep
        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago

        INNER JOIN trabajadores AS t
        ON pg.id_trabajador = t.id_trabajador
        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona
	
	-- id_declaracion :: EL mes = 2 15cenas
        WHERE (id_pdeclaracion = ? AND p.id_persona = ?) 
        GROUP BY p.id_persona 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_persona);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

}

?>
