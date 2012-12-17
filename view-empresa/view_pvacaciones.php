<script type="text/javascript">

	var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	var periodo = document.getElementById('periodo').value;

    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		cargarTablaVacacionPeriodo();

		
	});
	
	//----------------- jqgrid
	
    function cargarTablaVacacionPeriodo(){
		var parametro = 'id_pdeclaracion='+id_pdeclaracion+'&periodo='+periodo;

        //$("#list-2").jqGrid('GridUnload');
        $("#list").jqGrid({
url:'sunat_planilla/controller/VacacionController.php?oper=vacacion_periodo&estado=1&'+parametro,
            datatype: 'json',
            colNames:['id','Numero Doc','Apellido Paterno',
                'Apellido Materno','Nombres','F programada','vacacion','Opciones'],
            colModel :[
                {
                    name:'id_trabajador', 
                    editable:false, 
                    index:'id_trabajador',
                    search:false,
                    width:20,
                    align:'center'
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
					search:false,
                    width:100,
                    align:'left',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return ' colspan=4';
                    },
                    formatter : function(value, options, rData){4
                        return ": "+value + " - "+rData['2']+" "+rData['3']+" "+rData['4'] ;
                    }
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
                },
				{
                    name:'fecha_programada',
                    index:'fecha_programada',
					hidden :false,
                    editable:true,
                    search:false,
                    width:100, 
                    align:'center'
                },
				{
                    name:'estado_vaca',
                    index:'estado_vaca',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
					
				},
                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                }							


            ],
            pager: '#pager',
			rownumbers: true,
            //autowidth: true,
			//width: '',
			height:320,
            rowNum:10,
            rowList:[10,20],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: 'List',
            //toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false,
            //onSelectRow: function(rowid, selected) {}
			//---
			gridComplete    : function(){  //alert("grid okD");
		
				var ids = $("#list").getDataIDs();
				//console.log("-ids-");
				//console.dir(ids);
				//console.log("-ids-");
				var act;
				var name_space = '';
				for(var i=0;i<ids.length;i++){
					//Obteniendo Fila X Fila
					var data = $("#list").getRowData(ids[i]);
					if (data.nombre_ubigeo_reniec == "" && data.estado_direccion == "Primera") {
						name_space = data.num_documento+"  "+data.apellido_paterno+" "+data.apellido_materno+" "+data.nombres;
						//act =' <b class="red">Debe Ingresar La Primera Direccion es Obligatorio!. </b>';
						//$("#list").setRowData(ids[i],{nombre_ubigeo_reniec: act });
					}
				}//ENDFOR
			}			
			
			//---
			
			
        	});
		
		
        //--- PIE GRID
	//jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }
	
</script>


<div class="demo" align="left">

<div class="ocultar">
id_pdeclaracion
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
value="<?php echo $_REQUEST['id_declaracion']; ?>"/><br />
periodo
<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo']; ?>" />
</div>


    <div id="tabs">
   
        <ul>
            <li><a href="#tabs-1">Vacaciones</a></li>			

        </ul>
        <div id="tabs-1">
<!-- Boton cancelar-->
<input type="button" onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_registro_concepto_e.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" class="submit-cancelar" value="Cancelar" name="Retornar ">           
<br />  
          <h2>Lista de Trabajadores con vacaciones.</h2>
          <table id="list">
          </table>
        <div id="pager">
        </div>
<p></p>
<p>&nbsp;</p> 





          
          
        </div>
</div>

</div>






<!-- DIALOG -->

<div id="dialog_view_vacacion" title="Vacaciones">
    <div id="view_vacacion" align="left"></div>
</div>








