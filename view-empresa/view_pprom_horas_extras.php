<?php
//session_start();
require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');

// Buscar Nombre Detalle Concepto: FULL
require_once('../dao/PlameDetalleConceptoDao.php');
require_once('../controller/PlameDetalleConceptoController.php');


$cod_detalle_concepto = $_REQUEST['cod_detalle_concepto'];


$data_detalle_concepto = buscar_detalle_concepto_id($cod_detalle_concepto);

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
    var periodo = document.getElementById('periodo').value;
	//---------------- inicio --------------//
	
		
	cargarTablaRPC2(id_pdeclaracion);
	
	cargar_tabla_rpc_buscar_phextras(id_pdeclaracion,periodo);
	
	
	//---------------- inicio --------------//
	function validarGrid_RPC2(value, colname) {
		console.log("value = "+ value);
		console.log("colname = "+ colname);
		
		// ------------------------------------------------	
		// 0701 = ADELANTO
		if(true){ 			
			if(value < 0 || value >1000){
				return [false,"Numero en Porcentaje de 0 and 100"];
			}else{
				return [true,""];
			}			
		}

		
			
	}
	
	
    function cargarTablaRPC2(id_pdeclaracion){

        $("#list").jqGrid({
            url:'sunat_planilla/controller/PromedioHoraExtraController.php?oper=cargar_tabla&id_pdeclaracion='+id_pdeclaracion,
            datatype: 'json',
            colNames:['','Id','idt','N. Doc','A.Paterno','A.Materno','Nombres','Valor'],
            colModel :[
				{name: 'myac',
				search:false,
				width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_promedio_hextras',
				sortable:true,
				key : true,
				index:'id_promedio_hextras',
				width:55,
				 search:false,
				 hidden:true,
				 
				},
				{
				name:'id_trabajador',
				sortable:true,				
				index:'id_trabajador',
				width:55,
				 search:false, 
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
                    	return ": "+value + " - "+rData['4']+" "+rData['5']+" "+rData['6'] ;
                    }

                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    search:true,					
                    editable:false,
					editrules:{required:true},
                    width:100,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
					
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    search:true,					
                    editable:false,
					editrules:{required:true},
                    width:100,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
					
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    search:true,					
                    editable:false,
					editrules:{required:true},
                    width:100,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
					
                },
                {
                    name:'monto', 
                    index:'monto',
                    search:false,
					sortable:false,
                    editable:true,
					editrules:{custom:true,number:true, custom_func:validarGrid_RPC2},
                    width:95,
                    align:'center'
                }
						
						


            ],
            pager: '#pager',
			//rownumbers :true,
			height:320,
            rowNum:25,
            rowList:[25,50],
            //sortname: 'id_promedio_hextras',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/PromedioHoraExtraController.php", 
            caption: 'Lista de Trabajadores',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false,search:true});
	//---------

	
    }

	
	//-------------------------------------------------------------------------------
	
	
