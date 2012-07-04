<?php


function buscarUbigeoReniecPorId($id){

	$dao = new UbigeoReniecDao();
	$data = $dao->buscarUbigeoReniecPorId($id);

	return $data;
}


?>