<?php

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		cargarTablaConceptos('config');
		crearDialogoAfectacion();
		
	});
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Mnto Conceptos</a></li>			

        </ul>
        <div id="tabs-1">
        
            <div id="detalle_concepto">
            
                    <table id="list">
                    </table>
                    <div id="pager">
                    </div>
                    
            </div>        
        
        </div><!-- tabs-1 -->
        
        
</div>





<!-- -->
<div id="dialog-form-editarAfectacion" title="Editar Afectacion">
    <div id="editarAfectacion" align="left"></div>
</div>
