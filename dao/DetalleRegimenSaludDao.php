<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleRegimenSaludDao
 *
 * @author conta 1
 */
final class DetalleRegimenSaludDao extends AbstractDao {
    //put your code here
    
    public function registrarDetalleRegimenSalud($obj_dregimen_salud){
        $query = "
        INSERT INTO detalle_regimenes_salud
                    (id_trabajador,
                    cod_regimen_aseguramiento_salud,
                    fecha_inicio,
                    fecha_fin,
                    cod_eps,
                    id_persona)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);            
        ";
        
        $em = new DetalleRegimenSalud();
        $em = $obj_dregimen_salud;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getId_trabajador());
        $stm->bindValue(2, $em->getCod_regimen_aseguramiento_salud());
        $stm->bindValue(3, $em->getFecha_inicio());
        $stm->bindValue(4, $em->getFecha_fin());
        $stm->bindValue(5, $em->getCod_eps());
        $stm->bindValue(6, $em->getId_persona());
        
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;

    }
    
    
    public function actualizarDetalleRegimenSalud($obj){
        $query = "
        UPDATE detalle_regimenes_salud
        SET 
        cod_regimen_aseguramiento_salud = ?,
        fecha_inicio = ?,
        fecha_fin = ?,
        cod_eps = ?
        WHERE id_detalle_regimen_salud = ?;       
        ";
        
        $em = new DetalleRegimenSalud();
        $em = $obj;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getCod_regimen_aseguramiento_salud());
        $stm->bindValue(2, $em->getFecha_inicio());
        $stm->bindValue(3, $em->getFecha_fin());
        $stm->bindValue(4, $em->getCod_eps());
        $stm->bindValue(5, $em->getId_detalle_regimen_salud());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;        
    }
    
    //buscar
   public function buscarDetalleRegimenSalud($id_TRA){
        $query = "SELECT *FROM detalle_regimenes_salud WHERE id_trabajador = ?";        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_TRA);
        $stm->execute();
        $data = $stm->fetchAll();
        $stm = null;
        return $data[0];     
    }
    
    
    
    
    
    
    
    
    
    
    
    
}

?>
