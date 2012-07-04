<?php

/**
* CONTROLLER USADO Cargar Lista de tipo de Servicio OK
**
**/ 
    function listarServiciosPrestadosYourself($id_empleador_maestro,$id_empleador){        
       
        
       // (01) ->Buscar Id Empleador Destaque Yourself
        $dao = new EmpleadorDestaqueYourSelfDao();
       $id_emp_destaque_yourself = $dao->buscarIdEmpleadorDestaqueYourself($id_empleador_maestro, $id_empleador);
   
       // (02) ->Listar Servicios
       if(!empty ($id_emp_destaque_yourself)){           
       
       $dao = new ServicioPrestadoYourSelfDao();
       $data = $dao->listar($id_emp_destaque_yourself);
       
       //MODEL
       //$array_obj = array(); 
     /* foreach ($data as $indice ){
           $obj = new DetalleServicioPrestado2();
           $obj->setId_detalle_servicio_prestado2($indice['id_detalle_servicio_prestado_prestado2']);
           $obj->setId_empleador_maestro_empleador($indice['id_empleador_maestro_empleador']);
           $obj->setCod_tipo_actividad($indice['cod_tipo_actividad']);
           $obj->setEstado($indice['estado']);
           $obj->setFecha_inicio($indice['fecha_inicio']);
           $obj->setFecha_inicio($indice['fecha_fin']);
           
           $array_obj = $obj;
       }*/
	   //echo "PRITN EN CONTROLLER<pre>";
	   //print_r($data);
	   //echo "</pre>";
       }
        return $data;
        
    }
?>
