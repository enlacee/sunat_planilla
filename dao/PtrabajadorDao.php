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
        
    /**
     *
     * @param type $id_empleador_maestro
     * @param type $periodo
     * @return type 
     * Lista de trabajadores en PLANILLA
     * para un Empleador Maestro
     * y un Periodo "Y-m-d"
     * 
     */
    public function listar($id_empleador_maestro, $periodo){

        $query = "
        SELECT
        pd.id_pdeclaracion,
        pd.periodo,
        pt.id_ptrabajador,
        -- pt.id_trabajador,

        p.cod_tipo_documento,
        p.num_documento,
        p.apellido_paterno,
        p.apellido_materno,
        p.nombres,

        -- jornada laboral
        pjl.dia_laborado

        FROM pdeclaraciones AS pd
        INNER JOIN empleadores_maestros AS em
        ON pd.id_empleador_maestro = em.id_empleador_maestro

        INNER JOIN ptrabajadores AS pt
        ON pd.id_pdeclaracion = pt.id_pdeclaracion

        INNER JOIN trabajadores AS tra
        ON pt.id_trabajador = tra.id_trabajador

        INNER JOIN personas AS p
        ON tra.id_persona = p.id_persona


        INNER JOIN pjornadas_laborales AS pjl
        ON pt.id_ptrabajador = pjl.id_ptrabajador

        WHERE (em.id_empleador_maestro = ? AND  pd.periodo = ? )
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $periodo);
        $stm->execute();
        $data = $stm->fetchAll();
        
        return $data;
        
    }
    
    public function buscar_ID($id_ptrabajador){
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
    
    
}

?>
