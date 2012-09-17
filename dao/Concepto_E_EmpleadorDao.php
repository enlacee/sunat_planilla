<?php

class Concepto_E_EmpleadorDao extends AbstractDao {

    public function listar($id_empleador) {
        $query = "
        SELECT
          cee.id_concepto_e_empleador,
          cee.id_concepto_e,
          cee.seleccionado
         
        FROM conceptos_e_empleadores AS cee
        WHERE cee.id_empleador = ?
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function cargar_tabla($id_empleador, $seleccionado, $WHERE, $start, $limit, $sidx, $sord) {
        $start = (isset($start) and isset($sord) ) ? " LIMIT " . $start : null;
        $limit = ($sord) ? null : "," . $limit;

        $query = "
        SELECT
          cee.id_concepto_e_empleador,
          cee.id_concepto_e,
          cee.seleccionado,
          ce.descripcion
        FROM conceptos_e_empleadores AS cee
        INNER JOIN conceptos_e AS ce
        ON cee.id_concepto_e = ce.id_concepto_e

        WHERE (cee.id_empleador = ? AND cee.seleccionado = ?)
        $WHERE  ORDER BY $sidx $sord $start  $limit; ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $seleccionado);
//echo "$query";
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function add($id_empleador, $id_concepto_e, $seleccionado) {
        $query = "
        INSERT INTO conceptos_e_empleadores
                    (
                     id_empleador,
                     id_concepto_e,
                     seleccionado)
        VALUES (
                ?,
                ?,
                ?);
            ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $id_concepto_e);
        $stm->bindValue(3, $seleccionado);

        $stm->execute();
        $stm = null;

        return true;
    }

    public function edit($id_concepto_e_empleador, $seleccionado) {
        $query = "
        UPDATE conceptos_e_empleadores
        SET 
          seleccionado = ?
        WHERE id_concepto_e_empleador = ?;
            ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $seleccionado);
        $stm->bindValue(2, $id_concepto_e_empleador);

        $stm->execute();
        $stm = null;

        return true;
    }

    //ayuda controller

    public function buscarId_ConceptoEmpleador($id_empleador, $id_concepto_e) {
        $query = "
        SELECT
          cee.id_concepto_e_empleador,
          cee.id_concepto_e,
          cee.seleccionado
         
        FROM conceptos_e_empleadores AS cee
        WHERE  (cee.id_empleador = ? AND cee.id_concepto_e = ?)
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $id_concepto_e);

        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]['id_concepto_e_empleador'];
    }

}

?>
