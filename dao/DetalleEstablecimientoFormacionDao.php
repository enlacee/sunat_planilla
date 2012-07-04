<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleEstablecimientoFormacionDao
 *
 * @author conta 1
 */
class DetalleEstablecimientoFormacionDao extends AbstractDao {

    //put your code here

    public function registrar($obj) {
		
		

		
        $query = "
        INSERT INTO detalle_establecimientos_formacion
                    (
                    id_personal_formacion_laboral,
                    id_establecimiento)
        VALUES (
                ?,
                ?);
        ";

        $model = new DetalleEstablecimientoFormacion();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_personal_formacion_laboral());
        $stm->bindValue(2, $model->getId_establecimiento());
        $stm->execute();
        //$data = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($obj) {
		

		
        $query = "
        UPDATE detalle_establecimientos_formacion
        SET 
        id_personal_formacion_laboral = ?,
        id_establecimiento = ?
        WHERE id_detalle_establecimiento_formacion = ?;          
        ";
        $model = new DetalleEstablecimientoFormacion();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_personal_formacion_laboral());
        $stm->bindValue(2, $model->getId_establecimiento());
        $stm->bindValue(3, $model->getId_detalle_establecimiento_formacion());
        $stm->execute();
        $stm = null;
        return true;
    }

    
    //------- buscador
/**
 *
 * @param type $id
 * @return type  Es alternativo 
 * @example :: Si quieres
 */
    function buscarDetalleEstablecimientoFormacion($id) {
        $query = "
            SELECT *FROM detalle_establecimientos_formacion WHERE id_personal_formacion_laboral = ? ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $data = $stm->fetchAll();
        return $data[0];
        
    }    
    
    
}

?>
