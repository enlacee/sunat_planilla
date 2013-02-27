<?php
class Vacacion {
    
    private $id_vacacion;
    private $id_trabajador;
    private $id_pdeclaracion;
    
    public function getId_vacacion() {
        return $this->id_vacacion;
    }

    public function setId_vacacion($id_vacacion) {
        $this->id_vacacion = $id_vacacion;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
    }



    
}
?>
