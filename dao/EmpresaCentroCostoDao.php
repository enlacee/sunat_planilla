<?php

class EmpresaCentroCostoDao extends AbstractDao {

    // NO UTILIZADO
    public function registrar($descripcion) {

        $query = "
        INSERT INTO empresa_centro_costo
                    (descripcion)
        VALUES (?); 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $descripcion);
        $stm->execute();
        $stm = null;
        return true;
    }

    // NO UTILIZADO
    public function actualizar($id, $descripcion) {

        $query = "
        UPDATE empresa_centro_costo
        SET   descripcion = ?
        WHERE id_empresa_centro_costo = ?;     
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $descripcion);
        $stm->bindValue(2, $id);
        $stm->execute();
        $stm = null;
        return true;
    }

    //OK   
    public function listar() {

        $query = "SELECT *FROM empresa_centro_costo";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function listarCCosto_PorIdEstablecimiento($id_establecimiento) {

        $query = "
        SELECT 
        estcc.id_establecimiento,
        estcc.id_empresa_centro_costo,
        empcc.descripcion
        FROM establecimientos_centros_costos AS estcc

        INNER JOIN empresa_centro_costo AS empcc
        ON estcc.id_empresa_centro_costo = empcc.id_empresa_centro_costo
        WHERE estcc.id_establecimiento = ?
        AND estcc.seleccionado = 1
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_establecimiento);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;


        return $lista;
    }

    //edit 06/09/2012 ->Usado en Planilla
    function listar_Ids_EmpresaCentroCosto($id_establecimiento) {

        $query = "
        SELECT 
        estcc.id_empresa_centro_costo,
        ecc.descripcion
        -- estcc.id_establecimiento,
        -- estcc.id_establecimiento_centro_costo,

        FROM establecimientos AS est

        INNER JOIN establecimientos_centros_costos AS estcc
        ON est.id_establecimiento = estcc.id_establecimiento
        INNER JOIN empresa_centro_costo AS ecc
        ON estcc.id_empresa_centro_costo = ecc.id_empresa_centro_costo
        WHERE est.id_establecimiento = ?
        AND estcc.seleccionado = 1
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_establecimiento);
        $stm->execute();
        $lista = $stm->fetchAll();
        return $lista;
    }

}

?>
