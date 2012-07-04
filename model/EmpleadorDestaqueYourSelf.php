<?php

class EmpleadorDestaqueYourSelf {

    private $id_empleador_destaque_yourselft;
    protected $id_empleador;
    protected $id_empleador_maestro;
    protected $estado;

    function __construct() {
        $this->id_empleador_destaque_yourselft = null;
        $this->id_empleador = null;
        $this->id_empleador_maestro = null;
        $this->estado = null;
    }

    public function getId_empleador_destaque_yourselft() {
        return $this->id_empleador_destaque_yourselft;
    }

    public function setId_empleador_destaque_yourselft($id_empleador_destaque_yourselft) {
        $this->id_empleador_destaque_yourselft = $id_empleador_destaque_yourselft;
    }

    public function getId_empleador() {
        return $this->id_empleador;
    }

    public function setId_empleador($id_empleador) {
        $this->id_empleador = $id_empleador;
    }

    public function getId_empleador_maestro() {
        return $this->id_empleador_maestro;
    }

    public function setId_empleador_maestro($id_empleador_maestro) {
        $this->id_empleador_maestro = $id_empleador_maestro;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

}

?>
