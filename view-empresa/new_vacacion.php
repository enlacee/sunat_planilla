<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
$id_pdeclaracion = ($_SESSION['sunat_empleador']['config']['id_pdeclaracion']) ? $_SESSION['sunat_empleador']['config']['id_pdeclaracion'] : 'null';
$periodo = ($_SESSION['sunat_empleador']['config']['periodo']) ? $_SESSION['sunat_empleador']['config']['periodo'] : 'null';

require_once('../util/funciones.php');
?>
<script type="text/javascript" src="sunat_planilla/view/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    $( "#tabs").tabs();
    //variables globales
    var id_trabajador = $("#id_trabajador").val();
    var id_pdeclaracion = $("#id_pdeclaracion").val();
    var periodo = $("#periodo").val();
	//jqgrid
    cargarTablaDetalleVacacion(id_pdeclaracion,periodo,id_trabajador);
	 
    function eliminarElemento(obj){
        var parrafo = obj;
        parrafo.parentNode.removeChild(parrafo);
    }
    //--- Eliminar Elemento
    $( ".quote .close" ).live( "click", function( e ) {          
          var $elemento = $(this).parent();
          //console.log($elemento);
          $elemento.remove();								   
    });


    //$("#v_fechaInicio_1").mask("99/99/9999");
    $("#v_fechFin_1").mask("99/99/9999");
    $("#v_fechaInicio_1").mask("99/99/9999",{completed:function(){
        //validacion de anio
        var periodo_local = cadenaFecha(periodo);
        var input_inicio = cadenaFecha(this.val());
        if(periodo_local.getFullYear()==input_inicio.getFullYear()){
        }else{
            alert("El valor ingresado : "+this.val()+"\nPeriodo "+input_inicio.getFullYear()+" no esta permitido.");
            this.val('');
        }
        
    }});

    

});
//-------------------------------------------------

//--------------------------------------------------
var $vacacion = $("#vacacion");
countGlobal = function(vehicle){
    var $contador = 1;        
    var hijos = vehicle[0].children;

    for(i=0;i<hijos.length;i++){
        console.log(i);
        console.log(hijos[i].nodeName);
        if(hijos[i].nodeName =="UL"){
            $contador++;
        }
        console.log(hijos[i].type);
        console.log(i);
    }
    return $contador;
}

createBodyVacacion= function(){
    var count = countGlobal($vacacion);
    console.log(count);
    if(count>3){
        alert('solo se permiten 3 fechas');
        return false;
    }
    //---- body
    var ul = document.createElement("ul");
    ul.setAttribute("row","vacacion-"+count);
    ul.setAttribute("class","lista");
    //ul.innerHtml='<li>lista</li>';

    // inicio de html
    var close,li1;
        close = '<h2>'+count+'</h2><div class="close">[X]</div>';
        li1 = '    <li>';
        li1 += '        <span><input type="text" name="v_fechaInicio_'+count+'" id="v_fechaInicio_'+count+'" value="" size="10" onkeyup="return calFechaInico(event,this)" /></span>';
        li1 += '        <span><input type="text" name="v_fechFin_'+count+'" id="v_fechFin_'+count+'" value="" size="10" readonly/></span>';
        li1 += '        <span><input type="text" name="v_numDia_'+count+'" id="v_numDia_'+count+'" value="" size="5" onkeyup="return calcFechaVacacion(event,this)" /></span>';
        li1 += '    </li>';
    // final de html
    var html ='';
    html += close;
    html += li1;

    $vacacion.append(ul); //PRINCIPAL
    ul.innerHTML=html;
    /// Help Library Jquery
    //$("#v_fechaInicio_"+count).mask("99/99/9999");
    $("#v_fechaInicio_"+count).mask("99/99/9999",{completed:function(){
        //validacion de anio
        var periodo_local = cadenaFecha(periodo);
        var input_inicio = cadenaFecha(this.val());
        if(periodo_local.getFullYear()==input_inicio.getFullYear()){
        }else{
            alert("El valor ingresado : "+this.val()+"\nPeriodo "+input_inicio.getFullYear()+" no esta permitido.");
            this.val('');
        }        
    }});

    $("#v_fechFin_"+count).mask("99/99/9999");
}

function createVacacion(obj){
    var data = document.frmVacacion.addVacacion;
    var flag = false;
    for(i=0;i<data.length;i++){
        //console.log(data[i].getAttribute('selected'));
        if(data[i].checked && data[i].value==1){
            flag=true;
            break;
        }
    }
    if(flag){
        createBodyVacacion();
    }
}
//------ funcion calc fecha
var calcFechaVacacion = function(e,obj){
    var num = getNumeroEnCadena(obj.id,'_');
    var numDia = parseInt(obj.value);
    var tecla = (document.all) ? e.keyCode : e.which; 
    

    if(obj.value <= 30 && numDia>=1){
        numDia = numDia-1;
        var fecha_inicio = cadenaFecha(document.getElementById('v_fechaInicio_'+num).value);                  
        var newDate = addTimeToDate(numDia, 'd', fecha_inicio);
        var fecha_fin = document.getElementById('v_fechFin_'+num).value = ''+newDate.getDate()+'/'+(newDate.getMonth()+1)+'/'+newDate.getFullYear();

    }else{
        obj.value = '';
        document.getElementById('v_fechFin_'+num).value ='';
    }
    // console.log('num',num);  
    // console.log('v_fechaInicio_= ',fecha_inicio);
    // console.log('newDate= ',newDate);    
    // console.log('v_fechFin_= ',fecha_fin);
}

