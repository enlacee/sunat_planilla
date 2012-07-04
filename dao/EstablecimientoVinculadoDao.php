<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleEstablecimientoVinculadoDao
 *
 * @author conta 1
 */
class EstablecimientoVinculadoDao extends AbstractDao {

    //put your code here

    function registrar($obj) {
        $query = "            
        INSERT INTO establecimientos_vinculados
                    (
                     id_empleador_destaque,
                     id_establecimiento,
                     realizan_trabajo_de_riesgo,
                     estado)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";

        $model = new EstablecimientoVinculado();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador_destaque());
        $stm->bindValue(2, $model->getId_establecimiento());
        $stm->bindValue(3, $model->getRealizan_trabajo_de_riesgo());
        $stm->bindValue(4, $model->getEstado());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    function actualizar($obj) {
        $query = "            
        UPDATE establecimientos_vinculados
        SET 
          id_empleador_destaque = ?,
          id_establecimiento = ?,
          realizan_trabajo_de_riesgo = ?,
          estado = ?
        WHERE id_establecimiento_vinculado = ?;
        ";


        $model = new EstablecimientoVinculado();
        $model = $obj;
        //require_once("../model/DetalleServicioPrestado1.php");
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador_destaque());
        $stm->bindValue(2, $model->getId_establecimiento());
        $stm->bindValue(3, $model->getRealizan_trabajo_de_riesgo());
        $stm->bindValue(4, $model->getEstado());
        $stm->bindValue(5, $model->getId_establecimiento_vinculado());

        $stm->execute();
        //$data = $stm->fetchAll();
        //echo "ENTRO EN DAAAO<BR>";
        //printr($model);
        return true;
    }

    function baja($id) {
        $query = "            
        UPDATE establecimientos_vinculados
        SET 
        estado = 'INACTIVO'       
        WHERE id_establecimiento_vinculado = ?; ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    /*
     * Usando LOAD JAVASCRIPT :: load_empleador_dd2_1
     *
     */

    public function listar($id_empleador_destaque) {

        $query = "
        SELECT
        ed.id_empleador_destaque,

        ev.id_establecimiento_vinculado,
        ev.id_establecimiento,
        CONCAT(e.cod_establecimiento,'|',ev.id_establecimiento) AS concat_id_establecimiento,
        e.cod_establecimiento,
        ev.realizan_trabajo_de_riesgo

        FROM empleadores_destaques AS  ed

        INNER JOIN establecimientos_vinculados AS ev
        ON ed.id_empleador_destaque = ev.id_empleador_destaque

        INNER JOIN establecimientos AS e
        ON ev.id_establecimiento = e.id_establecimiento

        WHERE ev.id_empleador_destaque = ?
		";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_destaque);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "Imprimiendo DAta";
        //print($data);
        return $data;
    }

}

?>