//--
// Funcion para lista de trabajadores :: Mes 01/01/2012 a 31/01/2012
function cargar_tabla_rpc_buscar_phextras(id_pdeclaracion,periodo){

	var parametro = '?oper=trabajador_por_mes&tipo=2&id_pdeclaracion='+id_pdeclaracion+'&periodo='+periodo;
	
        //$("#list-buscar").jqGrid('GridUnload');
        $("#list-buscar").jqGrid({
            url:'sunat_planilla/controller/RegistroPorConceptoController.php'+parametro,
            datatype: 'json',
            colNames:['Id','tipo_doc','Numero Doc','APaterno',
                'AMaterno', 'Nombres','F inicio','F fin', 'Opciones'],
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
                    name:'cod_tipo_documento',
                    index:'cod_tipo_documento',
                    search:false, 
                    editable:false,
                    width:30, 
                    align:'center' 
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
					search:false, 
                    width:90,
                    align:'left',
					cellattr: function(rowId, value, rowObject, colModel, arrData) {
						return ' colspan=4';
					},
                    formatter : function(value, options, rData){4
                    	return ": "+value + " - "+rData['3']+" "+rData['4']+" "+rData['5'] ;
                    }
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:80,
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
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
                },
                {
                    name:'fecha_inicio', 
                    index:'fecha_inicio',
                    editable:false,
                    search:false,
					hideen:true,
                    width:90,
                    align:'center'
                },
                 {
                    name:'fecha_fin', 
                    index:'fecha_fin',
                    editable:false,
                    search:false,
                    width:90,
                    align:'center'
                },
                 {
                    name:'opciones', 
                    index:'opciones',
                    editable:false,
                    search:false,
                    width:90,
                    align:'center'
                },				

            ],
            pager: '#pager-buscar',
            rownumbers: true,
            //height:320,
            rowNum:25,
            rowList:[25,50],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'list',
            //toolbar: [true,"top"],
            multiselect: false,
            hiddengrid: false,
        });
		
     //--- PIE GRID
	jQuery("#list-buscar").jqGrid('navGrid','#pager-buscar',{add:false,edit:false,del:false,search:true});
	//---------
		
		


}
//--	
	
	
//---------------------------------------------------------------------------------------	
// function Agreagar()
// Agregar Trabajador a promedio de horas extras
function agregarTrabajador_rpc2_phe(id_trabajador){
	
	var estado =confirm('Realmente quiere agregar al Trabajador');	
	if(estado){
	//var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	// 
	console.log("VARIABLE CARGADO EN GLOBAL id_pdeclaracion ="+id_pdeclaracion);
	
	
	//ajax (Preguntar si ya existe trabajador.)
	$.ajax({
	type: "POST",
	dataType:'json',
	url: "sunat_planilla/controller/PromedioHoraExtraController.php",
	data: {
		 oper : 'add',
		 id_pdeclaracion : id_pdeclaracion,		 
		 id_trabajador : id_trabajador
		 },
	async:true,	
	success: function(data){	
		console.log(data);	
		if(data.estado){
			
			alert("Se Guardo correctamente.");
			//$("#list").jqGrid('GridUnload');
			jQuery("#list").trigger("reloadGrid");
			
		}else{
			alert(data.mensaje);
		}
		
	
	}
	});
   //---	
	}
	

	
}


//-------------
$("#nuevo_trabajador").click(function(){
	console.log("click");

$("#trabajador").fadeToggle("slow", "linear");
				

});	
	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Monto de Promedio de Horas Extras</a></li>			

        </ul>
        <div id="tabs-1">
        
<div class="ocultar">id_pdeclaracion :
  <input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
           value="<?php echo $_REQUEST['id_declaracion']; ?>" />
  <br />
  periodo:<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo']; ?>" />

</div>        
        
        
<h2>
 Monto de Promedio Horas Extras 
 </h2>
<p>




  <!--I.Buscador-->


  <input type="button" name="retornar" id="retornar" value="Cancelar"
  class="submit-cancelar"
  onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_registro_concepto_e.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" />
<div id="trabajador" class="trabajador" style="display:none">
<div class="ocultar">
  <br />
  cod_detalle_concepto
  <input type="text" name="cod_detalle_concepto" id="cod_detalle_concepto"
     value="<?php echo $cod_detalle_concepto; ?>"  />
  <br /> 
</div>
<h3>  
  Lista de Trabajadores activos: por Declaracion o mes  
</h3>
<table id="list-buscar">
</table>
<div id="pager-buscar"></div> 
</div>
<a href="#" id="nuevo_trabajador" class="red resaltar_1">Agregar Nuevo</a></p>







<!--F.Buscador-->
<table id="list">
</table>
<div id="pager"></div>  



      
        
      </div>
</div>

</div>




<!-- -->
<div id="dialog_new_trabajador_rpc">

<div id="new_trabajador_rpc"> </div>

</div>