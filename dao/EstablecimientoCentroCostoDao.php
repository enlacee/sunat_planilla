<?php

class EstablecimientoCentroCostoDao extends AbstractDao {

    //put your code here

    public function registrar($objeto) {
        $obj = new EstablecimientoCentroCosto();
        $obj = $objeto;

        $query = "
        INSERT INTO establecimientos_centros_costos
            (
            id_establecimiento,
            id_empresa_centro_costo,
            seleccionado,
            estado)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $obj->getId_establecimiento());
        $stm->bindValue(2, $obj->getId_empresa_centro_costo());
        $stm->bindValue(3, $obj->getSeleccionado());
        $stm->bindValue(4, $obj->getEstado());
        $stm->execute();
        $stm = null;
        return true;
    }

    /*
    public function actualizar($objeto) {
        $obj = new EstablecimientoCentroCosto();
        $obj = $objeto;

        $query = "
        UPDATE establecimientos_centros_costos
        SET 
        seleccionado = ?
        WHERE id_establecimiento_centro_costo = ?; 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $obj->getSeleccionado());
        //$stm->bindValue(2, $obj->getEstado());
        $stm->bindValue(2, $obj->getId_establecimiento_centro_costo());
        $stm->execute();
        $stm = null;
        return true;
        
    }
*/
    
    public function marcarCheck($id, $seleccionado){
        
        $query = "
        UPDATE establecimientos_centros_costos
        SET 
        seleccionado = ?
        WHERE id_establecimiento_centro_costo = ?; 
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $seleccionado );
        $stm->bindValue(2, $id );
        $stm->execute();
        $stm = null;
        return true;   
        
        
    }
    
    
    
    public function listar($id_establecimiento,$estado) {
        $query = "
        SELECT
            ecc.id_establecimiento_centro_costo,
            ecc.id_establecimiento,
            ecc.id_empresa_centro_costo,
            ecc.seleccionado,
            ecc.estado,
            cc.descripcion
        FROM establecimientos_centros_costos AS ecc
	INNER JOIN empresa_centro_costo AS cc
	ON ecc.id_empresa_centro_costo = cc.id_empresa_centro_costo
        WHERE estado = ?
        AND ecc.id_establecimiento = ?
    ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $estado);
        $stm->bindValue(2, $id_establecimiento);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    
    
    

            
            
            
            
}

?>
