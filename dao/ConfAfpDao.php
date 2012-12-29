<?php

class ConfAfpDao extends AbstractDao {

    //put your code here
    public function registrar($cod_regimen_pensionario, $ap_obligatorio, $comision, $prima_seguro, $fecha_vigencia) {
        $query = "
        INSERT INTO conf_afp
                    (
                    cod_regimen_pensionario,
                    aporte_obligatorio,
                    comision,
                    prima_seguro,
                    fecha,
                    fecha_creacion)
        VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);       
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_regimen_pensionario);
        $stm->bindValue(2, $ap_obligatorio);
        $stm->bindValue(3, $comision);
        $stm->bindValue(4, $prima_seguro);
        $stm->bindValue(5, $fecha_vigencia);
        $stm->bindValue(6, date("Y-m-d"));

        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    public function actualizar($id, $cod_regimen_pensionario, $ap_obligatorio, $comision, $prima_seguro, $fecha_vigencia) {

        $query = "
        UPDATE conf_afp
        SET 
        cod_regimen_pensionario = ?,
        aporte_obligatorio = ?,
        comision = ?,
        prima_seguro = ?,
        fecha = ?
        WHERE id_conf_afp = ?;        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_regimen_pensionario);
        $stm->bindValue(2, $ap_obligatorio);
        $stm->bindValue(3, $comision);
        $stm->bindValue(4, $prima_seguro);
        $stm->bindValue(5, $fecha_vigencia);

        $stm->bindValue(6, $id);
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
    }

    /*    public function eliminar($id) {
      $query = "
      DELETE
      FROM conf_afp
      WHERE id_conf_afp = ?;
      ";

      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id);
      $stm->execute();
      return true;
      } */

    public function listarCount($WHERE) {
        
        $query = "
        SELECT
            COUNT(id_conf_afp) as counteo
        FROM conf_afp AS cafp
        INNER JOIN regimenes_pensionarios AS rp
        ON cafp.cod_regimen_pensionario = rp.cod_regimen_pensionario
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }

    public function listar($WHERE, $start, $limit, $sidx, $sord) {
        $cadena = null;
        if ($sidx == 'cod_regimen_pensionario'):
            $sidx = 'rp.cod_regimen_pensionario';
        endif;

        if (is_null($WHERE)) {
            $cadena = $WHERE;
        } else {

            $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
        }
        //echo($cadena);
        $query = "
        SELECT
            id_conf_afp,
            rp.descripcion_abreviada,
            aporte_obligatorio,
            comision,
            prima_seguro,
            fecha,
            fecha_creacion
        FROM conf_afp AS cafp
        INNER JOIN regimenes_pensionarios AS rp
        ON cafp.cod_regimen_pensionario = rp.cod_regimen_pensionario
        $cadena
";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// AFP -- obtiene los datos de afp del ultimo año ingresado
// siempre 4 afp y anio = 2011,2012    
    public function vigenteAfp($cod_regimen_pensionario, $periodo) {

        $query = "
        SELECT
            id_conf_afp,
            cod_regimen_pensionario,
            aporte_obligatorio,
            comision,
            prima_seguro,
            fecha,
            fecha_creacion
        FROM conf_afp AS cafp
        WHERE cod_regimen_pensionario = ?
        AND fecha <= '$periodo'

        ORDER BY fecha DESC      
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $cod_regimen_pensionario);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0];
    }

}

?>
