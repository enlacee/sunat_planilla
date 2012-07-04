//JavaScript Document
var demoApp;

//INICIO HISTORIAL
$(document).ready(function(){
						   
demoApp = new Historial();
	
});


/*****************************************************/
/***************** Kits ***************************/
/*****************************************************/



//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
function cargarTablaKits(){

//$("#list").jqGrid('GridUnload');
$("#list").jqGrid({
	url:'../controller/kitController.php?oper=cargar_tabla',
	datatype: 'json',
	colNames:['Id','Codigo','Precio','Stock','Estado','Fech Creacion','Opciones'],
	colModel :[
		{name:'id_kit',editable:true, edittype:'text', index:'id_kit', width:30,align:'center'},		
		{name:'codigo', index:'codigo', width:70, align:'center'},
		{name:'precio', index:'precio', width:80, align:'center'}, 
		{name:'stock',  index:'stock', width:40, align:'center'},			
		{name:'estado', index:'estado', width:60,sortable:false},
		{name:'fecha_creacion', index:'fecha_creacion', width:50, align:'center'},
		{name:'opciones', index:'opciones', width:100,sortable:false}
		
	],
	pager: '#pager',
	rowNum:10,
	rowList:[10,20,30],
	sortname: 'id_kit',
	sortorder: 'asc',
	viewrecords: true,
	gridview: true,
	caption: 'Lista de Productos',
	editurl:'../controller/nuevoProducto.php?op=01',
	height:250,
	width:720
});
	$("#bsdata").click(function(){ jQuery("#list").jqGrid('searchGrid', {sopt:['cn','bw','eq','ne','lt','gt','ew']} ); });
	
	$("#bedata").click(function(){ var gr = jQuery("#list").jqGrid('getGridParam','selrow'); if( gr != null ) jQuery("#list").jqGrid('editGridRow',gr,{height:280,reloadAfterSubmit:false}); else alert("Please Select Row"); });
	
	$("#bndata").click(function(){ jQuery("#list").jqGrid('editGridRow',"new",{height:280,reloadAfterSubmit:false}); });
	
	$("#bddata").click(function(){ var gr = jQuery("#list").jqGrid('getGridParam','selrow'); if( gr != null ) jQuery("#list").jqGrid('delGridRow',gr,{reloadAfterSubmit:false}); else alert("Please Select Row to delete!"); });

}





/*****************************************************/
/***************** Productos ***************************/
/*****************************************************/

function validarPrecio(value, colname) {
	
	if(value!=""){
		Numer=parseFloat(value);
		if(Numer){
				return [true,""];
		}
		else{
			if(Numer==0){
				return [true,""];
			}else{
				return [false,"El precio es und dato numerico"];
			}
		}
	}else{
		return [true,""];
	}
	   
	   
}
var estado=true;
function validarCodigo(value, colname) {
	
	if(value==""){
		return [false,"Ingrese codigo"];
		
		}else{
			return [true,""];		
		}
	   
}

//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
function cargarTablaProductos(){

//$("#list").jqGrid('GridUnload');
$("#list").jqGrid({
	url:'../controller/ProductoController.php?oper=cargar_tabla',
	datatype: 'json',
	colNames:['Id','Codigo','Precio','Stock','Estado','Fecha Creacion','Opciones'],
	colModel :[
		{name:'id_producto', index:'id_producto', width:30,align:'center'},		
		{name:'codigo',editable:true, editrules:{custom:true, custom_func:validarCodigo} , index:'codigo', width:70, align:'center', display :'none'},
		{name:'precio', editable:true, editrules:{custom:true, custom_func:validarPrecio} ,  index:'precio', width:80, align:'center'},      
		{name:'stock',  index:'stock',  width:40, align:'center'}, 
		{name:'estado',  editable:true,width:60,  edittype:"select",   editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
		{name:'fecha_creacion', index:'fecha_creacion',width:50, align:'center'},
		{name:'opciones',  index:'opciones', width:100,sortable:false}
		
	],
	pager: '#pager',
	rowNum:10,
	rowList:[10,20,30],
	sortname: 'id_producto',
	sortorder: 'asc',
	viewrecords: true,
	gridview: true,
	caption: 'Lista de Articulos',
	height:250,
	width:720
	
});

	jQuery("#list").jqGrid('navGrid','#pager', {view:true}, //options 
						  {		height:280,
								url:'../controller/ProductoController.php',
								closeAfterEdit:true,
								reloadAfterSubmit :true,
								modal:true,
							  	onInitializeForm: function(formid) {
									document.FormPost.codigo.disabled=true;
							  	},
								recreateForm:true,
								
						   },// edit options 
						  {		height:280,
						 		closeAfterEdit:true,
						  		reloadAfterSubmit:true,
								
								afterSubmit : function (response, postdata) {
								
									if(response.responseText=="true")
										return [true,""];
									if(response.responseText=="false")
										return [false,"El codigo ya existe"];
								} ,
								
								url:'../controller/ProductoController.php?id_almacen='+$("#id_almacen").val(),
								
								onInitializeForm: function(formid) {
									document.FormPost.codigo.disabled=false;
							  	},
								recreateForm:true,
								
						  },// add options 
						  
						  {
							  reloadAfterSubmit:false,
							  url:'../controller/ProductoController.php'
						  }, // del options 
						  {
							  sopt:['eq','ne','lt','le','gt','ge','cn']
						   } // search options 
						  ,{}
						  );


	
	
}


