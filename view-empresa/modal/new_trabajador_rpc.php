<?php
$cod_detalle_concepto = $_REQUEST['cod_detalle_concepto'];
$tipo_concepto = $_REQUEST['tipo_concepto'];
?>
<form action="" method="get" name="nuevo_trabajador_rpc"  id="nuevo_trabajador_rpc" >

<p>
  
  Registre al trabajador
</p>
<div class="ocultar">
  <p>oper
    
    <input name="oper" type="text" id="oper" value="add" size="10" />
    <br />
    cod_detalle_concepto
    <input type="text" name="cod_detalle_concepto" id="cod_detalle_concepto"
     value="<?php echo $cod_detalle_concepto; ?>"  />
    <br />
    tipo_concepto
    <input type="text" name="tipo_concepto" id="tipo_concepto" value="<?php echo $tipo_concepto; ?>" />
  </p>
  <p><br />
  </p>
</div>
<p>DNI
  <input name="num_documento" type="text" id="num_documento" maxlength="8" />
  Tipo Doc
  <select name="tipoDoc" id="tipoDoc">
    <option value="01">DNI</option>
  </select>
  <br />
</p>
</form>