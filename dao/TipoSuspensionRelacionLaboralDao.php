<?php

class TipoSuspensionRelacionLaboralDao extends AbstractDao {

    //put your code here
    public function listar() {

        $query = "
        SELECT 
        cod_tipo_suspen_relacion_laboral,
        descripcion_abreviada
        FROM tipos_suspensiones_relaciones_laborales
        ";       
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        
        $stm = null;
        return $lista;
    }
    
    
    

}

?>
