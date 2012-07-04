//JavaScript Document
var demoApp;


//INICIO HISTORIAL
$(document).ready(function(){
						   
    demoApp = new Historial();


});

/*****************************************************/
/***************** Cargando AJAX ********************/
/*****************************************************/

function inicioEnvioAjax(){
    displayDiv = document.getElementById("content-view");
	
    var div = document.createElement("div");
    div.setAttribute("id", "loading");
    div.innerHTML = '<img src="img/loading.gif"> <h5>Cargando...</h5>';
	
    displayDiv.appendChild(div);

	
}



/*****************************************************/
/***************** Terrenos ***************************/
/*****************************************************/

function viewTerrenos(){

    displayDiv = document.getElementById("content-view");

    $.ajax({
        url: 'modules/terreno/view.php',
        type: "POST",
        data: "",
        async: true,
        dataType: "html", //html,json,xml,script
        error: function(objeto, quepaso, otroobj){ //error : devulve 3 parametros
            alert("Paso lo siguiente: "+quepaso);
        },
        beforeSend:inicioEnvioAjax,
        success: function(datos){  //success : exe al ser exitosa un llamado
            //$(div).html("");
			
            displayDiv.innerHTML = datos;			
            cargarTablaTerrenos();
			
        //	cargar_tablaProducto();
        //   crearDialogoNuevoProducto();
        //    crearDialogoEditarProducto();
			
        }
    });
	
}


//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
function cargarTablaTerrenos(){

    //$("#list").jqGrid('GridUnload');
    $("#list").jqGrid({
        url:'../controller/TerrenoController.php?oper=cargar_tabla&id_tipo_inmueble=1',
        datatype: 'json',
        colNames:['id_empleador','RUC','Razon Social','Nombre Comercial','Telefono','Descripcion'],
        colModel :[
        {
            name:'id_empleador', 
            editable:false, 
            index:'id_empleador',
            width:30,
            align:'center'
        },		
        {
            name:'ruc',
            index:'ruc', 
            editable:true,
            editrules:{
                required:true
            },
            width:70, 
            align:'center', 
        },
        {
            name:'razon_social', 
            index:'razon_social',
            editable:false,
            width:80,
            align:'center'
        },
        //	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
        {
            name:'nombre_comercial', 
            index:'nombre_comercial',
            editable:true,
            width:80,
            align:'center'
        },
        {
            name:'telefono',
            editable:true,
            width:60, 
            index:'telefono', 
            align:'center'
        },
        {
            name:'descripcion',
            editable:false,
            width:60, 
            index:'descripcion', 
            align:'center'
        },
	
		
		
        ],
        pager: '#pager',
        rowNum:10,
        rowList:[10,20,30],
        sortname: 'id_inmueble',
        sortorder: 'asc',
        viewrecords: true,
        gridview: true,
        caption: 'Lista de Terrenos',
        onSelectRow: function(ids) {
        /*
		document.form_ayuda.id_producto.value = ids;
		//document.getElementById('id_producto').value=ids;	
		
		var id_almacen  = document.form_ayuda.id_almacen.value;
		var id_kit = document.form_ayuda.id_producto.value;
		
		cargarKitDetalle(id_almacen,id_kit);
		*/
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
        reloadAfterSubmit:false,
        url:'../controller/KitController.php'
    }, // del options 
    {
        sopt:['eq','ne','lt','le','gt','ge','cn']
    }, // search options
    {}
    );


}

