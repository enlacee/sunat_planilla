<?php
class TipoSuspensionRelacionLaboral {
    //put your code here
    
    private $cod_tipo_suspen_relacion_laboral;
    private $descripcion;
    private $descripcion_abreviada;
    
    
    public function __construct(){
        
     $this->cod_tipo_suspen_relacion_laboral=null;
     $this->descripcion=null;
     $this->descripcion_abreviada=null;
    }
    
    public function getCod_tipo_suspen_relacion_laboral() {
        return $this->cod_tipo_suspen_relacion_laboral;
    }

    public function setCod_tipo_suspen_relacion_laboral($cod_tipo_suspen_relacion_laboral) {
        $this->cod_tipo_suspen_relacion_laboral = $cod_tipo_suspen_relacion_laboral;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getDescripcion_abreviada() {
        return $this->descripcion_abreviada;
    }

    public function setDescripcion_abreviada($descripcion_abreviada) {
        $this->descripcion_abreviada = $descripcion_abreviada;
    }


    
}

?>
