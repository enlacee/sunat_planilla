<?php

class PromedioHoraExtraDao extends AbstractDao {

    function buscar_RPC2_PorTrabajador($id_pdeclaracion, $id_trabajador) {

        $query = "
        SELECT 
        monto
        FROM promedios_hextras
        WHERE id_pdeclaracion =? 
        AND id_trabajador = ?   
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['monto'];
    }

    function listaCount($id_pdeclaracion,$WHERE) {
        //echo $WHERE;
        $query = "
        SELECT
        COUNT(phe.id_promedio_hextras) AS counteo
        
        FROM promedios_hextras AS phe

        INNER JOIN trabajadores AS t
        ON phe.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona 

        WHERE id_pdeclaracion = ?
        $WHERE
";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }

    function lista($id_pdeclaracion, $WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
        SELECT
        phe.id_promedio_hextras,  
        phe.id_trabajador,
        phe.monto,
        --   
        per.num_documento,
        per.apellido_paterno,
        per.apellido_materno,
        per.nombres 

        FROM promedios_hextras AS phe

        INNER JOIN trabajadores AS t
        ON phe.id_trabajador = t.id_trabajador

        INNER JOIN personas AS per
        ON t.id_persona = per.id_persona 

        WHERE id_pdeclaracion = ?
        $cadena
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    function buscar_ID_trabajador($id_pdeclaracion, $id_trabajador) {
        $query = "        
            SELECT
            id_trabajador
            FROM promedios_hextras
            WHERE id_pdeclaracion = ?
            AND id_trabajador = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista[0]['id_trabajador'];
    }

    function add($obj) {

        $model = new PromedioHoraExtra();
        $model = $obj;

        $query = "
        INSERT INTO promedios_hextras
                    (
                     id_pdeclaracion,
                     id_trabajador,
                     monto)
        VALUES (
                ?,
                ?,
                ?);         
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_pdeclaracion());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getMonto());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    function edit($obj) {

        $query = "
        UPDATE promedios_hextras
        SET 
          monto = ?
        WHERE id_promedio_hextras = ?;        
        ";
        $model = new PromedioHoraExtra();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getMonto());
        $stm->bindValue(2, $model->getId_promedio_hextras());

        $stm->execute();
        $stm = null;
        // $lista = $stm->fetchAll();
        return true;
    }

    // -- funcion lista trabajadores por concepto para realizar calculo segun
    // corresponda. usando 04/10/2012 ONLY EN DAO...
    //
    function del($id) {
        $query = "
        DELETE
        FROM promedios_hextras
        WHERE id_promedio_hextras = ?          
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

}

?>
