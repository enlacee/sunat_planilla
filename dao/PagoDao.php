<?php

class PagoDao extends AbstractDao {

    //put your code here
    public function registrar($obj) {
        $model = new Pago();
        $model = $obj;
        $query = "
        INSERT INTO pagos
                    (
                     id_etapa_pago,
                     id_trabajador,
                     id_empresa_centro_costo,                     
                     valor,
                     descuento,
                     descripcion,
                     dia_total,
                     dia_nosubsidiado,
                     ordinario_hora,
                     ordinario_min,
                     sobretiempo_hora,
                     sobretiempo_min,
                     estado)
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

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_etapa_pago());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getId_empresa_centro_costo());        
        $stm->bindValue(4, $model->getValor());
        $stm->bindValue(5, $model->getDescuento());
        $stm->bindValue(6, $model->getDescripcion());
        $stm->bindValue(7, $model->getDia_total());
        $stm->bindValue(8, $model->getDia_nosubsidiado());
        $stm->bindValue(9, $model->getOrdinario_hora());
        $stm->bindValue(10, $model->getOrdinario_min());
        $stm->bindValue(11, $model->getSobretiempo_hora());
        $stm->bindValue(12, $model->getSobretiempo_min());
        $stm->bindValue(13, $model->getEstado());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {

        $model = new Pago();
        $model = $obj;
        $query = "
        UPDATE pagos
        SET           
          descuento = ?,
          descripcion = ?,
          dia_nosubsidiado = ?,
          sobretiempo_hora = ?,
          sobretiempo_min = ?
        WHERE id_pago = ?; 
        ";

        $stm = $this->pdo->prepare($query);          
        $stm->bindValue(1, $model->getDescuento());
        $stm->bindValue(2, $model->getDescripcion());
        $stm->bindValue(3, $model->getDia_nosubsidiado());
        $stm->bindValue(4, $model->getSobretiempo_hora());
        $stm->bindValue(5, $model->getSobretiempo_min());
        $stm->bindValue(6, $model->getId_pago());

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
          
          p.valor,          
          p.dia_total,
          p.dia_nosubsidiado,
          p.estado,	  
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
          id_empresa_centro_costo,          
          valor,
          descuento,
          descripcion,
          dia_total,
          dia_nosubsidiado,
          ordinario_hora,
          ordinario_min,
          sobretiempo_hora,
          sobretiempo_min,
          estado
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
           p.dia_total, -- dia calculado q SE TRABAJO CALC 01
           p.dia_nosubsidiado, -- hacer CAL 02
            p.valor,
            p.descuento,
            -- neto a pagar CALC 
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

}

?>
