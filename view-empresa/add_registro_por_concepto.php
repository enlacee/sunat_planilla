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
	cargarTablaRPC(cod_detalle_concepto);
	
	function validarGrid_RPC(value, colname) {
		console.log("value = "+ value);
		console.log("colname = "+ colname);
		console.log("cod_detalle_concepto = "+cod_detalle_concepto);
		
		// ------------------------------------------------	
		// 0701 = ADELANTO
		if(cod_detalle_concepto=='0701'){ 
			
			if(value < 0 || value >100){
				return [false,"Please enter value between 0 and 100"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------	
		// 0105 = TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 25%
		// 0106 = TRABAJO EN SOBRETIEMPO (HORAS EXTRAS) 35%
		// 0107 = TRABAJO EN DÍA FERIADO O DÍA DE DESCANSO			
		}else if(cod_detalle_concepto=='0105'||cod_detalle_concepto=='0106'||cod_detalle_concepto=='0107'){
			if(value < 0 || value >50){
				return [false,"Please enter value between 0 and 50"];
			}else{
				return [true,""];
			}
			
		// ------------------------------------------------			
		// 0201 = ASIGNACION FAMILIAR
		}else if(cod_detalle_concepto=='0201'){
			if(value < 0 || value >1){
				return [false,"Please enter value between 0 and 1"];
			}else{
				return [true,""];
			}
			
		// ------------------------------------------------				
		// 0304 = BONIFICACION POR RIESGO DE CAJA			
		}else if(cod_detalle_concepto=='0304'){
			if(value < 0 || value >1000){
				return [false,"Please enter value between 0 and 1000"];
			}else{
				return [true,""];
			}
			
		// ------------------------------------------------				
		// 0308 = COMPENSACION POR TRABAJOS EN DIAS DE DESCANSO Y EN FERIADOS
		}else if(cod_detalle_concepto=='0308'){
			if(value < 0 || value >10){
				return [false,"Please enter value between 0 and 10"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------				
		// 0704 = TARDANZAS	
		}else if(cod_detalle_concepto=='0704'){
			if(value < 0 || value >8){
				return [false,"Please enter value between 0.0 and 8.0 horas"];
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

	
	
    function cargarTablaRPC(cod_detalle_concepto){

        $("#list").jqGrid({
            url:'sunat_planilla/controller/RegistroPorConceptoController.php?oper=cargar_tabla&cod_detalle_concepto='+cod_detalle_concepto,
            datatype: 'json',
            colNames:['','Id','Tipo Doc','N. Doc','A.Paterno','A.Materno','Nombres','Valor','Activo'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_registro_por_concepto',
				sortable:true,
				key : true,
				index:'id_registro_por_concepto',
				width:55
				},
                {
                    name:'cod_tipo_documento',
                    index:'cod_tipo_documento',
                    search:true, 
					sortable:false,
                    editable:false,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    search:true,
					sortable:false,
                    editable:false,
					editrules:{required:true},
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    search:true,
					sortable:false,
                    editable:false,
					editrules:{required:true},
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    search:true,
					sortable:false,
                    editable:false,
					editrules:{required:true},
                    width:90,
                    align:'center'
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    search:true,
					sortable:false,
                    editable:false,
					editrules:{required:true},
                    width:90,
                    align:'center'
                },
                {
                    name:'valor', 
                    index:'valor',
                    search:true,
					sortable:false,
                    editable:true, //true,
					editrules:{custom:true,number:true, custom_func:validarGrid_RPC},
                    width:90,
                    align:'center'
                },
                {
                    name:'estado', 
                    index:'estado',
                    search:false,
					sortable:false,
                    editable:false,					
                    width:90,
                    align:'center'
                }		
						
						


            ],
            pager: '#pager',
			//height:200,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_registro_por_concepto',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/RegistroPorConceptoController.php", 
            caption: 'Lista de Trbajadores : concepto '+cod_detalle_concepto,
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:false});
	//---------

	
    }

	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registrar Trabajador por Conceptos</a></li>			

        </ul>
        <div id="tabs-1">
<h2>
  <?php echo $data_detalle_concepto['cod_detalle_concepto'] ." - ". $data_detalle_concepto['descripcion'] ?>
</h2>
<div class="ocultar">
oper
  <input type="text" name="oper" id="oper" value="add" />
  <br />
cod_detalle_concepto
<label for="cod_detalle_concepto"></label>
  <input type="text" name="cod_detalle_concepto" id="cod_detalle_concepto" 
  value="<?php echo $cod_detalle_concepto; ?>" />
</div>
<p>Registrar
  <input name="addNuevoRPC" type="button" value="nuevo rpc" onclick="newRPC('<?php echo $cod_detalle_concepto;?>')" />
  
  
</p>
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