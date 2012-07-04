<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleServicioPrestado2
 *
 * @author Anibal
 */
class ServicioPrestadoYourSelfDao extends AbstractDao {

    //put your code here

    function registrar($obj) {
        $query = "
        INSERT INTO servicios_prestados_yourself
                    (
                     id_empleador_destaque_yoursef,
                     cod_tipo_actividad,
                     fecha_inicio,
                     fecha_fin,
                     estado)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?);
                ";


        $model = new ServicioPrestadoYourself();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador_destaque_yourself());
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
        UPDATE servicios_prestados_yourself
        SET 
          id_empleador_destaque_yoursef = ?,
          cod_tipo_actividad = ?,
          fecha_inicio = ?,
          fecha_fin = ?
        WHERE id_servicio_prestado_yoursef = ?;
        ";

        $model = new ServicioPrestadoYourself();
        $model = $obj;
//require_once("../model/DetalleServicioPrestado2.php");
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador_destaque_yourself());
        $stm->bindValue(2, $model->getCod_tipo_actividad());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getFecha_fin());
        $stm->bindValue(5, $model->getId_servicio_prestado_yourself());
        $stm->execute();
        //$data = $stm->fetchAll();
        //echo "ENTRO EN DAAAO<BR>";
        //printr($model);
        return true;
    }

    function baja($obj) {
        $query = "            
        UPDATE detalles_servicios_prestados2
        SET          
          estado = ?
        WHERE id_servicio_prestado_yoursef = ?;";

        $model = new ServicioPrestadoYourself();
        $model = $obj;

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getEstado());
        $stm->bindValue(2, $model->getId_servicio_prestado_yourself());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    function eliminar($id_detalle_servicio_prestado2) {}

    //---------------------------------------------------
    //---------------------------------------------------
    //---------------------------------------------------
    /*
     * Usando LOAD JAVASCRIPT :: load_empleador_dd2_1
     *
     */
    public function listar($id_emp_destaque_yourself) {
        $query = " 
        SELECT 
        edy.id_empleador_destaque_yoursef,
        -- edy.id_empleador,
        -- edy.id_empleador_maestro,
        spy.id_servicio_prestado_yoursef,
        spy.cod_tipo_actividad,
        spy.fecha_inicio,
        spy.fecha_fin,
        spy.estado

        FROM empleadores_destaques_yourself AS  edy
        INNER JOIN servicios_prestados_yourself AS spy
        ON edy.id_empleador_destaque_yoursef = spy.id_empleador_destaque_yoursef
        WHERE spy.id_empleador_destaque_yoursef = ?

        ORDER BY id_servicio_prestado_yoursef ASC	
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_emp_destaque_yourself);
        $stm->execute();
        $data = $stm->fetchAll();

        return $data;
    }

    /*
     * Funcion Exclusivamente Para Listar
     * el tipo de servicio que brinda este Empleador Subordinado
     * THIS function ayuda a No registrar Nuevamente Los mismos servicios[evitando duplicar reg]
     */

    public function listarDetalleServicioPrestado2PorIdEME($ID_EME) {
        $query = " 
        SELECT 
        id_detalle_servicio_prestado2,
        -- id_empleador_maestro_empleador,
        cod_tipo_actividad
        FROM detalles_servicios_prestados2
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
    }

//ENDFUNCTION
}

?>
