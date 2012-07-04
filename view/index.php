<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Untitled Document</title>
        <!-- INICIO = Componentes/librerias.php -->

        <!-- ESTILOS  PRINCIPALES CSS -->
        <link rel="stylesheet" type="text/css" href="csss/main.css"/>
        <!-- JAVASCRIPT HISTORIAL -->      

        <script type="text/javascript"  src="js/unFocus-History/unFocus-History-p.js"></script>
        <!-- SCRIPTS  PRINCIPALES JS -->
        <script type="text/javascript"  src="js/jquery-1.7_min.js"></script>
        <!-- FINAL = Componentes/librerias.php --><!--INICIO UI-->
        <link rel="stylesheet" href="themes/base/jquery.ui.all.css">
            <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
              <!--  <script src="ui/jquery-ui-1.8.16.custom.js"></script>-->
            <script src="ui/jquery-ui-1.8.16.custom.js"></script>

            <script src="js/grid.locale-es.js" type="text/javascript"></script>  
            <script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
            <script src="js/jquery.jqGrid.src.js" type="text/javascript"></script>

<!-- <script src="js/src/grid.common.js" type="text/javascript"></script>   		
<script src="js/src/grid.postext.js" type="text/javascript"></script>   -->	

            <!--FINAL UI    

            <script type="text/javascript" src="js/intranet_base.js"></script> -->
            <script type="text/javascript" src="js/intranet2XXXX.js"></script>
            <script>
            
                /*****************************************************/
                /***************** Terrenos ***************************/
                /*****************************************************/

                //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
                function cargarTablaTerrenos(){

                    $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'../controller/intranet/EmpleadorController.php?oper=cargar_tabla&id_tipo_inmueble=1',
                        datatype: 'json',
                        colNames:['id_empleador','RUC','Razon Social','Nombre Comercial','Telefono','Tipo empleador','Opciones'],
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
                                name:'nombre_tipo_empleador',
                                editable:true,
                                width:120, 
                                index:'nombre_tipo_empleador', 
                                align:'center'
                            },
                            {
                                name:'opciones',
                                editable:false,
                                width:60, 
                                index:'opciones', 
                                align:'center'
                            },
	
		
		
                        ],
                        pager: '#pager',
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_empleador',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de Empleadores',
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
                        view:false
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

    

            </script>

    </head>
    <body >





        <input type ="button" onclick='cargarTablaTerrenos()' value="grid "/>


        <table id="list"><tr><td/></tr></table>
        <div id="pager"></div>







        <div id="content-view" style="border: 1px solid red;"> |content-php|
            <!-- Contenido Dinamico VIEW Dialog -->Pagina ERROR</div> 






        <script>
    

    
        </script>






























    </body>
</html>
