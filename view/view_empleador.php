<?php 
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
?>
            <script>
                //INICIO HISTORIAL
                $(document).ready(function(){						   
                    //demoApp = new Historial();
					//alert ("hola anb");
                    cargarTablaEmpleador();
				 $( "#tabs" ).tabs();


                });
             
                /*****************************************************/
                /***************** Terrenos ***************************/
                /*****************************************************/

                //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
                function cargarTablaEmpleador(){ 

                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/EmpleadorController.php?oper=cargar_tabla&id_tipo_inmueble=1',
                        datatype: 'json',
                        colNames:['id_empleador','RUC','Razon Social','Nombre Comercial','Telefono','Tipo empleador','Opciones','tipo'],
                        colModel :[
                            {
                                name:'id_empleador', 
                                editable:false,
                                search:false,
                                index:'id_empleador',
                                width:30,
                                align:'center'
                            },		
                            {
                                name:'ruc',
                                index:'ruc', 
                                editable:true,
                                editrules:{
                                    required:true
                                },
                                width:70, 
                                align:'center' 
                            },
                            {
                                name:'razon_social', 
                                index:'razon_social',
                                editable:false,
                                width:80,
                                align:'center'
                            },
                            //	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
                            {
                                name:'nombre_comercial', 
                                index:'nombre_comercial',
                                editable:true,
                                width:80,
                                align:'center'
                            },
                            {
                                name:'telefono',
                                editable:true,
                                search:false,
                                width:60, 
                                index:'telefono', 
                                align:'center'
                            },
                            {
                                name:'nombre_tipo_empleador',
                                editable:true,
                                
                                width:120, 
                                index:'nombre_tipo_empleador', 
                                align:'center'
                            },
                            {
                                name:'opciones',
                                editable:false,
                                search:false,
                                width:60, 
                                index:'opciones', 
                                align:'center'
                            },
							 {
                                name:'tipo',
                                editable:true,
                                search:true,
                                width:60, 
                                index:'tipo', 
                                align:'center',
								hidden:true
                            }
		
		
                        ],
                        pager: '#pager',
						autowidth: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_empleador',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de Empleadores',
						height:200,
                        width:720

                    });


                    //--- PIE GRID
                    jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
                    
                   /* jQuery("#list").jqGrid('navGrid','#pager', 
                    {	
                        view:false
                    },
                    {	
                        height:280,
                        reloadAfterSubmit:true,
                        url:'../controller/KitController.php',
                        onInitializeForm: function(formid) {
                            $("#FrmGrid_list #codigo").attr("disabled","disabled")
								
                        },
                        recreateForm:true
                    }, // edit options 
                    {	
                        height:280,
                        reloadAfterSubmit:true,
							
                        afterSubmit : function (response, postdata) {
								
                            if(response.responseText=="null")
                                return [true,""];
                            else
                                return [false,"El codigo ya existe"];
                        } ,
								
                        url:'../controller/KitController.php',
                        onInitializeForm: function(formid) {
                            $("#FrmGrid_list #codigo").removeAttr("disabled")
                        },
                        recreateForm:true
                    }, // add options 
                    {	
                        reloadAfterSubmit:false,
                        url:'../controller/KitController.php'
                    }, // del options 
                    {
                        sopt:['eq','ne','lt','le','gt','ge','cn']
                    }, // search options
                    {}
                );*/


                }

    

            </script>
            
            

			
			

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Empleador</a></li>			
			
        </ul>
        <div id="tabs-1">

            <input type ="button" 
            onclick="javascript:cargar_pagina('sunat_planilla/view/new_empleador.php','#CapaContenedorFormulario')"
             value="Nuevo Empleador "/>
             <br />
             <input type="checkbox" name="chk_historial_empleadores" id="chk_historial_empleadores" />
          Mostrar Histórico de Prestadores
<table id="list"><tr><td/></tr></table>
            <div id="pager"></div>

		

        </div>

        
    </div>
</div>

