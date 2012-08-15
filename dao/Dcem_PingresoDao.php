<?php
//require_once 'AbstractDao.php';
class Dcem_PingresoDao extends AbstractDao {

    //put your code here
    public function registrar($obj) {

        $model = new Dcem_Pingreso();
        $model = $obj;

        $query = "
        INSERT INTO dcem_pingresos
                    (
                    id_ptrabajador,
                    id_detalle_concepto_empleador_maestro,
                    devengado,
                    pagado)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_ptrabajador());
        $stm->bindValue(2, $model->getId_detalle_concepto_empleador_maestro());
        $stm->bindValue(3, $model->getDevengado());
        $stm->bindValue(4, $model->getPagado());


        $stm->execute();
        //$lista = $stm->fetchAll();
        return true;
    }

    public function actualizar($obj) {

        $model = new Dcem_Pingreso();
        $model = $obj;

        $query = "
        UPDATE dcem_pingresos
        SET 
        devengado = ?,
        pagado = ?
        WHERE id_dcem_pingreso = ?;
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getDevengado());
        $stm->bindValue(2, $model->getPagado());
        $stm->bindValue(3, $model->getId_dcem_pingreso());

        $stm->execute();
        //$lista = $stm->fetchAll();
        return true;
    }

    //view
    public function listar_ID_Ptrabajador($id_ptrabajador, $seleccionado) {
        $query = "
        SELECT 
        dcem_pi.id_dcem_pingreso,
        dcem_pi.id_ptrabajador,
        dcem_pi.devengado,
        dcem_pi.pagado,

        -- detalle concepto empleador maestro
        dcem.id_detalle_concepto_empleador_maestro,
        dcem.cod_detalle_concepto,
        dcem.seleccionado,
        
        -- detalle descripcion
        dc.descripcion

        FROM dcem_pingresos AS dcem_pi
        INNER JOIN detalles_conceptos_empleadores_maestros AS dcem
        ON dcem_pi.id_detalle_concepto_empleador_maestro = dcem.id_detalle_concepto_empleador_maestro
        
        INNER JOIN detalles_conceptos AS dc
        ON dcem.cod_detalle_concepto = dc.cod_detalle_concepto

        WHERE (dcem_pi.id_ptrabajador = ? AND dcem.seleccionado = ?)
        
         ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_ptrabajador);
        $stm->bindValue(2, $seleccionado);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
/*
    // 01 JUAN
    public function get_ID_dcem($id_empleador_maestro, $cod_detalle_concepto) {

        $query = "
          -- 01
        SELECT 
        id_detalle_concepto_empleador_maestro AS id_dcem
        FROM detalles_conceptos_empleadores_maestros
        WHERE id_empleador_maestro = ?
        AND cod_detalle_concepto = ?   -- 0121 sueldo basico      
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $cod_detalle_concepto);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]['id_dcem'];
    }*/

 /*   // 02 MARIA
    public function get_ID_dcem($id_dcem, $id_ptrabajador) {

        $query = "
        -- 02
        SELECT *FROM dcem_pingresos 
        WHERE id_detalle_concepto_empleador_maestro = ?
        AND id_ptrabajador = ?     
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_dcem);
        $stm->bindValue(2, $id_ptrabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0]['id_detalle_concepto_empleador_maestro'];
    }
*/
    public function get_id_dcem_pingreso($id_empleador_maestro, $cod_detalle_concepto, $id_ptrabajador) {
        $query = "
        SELECT 
        id_detalle_concepto_empleador_maestro AS id_dcem
        FROM detalles_conceptos_empleadores_maestros
        WHERE id_empleador_maestro = ?
        AND cod_detalle_concepto = ?      
";

        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $cod_detalle_concepto);

        $stm->execute();
        $lista_a = $stm->fetchAll();
       
        $id_dcem = $lista_a[0]['id_dcem'];

        $query2 = "
        -- 02
        SELECT 
        id_dcem_pingreso
        FROM dcem_pingresos 
        WHERE id_detalle_concepto_empleador_maestro = ?
        AND id_ptrabajador = ?                  
        ";
        $stm = $this->pdo->prepare($query2);
        $stm->bindValue(1, $id_dcem);
        $stm->bindValue(2, $id_ptrabajador);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        return $lista[0]['id_dcem_pingreso'];
    }

}


/*
$dao = new Dcem_PingresoDao();
$data = $dao->get_id_dcem_pingreso('2', '0121', '16');
var_dump($data);
*/
?>
