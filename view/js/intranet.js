//JavaScript Document
var demoApp;
var obj_id;
var ID=0;

function ObjetoId(){
	this.setId = function(id){
		this.id = id || 0;
	}
	this.getId = function(){
		return this.id;
	}
	this.alerta = function(){
		alert (this.id);	
	}
}


//INICIO HISTORIAL
$(document).ready(function(){
						   
demoApp = new Historial();

obj_id = new ObjetoId();
var hola = 111;



});



/*****************************************************/
/***************** Kits ***************************/
/*****************************************************/


//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
function cargarTablaKits(){

//$("#list").jqGrid('GridUnload');
$("#list").jqGrid({
	url:'../controller/KitController.php?oper=cargar_tabla',
	datatype: 'json',
	colNames:['Id','Codigo','Nombre','Estado','Fecha Creacion'],
	colModel :[
		{
			name:'id_kit', 
			editable:false, 
			index:'id_kit',
			width:30,
			align:'center'
		},		
		{	name:'codigo',
			index:'codigo', 
			editable:true,
			editrules:{required:true},
			width:70, 
			align:'center', 
		},
		{	name:'nombre', 
			index:'nombre',
			editable:true,
			width:80,
			align:'center'
		},
	//	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
		{	name:'estado',  
			index:'estado',
			editable:true,
			edittype:"select",
			editoptions:{value:"A:Activo;I:Inactivo"}, 
			width:60,  
			align:'center'
		},
		{
			name:'fecha_creacion',
			editable:false,
			width:60, 
			index:'fecha_creacion', 
			align:'center'
		},
		
		
	],
	pager: '#pager',
	rowNum:10,
	rowList:[10,20,30],
	sortname: 'id_kit',
	sortorder: 'asc',
	viewrecords: true,
	gridview: true,
	caption: 'Lista de Productos',
	onSelectRow: function(ids) {
	
		document.form_ayuda.id_producto.value = ids;
		//document.getElementById('id_producto').value=ids;	
		
		var id_almacen  = document.form_ayuda.id_almacen.value;
		var id_kit = document.form_ayuda.id_producto.value;
		
		cargarKitDetalle(id_almacen,id_kit);
		
	},


	height:"100%",
	width:720 
});


//--- PIE GRID
jQuery("#list").jqGrid('navGrid','#pager', 
					    {	
							view:true
						},
					    {	
							height:280,
							reloadAfterSubmit:true,
							url:'../controller/KitController.php',
							onInitializeForm: function(formid) {
								$("#FrmGrid_list #codigo").attr("disabled","disabled")
								
							},
							recreateForm:true,
						}, // edit options 
					    {	
							height:280,
							reloadAfterSubmit:true,
							
							afterSubmit : function (response, postdata) {
								
									if(response.responseText=="null")
										return [true,""];
									else
										return [false,"El codigo ya existe"];
								} ,
								
							url:'../controller/KitController.php',
							onInitializeForm: function(formid) {
								$("#FrmGrid_list #codigo").removeAttr("disabled")
							},
							recreateForm:true,
						}, // add options 
						{	
							reloadAfterSubmit:false,url:'../controller/KitController.php'
						}, // del options 
						{
							sopt:['eq','ne','lt','le','gt','ge','cn']
						}, // search options
						{}
);


}


//---------------------------------------------------------------------------------------------------------
/*****************************************************/
/***************** Kits Detalles ***************************/
/*****************************************************/

