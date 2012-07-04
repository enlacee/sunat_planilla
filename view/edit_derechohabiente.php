<?php
//COMBO FILE
require_once('../dao/AbstractDao.php');
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');

require_once '../dao/ComboCategoriaDao.php';
require_once '../controller/ComboCategoriaController.php';

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

$cbo_documentos_vinculos_familiares = comboDocumentoVinculoFamiliar($obj_dh->getCod_vinculo_familiar());
//cod_documento_vinculo_familiar
// COMBO 03 cod_situacion

$cbo_situaciones = comboSituacion();

echo "<pre>";
//print_r($cbo_documentos_vinculos_familiares);
echo "</pre>";
?>

<script>
			
                
    //INICIO HISTORIAL
    $(document).ready(function(){
        //demoApp = new Historial();                  
        $( "#tabs").tabs();
		crearDialogoDerechohabienteDireccion();
        //new
        //-------------------------------------------------------------------------------------
        //-------------------------------------------------------------------------------------
        //$('#form_new_personal').ajaxForm( { beforeSubmit: validate } ); 
        $("#form_edit_derechohabiente").validate({
            rules: {				
                txt_fecha_nacimiento:{
                    required:true					
                },
                cbo_pais_emisor_documento: {
                    required: true				  
                },
                txt_apellido_paterno:{
                  required:true  
                },
                txt_apellido_materno:{
                  required:true  
                },
                txt_nombre:{
                  required:true  
                },
                cbo_tipo_documento:{
                    required:true					
                },
                txt_num_documento:{
                    required: true,
                    rangelength: [8, 15]
                },
                cbo_pais_emisor_documento:{
                    required: true
                },
                rbtn_sexo:{
                    required: true
                },
                cbo_estado_civil:{
                    required: true
                },
                cbo_vinculo_familiar:{
                    required: true
                },
                cbo_documento_vinculo_familiar:{
                    required: true
                },txt_vf_num_documento:{
                    required: true
                },
                txt_vf_mes_concepcion:{
                    required: true
                }				
				
            },
            submitHandler: function(data) {		 //alert('en submitHandler');		
		var from_data =  $("#form_edit_derechohabiente").serialize();		
                $.getJSON('sunat_planilla/controller/DerechohabienteController.php?'+from_data,
                function(data){
                    if(data){
                        disableForm('form_edit_derechohabiente');
                        alert("Se guardo Correctamente JSON");					
						
                    }else{
                        alert("Ocurrio un error, intente nuevamente no hay datos JSON");
                    }
                }); 
                //-----------------------------------------------------------------------

            }//ENDsubmitHandler			
			
        });
 
        
        
        
        
        
        
                    
        //$( "#tabs_2").tabs();
        id_persona = document.form_edit_derechohabiente.id_derechohabiente.value;
        
        cargarTablaDerechohabienteDireccion(id_persona);					
        //---------------------------------------------


        //-------------------------------------------------------------------
    }); //End Ready
				
				
			
				
    /*****************************************************/
    /***************** Terrenos ***************************/
    /*****************************************************/
    
    //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011		
    function cargarTablaDerechohabienteDireccion(id){  //console.log('id_derechohabiente = '+id);			
        //OBTENER ID PERSONA
        //$("#list").jqGrid('GridUnload');+	
        $("#list").jqGrid({
            url:'sunat_planilla/controller/DerechohabienteDireccionController.php?oper=cargar_tabla&id_derechohabiente='+id,
            datatype: 'json',
            colNames:['Id','id_derechohabiente','nombre_ubigeo_reniec','Direccion','Opciones'],
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
					hidden:true,

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
		
        	
    }		
    //-----------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------

</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Editar DerechoHabiente</a></li>
        </ul>
        <div id="tabs-1">
            <!--INICIO TAB2 -->
            <div id="messageBox"></div>	

            <form action="hola.php" method="post" 
                  name="form_edit_derechohabiente" id="form_edit_derechohabiente" novalidate="novalidate">

                <input name="oper" type="hidden" value="edit">
                <fieldset>
                    <legend>Datos de Identificacion </legend>


<div class="fila_input">
                    <label><label>id_derechohabiente</label></label>
                    <input type="text" name="id_derechohabiente" id="id_derechohabiente" 
                           value="<?php echo $obj_dh->getId_Derechohabiente(); ?>" >
</div>					   

					<div class="fila_input">
                    <label>id_persona</label>
                    <input name="id_persona" type="text" id="id_persona" value="<?php echo $obj_dh->getId_persona(); ?>" />
					</div>

                    <div class="fila_input">
                        <label>Tipo de Documento: </label>

                        <select name="cbo_tipo_documento" id="cbo_tipo_documento" onChange="cambioSelect()" >
                            <option value="">-</option>
                            <?php
                            foreach ($cbo_tipo_documento as $indice) {

                                if ($indice['cod_tipo_documento'] == $obj_dh->getCod_tipo_documento()) {

                                    $html = '<option value="' . $indice['cod_tipo_documento'] . '" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
                                } else {
                                    $html = '<option value="' . $indice['cod_tipo_documento'] . '" >' . $indice['descripcion_abreviada'] . '</option>';
                                }
                                echo $html;
                            }
                            ?>


                        </select>


                        cbo</div>
                    <div>
                        <label>Numero Documento </label>
                        <input name="txt_num_documento" type="text" class="required "  id="txt_num_documento" 
                               value="<?php echo $obj_dh->getNum_documento(); ?>">
                    </div>
                    <div class="fila_input">
                        <label>Fecha Nacimiento</label>
                        <input name="txt_fecha_nacimiento" type="text"  id="txt_fecha_nacimiento" class="required"
                               value="<?php echo $obj_dh->getFecha_nacimiento(); ?>">
                    </div>
                    <div style="clear:both">
                        <label>País Emisor del Documento:</label>
                        <select name="cbo_pais_emisor_documento" id="cbo_pais_emisor_documento">
                            <option value="">-</option>
                            <?php
                            foreach ($cbo_pais_emisor_documento as $indice) {

                                if ($indice['cod_pais_emisor_documento'] == $obj_dh->getCod_pais_emisor_documentos()) {

                                    $html = '<option value="' . $indice['cod_pais_emisor_documento'] . '" selected="selected" >' . $indice['descripcion'] . '</option>';
                                } else {
                                    $html = '<option value="' . $indice['cod_pais_emisor_documento'] . '" >' . $indice['descripcion'] . '</option>';
                                }
                                echo $html;
                            }
                            ?>

                        </select>
                        cbo</div>
                    <div class="fila_input">
                        <label>Apellido Paterno:</label>
                        <input name="txt_apellido_paterno" type="text" id="txt_apellido_paterno" 
                               value="<?php echo $obj_dh->getApellido_paterno(); ?>">
                    </div>
                    <div class="fila_input">
                        <label>Apellido Materno:</label>
                        <input name="txt_apellido_materno" type="text"  id="txt_apellido_materno" 
                               value="<?php echo $obj_dh->getApellido_materno(); ?>">
                    </div>
                    <div class="fila_input">
                        <label>Nombres:</label>
                        <input name="txt_nombre" type="text" id="txt_nombre" value="<?php echo $obj_dh->getNombres(); ?>">
                    </div>
                    <div class="fila_input">
                        <label>Sexo:</label>
                        <input name="rbtn_sexo" type="radio" 
                               value="M" <?php if ($obj_dh->getSexo() == "M") {
                                ; ?>checked="checked" <?php } ?>>
                        M
                        <input name="rbtn_sexo" type="radio" 
                               value="F" <?php if ($obj_dh->getSexo() == "F") {
                                   ; ?>checked="checked" <?php } ?>>
                        F </div>
                    <div class="fila_input">
                        <label >Estado Civil:</label>
                        <select name="cbo_estado_civil">

                            <option value="">-</option>
                            <?php
                            foreach ($cbo_estado_civil as $indice) {

                                if ($indice['id_estado_civil'] == $obj_dh->getId_estado_civil()) {
                                    $html = '<option value="' . $indice['id_estado_civil'] . '" selected="selected" >' . $indice['descripcion'] . '</option>';
                                } else {
                                    $html = '<option value="' . $indice['id_estado_civil'] . '" >' . $indice['descripcion'] . '</option>';
                                }
                                echo $html;
                            }
                            ?>

                        </select>						
                    </div>
                    <br/>
                </fieldset>

                <p></p>
                <fieldset><legend>V&iacute;nculo Familiar</legend>



<div class="fila_input">
                    <label>Vínculo Familiar:</label>
                    <select name="cbo_vinculo_familiar" 
                    onchange="seleccionandoCombo_1VF(this, 'cbo_documento_vinculo_familiar')" >
                        <option value="">-</option>
                        <?php
                        foreach ($cbo_vinculos_familiares as $indice) {

                            if ($indice['cod_vinculo_familiar'] == $obj_dh->getCod_vinculo_familiar()) {
                                $html = '<option value="' . $indice['cod_vinculo_familiar'] . '" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
                            } else {
                                $html = '<option value="' . $indice['cod_vinculo_familiar'] . '" >' . $indice['descripcion_abreviada'] . '</option>';
                            }
                            echo $html;
                        }
                        ?>
                    </select>
</div>
                    <div>
                        <label>Documento que sustenta vínculo:</label>
                        <select name="cbo_documento_vinculo_familiar" id="cbo_documento_vinculo_familiar">
                            <?php
                            foreach ($cbo_documentos_vinculos_familiares as $indice) {

                                if ($indice['cod_documento_vinculo_familiar'] == $obj_dh->getCod_vinculo_familiar()) {
                                    $html = '<option value="' . $indice['cod_documento_vinculo_familiar'] . '" selected="selected" >' . $indice['nombre_doc_vinculos_familiares'] . '</option>';
                                } else {
                                    $html = '<option value="' . $indice['cod_documento_vinculo_familiar'] . '" >' . $indice['nombre_doc_vinculos_familiares'] . '</option>';
                                }
                                echo $html;
                            }
                            ?>
                        </select>
                    </div>


                    <div class="fila_input">
                        <label>N&uacute;mero de Doc:</label>
                        <input name="txt_vf_num_documento" type="text"  id="txt_vf_num_documento" value="<?php echo $obj_dh->getVf_num_documento(); ?>" />
                    </div>

                    <div class="fila_input">
                        <label>Mes de Concepci&oacute;n:</label>
                        <input name="txt_vf_mes_concepcion" type="text"  id="txt_vf_mes_concepcion" 
                         value="<?php echo $obj_dh->getVf_mes_concepcion(); ?>" 
                         onblur="validarvfMesConcepcion(this)"/> 
                        (mm/aaaa) VALIDA!!
                    </div>

                    <div class="fila_input ocultar" style="">
                        situaci&oacute;n: <select name="cbo_situacion" 
                        <?php //echo ($obj_dh->getCod_situacion()=='1')? ' disabled="disabled"' : '' ?> >
                            <?php
                            foreach ($cbo_situaciones as $indice) {

                                if ($indice['cod_situacion'] == $obj_dh->getCod_situacion()) {
                                    $html = '<option value="' . $indice['cod_situacion'] . '" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
                                } else {
                                    $html = '<option value="' . $indice['cod_situacion'] . '" >' . $indice['descripcion_abreviada'] . '</option>';
                                }
                                echo $html;
                            }
                            ?>
                        </select>
                    </div>



                </fieldset>
                <p></p>





                <fieldset id=""><legend>datos opcionales</legend>


                    <div class="fila_input">
                        <label>Teléfono( código y número ): </label>
                        <select name="cbo_telefono_codigo_nacional" >
                            <?php
                            foreach ($cbo_telefono_codigo_nacional as $indice) {

                                if ($indice['cod_telefono_codigo_nacional'] == $obj_dh->getCod_telefono_codigo_nacional()) {

                                    $html = '<option value="' . $indice['cod_telefono_codigo_nacional'] . '" selected="selected" >' . $indice['descripcion'] . '</option>';
                                } else {
                                    $html = '<option value="' . $indice['cod_telefono_codigo_nacional'] . '" >' . $indice['descripcion'] . '</option>';
                                }
                                echo $html;
                            }
                            ?>

                        </select>
                        <input name="txt_telefono" type="text" id="txt_telefono" value="<?php echo $obj_dh->getTelefono(); ?>" />
                        
                  </div>

                    <div class="fila_input">
                        <label>Correo Electronico </label> 
                        <input name="txt_correo_electronico" type="text" value="<?php echo $obj_dh->getCorreo(); ?>">
                    </div>



                </fieldset>



                <p></p>


                <div style=" display:block; " id="DIV_GRID_DIRECCION">


                        <table id="list"></table>
                        <div id="pager"></div>


		
                </div>
                <!-- DIRECCION 2-->
                <input name="btn_grabar" type="submit" id="btn_grabar" value="Grabar Derechohabiente">

            </form>

            <!--FINAL TAB2 -->




        </div>


    </div>
</div>





<!--  -------------------------------------- -->			


</DIV>







<!-- -->

<!-- -->

<div id="dialog-form-editarDireccion-Derechohabiente" title="Editar Direccion">X
    <div id="editarDerechohabienteDireccion" align="left">Y</div>
</div>

