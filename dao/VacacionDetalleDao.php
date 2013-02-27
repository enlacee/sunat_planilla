<?php

class VacacionDetalleDao extends AbstractDao {

    //put your code here
    function add($obj) {
        $model = new VacacionDetalle();
        $model = $obj;
        $query = "
        INSERT INTO vacaciones_detalles
                    (
                     id_vacacion,
                     fecha_inicio,
                     fecha_fin,
                     dia,
                     fecha_creacion
                     )
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?
                );
        ";

        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_vacacion());
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getFecha_fin());
        $stm->bindValue(4, $model->getDia());
        $stm->bindValue(5, $model->getFecha_creacion());
        $stm->execute();
        $this->pdo->commit();
        $stm = null;
        return true;
    }

    // lista jqgrid HISTORIAL ANUAL
    function listar($id_pdeclaracion, $id_trabajador, $WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }
        $query = "
        SELECT
        vd.id_vacacion_detalle,
        vd.fecha_inicio,
        vd.fecha_fin,
        vd.dia
        FROM vacaciones AS v
        INNER JOIN vacaciones_detalles AS vd
        ON v.id_vacacion = vd.id_vacacion
        WHERE v.id_pdeclaracion = ?
        AND  v.id_trabajador = ?
        $cadena
        ";
//        echoo($query);
        try {
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_pdeclaracion);
            $stm->bindValue(2, $id_trabajador);
            $stm->execute();
            $lista = $stm->fetchAll();
            $stm = null;
            return $lista;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    //lista jqgrid HISTORIAL ANUAL
    function listarCount($id_pdeclaracion, $id_trabajador, $WHERE) {
        $query = "
        SELECT
        count(*) as  counteo
        FROM vacaciones AS v
        INNER JOIN vacaciones_detalles AS vd
        ON v.id_vacacion = vd.id_vacacion
        WHERE v.id_pdeclaracion = ?
        AND  v.id_trabajador = ?
        $WHERE
        ";
//        echoo($query);
        try {
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $id_pdeclaracion);
            $stm->bindValue(2, $id_trabajador);
            $stm->execute();
            $lista = $stm->fetchAll();
            return $lista[0]['counteo'];
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // LISTA DE FECHAS DE Vacaciones
    function vacacionDetalle($id_vacacion) {

        $query = "
        SELECT
        id_vacacion_detalle,
        fecha_inicio,
        fecha_fin,
        dia
        FROM vacaciones_detalles
        WHERE id_vacacion = ?     
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_vacacion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm=null;
        return $lista;
    }

}

?>