function cargarKitDetalle(id_almacen,id_kit){
	
	$("#list10_d").jqGrid('GridUnload');


jQuery("#list10_d").jqGrid({  
	height: 100,
   	url:'../controller/KitDetalleController.php?oper=cargar_tabla&id_almacen='+id_almacen+'&id_kit='+id_kit,
	datatype: "json",
   	colNames:['Id','Codigo','Precio', 'Color', 'Modelo','Cantidad','Costo'],
   	colModel:[
   		{	
			name:'id_kit_detalle',
			index:'id_kit_detalle',
			width:55, 
			align:"center"
		},
   		{	
			name:'codigo',
			index:'codigo', 
			editable:true, 
			editrules:{required:true},
			width:100, 
			align:"center"
		},
        {	
			name:'precio',
			index:'precio', 
			editable:true, 
			editrules:{number:true},
			width:80, 
			align:"center"
		},
   		{
			name:'color',
			index:'color', 
			editable:true, 
			width:80, 
			align:"center"
		},
   		{
			name:'talla',
			index:'talla', 
			editable:true, 
			width:80, 
			align:"center"
		},		
   		{
			name:'cantidad',
			index:'cantidad',
			editable:true,
			editrules:{integer:true},
			width:80, 
			align:"center"
		},		
   		{
			name:'costo',
			index:'costo',
			editable:true,
			editrules:{number:true},
			width:80, 
			align:"center"
		}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pager10_d',
	width:"100%",
	height:"100%",
   	sortname: 'id_kit_detalle',
    viewrecords: true,
    sortorder: "asc",
	multiselect: false,
	caption:"Producto Detalles", 
	subGrid: true, 
	subGridOptions: { 
		"plusicon" : "ui-icon-triangle-1-e", 
		"minusicon" : "ui-icon-triangle-1-s", 
		"openicon" : "ui-icon-arrowreturn-1-e", 
		"reloadOnExpand" : true, 
		"selectOnExpand" : true }, 
	subGridRowExpanded: function(subgrid_id, row_id) { 
		var subgrid_table_id,pager_id; 
		subgrid_table_id = subgrid_id+"_t"; 
		pager_id = "p_"+subgrid_table_id; 
		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>"); 
		jQuery("#"+subgrid_table_id).jqGrid({ 
		url:"../controller/ProductosxKitController.php?oper=cargar_tabla&id_almacen="+id_almacen+"&id_kit_detalle="+row_id, 
		datatype: "json", 
		colNames:['Id','Codigo Articulo','Codigo Articulo Detalle','Nombre Articulo','Cantidad'],
		colModel:[
			{
				name:'id_producto_por_kit',	
				index:'id_producto_por_kit', 
				hidden:true,
				width:120,	
				align:"center",
			},
			{
				name:'codigo_articulo',	
				index:'codigo_articulo',
				width:120,	
				align:"center",
			},
			{
				name:'codigo_articulo_detalle',		
				index:'codigo_articulo_detalle',
				editable:true,
				edittype:"select",
				editoptions:{dataUrl:'../controller/ProductosxKitController.php?oper=select_codigo&id_almacen='+id_almacen },
				width:140,
				sortable:false,
				align:"center",	
			},
			{
				name:'nombre',		
				index:'nombre',
				width:120,	
				align:"left",	
			},
			{
				name:'cantidad',		
				index:'cantidad', 
				editable:true,
				editrules:{integer:true},
				width:75,	
				align:"center",	
			},
						
		],
		rowNum:10, 
		rowList:[10,20,30],
		viewrecords: true, 
		pager: "#"+pager_id, 
		sortname: 'id_producto_por_kit', 
		sortorder: "asc", 
		editurl: "../controller/ProductosxKitController.php", 
		width: '100%',
		height: '100%' 
		}); 
		
		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id, 
		  	{search:false},
			{
				height:280,
				reloadAfterSubmit:true,
				url:"../controller/ProductosxKitController.php"
			}, // edit options 
			{
				height:280,
				reloadAfterSubmit:true,
				url:'../controller/ProductosxKitController.php?id_kit_detalle='+row_id
			}, // add options 
			{
				reloadAfterSubmit:true,
				url:'../controller/ProductosxKitController.php',
			}, // del options 
			{}
			);}
		});

//----- PIE GRID
jQuery("#list10_d").jqGrid(
	'navGrid',
	'#pager10_d',	
	{view:true},
	{
		height:280,
		reloadAfterSubmit:true,
		url:'../controller/KitDetalleController.php',
		onInitializeForm: function(formid) {
			$("#FrmGrid_list10_d #codigo").attr("disabled","disabled")
		},
		recreateForm:true,
	}, // edit options 
	{
		height:280,
		reloadAfterSubmit:true,
		afterSubmit : function (response, postdata) {
								
			if(response.responseText=="null")
				return [true,""];
			else
				return [false,"El codigo ya existe"];
		} ,
		url:'../controller/KitDetalleController.php?id_almacen='+id_almacen+'&id_kit='+id_kit,
		onInitializeForm: function(formid) {
			$("#FrmGrid_list10_d #codigo").removeAttr("disabled")
		},
		recreateForm:true,
	}, // add options 
	{
		reloadAfterSubmit:true,
		url:'../controller/KitDetalleController.php',
		
	}, // del options 
	{
		sopt:['eq','ne','lt','le','gt','ge','cn']
	}, // search options
	{}
);




}