var calFechaInico = function(e,obj){
    var num = getNumeroEnCadena(obj.id,'_');
    var tecla = (document.all) ? e.keyCode : e.which;

    if(tecla){
        document.getElementById('v_fechFin_'+num).value='';
        document.getElementById('v_numDia_'+num).value='';        
    }
}

//---------- function validar fecha vacacion
function validarFechaVacacion(e,obj){
	
	var form,
		finicio,
		ffin,
		id_pdeclaracion,
		id_trabajador;
	//data = $('#frmVacacion').serialize();
	form = document.frmVacacion;
	finicio = form.v_fechaInicio_1.value;
	ffin =form.v_fechFin_1.value;
	id_pdeclaracion = $("#id_pdeclaracion").val();
    periodo = $("#periodo").val();
	id_trabajador = form.id_trabajador.value;
	
	if(ffin!=""){
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "sunat_planilla/controller/VacacionController.php",
			data: {
				oper : 'validarFecha',
				fecha_inicio : finicio,
				fecha_fin : ffin,
				id_pdeclaracion : id_pdeclaracion,
                periodo : periodo,
				id_trabajador : id_trabajador,
				},
			async:true,
			success: function(data){
				if(data.rpta){
					//enviarVacacion();
                    //console.error("error eh.");
                    enviarVacacion();					
				}else{
					alert(data.mensaje);					
				}				
			},
			//beforeSend:function(){ $('#tabs-1').html("<p class='loading'></p>"); },        
			//timeout:4000,
			//error:function(){ alert('error en servidor');}
		});
	}else{
		alert("No se permiten campos vacios");
	}	
	//enviarVacacion();
}




</script>

<style type="text/css">
h2{
margin:0  !important;
padding:0  !important;
}
.lista{
    border:1px solid blue;
    margin: 5px;
    padding: 5px;
}
.close{
    position: relative;;
    float: right;
    background-color: black;
    color: red;
}
.mini_1{
    /*width: 50px !important;*/
}
/* base */
.quote {
padding: 0;
position: relative;
width: 400px;
}
.quote ul li{
    color: #444;
    margin-bottom: 10px;
}
.quote ul li span{
    display: inline-block;
    text-align: center;
    width: 28%;
}
</style>
<div class="demo" align="left">

<div class="ocultar">
id_pdeclaracion
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
value="<?php echo $id_pdeclaracion ; ?>"/>
periodo
<input type="text" name="periodo" id="periodo"
value="<?php echo $periodo;?>" />
</div>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Asignar vacacion</a></li>			

        </ul>
        <div id="tabs-1">
<div style="float:right; width:350px; min-height:250px; border:1px solid blue;">
    <h2>Historial de vacaciones</h2>
    <table id="list">
    </table>
    <div id="pager">
    </div>
</div>


<div class ="quote">
<input type="button" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_vacacion.php?id_pdeclaracion=<?php echo $_REQUEST['id_pdeclaracion'];?>&periodo=<?php echo $_REQUEST['periodo'];?>','#CapaContenedorFormulario')" class="submit-cancelar" value="Cancelar" name="Retornar ">


    <form action=""
    name ="frmVacacion" id="frmVacacion" onsubmit="" >

        <div class="ocultar private">
            <input name="id_trabajador" id="id_trabajador" 
            value="<?php echo $_REQUEST['id_trabajador'];?>"/>            
            <input name="oper" value="add"/>
        </div>


        <div id="vacacion" >
<!--        <h2>GESTIONAR VACACION</h2>-->
	TRABAJADOR : <span class="red"><?php echo $_REQUEST['name'];?></span>
        <hr>
        <p>
            <span>Agregar otra fechas?</span>
            <input type ="radio" name="addVacacion" value="1" />
            Si
            <input type ="radio" name="addVacacion" value="0" checked/>No

            <input type="button" class="button" value="NEXT" style="cursor: pointer;"
            onclick="createVacacion(this)"/>
        </p><!-- new vehicle -->
        <hr>

        <ul row = 'vacacion-1' class='lista'>
            <h2>1</h2>
            <!--<div class='close'>X</div>-->
            <li>
                <span>Fecha Inicio</span>
                <span>Fecha Fin</span>
                <span>Num Dias</span>                                   
            </li>
            <li>
                <span><input type="text" name="v_fechaInicio_1" id="v_fechaInicio_1" value="" size="10" onkeyup= "return calFechaInico(event,this)"/></span>
                <span><input type="text" name="v_fechFin_1" id="v_fechFin_1" value="" size="10" readonly/></span>
                <span><input type="text" name="v_numDia_1" id="v_numDia_1" value="" size="5" onkeyup="return calcFechaVacacion(event,this)" /></span>                
            </li>

        </ul>


        </div><!--- vacacion -->
        <input type='button' value='Enviar Datos'  onclick="validarFechaVacacion(event,this)" /><!-- onclick='formValid()' -->

    </form>
    <div id='info'>info</div><!-- info -->
</div><!--- quote -->

        
        </div>
</div>

</div>











