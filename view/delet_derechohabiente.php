<?php


//COMBO FILE
require_once('../dao/AbstractDao.php');
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');

//--
//require_once('../dao/DerechohabienteDao.php');
//require_once('../model/Derechohabiente.php');
require_once('../controller/DerechohabienteController.php');

// COMBO 01
$cbo_tipo_documento = comboTipoDocumento();

// COMBO 02
$cbo_pais_emisor_documento = comboPaisEmisorDocumento();

// COMBO 03
//$cbo_nacionalidades = comboNacionalidades();

// COMBO 04
$cbo_telefono_codigo_nacional = comboTelefonoCodigoNacional();

// COMOBO 05
$cbo_estado_civil = comboEstadosCiviles();


//---------------------------- EDITAR PERSONA--------------------------------- //
/*
$ID_PERSONA = $_REQUEST['id_persona'];

$obj_dh = new Persona();
// funcion del Controlador
$obj_dh = buscarPersonaPorId($ID_PERSONA);

echo "<pre>";
print_r($obj_dh);
echo "</pre>";
*/
//---------------------------- EDITAR DERECHOHABIENTE--------------------------------- //

$ID_DERECHOHABIENTE = $_REQUEST['id_derechohabiente'];

$obj_dh = new Derechohabiente();
// funcion del Controlador
$obj_dh = buscarDerechohabientePorId($ID_DERECHOHABIENTE);


//--------------- COMBO DERECHO HABIENTE -------------------//
// COMBO 01
$cbo_vinculos_familiares = comboVinculoFamiliar();

// COMBO 02
$cbo_documentos_vinculos_familiares =comboDocumentoVinculoFamiliar();

// COMBO 03 cod_situacion

$cbo_situaciones =comboSituacion();

echo "<pre>";
print_r($obj_dh);
echo "</pre>";


?>

            <script>
			
                
                //INICIO HISTORIAL
                $(document).ready(function(){
						   
                    //demoApp = new Historial();                  
                    $( "#tabs").tabs();
					//new
					crearDialogoPersonaDireccion();
                    
                    //$( "#tabs_2").tabs();
				id_persona = document.form_edit_derechohabiente.id_derechohabiente.value;
				alert(id_persona);
				cargarTablaDerechohabienteDireccion(id_persona);					
			//---------------------------------------------
	
					
			// -----------Validacion
			$("#form_edit_derechohabiente").validate({
			
            rules: {
                txt_fecha_nacimiento: {
                    required: true,
                    date: true
                },
                cbo_pais_emisor_documento: {
                    required: true				  
                },				
                cbo_tipo_documento:{
                    required:true					
                },
                txt_num_documento:{
                    required: true,
                    rangelength: [8, 15]
                },
                txt_apellido_paterno:{
                    required: true
                },
                txt_apellido_materno:{
                    required: true
                },
                txt_nombre:{
                    required: true
                },
                rbtn_sexo:{
                    required: true
                },
                cbo_estado_civil:{
                    required: true	
                },
                cbo_Nacionalidad:{
                    required: true
                }				
				
            },			
			  // errorLabelContainer: "#messageBox",
			  // wrapper: "li",
			   submitHandler: function() { 
			   //Inicio Submit
			   		//alert("Submitted!\n.....");
					//disableForm('form_edit_derechohabiente');
				var from_data =  $("#form_edit_derechohabiente").serialize();
				
				//-----------------------------------------------------------------------	
				$.getJSON('sunat_planilla/controller/PersonaController.php?'+from_data,
					function(data){
					//funcion.js index.php
					//disableForm('form_new_personal');	
						if(data){
						//document.getElementById('id_persona').value = data.id_persona;
						//cargarTablaDerechohabienteDireccion(data.id_persona);
						//ID = data.id_persona;
						disableForm('form_edit_derechohabiente');
						alert("Se guardo Correctamente JSON");					
						
						}else{
							alert("Ocurrio un error, intente nuevamente no hay datos JSON");
						}
					}); 
				//-----------------------------------------------------------------------
					
			   //Inicio Submit
			   }
			   
			})
					
					

//-------------------------------------------------------------------
                }); //End Ready
				
				
				
				
/*****************************************************/
/***************** Terrenos ***************************/
/*****************************************************/

//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011		
	function cargarTablaDerechohabienteDireccion(id){  console.log('id_derechohabiente = '+id);			
			//OBTENER ID PERSONA
			//$("#list").jqGrid('GridUnload');+	
			$("#list").jqGrid({
				url:'sunat_planilla/controller/DerechohabienteDireccionController.php?oper=cargar_tabla&id_derechohabiente='+id,
				datatype: 'json',
				colNames:['id_derechohabiente_direccion','id_derechohabiente','nombre_ubigeo_reniec','Direccion','Opciones'],
				colModel :[
					{
						name:'id_derechohabiente_direccion', 
						editable:false, 
						index:'id_derechohabiente_direccion',
						search:false,
						hidden:false,
						width:15,
						align:'center'
					},
					{
						name:'id_derechohabiente', 
						editable:false, 
						index:'id_persona_direccion',
						search:false,
						width:15,
						align:'center'
					},		
					{
						name:'nombre_ubigeo_reniec',
						index:'nombre_ubigeo_reniec', 
						editable:false,
						width:280, 
						align:'center' 
					},
					{
						name:'estado_direccion',
						index:'estado_direccion', 
						editable:false,
						width:30, 
						align:'left', 
					},
					{
						name:'opciones',
						index:'opciones', 
						editable:false,
						width:20,
						align:'center'
					},	
						

				],
				pager: '#pager',
				autowidth: true,
				rowNum:10,
				rowList:[10,20,30],
				sortname: 'estado_direccion',
				sortorder: 'asc',
				viewrecords: true,
				gridview: true,
				caption: 'Lista de Derechohabiente Direcciones',
				onSelectRow: function(ids) {},
				height:100,
				width:'100%' 
			});
			myGrid.jqGrid('navGrid','#mypager',{edit:true,add:true,del:true,search:true});	
		}		
//-----------------------------------------------------------------------------------
//------------------------------------------------------------------------------------
//  intranet intranet intranet intranet intranet intranet intranet intranet

function crearDialogoPersonaDireccion(){

	$("#dialog-form-editarDireccion").dialog({
           
			autoOpen: false,
			height: 470,
			width: 950,
			modal: true,
                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {
					
				}
                                
			},
			open: function() {},
			close: function() {}
	});
}



function editarPersonaDireccion(id_persona_direccion){  //alert (".");
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view/modal/detalle_persona_direccion.php",
   data: "id_persona_direccion="+id_persona_direccion,//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(datos){
    $('#editarPersonaDireccion').html(datos);
    
    $('#dialog-form-editarDireccion').dialog('open');
   }
   }); 
}

//-------------------------------------------------------------
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Baja DerechoHabiente</a></li>
        </ul>
        <div id="tabs-1">
<!--INICIO TAB2 -->




		
        </div>

        
    </div>
</div>



			
			
<!--  -------------------------------------- -->			


</DIV>







<!-- -->

<!-- -->

<div id="dialog-form-editarDireccion" title="Editar Direccion">
    <div id="editarPersonaDireccion" align="left"></div>
</div>