//---------------------------------------------------------------------------------------------------------
/*****************************************************/
/***************** Productos ***************************/
/*****************************************************/

function pickdates(id){
	jQuery("#"+id+"_fecha_creacion","#list").datepicker({dateFormat:"dd-mm-yy"}); //dateFormat:"yy-mm-dd"
}

//var idd;
//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011



function cargarTablaProductos(){
	
var lastgridsel;

var id_almacen = document.form_ayuda.id_almacen.value;
var id;
	
//$("#list").jqGrid('GridUnload');
$("#list").jqGrid({ 
	url:'../controller/ProductoController.php?oper=cargar_tabla',
	datatype: 'json',
	colNames:['Id','Codigo','Nombre','Marca','Estado','Fecha Creacion','Proveedor'],
	colModel :[
	{	name:'id_producto', 
		index:'id_producto', 
		width:30,
		align:'center'},
	//IMAGEN	{name:'codigo',editable:true, width:70, edittype:"image", editoptions: {src:'img/b_pack.png'},  index:'codigo', align:'center', display : 'none'},		
	{	name:'codigo',
		index:'codigo', 
		editable:true,
		editrules:{required:true},
		width:70, 
		align:'center', 
	},
	/*elmsuffix  - label */
	{	name:'nombre', 
    	index:'nombre',
		editable:true,
    	width:80,
    	align:'center'
	},
    {	name:'marca', 
		index:'marca',
		editable:true, 
		width:40, 
		align:'center'
	},	 
//	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
	{	name:'estado',  
		index:'estado',
		editable:true,
		edittype:"select",
		editoptions:{value:"A:Activo;I:Inactivo"}, 
		width:60,  
		align:'center'
	},
	{
		name:'fecha_creacion',
		editable:false,
		width:60, 
		index:'fecha_creacion', 
		align:'center'
	},
	{
		name:'razon_social',
		editable:true,
		width:60, 
		index:'razon_social', 
		align:'center',
		edittype:"select",
				editoptions:{dataUrl:
				'../controller/ProductoController.php?oper=select_codigo' },
	}
	
		
	],
        //searchoptions: { sopt: ['eq','ne','lt','le','gt','ge', 'cn', 'nc', 'bw', 'bn'] },
	pager: '#pager',
	rowNum:10,
	rowList:[10,20,30],
	sortname: 'id_producto',
	sortorder: 'asc',
	viewrecords: true,
	gridview: true,
	caption: 'Lista de Articulos',
	onSelectRow: function(ids) {
	
		document.form_ayuda.id_producto.value = ids;
		//document.getElementById('id_producto').value=ids;	
		
		var id_almacen  = document.form_ayuda.id_almacen.value;
		var id_producto = document.form_ayuda.id_producto.value;
		
		cargarProductoDetalle(id_almacen,id_producto);
		
	},


	height:"100%",
	width:720 
    
	
});

//--- PIE GRID
jQuery("#list").jqGrid('navGrid','#pager', 
					    {	
							view:true
						},
					    {	
							height:280,
							reloadAfterSubmit:true,
							url:'../controller/ProductoController.php',
							onInitializeForm: function(formid) {
								$("#FrmGrid_list #codigo").attr("disabled","disabled")
								
							},
							recreateForm:true,
						}, // edit options 
					    {	
							height:280,
							reloadAfterSubmit:true,
							
							afterSubmit : function (response, postdata) {
								
									if(response.responseText=="null")
										return [true,""];
									else
										return [false,"El codigo ya existe"];
								} ,
								
							url:'../controller/ProductoController.php',
							onInitializeForm: function(formid) {
								$("#FrmGrid_list #codigo").removeAttr("disabled")
							},
							recreateForm:true,
						}, // add options 
						{	
							reloadAfterSubmit:false,url:'../controller/ProductoController.php'
						}, // del options 
						{
							sopt:['eq','ne','lt','le','gt','ge','cn']
						}, // search options
						{}
);

 
	
}


//---------------------------------------------------------------------------------------------------------
/*****************************************************/
/***************** Productos Detalles ***************************/
/*****************************************************/

