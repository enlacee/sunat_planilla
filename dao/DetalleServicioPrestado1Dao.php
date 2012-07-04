<?php

class DetalleServicioPrestado1Dao extends AbstractDao {

    //put your code here

    function registrarDetalleServicioPrestado1($obj) {
        $query = "            
        INSERT INTO detalles_servicios_prestados
                    (
                     id_empleador_maestro_empleador,
                     cod_tipo_actividad,
                     estado,
                     fecha_inicio,
                     fecha_fin)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?);";

        $model = new DetalleServicioPrestado1();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador_maestro_empleador());
        $stm->bindValue(2, $model->getCod_tipo_actividad());
        $stm->bindValue(3, $model->getEstado());
        $stm->bindValue(4, $model->getFecha_inicio());
        $stm->bindValue(5, $model->getFecha_final());

        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    function actualizarDetalleServicioPrestado1($obj) {
        $query = "            
        UPDATE detalles_servicios_prestados
        SET 
          cod_tipo_actividad = ?, 
          fecha_inicio = ?,
          fecha_fin = ?
        WHERE id_detalle_servicio_prestado = ? ";


        $model = new DetalleServicioPrestado1();
        $model = $obj;
		//require_once("../model/DetalleServicioPrestado1.php");
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getCod_tipo_actividad());
        $stm->bindValue(2, $model->getFecha_inicio());
        $stm->bindValue(3, $model->getFecha_final());
        $stm->bindValue(4, $model->getId_detalle_servicio_prestado());
        $stm->execute();
        //$data = $stm->fetchAll();
		//echo "ENTRO EN DAAAO<BR>";
		//printr($model);
        return true;
    }

    function bajaDetalleServicioPrestado1($obj) {
        $query = "            
        UPDATE detalles_servicios_prestados
        SET          
          estado = ?
        WHERE id_detalle_servicio_prestado = ?;";

        $model = new DetalleServicioPrestado1();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getEstado());
        $stm->bindValue(2, $model->getId_detalle_servicio_prestado());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

      
    function eliminarDetalleServicioPrestado1($id_detalle_servicio_prestado2) {
        $query = "            
        DELETE
        FROM detalles_servicios_prestados
        WHERE id_detalle_servicio_prestado = ?;";

        $model = new DetalleServicioPrestado1();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_detalle_servicio_prestado2);
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }
    
    
    
    //---------------------------------------------------
    //---------------------------------------------------
    //---------------------------------------------------
/*
* Usando LOAD JAVASCRIPT :: load_empleador_dd2_1
*
*/	
    public function listarDetalleServicioPrestado1($id_empleador_maestro, $id_empleador){

        $query = " 
			-- empleador maestro = id_empleador
			SELECT 
			dsp.id_detalle_servicio_prestado,
			eme.id_empleador_maestro_empleador,
			eme.id_empleador_maestro,
			eme.id_empleador,			
			dsp.cod_tipo_actividad,
			dsp.estado,
			dsp.fecha_inicio,
			dsp.fecha_fin
			FROM empleadores_maestros_empleadores AS eme
			INNER JOIN detalles_servicios_prestados AS dsp
			ON eme.id_empleador_maestro_empleador = dsp.id_empleador_maestro_empleador
			WHERE (eme.id_empleador_maestro = ?) AND (eme.id_empleador = ?)
			-- Condicion Logica 'importante'
			AND (eme.id_empleador_maestro_empleador = dsp.id_empleador_maestro_empleador)
			ORDER BY dsp.id_detalle_servicio_prestado ASC ;		
		";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
		$stm->bindValue(2, $id_empleador);
        $stm->execute();
        $data = $stm->fetchAll();
        //echo "Imprimiendo DAta";
		//print($data);
        return $data;
     
        
    }
	
	
/*
* Funcion Exclusivamente Para Listar
* el tipo de servicio que brinda este Empleador Subordinado
* THIS function ayuda a No registrar Nuevamente Los mismos servicios[evitando duplicar reg]
*/
public function listarDetalleServicioPrestadoPorIdEME($ID_EME){
        $query = " 
		SELECT 
		id_detalle_servicio_prestado,
		-- id_empleador_maestro_empleador,
		cod_tipo_actividad
		FROM detalles_servicios_prestados
		WHERE id_empleador_maestro_empleador = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $ID_EME);
        $stm->execute();
        $data = $stm->fetchAll();
		
		//$DATA_TipoA= array();
		//foreach($data as  $indice){
		//	$DATA_TipoA[]= $indice['cod_tipo_actividad'];		
		//}
		
		return data;
		//return $DATA_TipoA;//$data;
}//ENDFUNCTION
	

}

?>
