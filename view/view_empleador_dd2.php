<?php 
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
require_once('../dao/EmpleadorMaestroDao.php');
require_once('../controller/EmpleadorMaestroController.php');
echo "<pre>";
//echo print_r($_SESSION);
echo "</pre>";

$id_empleador_maestro = $_SESSION['sunat_empleador']['id_empleador'];

$DATA_EME = buscarIdEmpleadorMaestroPorIDEMPLEADOR($id_empleador_maestro);

?>

            <script>
                //INICIO HISTORIAL
                $(document).ready(function(){						   
                    //demoApp = new Historial();
					//alert ("hola anb");
                   
				 $( "#tabs" ).tabs();
				cargarTablaEmpleador_dd2();
				
                });
             
			/*****************************************************/
			/***************** Terrenos ***************************/
			/*****************************************************/
//FUNNCION CARGAR_TABLA EMPLEADOR DESPLAZA O DESTACA 10/12/2011
                function cargarTablaEmpleador_dd2(){
					
					var id_maestro_empleador = document.getElementById('id_empleador_maestro').value;
					console.log('id_maestro_empleador ='+id_maestro_empleador);
                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/EmpleadorDestaqueYourselfController.php?oper=cargar_tabla&id_empleador_maestro='+id_maestro_empleador,
                        datatype: 'json',
                        colNames:['id_empleador_destaque_yoursef','id_empleador_maestro','id_empleador','RUC','Razon Social','Opciones'],
                        colModel :[
                            {
                                name:'id_empleador_destaque_yoursef', 
                                editable:false, 
                                index:'id_empleador_destaque_yoursef',
                                width:30,
                                align:'center'
                            },		
                            {
                                name:'id_empleador_maestro',
                                index:'id_empleador_maestro', 
                                editable:true,
                                width:70, 
                                align:'center'
                            },
                            {
                                name:'id_empleador', 
                                index:'id_empleador',
                                editable:false,
                                width:80,
                                align:'center'
                            },                            
                            {
                                name:'ruc', 
                                index:'ruc',
                                editable:true,
                                width:80,
                                align:'center'
                            },
							{
                                name:'razon_social_concatenado', 
                                index:'razon_social_concatenado',
                                editable:false,
                                width:80,
                                align:'center'
                            },
                            {
                                name:'opciones',
                                editable:false,
                                width:60, 
                                index:'opciones', 
                                align:'center'
                            },
	
		
		
                        ],
                        pager: '#pager',
						autowidth: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_empleador_destaque_yoursef',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Empleadores que me desplazan personal:',
                        onSelectRow: function(ids) {
                        },


                        height:150,
                        width:720 
                    });
                    //--- PIE GRID
                    jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
					
					

                }
    

            </script>			
			

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Empleador</a>
            </li>
        </ul>
        <div class="ocultar">
          <div><label>tipo</label>
            <input name="tipo"  id="tipo" type="text" value="<?php echo $DATA_EME['tipo']; ?>" />
            </div>
          <div><label>id_empleador_maestro</label>
            <input name="id_empleador_maestro" id="id_empleador_maestro"type="text" value="<?php echo $DATA_EME['id_empleador_maestro']; ?>" />
          </div>
          <div><label>id_empleador</label>
            <input type="text" name="id_empleador" id="id_empleador" 
		value="<?php echo $DATA_EME['id_empleador']; ?>" />
          </div>
          <div><a href="http://localhost/phpmyadmin/sql.php?db=db&amp;table=servicios_prestados_yourself&amp;sql_query=SELECT+%2A+FROM+%60servicios_prestados_yourself%60+ORDER+BY+%60servicios_prestados_yourself%60.%60id_empleador_destaque_yoursef%60+ASC&amp;token=8cf5b78a1758154051f33ba4054a47a6" title="Sort">id_empleador_destaque_yoursef</a> 
            <label for="id_empleador_destaque_yoursef"></label>
            <input type="text" name="id_empleador_destaque_yoursef" id="id_empleador_destaque_yoursef" />
          </div>
        </div>
<div id="tabs-1">
<p>Empleadores que me destacan o desplazan personal: <span class="red">ELLOS ME DESPLAZAN TRBAJADORES:</span></p>

        <p>
          <input name="button" type ="button" 
            onclick="javascript:cargar_pagina('sunat_planilla/view/view_empleador_buscar.php?tipo_emp=emp-dd2','#CapaContenedorFormulario')"
             value="Nuevo"/>
          
        </p>
      <table id="list">
      </table>
            <div id="pager"></div>



<p>
        Para mayor detalle respecto a las opciones e informaci&oacute;n solicitada, s&iacute;rvase   ingresar al siguiente enlace: <a id="aAyuda" href="http://www.sunat.gob.pe/ayuda/tributos/tregistro-E-P/T-RegistroPrivado-H02.html#terceros" target="_blank"><U>Ayuda del T-Registro</U></a></div>
</p>        
    </div>
</div>

