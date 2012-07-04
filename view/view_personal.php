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
            
    /*****************************************************/
    /***************** Terrenos ***************************/
    /*****************************************************/

    //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
				
    function cargarTablaPersonalServicio(){

        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PersonaController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['Id','Categoria','Ttipo_doc','Numero Doc','Apellido Paterno',
                'Apellido Materno','Nombres','Fecha Nacimiento','Sexo','Estado'
                ,'Opciones'],
            colModel :[
                {
                    name:'id_persona', 
                    editable:false, 
                    index:'id_persona',
                    search:false,
                    width:30,
                    align:'center'
                },		
                {
                    name:'categoria',
                    index:'categoria',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center'
                },
                {
                    name:'nombre_tipo_documento', 
                    index:'nombre_tipo_documento',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:60,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:80,
                    align:'center'
                },
                {
                    name:'fecha_nacimiento',
                    index:'fecha_nacimiento',
                    editable:true,
                    width:60,  
                    align:'center'
                },
                {
                    name:'sexo',
                    index:'sexo',
                    editable:true,
                    search:false,
                    width:30, 
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    editable:true,
                    search:false,
                    width:40, 
                    align:'center'
                },
                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:50, 
                    align:'center'
                }							

		
            ],
            pager: '#pager',
            autowidth: true,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_persona',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: 'Lista de Personal',
            /*						multiselect: false,
                                    hiddengrid: true,*/
            onSelectRow: function(ids) {},
            height:250,
            width:720
        });
        //--- PIE GRID
        jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
					
					
					
					
    }





				
				
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

