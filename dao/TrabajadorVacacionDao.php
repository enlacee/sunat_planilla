<?php
/*
require_once '../util/funciones.php';
require_once '../dao/AbstractDao.php';
*/
class TrabajadorVacacionDao extends AbstractDao {

    function add($obj) {
        try {
            $query = "
        INSERT INTO trabajadores_vacaciones
                    (
                    id_pdeclaracion,
                    id_trabajador,
                    fecha_inicio,
                    fecha_fin,
                    num_dia,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);
                    ";
            //Inicia transaccion
            $model = new TrabajadorVacacion();
            $model = $obj;
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_pdeclaracion());
            $stm->bindValue(2, $model->getId_trabajador());
            $stm->bindValue(3, $model->getFecha_inicio());
            $stm->bindValue(4, $model->getFecha_fin());
            $stm->bindValue(5, $model->getNum_dia());
            $stm->bindValue(6, $model->getFecha_creacion());
            $stm->execute();

            // id Comerico
            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();

            $this->pdo->commit();
            //finaliza transaccion
            //return true;
            $stm = null;
            return $lista[0]['id'];
        } catch (Exception $e) {
            //  Util::rigistrarLog( $e, $query );
            $this->pdo->rollBack();
            throw $e;
        }
    }

    function existeTrabajadorVacacion($id_pdeclaracion, $id_trabajador, $fecha_inicio) {

        $query = "
            SELECT *FROM trabajadores_vacaciones
            WHERE id_pdeclaracion = ?
            AND id_trabajador = ?
            AND fecha_inicio = ?            
        ";
        //Inicia transaccion
        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->bindValue(3, $fecha_inicio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $this->pdo->commit();
        $stm = null;

        if (count($lista) >= 1) {
            return false;
        } else {
            return true;
        }
    }

    
    //buscar what...
    function buscar_what($id_pdeclaracion,$id_trabajador, $atributo) { //num_dia
        $query = "
            SELECT 
            $atributo
            FROM trabajadores_vacaciones
            WHERE id_pdeclaracion = ?
            AND id_trabajador = ? 
            ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);        
        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0][$atributo];
    }
    
    // OJO SOLO ID
    function listaIDTrabajadoresVacacion($id_pdeclaracion){
        $query = "
            SELECT 
            id_trabajador
            FROM trabajadores_vacaciones
            WHERE id_pdeclaracion = ?            
            ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;        
        return $lista;
        
    }
    
    function getDiaVacacion($id_pdeclaracion,$id_trabajador){        

        $query = "
            SELECT 
            num_dia
            FROM trabajadores_vacaciones
            WHERE id_pdeclaracion = ?
            AND id_trabajador = ?
            ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $id_trabajador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        
        $num_dia = 0;
        foreach ($lista as $key => $value) {
            $num_dia = $num_dia + $value['num_dia'];
        }       
        
        return $num_dia;        
        
    }
    
}


/*
$dao = new TrabajadorVacacionDao();
$data = $dao->getDiaVacacion(24, 5);
echoo($data);
*/

?>
