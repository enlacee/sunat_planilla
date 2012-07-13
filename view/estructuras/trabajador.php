<?php
//session_start();
//*******************************************************************//
require_once('../ide2.php');
//*******************************************************************//
?>
<script type="text/javascript">
    /**
     * Ids IDE
     */			
    var ids_trabajadores = new Array();
    var ids_trabajadores_2 = new Array();
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
		cargarTablaTrabajadorINACTIVO();
		
		cargarTablaTrabajadorACTIVO();
		
	});
//-----------------------------------------------------------------------------


    // GRID 2
    function cargarTablaTrabajadorINACTIVO(){

        //$("#list-1").jqGrid('GridUnload');
        $("#list-1").jqGrid({
            url:'sunat_planilla/controller/CategoriaTrabajadorController.php?oper=cargar_tabla_trabajador&estado=1',
            datatype: 'json',
            colNames:['ID','Categoria','Ttipo_doc','Numero Doc','Apellido Paterno',
                'Apellido Materno','Nombres','Fecha Nacimiento','Sexo','Estado'
                ,'Reportado'],
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
                    name:'categoria',
                    index:'categoria',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center' 
                },
                {
                    name:'nombre_tipo_documento', 
                    index:'nombre_tipo_documento',
                    search:false,
                    editable:false,
                    width:100,
                    align:'center'
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:100,
                    align:'center'
                },
                {
                    name:'fecha_nacimiento',
                    index:'fecha_nacimiento',
                    editable:true,
                    width: 100, 
                    align:'center'
                },
                {
                    name:'sexo',
                    index:'sexo',
                    editable:true,
                    search:false,
                    width:40, 
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    editable:true,
                    search:false,
                    width:50, 
                    align:'center'
                },
                {
                    name:'reporte',
                    index:'reporte',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                }							


            ],
            pager: '#pager-1',
			height:'200px',
            //width:'800px',
            //autowidth: true,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: 'Lista de Trabajadores Inactivo',
            toolbar: [true,"top"],
            multiselect: true,
            hiddengrid: false,
            onSelectRow: function(rowid, selected) {
					
                var counteo = ids_trabajadores.length;
		
                if( counteo != 0){
                    //console.log("MAS DE UNOO");
                    var bandera = false;					 
                    for(var i = 0; i < counteo ;i++){
                        // alert( rowid +"a igualar = " + ids_trabajadores[i]);
                        if(ids_trabajadores[i] == rowid){
                            // Ya existe rowid en array
                            bandera = true;
                            ids_trabajadores[i] =null;
                            break;
                        }
                    }//ENDFOR
		
                    if(bandera==false){
                        ids_trabajadores[counteo]=rowid;
                    }
		
                }else{
                    // console.log("UNOO");
                    ids_trabajadores[counteo]=rowid;	
                }
		
            },
            onSelectAll : function(rowids,selected) {            
                // alert("num de ids_trabajadores.length  "+ids_trabajadores.length);  
               
                for(var i=0; i<ids_trabajadores.length;i++){				
                    ids_trabajadores[i]=null;                    			
                }	
			
                if(selected){												
                    var array = new Array();
                    for(var i=0;i<rowids.length;i++){
                        //var data = jQuery("#list-1").jqGrid('getRowData',rowids[i]);
                        //array[i]=parseInt(data.id_persona);
                        array[i] = rowids[i];
                    }
                    ids_trabajadores = array;
                }//ENFIF
                
            },		

        });
        //--- PIE GRID
  jQuery("#list-1").jqGrid('navGrid','#pager-1',{add:false,edit:false,del:false});
