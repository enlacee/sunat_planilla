<?php


class ServicioPrestadoDao extends AbstractDao {

   function registrar($obj) {
        $query = "
        INSERT INTO servicios_prestados
                    (
                     id_empleador_destaque,
                     cod_tipo_actividad,
                     fecha_inicio,
                     fecha_fin,
                     estado)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?)
                ";


        $model = new ServicioPrestado();
        $model = $obj;
        //ECHO "<PRE>";
        //print_r($model);
        //echo "</pre>";
        //echo $model->getEstado();

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador_destaque());
        $stm->bindValue(2, $model->getCod_tipo_actividad());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getFecha_fin());
        $stm->bindValue(5, $model->getEstado());

        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    function actualizar($obj) {
        $query = "            
        UPDATE servicios_prestados
        SET 
          id_empleador_destaque = ?,
          cod_tipo_actividad = ?,
          fecha_inicio = ?,
          fecha_fin = ?
        WHERE id_servicio_prestado = ?;
        ";

        $model = new ServicioPrestado();
        $model = $obj;
//require_once("../model/DetalleServicioPrestado2.php");
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador_destaque());
        $stm->bindValue(2, $model->getCod_tipo_actividad());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getFecha_fin());
        $stm->bindValue(5, $model->getId_servicio_prestado());
        $stm->execute();
        //$data = $stm->fetchAll();
        //echo "ENTRO EN DAAAO<BR>";
        //printr($model);
        return true;
    }

    function baja($id) {
        $query = "            
        UPDATE detalles_servicios
        SET          
          estado = 'INACTIVO'
        WHERE id_servicio_prestado = ?;";

        $model = new ServicioPrestadoYourself();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    function eliminar($id) {}

    //---------------------------------------------------
    //---------------------------------------------------
    //---------------------------------------------------
    /*
     * Usando LOAD JAVASCRIPT :: load_empleador_dd2_1
     *
     */
    public function listar($id_emp_destaque) {
        $query = " 
        SELECT *FROM servicios_prestados
        WHERE id_empleador_destaque = ?
        ORDER BY id_servicio_prestado ASC	
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_emp_destaque);
        $stm->execute();
        $data = $stm->fetchAll();

        return $data;
    }
    
    
}

?>
