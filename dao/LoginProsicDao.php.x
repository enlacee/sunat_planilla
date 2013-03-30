<?php

class LoginProsicDao extends AbstractDao {

    public function existe_usuario($email_usuario) {

        $query = "
        SELECT 
        id_usuario 
        FROM prosic_usuario
        WHERE email_usuario = ?
        AND status_usuario = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $email_usuario);
        $stm->bindValue(2, 'A');
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return count($lista);
    }

    public function iniciarSesionUsuario($email, $password, $empresa) {
        $query = "
		SELECT
		prosic_empresa.nombre_empresa
		, prosic_empresa.ruc_empresa
		, prosic_empresa.id_empresa
		, prosic_usuario.nombre_usuario
		, prosic_usuario.password_usuario
		, prosic_usuario.id_tipo_usuario
		, prosic_usuario.status_usuario
		, prosic_usuario.email_usuario
		, prosic_usuario.id_usuario
		FROM
		dbprosic.prosic_usuario
		INNER JOIN dbprosic.prosic_empresa
		ON (prosic_usuario.id_empresa = prosic_empresa.id_empresa)
		WHERE prosic_usuario.email_usuario = ?
		AND prosic_usuario.password_usuario = ?
		AND	prosic_usuario.id_empresa = ?";

		$stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $email);
        $stm->bindValue(2, $password);
		$stm->bindValue(2, $empresa);
		$stm->execute();
		$lista = $stm->fetchAll();
		$stm = null;
		return $lista;
/*
        $rsLogin = $this->Consulta_Mysql($query);

        $rowLogin = mysql_fetch_assoc($rsLogin);

        $this->setIdUsuario($rowLogin['id_usuario']);
        $this->setNombreUsuario($rowLogin['nombre_usuario']);
        $this->setEmailUsuario($rowLogin['email_usuario']);
        $this->setIdEmpresa($rowLogin['id_empresa']);
        $this->setRucEmpresa($rowLogin['ruc_empresa']);
        $this->setNombreEmpresa($rowLogin['nombre_empresa']);
        $this->setTipoUsuario($rowLogin['id_tipo_usuario']);
        $this->setStatusUsuario($rowLogin['status_usuario']);
        $countLogin = mysql_num_rows($rsLogin);
        return $countLogin;
        */
    }


	// NO NECESARIO PARA MODULOS
    public function get_perfil_usuario($id_tipo_usuario) {
        $query = "
	        SELECT 
	        *FROM prosic_acceso_modulo 
	        WHERE prosic_acceso_modulo.id_tipo_usuario = ?";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_tipo_usuario);
		$stm->execute();
		$lista = $stm->fetchAll();
		$stm = null;
		return $lista;

    }    


}