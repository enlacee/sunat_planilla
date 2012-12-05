<?php

class PrestamoDao extends AbstractDao {

    public function add($obj) {

        $model = new Prestamo();
        $model = $obj;
        $query = "
        INSERT INTO prestamos
                    (
                    id_empleador,
                    id_trabajador,
                    valor,
                    num_cuota,
                    fecha_inicio,
                    estado,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);       
        ";
        $this->pdo->beginTransaction();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_empleador());
        $stm->bindValue(2, $model->getId_trabajador());
        $stm->bindValue(3, $model->getValor());
        $stm->bindValue(4, $model->getNum_cuota());
        $stm->bindValue(5, $model->getFecha_inicio());
        $stm->bindValue(6, $model->getEstado());
        $stm->bindValue(7, $model->getFecha_creacion());
        $stm->execute();
        // id Comerico
        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();
        $this->pdo->commit();
        //finaliza transaccion
        //return true;
        $stm = null;
        return $lista[0]['id'];

    }

    public function edit($obj) {
        $model = new Prestamo();
        $model = $obj;
        $query = "
        UPDATE prestamos
        SET 
        valor = ?,
        num_cuota = ?,
        fecha_inicio = ?,
        estado = ?
        WHERE id_prestamo = ?;    
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getValor());
        $stm->bindValue(2, $model->getNum_cuota());
        $stm->bindValue(3, $model->getFecha_inicio());
        $stm->bindValue(4, $model->getEstado());
        $stm->bindValue(5, $model->getId_prestamo());

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function del($id) {

        $query = "
        DELETE
        FROM prestamos
        WHERE id_prestamo = ?;             
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    
    public function baja($id) {
        $query = "
        UPDATE prestamos
        SET 
          estado = 0
        WHERE id_prestamo = ?;
            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }
        public function alta($id) {
        $query = "
        UPDATE prestamos
        SET 
          estado = 1
        WHERE id_prestamo = ?;
            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }
    
    
    public function buscar_id($id) {

        $query = "
        SELECT  
        id_trabajador,
        valor,
        cuota_pago,
        fecha_inicio,
        estado
        FROM prestamos
        WHERE id_prestamo = ?
            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

    // UTILIZADO EN trabajadorPdeclaracionController.php
    public function buscar_idTrabajador($id_trabajador, $periodo) {

        $query = "
        SELECT
          pre.id_prestamo,
          pc.id_prestamo_cutoa,
          -- pre.valor,
          -- pre.num_cuota,
          -- pre.fecha_inicio,
          -- pre.estado,
	  -- prestamo cuota
          pc.fecha_calc,
          pc.monto,
          pc.monto_variable,
          pc.cubodin,
          pc.fecha_calc
          
        FROM prestamos AS pre	
	LEFT JOIN prestamos_cuotas AS pc
	ON pre.id_prestamo = pc.id_prestamo        
        
        WHERE pre.id_trabajador = ?
        AND pc.fecha_calc = ?
        -- AND pre.estado = 1 -- PRESTAMOS ACTIVOS ELIMINAR ESTE ATRIBUTO PARA FILTRO
        -- AND fecha_inicio <='2012-10-01'
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $periodo);        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    // Utilizado para reporte mensual !!!!! == funcion gemela
    public function getPagoCuotaPorPeriodo_Reporte($id_pdeclaracion,$id_trabajador){
        
        // Sobreentedido que hay un prestamo por periodo =pdeclaracion
        // error si ah futuro existiera varios prestamos en el mismo periodo.
        $query = "
        SELECT
        p.valor,
        pp.valor AS pago_cuota
        FROM prestamos AS p
        INNER JOIN ppagos AS pp
        ON p.id_prestamo = pp.id_prestamo
        WHERE p.id_trabajador =?
        AND pp.id_pdeclaracion =?    
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['pago_cuota'];      
        
    }
    
    
    // LISTA PRESTAMOS ACTIVOS = GRID
    public function listarCount($id_empleador, $WHERE) {
        $query = "
        SELECT
        count(*) as counteo
        FROM prestamos        
        WHERE id_empleador = ?
        -- AND estado = 1
        $WHERE
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }

    // LISTA PRESTAMOS ACTIVOS = GRID  // ALL activos y inactivos
    public function listar($id_empleador, $WHERE, $start = null, $limit = null, $sidx = null, $sord = null) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
        SELECT
        pre.id_prestamo,
        pre.id_empleador,
        pre.id_trabajador,
        pre.valor,
        pre.num_cuota,
        pre.estado,
        pre.fecha_inicio,
        p.cod_tipo_documento,
	p.num_documento,
	p.apellido_paterno,
	p.apellido_materno,
	p.nombres	
        
        FROM prestamos AS pre
        INNER JOIN trabajadores AS t
	ON pre.id_trabajador = t.id_trabajador
	
	INNER JOIN personas AS p
	ON p.id_persona = t.id_persona
        WHERE pre.id_empleador = ?
        -- AND pre.estado = 1   
        -- prestamo activo         
        $cadena
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    // lista de prestamos por Prestamos count
    function listarPrestamoPeriodoCount($id_empleador,$periodo, $WHERE){
        $query = "
        SELECT
	COUNT(pre.id_prestamo) AS counteo
        
        FROM prestamos AS pre	
	LEFT JOIN prestamos_cuotas AS pc
	ON pre.id_prestamo = pc.id_prestamo
	
	WHERE pre.id_empleador = ?
	AND pc.fecha_calc = ?
        $WHERE
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];    
        
    }
    
    // lista de prestamos por Prestamos
    function listarPrestamoPeriodo($id_empleador, $periodo,$WHERE, $start = null, $limit = null, $sidx = null, $sord = null){
        
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
        SELECT
        pre.id_prestamo,
        pre.id_trabajador,
        pre.valor,
        pre.num_cuota,
        pre.estado,
        pre.fecha_inicio,
        p.cod_tipo_documento,
	p.num_documento,
	p.apellido_paterno,
	p.apellido_materno,
	p.nombres	
	,pc.fecha_calc
        
        FROM prestamos AS pre	
	LEFT JOIN prestamos_cuotas AS pc
	ON pre.id_prestamo = pc.id_prestamo
	
	-- data
	INNER JOIN trabajadores AS t
	ON pre.id_trabajador = t.id_trabajador
	
	INNER JOIN personas AS p
	ON p.id_persona = t.id_persona	
	-- data
	
	WHERE pre.id_empleador = ?
	AND pc.fecha_calc = ?      
        $cadena
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;

        
    } 
    
    
    
    
    
    //------ INICIO  
    // lista para seleccionar Trabajadores
    public function listarTrabajadorCount($id_empleador_maestro, $WHERE) {
        $query = "
    SELECT 
    count(*) as  counteo
        
    FROM personas AS p        
    INNER JOIN tipos_documentos AS td
    ON p.cod_tipo_documento = td.cod_tipo_documento

    INNER JOIN empleadores_maestros AS em
    ON p.id_empleador = em.id_empleador

    INNER JOIN trabajadores AS t
    ON p.id_persona = t.id_persona

    INNER JOIN situaciones AS s
    ON t.cod_situacion = s.cod_situacion

    WHERE em.id_empleador_maestro = ?
    AND t.cod_situacion = 1        
    $WHERE
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }

    // lista para seleccionar Trabajadores
    public function listarTrabajador($id_empleador_maestro, $WHERE = null, $start = null, $limit = null, $sidx = null, $sord = null) {
        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
    SELECT 
    p.id_persona,
    t.id_trabajador,
    p.cod_tipo_documento,
    td.descripcion_abreviada AS nombre_tipo_documento,
    p.num_documento,
    p.apellido_paterno,
    p.apellido_materno,
    p.nombres
        
    FROM personas AS p        
    INNER JOIN tipos_documentos AS td
    ON p.cod_tipo_documento = td.cod_tipo_documento

    INNER JOIN empleadores_maestros AS em
    ON p.id_empleador = em.id_empleador

    INNER JOIN trabajadores AS t
    ON p.id_persona = t.id_persona

    INNER JOIN situaciones AS s
    ON t.cod_situacion = s.cod_situacion

    WHERE em.id_empleador_maestro = ?
    AND t.cod_situacion = 1
        
    $cadena
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    //------ FINAL    
    //ojo lista de trabajadores con Prestamos Activos!
    public function listarTrabajador_PrestamoActivo($id_empleador) {

        $query = "
        SELECT
        pre.id_prestamo,
        pre.id_empleador,
        pre.id_trabajador	
        
        FROM prestamos AS pre
        INNER JOIN trabajadores AS t
	ON pre.id_trabajador = t.id_trabajador
	
	INNER JOIN personas AS p
	ON p.id_persona = t.id_persona
        WHERE pre.id_empleador = ?
        
        AND pre.estado = 1
        AND t.cod_situacion=1
        ";
        
        // controlado por el trabajdor 
        // no aparecera si ya fue dado de baja!.
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function buscar_data_id($id_prestamo) {
        $query = "
        SELECT
        pre.id_prestamo,
        pre.id_empleador,
        pre.id_trabajador,
        pre.valor,
        pre.num_cuota,
        pre.estado,
        pre.fecha_inicio,
        p.cod_tipo_documento,
        p.num_documento,
        p.apellido_paterno,
        p.apellido_materno,
        p.nombres
        
        FROM prestamos AS pre
        
        INNER JOIN trabajadores AS t
	ON pre.id_trabajador = t.id_trabajador
	
	INNER JOIN personas AS p
	ON p.id_persona = t.id_persona
        WHERE pre.id_prestamo = ?         
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_prestamo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

?>
