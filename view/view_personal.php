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
        cargarTablaPersonalServicio(1);  

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


//funcion chekkk
function estadoCheckEstado(){
	var obj = document.getElementById('chk_historial_empleadores');
	//console.dir(obj);
	//alert(obj.checked);		
	//var estado = obj.checked;		
	//alert(obj.checked);
	
	if(obj.checked == true){ //alert("entro true");
		cargarTablaPersonalServicio(0)
	}else{
		//alert("entro false");
		cargarTablaPersonalServicio(1)
	}

}

</script>

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Personas</a></li>			
	
        </ul>
        <div id="tabs-1">

            <input type ="button"
            class="submit-go" 
                   onclick="javascript:cargar_pagina('sunat_planilla/view/new_personal.php','#CapaContenedorFormulario')" 
                   value="Nuevo Registro"/>
          <br />
          

            <table id="list">
            </table>
            <div id="pager"></div>


        </div>


        


    </div>
</div>



<div id="dialog-addTrabajador" title="Agregar Nuevo Trabajador">
    <div id="edit-addTrabajador" align="left"></div>
</div>

