<?php 
//session_start();
require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');

require_once ('../../dao/EmpresaCentroCostoDao.php');
require_once ('../../controller/EmpresaCentroCostoController.php');

//Seleccionados
require_once('../../dao/EstablecimientoCentroCostoDao.php');
require_once('../../controller/EstablecimientoCentroCostoController.php');


$id_establecimiento = $_REQUEST['id_establecimiento'];

$data_alterno = listarEstablecimientoCentroCosto($id_establecimiento,'A');
//---------------
$id = array();
for($i=0; $i<count($data_alterno); $i++){
	$id[] = $data_alterno[$i]['id_empresa_centro_costo'];
}
//---------------

//echo "<pre>";
//print_r($id);
//echo "</pre>";

$arreglo = listarEmpresaCentroConsto();
//---------------
    //ID A ELIMINAR
    //$id = array('23', '66', '71', '98', '24', 26);
    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['id_empresa_centro_costo']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }
//---------------


?>

<script type="text/javascript">
    //INICIO HISTORIAL
    $(document).ready(function(){						   
        //demoApp = new Historial();                  
        $( "#tabs").tabs();
		
	});
	
</script>

<div>

<form action="hola.php" method="get" name="formNewEmpresaCentroCosto"
id="formNewEmpresaCentroCosto">

<div class="ocultar">
<input type="text" name="oper" value="add">
<input  type="text" name="id_establecimiento" id="id_establecimiento"
value="<?php echo $id_establecimiento;  ?>" />
</div>


  <table width="350" border="1">

    
<?php
 if(count($arreglo)>0):
 ?>
 
     <tr>
      <td width="34">ID</td>
      <td width="199">Descripcion</td>
      <td width="95">    Estado</td>
    </tr>

 
 <?php
 foreach($arreglo as $data):  //FOR 002 ?>   
    
    <tr>
      <td><input name="id_empresa_centro_costo[]" type="text"  size="4"
      value="<?php  echo $data['id_empresa_centro_costo']; ?>">
      </td>
      
      
      <td><?php echo  $data['descripcion']; ?></td>
      
      
      <td>  
      <!--<input type="checkbox" name="seleccionado[]" 
      value="<?php // echo $data[$i]['id_empresa_centro_costo']; ?>"
      
       >-->
      
      <select name="estado[]" >
        <option value="A">ACTIVO</option>
          <option value="I" selected="selected" >INACTIVO</option>
      </select></td>
    </tr>

    
<?php 
endforeach;

else:

echo "<h1>No existe Nuevos Centros de Costos.</h1>";

endif;
 //FOR 001?>    
    
  
</table>
</form>  
  
</div>