<?php
require_once '../../dao/AbstractDao.php';
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

//datos
require_once '../../model/PdiaSubsidiado.php';
require_once '../../dao/PdiaSubsidiadoDao.php';
require_once '../../controller/PlameDiaSubsidiadoController.php';

$suspencion1 = comboSuspensionLaboral_1();
$id_pjoranada_laboral = $_REQUEST['id_pjoranada_laboral'];

//DATOS
$data = buscarPor_IdPjornadaLaboral($id_pjoranada_laboral);

//echo "<pre>";
//print_r($data);
//echo "</pre>";
?>

<script type="text/javascript">
    
    //var objCombo = document.getElementById('cbo_tipo_suspension-1');
    var num_max_dia = document.getElementById('dia_total').value;
    var num_dia_subsidiado = 0;	
    var num_dia_nosubsidiado = 0;

	
    var test = new Array(
<?php $counteo = count($suspencion1);
for ($i = 0; $i < $counteo; $i++): ?>	
    <?php
    if ($i == $counteo - 1) {
        echo "{id:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "', descripcion:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "-" . $suspencion1[$i]['descripcion_abreviada'] . "' }";
    } else {
        echo "{id:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "', descripcion:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "-" . $suspencion1[$i]['descripcion_abreviada'] . "' },";
    }
    ?>
<?php endfor; ?>
);//end array

