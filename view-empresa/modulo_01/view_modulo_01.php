<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		cargarTablaModulo_01();	
		
		
		
		jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false});
		
	
jQuery("#list").jqGrid('navButtonAdd','#pager',{caption:"Edit",
	onClickButton:function(){
		var gsr = jQuery("#list").jqGrid('getGridParam','selrow');
		console.log(gsr);
		/*if(gsr){
			jQuery("#list").jqGrid('GridToForm',gsr,"#order");
		} else {
			alert("Please select Row")
		}	*/						
	} 
});

		
		
		
	});
	
	
	
	
	
	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Editar Empleador</a></li>			

        </ul>
        <div id="tabs-1">


            <table id="list">
            </table>
            <div id="pager"></div>

        
        </div>
</div>