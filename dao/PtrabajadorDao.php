<?php

class PtrabajadorDao extends AbstractDao {

    //put your code here


    public function registrar($objeto) {
        $model = new PTrabajador();
        $model = $objeto;

        $query = "
        INSERT INTO ptrabajadores
                    (
                    id_pdeclaracion,
                    id_trabajador,
                    aporta_essalud_vida,
                    aporta_asegura_tu_pension,
                    domiciliado,
                    ingreso_5ta_categoria)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);
        ";
        try {
            $this->pdo->beginTransaction();
            
            $stm = $this->pdo->prepare($query);
            $stm->bindValue(1, $model->getId_pdeclaracion());
            $stm->bindValue(2, $model->getId_trabajador());
            $stm->bindValue(3, $model->getAporta_essalud_vida());
            $stm->bindValue(4, $model->getAporta_asegura_tu_pension());
            $stm->bindValue(5, $model->getDomiciliado());
            $stm->bindValue(6, $model->getIngreso_5ta_categoria());
            $stm->execute();

            $query2 = "select last_insert_id() as id";
            $stm = $this->pdo->prepare($query2);
            $stm->execute();
            $lista = $stm->fetchAll();
            
            $this->pdo->commit();
            return $lista[0]['id'];

            //finaliza transaccion
        } catch (Exception $e) {

            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function actualizar($objeto) {
        $obj = new PTrabajador();
        $obj = $objeto;

        $query = "
        UPDATE ptrabajadores
        SET
            aporta_essalud_vida = ?,
            aporta_asegura_tu_pension = ?,
            domiciliado = ?,
            ingreso_5ta_categoria = ?
        WHERE id_ptrabajador = ?;
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $obj->getAporta_essalud_vida());
        $stm->bindValue(2, $obj->getAporta_asegura_tu_pension());
        $stm->bindValue(3, $obj->getDomiciliado());
        $stm->bindValue(4, $obj->getIngreso_5ta_categoria());
        $stm->bindValue(5, $obj->getId_ptrabajador());
        $stm->execute();
        //$data = $stm->fetchAll();
        return true;
    }

    
    
    
    //jqgrid
    
    
    public function cantidad($periodo){
        
        
        
    }
    
    
    
    public function listar($periodo){
        
     /*   
        SELECT 
pt.id_ptrabajador,
pt.id_trabajador,

per.cod_tipo_documento,
per.num_documento,
per.apellido_materno,
per.apellido_materno,
per.nombres

FROM ptrabajadores AS pt

INNER JOIN trabajadores AS tra
ON pt.id_trabajador = tra.id_trabajador

INNER JOIN personas AS per
ON tra.id_persona = per.id_persona
                
                */
        
        ????
        
    }
    
    
}

?>