/*        jQuery("#list-1").jqGrid('navGrid','#pager-1', { 
            edit:false,add:false,del:false,search:true,refresh:true },
        {},// edit options 
        {}, // add options 
        {}, //del options 
        {multipleSearch:true} // search options 
    );
*/
        //-------

        //----	
        $("#t_list-1").append("<input type='button' value='Dar de ALTA' style='height:20px;font-size:-3'/>");
        $("input","#t_list-1").click(function(){
        
        var news = new Array();
        
            var j=0;
            for(var i=0; i<ids_trabajadores.length;i++){
                if(ids_trabajadores[i]!=null){
                    news[j]=ids_trabajadores[i];
                    j++;
                }
            }
            console.log(news);


			
			
if(news.length>=1){ 
	// -----arrayCadena
	var cadena='';
	for(var i=0; i < news.length;i++){	
		cadena+= "ids[]="+news[i];
		if(i != (news.length-1)){
			cadena+= "&";
		}	
	}
	//alert(cadena);
	// -----arrayCadena

	//$("#list-1").jqGrid('GridUnload');
	window.location.href="sunat_planilla/controller/Estructura_01TrabajadorController.php?oper=t-registro-alta&"+cadena;
	
	jQuery("#list-1").trigger("reloadGrid");
	jQuery("#list-2").trigger("reloadGrid") 
	//alert("Archivos Generados");
	//-------
	limpiarArray(news);
	limpiarArray(ids_trabajadores);
	
	console.log("limpiando news");
	console.log(news);

}else{
	alert("Debe Seleccionar 1 Registro");
}


/*
if(news.length > 0){
//-------	
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'sunat_planilla/controller/Estructura_01TrabajadorController.php',
                data: {oper: 't-registro-alta',ids: news },
                beforeSend: function(objeto){  },
                complete: function(objeto, exito){ 
                },
               success: function(data){
				   jQuery("#list-1").trigger("reloadGrid");
				   jQuery("#list-2").trigger("reloadGrid") 
					alert("Archivos Generados");
					//-------
					limpiarArray(news);
					limpiarArray(ids_trabajadores);
					
					console.log("limpiando news");
					console.log(news);
					
                }
            });	
		
            //-------------
}else{//ENDIF
	alert("Debe Seleccionar un Registro.");	
}
*/
	
        });
	
    }


