<?php 
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//

$ID_EMPLEADOR = $_REQUEST['id_empleador'];
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
					var id_empleador = document.getElementById('id_empleador').value;
                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/EstablecimientoController.php?oper=cargar_tabla&id_empleador='+id_empleador,
                        datatype: 'json',
                        colNames:['id_establecimiento','id_empleador','Codigo','RUC','nombre_establecimiento','fecha_creacion','Opciones'],
                        colModel :[
                            {
                                name:'id_establecimiento', 
                                editable:false, 
                                index:'id_establecimiento',
                                width:30,
                                align:'center'
								
                            },
                            {
                                name:'id_empleador', 
                                editable:false, 
                                index:'id_empleador',
                                width:30,
                                align:'center',
								hidden:true
                            },									
                            {
                                name:'nombre_establecimiento',
                                index:'nombre_establecimiento', 
                                editable:true,
                                editrules:{
                                    required:true
                                },
                                width:70, 
                                align:'center', 
                            },
                            {
                                name:'cod_establecimiento', 
                                index:'cod_establecimiento',
                                editable:false,
                                width:80,
                                align:'center'
                            },
						    {
                                name:'ruc_empleador', 
                                index:'ruc_empleador',
                                editable:false,
                                width:80,
                                align:'center'
                            },
                            //	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
                            {
                                name:'fecha_creacion', 
                                index:'fecha_creacion',
                                editable:true,
                                width:80,
                                align:'center'
                            },                           
							{
                                name:'opciones',
                                editable:true,
                                width:60, 
                                index:'opciones', 
                                align:'center'
                            }
		
		
                        ],
                        pager: '#pager',
						autowidth: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_establecimiento',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de Establecimientos',
                        onSelectRow: function(ids) {
                        },
                        height:200,
                        width:720 
                    });

                }

    

            </script>
            
			

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Establecimientos</a></li>			
			
        </ul>
        <div id="tabs-1">
          <input type="text" name="id_empleador" id="id_empleador"  value="<?php echo $ID_EMPLEADOR;?>"/>
          <br />
          <input type ="button" 
            onclick="javascript:cargar_pagina('sunat_planilla/view/new_establecimiento.php?id_empleador=<?php echo $ID_EMPLEADOR; ?>','#CapaContenedorFormulario')"
             value="Nuevo Establecimiento "/>

      <table id="list"><tr><td/></tr></table>
            <div id="pager"></div>

		

        </div>

        
    </div>
</div>