function cargarProductoDetalle(id_almacen,id_producto){
	
	$("#list10_d").jqGrid('GridUnload');


jQuery("#list10_d").jqGrid({  
	height: 100,
   	url:'../controller/ProductoDetalleController.php?oper=cargar_tabla&id_almacen='+id_almacen+'&id_producto='+id_producto,
	datatype: "json",
   	colNames:['Id','Codigo','Precio', 'Color', 'Modelo','Cantidad', 'Costo','Cantidad Minimo'],
   	colModel:[
   		{	
			name:'id_producto_detalle',
			index:'id_producto_detalle',
			width:55, 
			align:"center"
		},
   		{	
			name:'codigo',
			index:'codigo', 
			editable:true, 
			editrules:{required:true},
			width:100, 
			align:"center"
		},
        {	
			name:'precio',
			index:'precio', 
			editable:true, 
			editrules:{number:true},
			width:80, 
			align:"center"
		},
   		{
			name:'color',
			index:'color', 
			editable:true, 
			width:80, 
			align:"center"
		},
   		{
			name:'talla',
			index:'talla', 
			editable:true, 
			width:80, 
			align:"center"
		},		
   		{
			name:'cantidad',
			index:'cantidad',
			editable:false,
			editrules:{integer:true},
			width:80, 
			align:"center"
		},
		{	
			name:'costo',
			index:'costo', 
			editable:true, 
			editrules:{number:true},
			width:80, 
			align:"center"
		},
		{
			name:'cantidad_min',
			index:'cantidad_min',
			editable:true,
			editrules:{integer:true},
			width:80, 
			align:"center"
		}
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	pager: '#pager10_d',
	width:"100%",
	height:"100%",
   	sortname: 'id_producto_detalle',
    viewrecords: true,
    sortorder: "asc",
	caption:"Articulo Detalles",
	onSelectRow: function(ids) {
	
		//document.getElementById('id_producto').value=ids;	
		var id_almacen  = document.form_ayuda.id_almacen.value;
		var ret = jQuery("#list10_d").jqGrid('getRowData',ids);
		var id_producto = ret.id_producto_detalle;
		var costo = ret.costo;
		cargarCompra(id_almacen,id_producto,costo);
		
	}
});

//----- PIE GRID
jQuery("#list10_d").jqGrid(
	'navGrid',
	'#pager10_d',	
	{view:true},
	{
		height:280,
		reloadAfterSubmit:true,
		url:'../controller/ProductoDetalleController.php',
		onInitializeForm: function(formid) {
			$("#FrmGrid_list10_d #codigo").attr("disabled","disabled")
		},
		recreateForm:true,
	}, // edit options 
	{
		height:280,
		reloadAfterSubmit:true,
		afterSubmit : function (response, postdata) {
								
			if(response.responseText=="null")
				return [true,""];
			else
				return [false,"El codigo ya existe"];
		} ,
		url:'../controller/ProductoDetalleController.php?id_almacen='+id_almacen+'&id_producto='+id_producto,
		onInitializeForm: function(formid) {
			$("#FrmGrid_list10_d #codigo").removeAttr("disabled")
		},
		recreateForm:true,
	}, // add options 
	{
		reloadAfterSubmit:true,
		url:'../controller/ProductoDetalleController.php',
		
	}, // del options 
	{
		sopt:['eq','ne','lt','le','gt','ge','cn']
	}, // search options
	{}
);




}
function cargarProductoDetalle(id_almacen,id_producto){
	
	$("#list10_d").jqGrid('GridUnload');


jQuery("#list10_d").jqGrid({  
	height: 100,
   	url:'../controller/ProductoDetalleController.php?oper=cargar_tabla&id_almacen='+id_almacen+'&id_producto='+id_producto,
	datatype: "json",
   	colNames:['Id','Codigo','Precio', 'Color', 'Modelo','Cantidad', 'Costo','Cantidad Minimo'],
   	colModel:[
   		{	
			name:'id_producto_detalle',
			index:'id_producto_detalle',
			width:55, 
			align:"center"
		},
   		{	
			name:'codigo',
			index:'codigo', 
			editable:true, 
			editrules:{required:true},
			width:100, 
			align:"center"
		},
        {	
			name:'precio',
			index:'precio', 
			editable:true, 
			editrules:{number:true},
			width:80, 
			align:"center"
		},
   		{
			name:'color',
			index:'color', 
			editable:true, 
			width:80, 
			align:"center"
		},
   		{
			name:'talla',
			index:'talla', 
			editable:true, 
			width:80, 
			align:"center"
		},		
   		{
			name:'cantidad',
			index:'cantidad',
			editable:false,
			editrules:{integer:true},
			width:80, 
			align:"center"
		},
		{	
			name:'costo',
			index:'costo', 
			editable:true, 
			editrules:{number:true},
			width:80, 
			align:"center"
		},
		{
			name:'cantidad_min',
			index:'cantidad_min',
			editable:true,
			editrules:{integer:true},
			width:80, 
			align:"center"
		}
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	pager: '#pager10_d',
	width:"100%",
	height:"100%",
   	sortname: 'id_producto_detalle',
    viewrecords: true,
    sortorder: "asc",
	caption:"Articulo Detalles",
	onSelectRow: function(ids) {
	
		//document.getElementById('id_producto').value=ids;	
		var id_almacen  = document.form_ayuda.id_almacen.value;
		var ret = jQuery("#list10_d").jqGrid('getRowData',ids);
		var id_producto = ret.id_producto_detalle;
		var costo = ret.costo;
		cargarCompra(id_almacen,id_producto,costo);
		
	}
});

//----- PIE GRID
jQuery("#list10_d").jqGrid(
	'navGrid',
	'#pager10_d',	
	{view:true},
	{
		height:280,
		reloadAfterSubmit:true,
		url:'../controller/ProductoDetalleController.php',
		onInitializeForm: function(formid) {
			$("#FrmGrid_list10_d #codigo").attr("disabled","disabled")
		},
		recreateForm:true,
	}, // edit options 
	{
		height:280,
		reloadAfterSubmit:true,
		afterSubmit : function (response, postdata) {
								
			if(response.responseText=="null")
				return [true,""];
			else
				return [false,"El codigo ya existe"];
		} ,
		url:'../controller/ProductoDetalleController.php?id_almacen='+id_almacen+'&id_producto='+id_producto,
		onInitializeForm: function(formid) {
			$("#FrmGrid_list10_d #codigo").removeAttr("disabled")
		},
		recreateForm:true,
	}, // add options 
	{
		reloadAfterSubmit:true,
		url:'../controller/ProductoDetalleController.php',
		
	}, // del options 
	{
		sopt:['eq','ne','lt','le','gt','ge','cn']
	}, // search options
	{}
);




}
function cargarCompra(id_almacen,id_producto,costo){
	$("#list10_com").jqGrid('GridUnload');
	jQuery("#list10_com").jqGrid({  
	height: 100,
	 	url:'../controller/CompraController.php?oper=cargar_tabla&id_almacen='+id_almacen+'&id_producto='+id_producto+'&id_costo='+costo,
	datatype: "json",
   	colNames:['Id','Cantidad','Precio'],
   	colModel:[
   		{	
			name:'id_producto_ingreso',
			index:'id_producto_ingreso',
			width:55, 
			align:"center"
		},
		{
			name:'cantidad',
			index:'cantidad',
			editable:true,
			editrules:{integer:true},
			width:80, 
			align:"center"
		},
   		{	
			name:'precio_compra',
			index:'precio_compra', 
			editable:false, 
			editrules:{number:true},
			width:80, 
			align:"center"
		}   		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pager10_com',
	width:"100%",
	height:"100%",
   	sortname: 'id_producto_ingreso',
    viewrecords: true,
    sortorder: "asc",
	caption:"Compra Producto", 
				});

//----- PIE GRID
jQuery("#list10_com").jqGrid(
	'navGrid',
	'#pager10_com',	
	{view:false,edit:false,del:false},
	{
		height:280,
		reloadAfterSubmit:false,
		url:'../controller/CompraController.php?id_almacen='+id_almacen+'&id_producto='+id_producto+'&id_costo='+costo,
		onInitializeForm: function(formid) {
			$("#FrmGrid_list10_com #codigo").attr("disabled","disabled");
				},
		recreateForm:true,
	}, // edit options 
	{
		height:120,
		reloadAfterSubmit:true,
		url:'../controller/CompraController.php?id_almacen='+id_almacen+'&id_producto='+id_producto+'&id_costo='+costo,
		afterSubmit : function (response, postdata) {
					jQuery("#list10_com").trigger("reloadGrid");
					jQuery("#list10_d").trigger("reloadGrid");
					} ,
		onInitializeForm: function(formid) {
			//$("#FrmGrid_list10_com #codigo").removeAttr("disabled");
			//$("#FrmGrid_list10_d").removeAttr("disabled");	
			
		},
		//recreateForm:true,
	}, // add options 
	{
		reloadAfterSubmit:true,
		url:'../controller/CompraController.php',
		
	}, // del options 
	{
		sopt:['eq','ne','lt','le','gt','ge','cn']
	}, // search options
	{}
);




}




//---------------------------------------------------------------------------------------------------------
/*****************************************************/
/***************** Proveedores ***************************/
/*****************************************************/





function cargarTablaProveedores (){
	
var lastgridsel;

var id;
	
//$("#list").jqGrid('GridUnload');
$("#list").jqGrid({ 
	url:'../controller/ProveedorController.php?oper=cargar_tabla',
	datatype: 'json',
	colNames:['Id','Razon Social','Nombre Comercial','Correo','Direccion','Distrito','Telefono','Celular','Comprobante','Contacto'],
	colModel :[
	{	name:'id_proveedor', 
		index:'id_proveedor', 
		width:30,
		align:'center'},
	//IMAGEN	{name:'codigo',editable:true, width:70, edittype:"image", editoptions: {src:'img/b_pack.png'},  index:'codigo', align:'center', display : 'none'},		
	{	name:'razon_social',
		index:'razon_social', 
		editable:true,
		editrules:{required:true},
		width:70, 
		align:'center', 
	},
	/*elmsuffix  - label */
	{	name:'nombre_comercial', 
    	index:'nombre_comercial',
		editable:true,
    	width:80,
    	align:'center'
	},
    {	name:'correo', 
		index:'correo',
		editable:true, 
		width:40, 
		align:'center'
	},	 
	{	name:'direccion', 
		index:'direccion',
		editable:true, 
		width:40, 
		align:'center',
		hidden: true,
		editrules: {edithidden: true}
	},	
	{	name:'distrito', 
		index:'distrito',
		editable:true, 
		width:40, 
		align:'center',
		hidden: true,
		editrules: {edithidden: true}
	},	
	{	name:'telefono', 
		index:'telefono',
		editable:true, 
		width:40, 
		align:'center',
		hidden: true,
		editrules: {edithidden: true}
	},	
	{	name:'celular', 
		index:'celular',
		editable:true, 
		width:40, 
		align:'center',
		hidden: true,
		editrules: {edithidden: true}
	},	
//	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
	{	name:'tipo_comprobante_pago',  
		index:'tipo_comprobante_pago',
		editable:true,
		edittype:"select",
		editoptions:{value:"Boleta:Boleta;Factrua:Factrua"}, 
		width:60,  
		align:'center',
		hidden: true,
		editrules: {edithidden: true}
	},
	{
		name:'contacto',
		editable:true,
		width:60, 
		index:'contacto', 
		align:'center',
		hidden: true,
		editrules: {edithidden: true}
	}
	
		
	],
        //searchoptions: { sopt: ['eq','ne','lt','le','gt','ge', 'cn', 'nc', 'bw', 'bn'] },
	pager: '#pager',
	rowNum:10,
	rowList:[10,20,30],
	sortname: 'id_proveedor',
	sortorder: 'asc',
	viewrecords: true,
	gridview: true,
	caption: 'Lista de proveedor',
	


	height:"100%",
	width:720 
    
	
});

//--- PIE GRID
jQuery("#list").jqGrid('navGrid','#pager', 
					    {	
							view:true
						},
					    {	
							height:350,
							reloadAfterSubmit:true,
							url:'../controller/ProveedorController.php',
							onInitializeForm: function(formid) {
								$("#FrmGrid_list #codigo").attr("disabled","disabled")
								
							},
							recreateForm:true,
						}, // edit options 
					    {	
							height:350,
							reloadAfterSubmit:true,
							
							afterSubmit : function (response, postdata) {
								
									if(response.responseText=="null")
										return [true,""];
									else
										return [false,"El codigo ya existe"];
								} ,
								
							url:'../controller/ProveedorController.php',
							onInitializeForm: function(formid) {
								$("#FrmGrid_list #codigo").removeAttr("disabled")
							},
							recreateForm:true,
						}, // add options 
						{	
							reloadAfterSubmit:false,url:'../controller/ProveedorController.php'
						}, // del options 
						{
							sopt:['eq','ne','lt','le','gt','ge','cn']
						}, // search options
						{}
);

 
	
}
















