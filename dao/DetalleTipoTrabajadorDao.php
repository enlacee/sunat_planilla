<?php
class DetalleTipoTrabajadorDao extends AbstractDao{

	public function registrarDetalleTipoTrabajador($obj){
	$query = "
         INSERT INTO detalle_tipos_trabajadores
                    (
                     id_trabajador,
                     cod_tipo_trabajador,
                     fecha_inicio,
                     fecha_fin,
                     id_persona)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?);           
        ";
	//printr($obj);
        
        $model = new DetalleTipoTrabajador();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador());
        $stm->bindValue(2, $model->getCod_tipo_trabajador());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getFecha_fin());
        $stm->bindValue(5, $model->getId_persona());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
	
	}
	
	
	public function actualizarDetalleTipoTrabajador($obj){
        $query = "            
        UPDATE detalle_tipos_trabajadores
        SET 
          cod_tipo_trabajador = ?,
          fecha_inicio = ?,
          fecha_fin = ?
        WHERE id_detalle_tipo_trabajador = ?       
        ";
        $model = new DetalleTipoTrabajador();
        $model = $obj;
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getCod_tipo_trabajador());
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getFecha_fin());
        $stm->bindValue(4, $model->getId_detalle_tipo_trabajador());
        $stm->execute();
        return true;
	
	}
	
        


        // Buscar
        
        function buscarDetalleTipoTrabajador($id_TRA){
            $query="SELECT *FROM detalle_tipos_trabajadores WHERE id_trabajador = ?";
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1,$id_TRA);
            $stm->execute();  
            $data =$stm->fetchAll();
            return $data[0];
            
        }

}

?>