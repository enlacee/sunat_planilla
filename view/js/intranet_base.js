//document

function Historial() {
	// some application vars
	var stateVar = "nothin'", displayDiv = document.getElementById("content-ajax");

	// ---------- EDICION |1|---------//
	this.seleccionarNew = function(elemento) {

		var id = elemento.attributes.rel.value;
	
		unFocus.History.addHistory(id);
	};
	
	

	this.historyListener = function(historyHash) {
		// update the stateVar
		stateVar = historyHash;
		
		// update display content
		
		// ---------- EDICION |2|---------//---- JQGRID ------//
		
		html = '<table id="list"><tr><td/></tr></table>	<div id="pager"></div>';
/*		html += '<div id="botones_grid" style="margin:20px auto" ><a href="javascript:void(0)" id="bsdata" > Filtrar Busqueda de '+ historyHash +' </a> <br /><br /><a href="javascript:void(0)" id="bndata" > Agregar '+ historyHash +' </a> <br /><br /><a href="javascript:void(0)" id="bedata" > Editar '+ historyHash +' </a> <br /><br /><a href="javascript:void(0)" id="bddata" > Eliminar '+ historyHash +' </a> <br /><br /></div>';*/
//		html += "<br/><br/><br/><br/>";		
//		html += '<table id="list10_d"><tr><td/></tr></table>	<div id="pager10_d"></div>';
//		html += "<br/><br/><br/><br/>";	
//		html += '<table id="list10_com"><tr><td/></tr></table>	<div id="pager10_com"></div>';

		displayDiv.innerHTML = html+" Current History: " + historyHash;
		
		// update document title
		document.title = "Piso 10 .::. " + historyHash;
		
		
		// ---------- EDICION |3|---------//
		
		if( historyHash == "Producto"){
			viewTerrenos();
			//cargarTablaKits();			
			
		}else if(historyHash == "Articulo"){
                    
             cargarTablaProductos();
                    
        }else if( historyHash == "Proveedores"){
			
			cargarTablaProveedores();
			
		}else{
			
            displayDiv.innerHTML = "Configurar intranet_base.js " + historyHash;
			
		}
		
		
	};
	// subscribe to unFocus.History
	unFocus.History.addEventListener('historyChange', this.historyListener);
	
	// Check for an initial value (deep link).
	// In this demo app, the historyListener can handle the task.
	this.historyListener(unFocus.History.getCurrent());
};
