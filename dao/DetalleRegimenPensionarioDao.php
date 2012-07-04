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
final class DetalleRegimenPensionarioDao extends AbstractDao {
    //put your code here
    
    public function registrarDetalleRegimenPensionario($obj){
        $query = "
    INSERT INTO detalle_regimenes_pensionarios
                (
                id_trabajador,
                cod_regimen_pensionario,
                CUSPP,
                fecha_inicio,
                fecha_fin)
    VALUES (
            ?,
            ?,
            ?,
            ?,
            ?);          
            ";
        
        $em = new DetalleRegimenPensionario();
        $em = $obj;
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $em->getId_trabajador());
        $stm->bindValue(2, $em->getCod_regimen_pensionario());
        $stm->bindValue(3, $em->getCUSPP());
        $stm->bindValue(4, $em->getFecha_inicio());
        $stm->bindValue(5, $em->getFecha_fin());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;

    }
    
    
    public function actualizarDetalleRegimenPensionario($obj){
        //-- id_trabajador = ?,
        $query = "
        UPDATE detalle_regimenes_pensionarios
        SET         
        cod_regimen_pensionario = ?,
        cuspp=?,
        fecha_inicio = ?,
        fecha_fin = ?
        WHERE id_detalle_regimen_pensionario = ?
    
        ";
        
        $em = new DetalleRegimenPensionario();
        $em = $obj;
        
        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $em->getId_trabajador());
        $stm->bindValue(1, $em->getCod_regimen_pensionario());
        $stm->bindValue(2, $em->getCUSPP());
        $stm->bindValue(3, $em->getFecha_inicio());
        $stm->bindValue(4, $em->getFecha_fin());
        $stm->bindValue(5, $em->getId_detalle_regimen_pensionario());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;

        
    }
    
    
    
    
    //---- buscar
    public function buscarDetalleRegimenPensionario($id_TRA){
        $query = "SELECT *FROM detalle_regimenes_pensionarios WHERE id_trabajador=? ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_TRA);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];  
    }
    
    
    
    
    
    
    
}

?>
