<?php

class UbigeoReniecDao extends AbstractDao {


    public function buscarUbigeoReniecPorId($id) {

        $query = "SELECT *FROM ubigeo_reniec WHERE cod_ubigeo_reniec = ?";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1,$id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];

    }
	
	
	

}

//End Class
?>