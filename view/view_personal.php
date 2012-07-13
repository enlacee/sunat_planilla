<?php
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
?>

<script>
  
    //INICIO HISTORIAL
    $(document).ready(function(){					   
        //demoApp = new Historial();
        $( "#tabs").tabs();				 
        cargarTablaPersonalServicio();  

    });
            
				
    //----------------------------------------------------------								
    function eliminarPersona(id){
        if(confirm("Desea Eliminar el Registro seleccionado?")){		
            var data = "id_persona="+id+"&oper=del";		
            $.getJSON(
            'sunat_planilla/controller/PersonaController.php?'+data,
            function(data){
                if(data){
                    jQuery("#list").trigger("reloadGrid");
                    alert("Registro Fue Eliminado Correctamente");						
                }else{
                    alert("Ocurrio un error, intente nuevamente");						
                }
            }
        );	
        }
    }


</script>

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro de Trabajadores, Pensionistas y Otros Prestadores de Servicios</a></li>			
	
        </ul>
        <div id="tabs-1">
          <div class="ocultar">
            <input type="checkbox" name="chk_historial_empleadores" id="chk_historial_empleadores" />
            Mostrar Histórico de Prestadores
          </div>
          <br />
            <input type ="button" 
                   onclick="javascript:cargar_pagina('sunat_planilla/view/new_personal.php','#CapaContenedorFormulario')" 
                   value="Nuevo"/>
        <br />

            <table id="list">
            </table>
            <div id="pager"></div>


        </div>


        


    </div>
</div>

