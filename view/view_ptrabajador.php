<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
estadoCheckTrabajadorEstado();	
//---------------------- FUNCIONES ----------------------------	
	
	//funcion chekkk
function estadoCheckTrabajadorEstado(){
	
	var obj = document.getElementById('chk_historial_trabajador');

	if(obj.checked == true){
		cargarTablaTrabajadorServicio(0)
	}else{		
		cargarTablaTrabajadorServicio(1)
	}

}
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">
            Registro de Trabajadores, Pensionistas y Otros Prestadores de Servicios
            </a></li>			

        </ul>
        <div id="tabs-1">
        
          <div class="">
            <input type="checkbox" name="chk_historial_trabajador" id="chk_historial_trabajador"  onclick="estadoCheckTrabajadorEstado()"/>
            Mostrar Histórico de Bajas
          </div>
          <br />
            <table id="list">
            </table>
            <div id="pager"></div>
            
                      
        
        </div>
</div>

</div>

