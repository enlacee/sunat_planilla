// JavaScript Document

function validarPtrabajadores(){
	
	var data = $("#formPtrabajador").serialize();
	
	$.ajax({
   type: "POST",
   url: "sunat_planilla/controller/PlameTrabajadorController.php?"+data,
   data: {oper : 'edit'},
   async:true,
   success: function(datos){
    $('#editarDiaNoLaborado').html(datos);
    
    $('#dialog-dia-noLaborado').dialog('open');
   }
   }); 

	
	
}