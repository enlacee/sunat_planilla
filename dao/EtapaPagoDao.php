<?php

class EtapaPagoDao extends AbstractDao {

    //put your code here
    public function registrar($obj) {
        $model = new EtapaPago();
        $model = $obj;

        $query = "
        INSERT INTO etapas_pagos
                    (
                     id_pdeclaracion,
                     cod_periodo_remuneracion,
                     fecha_inicio,
                     fecha_fin,
                     fecha_creacion,
                     tipo,
                     glosa)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);
        ";
        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_pdeclaracion());
        $stm->bindValue(2, $model->getCod_periodo_remuneracion());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getFecha_fin());
        $stm->bindValue(5, $model->getFecha_creacion());
        $stm->bindValue(6, $model->getTipo());
        $stm->bindValue(7, $model->getGlosa());
        $stm->execute();

        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        $stm = null;

        return $lista[0]['id'];
    }

    public function actualizar($id_etapa_pago, $glosa) {

        $query = "
        UPDATE etapas_pagos
        SET 
          glosa = glosa
        WHERE id_etapa_pago = id_etapa_pago;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $glosa);
        $stm->bindValue(2, $id_etapa_pago);
        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    public function eliminar($etapas_pagos) {

        $query = "		
        DELETE
        FROM etapas_pagos
        WHERE id_etapa_pago = ?;
		";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $etapas_pagos);
        $stm->execute();
        $stm = null;
        return true;
    }

    /**
     *
     * @param type $id_declaracion
     * @param type $cod_periodo_remuneracion 15 O 7
     * @param type $tipo QUINCENA = 1,2 SEMANA = 1,2,3,4
     * @return type 
     */
    public function listar($id_pdeclaracion) {
        $query = "
        SELECT
          ep.id_etapa_pago,
          ep.id_pdeclaracion,
          ep.cod_periodo_remuneracion,
          ep.fecha_inicio,
          ep.fecha_fin,
          ep.fecha_creacion,
          ep.tipo,
          ep.glosa,
          pr.descripcion
        FROM etapas_pagos AS ep
        INNER JOIN periodos_remuneraciones AS pr
        ON ep.cod_periodo_remuneracion = pr.cod_periodo_remuneracion
        WHERE (id_pdeclaracion= ?)      
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function buscar_ID($id_etapa_pago, $op=null) {
        $query = "
        SELECT
          ep.id_etapa_pago,
          ep.id_pdeclaracion,
          ep.cod_periodo_remuneracion,
          ep.fecha_inicio,
          ep.fecha_fin,
          ep.fecha_creacion,
          ep.tipo,
          ep.glosa          
        FROM etapas_pagos AS ep        
        WHERE (ep.id_etapa_pago= ?)      
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_etapa_pago);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        if ($op == "data") {
            return $lista;
        } else {
            return $lista[0];
        }
    }

    public function buscarEtapaPago($id_declaracion, $cod_periodo_remuneracion) {
        $query = "
        SELECT
          id_etapa_pago
        FROM etapas_pagos
        WHERE (id_pdeclaracion= ? AND cod_periodo_remuneracion = ?)           
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_declaracion);
        $stm->bindValue(2, $cod_periodo_remuneracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function buscarEtapaPago2($id_declaracion, $cod_periodo_remuneracion, $tipo) {
        $query = "
        SELECT
          id_etapa_pago
        FROM etapas_pagos
        WHERE (id_pdeclaracion= ? AND cod_periodo_remuneracion = ?)
        AND tipo = ?;            
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_declaracion);
        $stm->bindValue(2, $cod_periodo_remuneracion);
        $stm->bindValue(3, $tipo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['id_etapa_pago'];
    }

}

?>