//-----------------------------------------------------------------------------------------
    // GRID 2
    function cargarTablaTrabajadorACTIVO(){

        //$("#list-2").jqGrid('GridUnload');
        $("#list-2").jqGrid({
            url:'sunat_planilla/controller/CategoriaTrabajadorController.php?oper=cargar_tabla_trabajador&estado=0',
            datatype: 'json',
            colNames:['ID','Categoria','Ttipo_doc','Numero Doc','Apellido Paterno',
                'Apellido Materno','Nombres','Fecha Nacimiento','Sexo','Estado'
                ,'Opciones'],
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
                    name:'categoria',
                    index:'categoria',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center' 
                },
                {
                    name:'nombre_tipo_documento', 
                    index:'nombre_tipo_documento',
                    search:false,
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:90,
                    align:'center'
                },
                {
                    name:'fecha_nacimiento',
                    index:'fecha_nacimiento',
                    editable:true,
                    width: 90, 
                    align:'center'
                },
                {
                    name:'sexo',
                    index:'sexo',
                    editable:true,
                    search:false,
                    width:40, 
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    editable:true,
                    search:false,
                    width:50, 
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
            pager: '#pager-2',
            //autowidth: true,
			//width: '',
			heigth:'200px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: 'Lista de Trabajadores Activos',
            toolbar: [true,"top"],
            multiselect: true,
            hiddengrid: false,
            onSelectRow: function(rowid, selected) {
					
                var counteo = ids_trabajadores_2.length;
		
                if( counteo != 0){
                    //console.log("MAS DE UNOO");
                    var bandera = false;					 
                    for(var i = 0; i < counteo ;i++){
                        // alert( rowid +"a igualar = " + ids_trabajadores_2[i]);
                        if(ids_trabajadores_2[i] == rowid){
                            // Ya existe rowid en array
                            bandera = true;
                            ids_trabajadores_2[i] =null;
                            break;
                        }
                    }//ENDFOR
		
                    if(bandera==false){
                        ids_trabajadores_2[counteo]=rowid;
                    }
		
                }else{
                    // console.log("UNOO");
                    ids_trabajadores_2[counteo]=rowid;	
                }
		
            },
            onSelectAll : function(rowids,selected) {            
                // alert("num de ids_trabajadores_2.length  "+ids_trabajadores_2.length);  
               
                for(var i=0; i<ids_trabajadores_2.length;i++){				
                    ids_trabajadores_2[i]=null;                    			
                }	
			
                if(selected){												
                    var array = new Array();
                    for(var i=0;i<rowids.length;i++){
                        //var data = jQuery("#list-2").jqGrid('getRowData',rowids[i]);
                        //array[i]=parseInt(data.id_persona);
                        array[i] = rowids[i];
                    }
                    ids_trabajadores_2 = array;
                }//ENFIF
                
            },	
			
        });
		
		
        //--- PIE GRID
	jQuery("#list-2").jqGrid('navGrid','#pager-2',{add:false,edit:false,del:false});


        //-------

        //----	
        $("#t_list-2").append("<input type='button' value='Dar de Baja' style='height:20px;font-size:-3'/>");
        $("input","#t_list-2").click(function(){
            //alert("Hi! I'm added button at this toolbar");        
        	var news = new Array();
        
            var j=0;
            for(var i=0; i<ids_trabajadores_2.length;i++){
                if(ids_trabajadores_2[i]!=null){
                    news[j]=ids_trabajadores_2[i];
                    j++;
                }
            }

            console.log(news);

		
//-------------
if(news.length>=1){ 

	// -----arrayCadena
	var cadena='';
	for(var i=0; i < news.length;i++){	
		cadena+= "ids[]="+news[i];
		if(i != (news.length-1)){
			cadena+= "&";
		}	
	}
	//alert(cadena);
	// -----arrayCadena
	
	window.location.href="sunat_planilla/controller/Estructura_01TrabajadorController.php?oper=t-registro-baja&"+cadena;
	//$("#list-2").jqGrid('GridUnload');
	   jQuery("#list-2").trigger("reloadGrid") 
	   jQuery("#list-1").trigger("reloadGrid");
		limpiarArray(ids_trabajadores_2);
		limpiarArray(news);

}else{
	alert("Debe Seleccionar 1 Registro");
}
//-------------
        

/*
if(news.length > 0){
//-----	
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'sunat_planilla/controller/Estructura_01TrabajadorController.php',
                data: {oper: 't-registro-baja',ids: news },
                beforeSend: function(objeto){  },
                complete: function(objeto, exito){ 
                },
               success: function(data){
				   //$("#list-2").jqGrid('GridUnload');
				   jQuery("#list-2").trigger("reloadGrid") 
				   jQuery("#list-1").trigger("reloadGrid");
					//news = null;
					limpiarArray(ids_trabajadores_2);
					limpiarArray(news);
					alert("Archivos de Baja Generados");
					console.log(news);
					console.log(ids_trabajadores_2);
					
					
                }
            });	
//-------
}else{
		alert("Debe seleccionar un Registro");
}
*/

	
	
        });
	
    }
	
	
	//-----------------
	function limpiarArray(arreglo){
		for(var i=0; i<arreglo.length;i++){				
			arreglo[i]=null;                    			
		}	
	}

	
</script>


<div class="demo" align="left">
<label for="select"></label>
<div id="tabs">
    <ul>	
			<li><a href="#tabs-1"> ALTA</a></li>	
            <li><a href="#tabs-2">BAJA</a></li>	
        </ul>
      
        
        
        <div id="tabs-1">

            <p>
                Trabajadores Pendientes            </p>
 
            <table id="list-1">
            </table>
            <div id="pager-1"></div>


<div class="ayuda">
  <p>Lista de los Trabajadores con Tipo de Documento :</p>
  <p>
  	- 01: DNI. <br />
    -  04: CARNÉ DE EXTRANJERÍA. <br />
    -   07: PASAPORTE. <br />
    -  11: PARTIDA DE NACIMIENTO. <br />
  </p>
</div>

      </div>
      
      
        <div id="tabs-2">
            <p>
                Trabajadores Pendientes            </p>
 
            <table id="list-2">
            </table>
            <div id="pager-2"></div>

        
        2</div>      
      
        
        
        
        
        
        
        
        
</div>