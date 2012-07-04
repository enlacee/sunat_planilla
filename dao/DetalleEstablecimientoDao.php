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
class DetalleEstablecimientoDao extends AbstractDao {

    //put your code here

    public function registrarDetalleEstablecimiento($obj) {
        $query = "
        INSERT INTO detalle_establecimiento
                    (id_trabajador,
                    id_establecimiento)
        VALUES (
                ?,
                ?);
        ";

        $model = new DetalleEstablecimiento();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getId_establecimiento());
        $stm->execute();
        //$data = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizarDetalleEstablecimiento($obj) {
        $query = "
        UPDATE detalle_establecimiento
        SET 
          id_trabajador = ?,
          id_establecimiento = ?
        WHERE id_detalle_establecimiento = ?;            
        ";
        $model = new DetalleEstablecimiento();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getId_establecimiento());
        $stm->bindValue(3, $model->getId_detalle_establecimiento());
        $stm->execute();
        $stm = null;
        return true;
    }
    
        // buscar
    function buscarDetalleEstablecimiento($id_TRA){
        $query = "SELECT *FROM detalle_establecimiento WHERE id_trabajador =?";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$id_TRA);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0];  
    }
    
    
/**
 *
 * @param type $id_trabajador iDentificado
 * @param type $id_detalle_establecimiento :: Necesario relativo ??
 * @return boolean 
 */
    public function buscarID_EMP_EmpleadorDestaquePorTrabajador($id_trabajador,$id_detalle_establecimiento) {
        $query = "
        SELECT 
        de.id_trabajador,
        e.id_establecimiento,
        -- e.cod_establecimiento,
        e.id_empleador

        FROM detalle_establecimiento AS de
        INNER JOIN establecimientos AS e
        ON de.id_establecimiento = e.id_establecimiento

        WHERE de.id_trabajador = ?
        AND de.id_detalle_establecimiento = ?       
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_detalle_establecimiento);

        $stm->execute();
        $data = $stm->fetchAll();
        $stm = null;
        
        return $data[0]['id_empleador'];
    }
/**
 *
 * @param type $id_trabajador Buscar ID  ok
 * @return type 
 */
    public function buscar_iDDetalleEstablecimiento($id_trabajador){
        $query = "
        SELECT id_detalle_establecimiento FROM detalle_establecimiento
        WHERE id_trabajador = ?            
        ";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$id_trabajador);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0]['id_detalle_establecimiento'];   
        
    }
    
}

?>