//FUNCION INICIO
calcDiaSubsidiado();


    function cargarSuspension_1(objCombo,ids){

        var counteo = 	test.length;
        console.dir(test);
        var z =0;
        //variables
        var arreglo = new Array();
        var eliminados = new Array();

        for(i=0; i<ids.length;i++){
	
            for(var j=0;j<test.length;j++){
		
                if( test[j].id == ids[i] ){ //ENCONTRO
                    //continue;			
                    eliminados = test.splice(j,1);
                    console.log(eliminados);
                }	
						
            }//ENDFOR 2
	
	
        }//ENDFOR 1


        for(var i=0;i<test.length;i++){
            objCombo.options[i] = new Option(test[i].descripcion, test[i].id);
        }
		
	
    }//ENDFN



    function validarNuevoInput(){

        if(num_dia_subsidiado == num_max_dia ){
            alert("No hay mas dias para ser subsidiados");
        }else{

            var ids = getIdsCombos();
            //alert("test.length "+test.length);
            if(test.length == 1){
                alert("No existe mas Conceptos por agregar");
            }else{
                nuevoDiaSubsidiado();
            }

        }

    }
    //--------------------------------------------------------------------------------


    function nuevoDiaSubsidiado(){
        //alert("holaaaaa");	
        //Capa Contenedora
        var tabla = document.getElementById("tb_dsubsidiado");
        var num_fila_tabla = contarTablaFila(tabla);
        num_fila_tabla = num_fila_tabla - 1;
        var COD_DETALLE_CONCEPTO =  num_fila_tabla + 1;

        var div = document.createElement('tr');
        div.setAttribute('id','dia_subsidiado-'+COD_DETALLE_CONCEPTO);
        //
        tabla.appendChild(div); //PRINCIPAL	
	

        //-------------------------------------------------------------
        //---- CODIGO
        var id = '<input type="text" size="4" id="pdia_subsidiado-'+ COD_DETALLE_CONCEPTO +'" name="pdia_subsidiado[]" value ="" >';
        var estado = '<input type="text" size="4" id="estado-'+ COD_DETALLE_CONCEPTO +'" name="estado[]" value ="0" >';
        //var codigo = '<input type="text" size="4" id="cod_detalle_concepto_" name="cod_detalle_concepto[]" value = '+ COD_DETALLE_CONCEPTO +'>';

        var input_cdia = '<input name="ds_cantidad_dia[]" type="text" id="ds_cantidad_dia-'+COD_DETALLE_CONCEPTO+'" size="7"  onblur="calcDiaSubsidiado()"/>';
        //-----input Descripcion
        var span1;
        var span2;


        var finSpan = '</span>';

        span1 = '<span title="editar">';
        span1 +="<a href=\"javascript:editar_ds('"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/edit.png\"></a>";
        span1 += finSpan;

        //html +='    <a href="javascript:eliminarElemento( document.getElementById( \'plaboral-row-'+id+'\' ) )" > delete </a>';
        span2 = '<span title="editar">';
        span2 +="<a href=\"javascript:eliminar_ds( 'dia_subsidiado-"+COD_DETALLE_CONCEPTO+"' )\"><img src=\"images/cancelar.png\"></a>";
        span2 += finSpan;


        var combo = "";
        combo +='     <select name="cbo_ds_tipo_suspension[]" id="cbo_ds_tipo_suspension-'+COD_DETALLE_CONCEPTO+'"  ';
        combo +='	  style="width:150px;"  onchange="" >';
        combo +='     </select>';


        //inicio html	
        var html ='';
        var cerrarTD = '<\/td>';


        html +='<td>';
        html += id;
        html += estado;
        html += combo;
        html += cerrarTD;


        html +='<td>';	
        html += input_cdia;
        html += cerrarTD;

        html +='<td>';	
        html += span1;
        html += cerrarTD;

        html +='<td>';	
        html += span2;
        html += cerrarTD;


        ////---
        div.innerHTML=html;

        //-------   - - --  -cargar combo
        cbo = document.getElementById('cbo_ds_tipo_suspension-'+COD_DETALLE_CONCEPTO);

        var ids = getIdsCombos();
        cargarSuspension_1(cbo,ids);	
	

    }

    function eliminar_ds(elementId){ alert (" "+elementId);
	
        var obj = document.getElementById(elementId)
        eliminarElemento(obj);
/*
        console.log("master intacto");
        console.dir(master);*/


    }



    function getIdsCombos(){
        var ids = new Array();
	
        var combos = document.getElementById('formDiaSubsidiado');	
        var numElementos = combos.elements.length;
	
        for(var i=0;i<combos.elements.length;i++){
            if(combos.elements[i].type == 'select-one'){
                var id_combo = combos.elements[i].id;
                var value = combos.elements[i].value;			
                ids.push(value);
            }
        }
	

        return ids;
    }


    function grabarDiaSubsidiado(){
        calcDiaSubsidiado();
        document.getElementById('dia_subsidiado').value = num_dia_subsidiado;
        alert("SESSION = La informacion se Guardo\nCorrectamente.");
	
        //-----
        var data = $("#formDiaSubsidiado").serialize();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'sunat_planilla/controller/PlameDiaSubsidiadoController.php?'+data,
            data: {/*id_pjornada_laboral: id_pjornada_laboral*/},
            success: function(json){
				
            }
        });
	
	

    }

    function calcDiaSubsidiado(){

        var tabla = document.getElementById("tb_dsubsidiado");
        var num_fila = parseInt(contarTablaFila(tabla));
        num_fila = num_fila - 1;	
	
        num_dia_subsidiado = 0;
	
        var total = document.getElementById('ds_total');
        var num_max_dia_local = num_max_dia;
	

        for(var i= 1; i <= num_fila; i++){ console.log("ciclo iiii="+ i);
	
            var cbo = document.getElementById('cbo_ds_tipo_suspension-'+i);
            var dia = document.getElementById('ds_cantidad_dia-'+i);		
		
            //	
            var diaint = parseInt( (dia.value == '' || dia.value < 0) ? 0 : dia.value );
            //rpta
            num_dia_subsidiado = num_dia_subsidiado + (diaint);	
		
		
            if( i == 1 ){
                num_max_dia_local = num_max_dia_local - diaint;	
            }else {
                //num_max_dia_local = num_max_dia_local - diaint;
            }		
		
            if(num_max_dia_local<0){
                alert("El maximo numero de dias esS: "+ num_max_dia);
                dia.value = '';			
                diaint = 0;
                break;
            }		

            if(num_dia_subsidiado > num_max_dia){
                alert("El maximo numero de dias es: "+ num_max_dia_local);
                dia.value = '';
                num_dia_subsidiado = num_dia_subsidiado - (diaint);
                diaint = 0;
			
            }
		
            //rpta
            //num_dia_subsidiado = num_dia_subsidiado + (diaint);
            dia.value = (diaint == 0) ? '' : diaint;		
		
        }

	
        total.value = (num_dia_subsidiado>num_max_dia) ? '' : num_dia_subsidiado;
        //alert("DIA TOTAL "+ num_dia);	
    }

