<?php
require_once('../util/funciones.php');
?>
<script type="text/javascript" src="sunat_planilla/view/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    console.log("LOAD");
	$( "#tabs").tabs();
	 
function eliminarElemento(obj){
var parrafo = obj; //document.getElementById("dos");
parrafo.parentNode.removeChild(parrafo);
}

    //--- Eliminar Elemento
$( ".quote .close" ).live( "click", function( e ) {
      console.log('x');
      var $elemento = $(this).parent();
      console.log($elemento);
      $elemento.remove();								   
});



/*
    $("body").delegate(".close", "click", function() {
      console.log('x');
      var $elemento = $(this).parent();
      $elemento.css('color','red');
      console.log($elemento);
      $elemento.remove();

    });*/

    $("#v_fechaInicio_1").mask("99/99/9999");
    $("#v_fechFin_1").mask("99/99/9999");

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
        li1 += '        <span class="mini_1"><input type="text" name="id_vacacion_'+count+'" id="id_vacacion_'+count+'" size="1"/></span>';
        li1 += '        <span><input type="text" name="v_fechaInicio_'+count+'" id="v_fechaInicio_'+count+'" value="" size="10"/></span>';
        li1 += '        <span><input type="text" name="v_fechFin_'+count+'" id="v_fechFin_'+count+'" value="" size="10" readonly/></span>';
        li1 += '        <span><input type="text" name="v_numDia_'+count+'" id="v_numDia_'+count+'" value="" size="5" /></span>';
        li1 += '        <span><input type="checkbox" name="v_estado_'+count+'" id="v_estado_'+count+'" value="1"/></span>';
        li1 += '    </li>';
    // final de html
    var html ='';
    html += close;
    html += li1;

    $vacacion.append(ul); //PRINCIPAL
    ul.innerHTML=html;
    /// Help Library Jquery
    $("#v_fechaInicio_"+count).mask("99/99/9999");
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
//--------------------------------------------------
//Eliminar Pago Mensual
function enviarDataVacacion(){ // Pago Quincena

        var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
        var periodo = document.getElementById('periodo').value; 
        $.ajax({
           type: "POST",
           dataType: 'json',
           url: "sunat_planilla/controller/TrabajadorPdeclaracionController.php",
           data: {id_pdeclaracion : id_pdeclaracion, oper : '', periodo : periodo },
           async:true,
           success: function(data){                      
                        
           },
            beforeSend:function(){ $('#tabs-1').html("<p class='loading'></p>"); },        
            timeout:4000,
            error:function(){ alert('error');}
   }); 

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
    width: 50px !important;
}
/* base */
.quote {
padding: 0;
position: relative;
width: 500px;
}
.quote ul li{
    color: #444;
    margin-bottom: 10px;
}
.quote ul li span{
    display: inline-block;
    text-align: center;
    width: 20%;
}
</style>
<div class="demo" align="left">

<div class="ocultar">
id_pdeclaracion
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
value="<?php echo $_REQUEST['id_pdeclaracion']; ?>"/><br />
periodo
<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo'];?>" />
</div>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Asignar vacacion</a></li>			

        </ul>
        <div id="tabs-1">


<div class ="quote">
<input type="button" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_pvacaciones_all.php?id_pdeclaracion=<?php echo $_REQUEST['id_pdeclaracion'];?>&periodo=<?php echo $_REQUEST['periodo'];?>','#CapaContenedorFormulario')" class="submit-cancelar" value="Cancelar" name="Retornar ">


    <form
    action="procesar.php"
    name ="frmVacacion" id="frmVacacion"onsubmit="return formValid()" ><!--  -->

        <div class="ocultar private">
            <input name="id_trabajador" id="id_trabajador" 
            value="<?php echo $_REQUEST['id_trabajador'];?>"/>
            <input name="oper" value="oper"/>
        </div>


        <div id="vacacion" >
<!--        <h2>GESTIONAR VACACION</h2>-->
	TRABAJADOR : <span class="red"><?php echo $_REQUEST['name'];?></span>
        <hr>
        <p>
            <span>Agregar otra fechas?</span>
            <input type ="radio" name="addVacacion" value="1" checked />
            Si
            <input type ="radio" name="addVacacion" value="0" />No

            <input type="button" class="button" value="NEXT" style="cursor: pointer;"
            onclick="createVacacion(this)"/>
        </p><!-- new vehicle -->
        <hr>

        <ul row = 'vacacion-1' class='lista'>
            <h2>1</h2>
            <!--<div class='close'>X</div>-->
            <li>
                <span class="mini_1">id</span>
                <span>Fecha Inicio</span>
                <span>Fecha Fin</span>
                <span>Num Dias</span>
                <span>Estado</span>                    
            </li>
            <li>
                <span class="mini_1"><input type="text" name="id_vacacion_1" id="id_vacacion_1" size="1"/></span>
                <span><input type="text" name="v_fechaInicio_1" id="v_fechaInicio_1" value="" size="10"/></span>
                <span><input type="text" name="v_fechFin_1" id="v_fechFin_1" value="" size="10" readonly/></span>
                <span><input type="text" name="v_numDia_1" id="v_numDia_1" value="" size="5" /></span>
                <span><input type="checkbox" name="v_estado_1" id="v_estado_1" value="1"/></span>
            </li>

        </ul>


        </div><!--- vacacion -->
        <input type='submit' value='Enviar Datos'  /><!-- onclick='formValid()' -->

    </form>
    <div id='info'>info</div><!-- info -->
</div><!--- quote -->

        
        </div>
</div>

</div>











