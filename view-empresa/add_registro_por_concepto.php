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
	
	
	var cod_detalle_concepto = document.getElementById('cod_detalle_concepto').value;
	var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	//---------------- inicio --------------//
	
		
	cargarTablaRPC(cod_detalle_concepto,id_pdeclaracion);
	
	cargar_tabla_rpc_buscar(id_pdeclaracion);
	
	
	//---------------- inicio --------------//
	function validarGrid_RPC(value, colname) {
		console.log("value = "+ value);
		console.log("colname = "+ colname);
		console.log("cod_detalle_concepto = "+cod_detalle_concepto);
		
		// ------------------------------------------------	
		// 0701 = ADELANTO
		if(cod_detalle_concepto=='0701'){ 
			
			if(value < 0 || value >100){
				return [false,"Numero en Porcentaje de 0 and 100"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------	
		// 0105 = TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 25%					
		}else if(cod_detalle_concepto=='0105'){
			if(value < 0 || value >20){
				return [false,"Horas validar 1 y 20 horas"];//Horas validar 1 y 2 horas
			}else{
				return [true,""];
			}
		// ------------------------------------------------	
		// 0106 = TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 35%	
		}else if(cod_detalle_concepto=='0106'){
			if(value < 0 || value >100){
				return [false,"Horas validas de 1 y 100 horas"];//Horas validas de 1 y 24 horas
			}else{
				return [true,""];
			}
		// ------------------------------------------------	
		// 0107 = TRABAJO EN DÍA FERIADO O DÍA DE DESCANSO	
		}else if(cod_detalle_concepto=='0107'){
			if(value < 0 || value >5){
				return [false,"Dias validos de 0 a 5"];
			}else{
				return [true,""];
			}

		// ------------------------------------------------	
		// 0115 = REMUNERACIÓN DÍA DE DESCANSO Y FERIADOS (INCLUIDA LA DEL 1° DE MAYO)	
		}else if(cod_detalle_concepto=='0115'){
			if(value < 0 || value >20){
				return [false,"Estados validos 0 y 1 \n Dia que hace Referencia al 20&deg; de Mayo."];
			}else{
				return [true,""];
			}//0115
		// ------------------------------------------------			
		// 0121 = REMUNERACION O JORNAL BASICO
		}else if(cod_detalle_concepto=='0121'){
			if(value < 0){
				return [false,"Remuneracion basica Fuera de Rango"];
			}else{
				return [true,""];
			}

		// ------------------------------------------------			
		// 0201 = ASIGNACION FAMILIAR
		}else if(cod_detalle_concepto=='0201'){
			if(value < 0 || value >1){
				return [false,"Estados validos 0 y 1"];
			}else{
				return [true,""];
			}
			
		// ------------------------------------------------				
		// 0304 = BONIFICACION POR RIESGO DE CAJA			
		}else if(cod_detalle_concepto=='0304'){
			if(value < 0 || value >1000){
				return [false,"Estados validos 0 y 1"];
			}else{
				return [true,""];
			}
			
		// ------------------------------------------------				
		// 0308 = COMPENSACION POR TRABAJOS EN DIAS DE DESCANSO Y EN FERIADOS
		}else if(cod_detalle_concepto=='0308'){
			if(value < 0 || value >10){
				return [false,"Estados validos 0 y 10"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------				
		// 0704 = TARDANZAS	
		}else if(cod_detalle_concepto=='0704'){
			if(value < 0 || value >160){
				return [false,"Horas validas en Decimales de 0 hasta 160 horas"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------				
		// 0705 = INASISTENCIAS
		}else if(cod_detalle_concepto=='0705'){
			
			if(value < 0 || value >31){
				return [false,"Dias validos 1 al 31"];
			}else{
				return [true,""];
			}
		
		//ESSALUD VIDA
		}else if(cod_detalle_concepto=='0604'){
			if(value < 0 || value >1){
				return [false,"Estados validos 0 y 1"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------				
		// 0612 = SISTEMA NACIONAL DE PENSIONES - ASEGURA TU PENSIÓN
		}else if(cod_detalle_concepto=='0612'){
			if(value < 0 || value >1){
				return [false,"Estados validos 0 y 1"];
			}else{
				return [true,""];
			}


		// ------------------------------------------------			 
		// 0703 = DESCUENTO AUTORIZADO U ORDENADO POR MANDATO JUDICIAL
		}else if(cod_detalle_concepto=='0703'){
			if(value < 0 || value >1000){
				return [false,"Monto valido como descuento valido de 0 a 1000"];
			}else{
				return [true,""];
			}		
		// ------------------------------------------------			 	
		// 0705 = INASISTENCIAS
		}else if(cod_detalle_concepto=='0705'){
			if(value < 0 || value >1){
				return [false,"Dias validos 0 y 30"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------			 	
		// 0909 = MOVILIDAD SUPEDITADA A ASISTENCIA Y QUE CUBRE SÓLO EL TRASLADO
		}else if(cod_detalle_concepto=='0909'){
			if(value < 0 || value >1000){
				return [false,"Numeros validos de 0 a 1000"];
			}else{
				return [true,""];
			}
		
		
		}

		
			
	}
	
	
	
	
	function fol(value, colname) {
		console.log("value = "+ value);
		console.log("colname = "+ colname);
		
		var id = jQuery("#list").jqGrid('getGridParam','selrow');
		var fol = jQuery("#list").jqGrid('getRowData',id);
		if (value > fol.max )
			return [false,"Min value can't be < tha Max value"];
		else
			return [true,""];
	}	

	
	
    function cargarTablaRPC(cod_detalle_concepto,id_pdeclaracion){

        $("#list").jqGrid({
            url:'sunat_planilla/controller/RegistroPorConceptoController.php?oper=cargar_tabla&cod_detalle_concepto='+cod_detalle_concepto+'&id_pdeclaracion='+id_pdeclaracion,
            datatype: 'json',
            colNames:['','Id','idt','Tipo Doc','N. Doc','A.Paterno','A.Materno','Nombres','Valor'],
            colModel :[
				{name: 'myac',
				search:false,
				width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_registro_por_concepto',
				sortable:true,
				key : true,
				index:'id_registro_por_concepto',
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
                    name:'cod_tipo_documento',
                    index:'cod_tipo_documento',
                    search:false, 
					sortable:false,
                    editable:false,
					editrules:{required:true},
                    width:60, 
                    align:'center',
					hidden:true,
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
                    	return ": "+value + " - "+rData['5']+" "+rData['6']+" "+rData['7'] ;
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
                    name:'valor', 
                    index:'valor',
                    search:false,
					sortable:false,
                    editable:true, //true,
					editrules:{custom:true,number:true, custom_func:validarGrid_RPC},
                    width:95,
                    align:'center'
                }
						
						


            ],
            pager: '#pager',
			//rownumbers :true,
			height:320,
            rowNum:25,
            rowList:[25,50],
            sortname: 'id_registro_por_concepto',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/RegistroPorConceptoController.php", 
            caption: 'Lista de Trabajadores : concepto '+cod_detalle_concepto,
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
function cargar_tabla_rpc_buscar(id_pdeclaracion){
	
	//var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
		//console.log("mensaje grid");
		//console.log(id_pdeclaracion);
		

        //$("#list-buscar").jqGrid('GridUnload');
        $("#list-buscar").jqGrid({
            url:'sunat_planilla/controller/EtapaPagoController.php?oper=trabajador_por_mes&id_pdeclaracion='+id_pdeclaracion,
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

function agregarTrabajador_rpc(id_trabajador){
	
	var estado =confirm('Realmente quiere agregar al Trabajador');
	
	//console.log(estado);
	
	if(estado){
	var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	var cod_detalle_concepto = document.getElementById('cod_detalle_concepto').value;
	
	//ajax (Preguntar si ya existe trabajador.)
	$.ajax({
	type: "POST",
	dataType:'json',
	url: "sunat_planilla/controller/RegistroPorConceptoController.php",
	data: {
		 oper : 'add',
		 id_pdeclaracion : id_pdeclaracion,
		 cod_detalle_concepto : cod_detalle_concepto,
		 id_trabajador : id_trabajador
		 },
	async:true,	
	success: function(data){		
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
            <li><a href="#tabs-1">Registrar Trabajador por Conceptos</a></li>			

        </ul>
        <div id="tabs-1">
        
<div class="ocultar">id_pdeclaracion :
  <input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
           value="<?php echo $_REQUEST['id_pdeclaracion']; ?>" />
  <br />
  periodo:<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo']; ?>" />

</div>        
        
        
<h2>
  <?php echo $data_detalle_concepto['cod_detalle_concepto'] ." - ". $data_detalle_concepto['descripcion'] ?></h2>
<p>




  <!--I.Buscador-->


  <input type="button" name="retornar" id="retornar" value="Cancelar"
  class="submit-cancelar"
  onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_registro_por_concepto.php?id_declaracion=<?php echo $_REQUEST['id_pdeclaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" />
<div id="trabajador" class="trabajador" style="display:none">
<div class="ocultar">id_pdeclaracion :
  <label for="id_pdeclaracion"></label>
  <input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
  value="<?php echo $_REQUEST['id_pdeclaracion'] ?>" />
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