<?php
/**
 *  FALTA IMPLEMENTAR.. :s
 */
//---------------------------------------------------------------------
//----------------- FUNCIONES Que No Necesitan -----------------//
//-----------------  requiere_once() this -----------------------//
//----------- Normalmente son llamandos de las Vistas --------//
//---------------------------------------------------------------------
/**
 *
 * @param type $id_empleador
 * @return type 
 * Comunme utilizado para obtener el id_empleador_maestro
 */
function buscarIdEmpleadorMaestroPorIDEMPLEADOR($id_empleador){
	
	$dao = new EmpleadorMaestroDao();	
	$data = $dao->buscarIdEmpleadorMaestroPorIDEMPLEADOR($id_empleador);
	
	return $data;
	
}











?>