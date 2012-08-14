<?php

class ConfPeriodoRemuneracionDao extends AbstractDao {

    //put your code here


    public function registrar($obj) {
        $model = new ConfPeriodoRemuneracion();
        $model = $obj;
        $query = "
    INSERT INTO conf_periodos_remuneraciones
                (
                 cod_periodo_remuneracion,
                 id_empleador_maestro,
                 valor,                 
                 estado)
    VALUES (
            ?,
            ?,
            ?,
            ?);        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getCod_periodo_remuneracion());
        $stm->bindValue(2, $model->getId_empleador_maestro());
        $stm->bindValue(3, $model->getValor());
        $stm->bindValue(4, $model->getDia());
        $stm->bindValue(5, $model->getEstado());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {

        $model = new ConfPeriodoRemuneracion();
        $model = $obj;
        $query = "
        UPDATE conf_periodos_remuneraciones
        SET   
          valor = ?          
        WHERE id_conf_periodo_remuneracion = ?;         
        "; //estado = ?

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getValor());
        $stm->bindValue(2, $model->getId_conf_periodo_remuneracion());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function listar($id_empleador_maestro) {
        $query = "
        SELECT
          cpr.id_conf_periodo_remuneracion,
          cpr.valor,
          cpr.estado,
          pr.descripcion
        FROM conf_periodos_remuneraciones AS cpr
        INNER JOIN periodos_remuneraciones AS pr
        ON cpr.cod_periodo_remuneracion = pr.cod_periodo_remuneracion
        WHERE cpr.id_empleador_maestro = ?
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function getDatosBasicosdeCalculo($id_empleador_maestro, $cod_periodo_remuneracion) {

        $query = "
        SELECT
          cpr.id_conf_periodo_remuneracion,
          cpr.cod_periodo_remuneracion,
          cpr.valor,
          cpr.estado,
          pr.descripcion,
          pr.dia
        FROM conf_periodos_remuneraciones AS cpr
        INNER JOIN periodos_remuneraciones AS pr
        ON cpr.cod_periodo_remuneracion = pr.cod_periodo_remuneracion
        WHERE (cpr.id_empleador_maestro = ? AND cpr.cod_periodo_remuneracion = ? )

";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $cod_periodo_remuneracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

?>
