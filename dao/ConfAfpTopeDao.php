<?php

class ConfAfpTopeDao extends AbstractDao{

    //put your code here
    public function add($valor, $fecha_vigencia) {
        $query = "
        INSERT INTO conf_afp_tope
                    (
                    valor,
                    fecha,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?);
";      
        //$pdo = MyPDO::getInstancia();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $fecha_vigencia);
        $stm->bindValue(3, date("Y-m-d"));
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function edit($id, $valor, $fecha_vigencia) {

        $query = "
        UPDATE conf_afp_tope
        SET 
        valor = ?,
        fecha = ?  
        WHERE id_conf_afp_tope = ?;     
";
        //$pdo = MyPDO::getInstancia();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $valor);
        $stm->bindValue(2, $fecha_vigencia);
        $stm->bindValue(3, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function eliminar($id) {
        $query = "
        DELETE
        FROM conf_afp_tope
        WHERE id_conf_afp_tope = ?; 
        ";
        //$pdo = MyPDO::getInstancia();
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id);
        $stm->execute();
        return true;
    }

    public function listarCounteo($WHERE) {
        $query = "
        SELECT
            Count(*) as counteo
        FROM conf_afp_tope
        $WHERE
";      
        //$pdo = MyPDO::getInstancia();
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];   
        
    }

    public function listar($WHERE, $start=null, $limit=null, $sidx=null, $sord=null) {

        $cadena = null;
        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {
            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }

        $query = "
        SELECT
            id_conf_afp_tope,
            valor,
            fecha,
            fecha_creacion
        FROM conf_afp_tope
        $cadena
";      
        //$pdo = MyPDO::getInstancia();
        //$stm = $pdo->prepare($query);
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    public function vigenteAux($periodo) {
        $query = "
        SELECT            
            valor      
        FROM conf_afp_tope
        WHERE fecha <='$periodo'
        ORDER BY fecha DESC     
";      
        //$pdo = MyPDO::getInstancia();
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['valor'];
    }

}

?>
