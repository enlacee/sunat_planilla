<?php 
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
require_once('../dao/EmpleadorMaestroDao.php');
require_once('../controller/EmpleadorMaestroController.php');

$id_empleador_maestro = $_SESSION['sunat_empleador']['id_empleador'];

$DATA_EME = buscarIdEmpleadorMaestroPorIDEMPLEADOR($id_empleador_maestro);






?>

            <script>
                //INICIO HISTORIAL
                $(document).ready(function(){						   
					cargarTablaEmpleador_dd1();
				 	$( "#tabs" ).tabs();


                });
             
                /*****************************************************/
                /***************** Terrenos ***************************/
                /*****************************************************/

                //FUNNCION CARGAR_TABLA EMPLEADOR DESPLAZA O DESTACA 10/12/2011
//FUNNCION CARGAR_TABLA EMPLEADOR DESPLAZA O DESTACA 10/12/2011
                function cargarTablaEmpleador_dd1(){ 
				
					var id_maestro_empleador = document.getElementById('id_empleador_maestro').value;
					//console.log('id_maestro_empleador ='+id_maestro_empleador);
                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/EmpleadorDestaqueController.php?oper=cargar_tabla&id_empleador_maestro='+id_maestro_empleador,
                        datatype: 'json',
                        colNames:['id_empleador_destaque','id_empleador_maestro','id_empleador','RUC','Razon Social','Opciones'],
                        colModel :[
                            {
                                name:'id_empleador_destaque', 
                                editable:false, 
                                index:'id_empleador_destaque',
								search:false,
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
                                name:'razon_social', 
                                index:'razon_social',
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
                        sortname: 'id_empleador_destaque',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Empleadores a quienes destaco o desplazo personal:',
                        onSelectRow: function(ids) {
                        },


                        height:150,
                        width:720 
                    });
					//-----------------
					jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

                }

    

            </script>
            
            

			
			

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Empleador</a></li>			
        </ul>
      <div class="ocultar">tipo
  <input name="tipo"  id="tipo" type="text" value="<?php echo $DATA_EME['tipo']; ?>" />
          
          
          id_empleador_maestro
          <input name="id_empleador_maestro" id="id_empleador_maestro"type="text" 
        value="<?php echo $DATA_EME['id_empleador_maestro']; ?>" />
          <br />
          
          Id_empleador
          <input type="text" name="id_empleador" id="id_empleador" 
		value="<?php echo $DATA_EME['id_empleador']; ?>" />        
      </div>
<div id="tabs-1">
  <br />
  <input type ="button" 
  class="submit-nuevo"
            onclick="javascript:cargar_pagina('sunat_planilla/view/view_empleador_buscar.php?tipo_emp=emp-dd1','#CapaContenedorFormulario')"
             value="Nuevo Registro"/>
              
                <input type="button" name="Submit" value="Cancelar"
                class="submit-cancelar"
                 onclick="javascript:cargar_pagina('sunat_planilla/view/edit_empleador.php?id_empleador=<?php echo $DATA_EME['id_empleador']; ?>','#CapaContenedorFormulario')" />
        </p>
      <table id="list"><tr><td/></tr></table>
            <div id="pager"></div>

		
            <div class="ayuda">
				<p>Empleadores a quienes destaco o desplazo personal:</p>
              Para mayor detalle respecto a las opciones e informaci&oacute;n solicitada, s&iacute;rvase   ingresar al siguiente enlace: <a id="aAyuda" href="http://www.sunat.gob.pe/ayuda/tributos/tregistro-E-P/T-RegistroPrivado-H02.html#destaca" target="_blank">Ayuda del T-Registro</a>
            </div>
      </div>

        
  </div>
</div>

