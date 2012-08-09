<?php
//class PlamePeriodosLaboralesController 
    //put your code here
    
    function buscarPLPorIdPtrabajador($id_ptrabajador){
        
        $dao = new PperiodoLaboralDao();
        $data = $dao->buscarPorIdPtrabajador($id_ptrabajador);

        
        
        $dataObj = array();
        
        
        for($i=0; $i<count($data);$i++){
            $model = new PperiodoLaboral();
            $model->setId_pperiodo_laboral($data[$i]['id_pperiodo_laboral']);
            $model->setId_ptrabajador($data[$i]['id_ptrabajador']);
            $model->setFecha_inicio($data[$i]['fecha_inicio']);
            $model->setFecha_fin($data[$i]['fecha_fin']);
            
            $dataObj[] = $model;
        }

        
        return $dataObj;
        
    }


?>
