<?php include_once '../../dao/usuarioDAO.php';


    
    $usuario=new usuarioDAO();
    
    if (isset($_REQUEST["id_usuario"])){
	$id=$_REQUEST["id_usuario"];
    
	
	$usu=$usuario->buscarUsuarioPorCodigo($id);
	}



?>
<table width="323" border="0" class="crud">
    <tr >
      <td></td>
      <td><input type="hidden" id="txt_IdUsuarioUE" name="txt_IdUsuarioUE" value="<?php echo($usu["id_usuario"]) ?>" disabled="disabled" /></td>
    </tr>
    <tr >
    <td width="120">Usuario</td>
    <td width="193"><input type="text" id="txt_UsuarioUE" name="txt_UsuarioUE" value="<?php echo($usu["nick"]) ?>" ></td>
  </tr>
  <tr>
    <td>Clave</td>
    <td>
      <input type="text" id="txt_ClaveUE" name="txt_ClaveUE" value="<?php echo($usu["clave"]) ?> "></td>
	   <tr>
	   <td>Estados</td>
	<td>
	<select name="sel_EstadosUE" id="sel_EstadosUE"> 
	<option value="I">Intranet</option>  
	<option value="A"  <?php if ($usu["estado"]=="A"){ ?> selected="selected" <?php } ?>>Almacen</option> 
	</select>
    </td>
  </tr>   
  </tr>
</table>
