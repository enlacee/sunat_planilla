<?php

class ComboDao extends AbstractDao {

    /**
     *   -----------------------------------------------------------------------------------------
     * 	FUNCIONES COMBO_BOX
     * 	-----------------------------------------------------------------------------------------
     * */

    /**
     * tabla  tipos_documentos
     *
     */
    public function comboTipoDocumento() {

        $query = "SELECT *FROM tipos_documentos ORDER BY cod_tipo_documento ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * tabla  paises_emisores_documentos
     *
     */
    public function comboPaisEmisorDocumento() {

        $query = "SELECT *FROM paises_emisores_documentos ORDER BY descripcion ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * tabla  telefonos_codigos_nacionales
     *
     */
    public function comboTelefonoCodigoNacional() {

        $query = "
		SELECT 
			cod_telefono_codigo_nacional,
			CONCAT( cod_telefono_codigo_nacional,' : ',descripcion) AS descripcion
		FROM telefonos_codigos_nacionales ORDER BY cod_telefono_codigo_nacional ASC
		";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * tabla  nacionalidades
     *
     */
    public function comboNacionalidades() {

        $query = "SELECT *FROM nacionalidades ORDER BY descripcion ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * tabla  ubigeo_departamentos
     *
     */
    public function comboUbigeoDepartamentos() {

        $query = "SELECT *FROM ubigeo_departamentos ORDER BY descripcion ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * tabla  ubigeo_departamentos
     *
     */
    public function comboUbigeoProvincias($id_departamento=null) { //HERE variable alternativo
	
	if(isset($id_departamento)){
		$query = "SELECT *FROM ubigeo_provincias WHERE cod_provincia LIKE '$id_departamento%';";
	}else{	
		$query = "SELECT *FROM ubigeo_provincias;";
	} 

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $id_departamento);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /**
     * tabla  Distritos
     *
     */
    public function comboUbigeoReniec($id_provincia=null) { //HERE variable alternativo
	
	if(isset($id_provincia)){	
    	$query = "SELECT *FROM ubigeo_reniec WHERE cod_ubigeo_reniec LIKE '$id_provincia%'";
	}else{
		$query = "SELECT *FROM ubigeo_reniec";
	}

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * tabla  estados_civiles
     *
     */
    public function comboEstadosCiviles() {

        $query = "SELECT *FROM estados_civiles ORDER BY id_estado_civil ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * 	Formulario 2 Direccion
     * ---------------------------------------------------------------------------------------------
     * */
    public function comboVias() {
        $query = "SELECT *FROM vias ORDER BY cod_via ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    public function comboZonas() {
        $query = "SELECT *FROM zonas ORDER BY cod_zona ASC";

        $stm = $this->pdo->prepare($query);
        //$stm->bindValue(1, $nombre);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }


    /**
     * ---------------------------------------------------------------------------------------------
     * 	Formulario Combo box Derechohabientes
     * ---------------------------------------------------------------------------------------------
     * */


public function comboVinculoFamiliar(){
        $query = "SELECT *FROM vinculos_familiares ORDER BY cod_vinculo_familiar";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
}


//DUPLICADO CON PARAMETRO cod_vinculo_familiar ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
public function comboDocumentoVinculoFamiliar($cod_vinculo_familiar){
        $query = "
        SELECT
        -- vf.cod_vinculo_familiar,
        -- vf.descripcion as nombre_vfamiliar,
        dvf.cod_documento_vinculo_familiar,
        dvf.descripcion AS nombre_doc_vinculos_familiares

        FROM vinculos_familiares AS vf
        INNER JOIN v_familiares_d_v_familiares AS vf_dvf
        ON vf.cod_vinculo_familiar = vf_dvf.cod_vinculo_familiar

        INNER JOIN documentos_vinculos_familiares AS dvf
        ON vf_dvf.cod_documento_vinculo_familiar = dvf.cod_documento_vinculo_familiar

        WHERE vf.cod_vinculo_familiar= ? ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_vinculo_familiar);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;

}


/**
* TIPOS ESTABLECIMIENTOS
*
*/


public function comboEstablecimiento(){
        $query = "SELECT *FROM tipos_establecimientos ORDER BY id_tipo_establecimiento ASC";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
}










}//End Class
?>