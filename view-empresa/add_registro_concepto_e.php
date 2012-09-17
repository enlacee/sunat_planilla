<?php
//session_start();
require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');

require_once('../dao/Concepto_EDao.php');
require_once('../controller/Concepto_EController.php');

$cod_detalle_concepto = $_REQUEST['id_concepto_e_empleador']; 

$id_concepto_e =$_REQUEST['id_concepto_e'];

$data_detalle_concepto = buscar_ID_ConceptoE($id_concepto_e);

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	var cod_detalle_concepto = document.getElementById('cod_detalle_concepto').value;
	cargarTablaRPCE(cod_detalle_concepto);
	
	function validarGrid_RPCE(value, colname) {
		console.log("value = "+ value);
		console.log("colname = "+ colname);
		console.log("cod_detalle_concepto = "+cod_detalle_concepto);
		
		// ------------------------------------------------	
		// 1 = PRESTAMO
		if(cod_detalle_concepto=='1'){ 
			
			if(value < 1 || value >3000){
				return [false,"Prestamo permitido de 1 and 3000"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------	
		// 2 = PARA TI FAMILIA					
		}else if(cod_detalle_concepto=='2'){
			if(value < 0 || value >1){
				return [false,"Estados permitidos: 0,1"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------	
		// 3 = PARA TI FAMILIA +	
		}else if(cod_detalle_concepto=='3'){
			if(value < 0 || value >1){
				return [false,"Estados permitidos: 0,1"];
			}else{
				return [true,""];
			}
		// ------------------------------------------------	

		}
		
		
		
			
	}
	
	
	
	

	
	
    function cargarTablaRPCE(cod_detalle_concepto){

        $("#list").jqGrid({
            url:'sunat_planilla/controller/RegistroConceptoEController.php?oper=cargar_tabla&cod_detalle_concepto='+cod_detalle_concepto,
            datatype: 'json',
            colNames:['','Id','Tipo Doc','N. Doc','A.Paterno','A.Materno','Nombres','Valor','Activo'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_registro_concepto_e',
				sortable:true,
				key : true,
				index:'id_registro_concepto_e',
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
					editrules:{custom:true,number:true, custom_func:validarGrid_RPCE},
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
			editurl: "sunat_planilla/controller/RegistroConceptoEController.php", 
            caption: 'Lista de Trabajadores : concepto '+cod_detalle_concepto,
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
  <?php echo $data_detalle_concepto['id_concepto_e'] ." - ". $data_detalle_concepto['descripcion'] ?>
</h2>
<div class="ocultar">
  <p>oper
    <input type="text" name="oper" id="oper" value="add" />
      <br />
    cod_detalle_concepto
  <label for="cod_detalle_concepto"></label>
    <input type="text" name="cod_detalle_concepto" id="cod_detalle_concepto" 
  value="<?php echo $cod_detalle_concepto; ?>" />
</p>
  <p>
    id_concepto_e
      <input type="text" name="id_concepto_e" id="id_concepto_e" value="<?php echo $id_concepto_e; ?>" />
</p>
</div>
<p>Registrar
  <input name="addNuevoRPC" type="button" value="nuevo rpce" onclick="newRPC('<?php echo $cod_detalle_concepto;?>','2')" />
  
  
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