</script>
<form action="" method="get" id="formDiaSubsidiado" name="formDiaSubsidiado">

    <div class="tb" style="width:450px;" >
        oper
        <input name="oper" type="text" value="dual" />
        <br />
        id_pjoranada_laboral
        <label for="id_pjoranada_laboral"></label>
        <input type="text" name="id_pjoranada_laboral" id="" value="<?php echo $id_pjoranada_laboral; ?>" />
        <table width="450" border="1" id="tb_dsubsidiado">
            <tr>
                <td width="217">tipo desuspens&oacute;n</td>
                <td width="81">cantidad de dias</td>
                <td width="88">Modificar</td>
                <td width="74">Eliminar</td>
            </tr>





            <?php
            $SELECCIONADO = array();
            $ID = 0;
            for ($i = 0; $i < count($data); $i++):
                $ID++;
                ?>
                <!-- -->


                <tr id="dia_subsidiado-<?php echo $ID;?>">
                    <td>
                        <input size="4" id="pdia_subsidiado-<?php echo $ID; ?>" name="pdia_subsidiado[]" 
                               value="<?php echo $data[$i]->getId_pdia_subsidiado(); ?>" 
                               type="hidden">
                        <input size="4" id="estado-<?php echo $ID; ?>" name="estado[]" 
                               value="<?php echo ($data[$i]->getId_pdia_subsidiado()) ? 1 : 0; ?>"
                               type="hidden">
                               
                             <input type="hidden" name="txt_cbo_ds_tipo_suspension[]" 
                             value="<?php echo $data[$i]->getCod_tipo_suspen_relacion_laboral(); ?>"/>  
                             
                        <select name="cbo_ds_tipo_suspension[]" id="cbo_ds_tipo_suspension-<?php echo $ID; ?>" 
                        style="width:150px;" onchange=""   >
                            <?php
                            foreach ($suspencion1 as $indice) {
             

                                if ($indice['cod_tipo_suspen_relacion_laboral'] == $data[$i]->getCod_tipo_suspen_relacion_laboral()) {                              
                                    $html = '<option value="' . $indice['cod_tipo_suspen_relacion_laboral'] . '" selected="selected" >' . $indice['cod_tipo_suspen_relacion_laboral'] . " - " . $indice['descripcion_abreviada'] . '</option>';
                                }/* else if($econtro == false ) {
                                    $html = '<option value="' . $indice['cod_tipo_suspen_relacion_laboral'] . '" >' . $indice['cod_tipo_suspen_relacion_laboral'] . " - " . $indice['descripcion_abreviada'] . '</option>';
                                }*/
                                
                                
                                echo $html;
                            }
                            ?>

                        </select>

                    </td>
                    <td>
                        <input name="ds_cantidad_dia[]" id="ds_cantidad_dia-<?php echo $ID; ?>" size="7" onblur="calcDiaSubsidiado()" type="text"
                               value="<?php echo $data[$i]->getCantidad_dia(); ?>" >
                    </td>
                    <td>
                        <span title="editar">
                            <a href="javascript:editar_ds('<?php echo $ID; ?>')">
                                <img src="images/edit.png">
                            </a>
                        </span>
                    </td>
                    <td>
                        <span title="editar">
                            <a href="javascript:eliminar_ds( 'dia_subsidiado-<?php echo $ID; ?>' )">
                                <img src="images/cancelar.png"></a></span>
                    </td>
                </tr>

                <!-- -->

            <?php endfor; ?>




        </table>

    </div>
    <br>
    <div style="width:150px; margin:0 0 0 174px;">
        <label for="ds_total">TOTAL</label>
        <input name="ds_total" type="text" id="ds_total" size="7">
    </div>
    <p>
        <input type="button" name="btnGrabar" id="btnGrabar" value="Grabar" onclick="grabarDiaSubsidiado()" />
        <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onclick="validarNuevoInput()" />
        <input type="button" name="demo" id="demo" value="Button"  onclick="getIdsCombos()"/>
    </p>
</form>