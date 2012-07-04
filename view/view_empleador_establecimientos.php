<script type="text/javascript">
    $(document).ready(function(){
		var anchoPantalla = document.body.offsetWidth - 320;
  		var lastsel2;                
        $( "#tabs").tabs();
		
		cargarTablaEmpleador_establecimientos();
		
	});
                /*****************************************************/
                /***************** Terrenos ***************************/
                /*****************************************************/

                //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
				var lastsel2;
                function cargarTablaEmpleador_establecimientos(){ 
				
					id_empleador = document.getElementById('id_empleador').value;
                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/EmpleadorEstablecimientoController.php?oper=cargar_tabla&id_empleador='+id_empleador,
                        datatype: 'json',
                        colNames:['Id','Id Empleador','Cod Establecimiento','Direccion','Realiza Act. Riesgo'],
                        colModel :[
                            {
                                name:'id_establecimiento', 
                                editable:false,
                                search:false,
                                index:'id_establecimiento',
                                width:10,
                                align:'center'
                            },		
                            {
                                name:'id_empleador',
                                index:'id_empleador', 
                                editable:false,
								hidden:true,
                                editrules:{
                                    required:true
                                },
                                width:10, 
                                align:'center' 
                            },
                            {
                                name:'cod_establecimiento', 
                                index:'cod_establecimiento',
                                editable:false,
                                width:15,
                                align:'center'
                            },
                            {
                                name:'direccion', 
                                index:'direccion',
                                editable:false,
                                width:15,
                                align:'center'
                            },
							{name:'realizaran_actividad_riesgo',
							index:'realizaran_actividad_riesgo',
							width:10, editable: true,edittype:"checkbox",editoptions: {value:"Si:No"}},
	
		
		
                        ],onSelectRow: function(id) {
	
//alert(id);


		if(id && id!==lastsel2){
			jQuery('#list').jqGrid('restoreRow',lastsel2);
			jQuery('#list').jqGrid('editRow',id,true);
			lastsel2=id;
		}







	},
	
	editurl: "sunat_planilla/controller/EmpleadorEstablecimientoController.php",
                        pager: '#pager',
						autowidth: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_establecimiento',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de Establecimientos del Empleador',
                        height:"200px",
                        width:"55px" 
                    });


                    //--- PIE GRID
                    jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
                    

                }

	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Editar Empleador</a>
              
              <input type="hidden" name="id_empleador" id="id_empleador" value="<?php echo $_REQUEST['id_empleador'];?>" />
            </li>			

        </ul>
        <div id="tabs-1">
          <h1><br />
            Establecimiento Propios (Identintificar centros de trabajo de riesgo)

          </h1>
<table id="list"><tr><td/></tr></table>
            <div id="pager"></div>

          <p>
            <input type="button" name="btn_retornar" id="btn_retornar" value="Retornar"
            onclick="javascript:cargar_pagina('sunat_planilla/view/edit_empleador.php?id_empleador=<?php echo $_REQUEST['id_empleador']; ?>','#CapaContenedorFormulario')"
             />
          </p>
      </div>
</div>
    <p>&nbsp;</p>
