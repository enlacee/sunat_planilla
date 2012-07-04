<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");


function buscarEstablecimientoDireccionPorId($id){

	$obj = new EstablecimientoDireccion;
	$dao = new EstablecimientoDireccionDao();
	$data = $dao->buscarEstablecimientoDireccionPorId($id);
	$obj->setId_establecimiento_direccion($data['id_establecimiento_direccion']);

	$obj->setId_establecimiento($data['id_establecimiento']);
	$obj->setCod_ubigeo_reinec($data['cod_ubigeo_reniec']);
	$obj->setCod_via($data['cod_via']);
	$obj->setNombre_via($data['nombre_via']);
	$obj->setNumero_via($data['numero_via']);
	$obj->setDepartamento($data['departamento']);
	$obj->setInterior($data['interior']);
	$obj->setManzanza($data['manzana']);
	$obj->setLote($data['lote']);
	$obj->setKilometro($data['etapa']);
	$obj->setBlock($data['block']);
	$obj->setEstapa($data['etapa']);
	$obj->setCod_zona($data['cod_zona']);
	$obj->setNombre_zona($data['nombre_zona']);
	$obj->setReferencia($data['referencia']);
	$obj->setReferente_essalud($data['referente_essalud']);
	//$obj->setEstado_direccion($data['estado_direccion']);
	
	
	//print_r($obj);
        
	return $obj;
}












?>