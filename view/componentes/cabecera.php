
            <!--  mennu-->
            <div class="menu-principal">
                <div class="clear"></div>

<?php if ( $_SESSION['adminweb'] ) { ?>
				
				<ul>
					<li><a href="../controller/usuarioWebController.php?op=03">Salir</a></li>
                    <li><a href="#"><?php echo $_SESSION["adminweb"]; ?></a></li>
                    
                </ul>
				
<?php } else {?>

                <ul>
                    <li><a href="#">login</a></li>
                    <li><a href="#">registro</a></li>
                </ul>

<?php }?>	
			
                <div class="clear"></div>
            </div>
            
            
<!--cabecera-->
<div id="cabecera">
	<div id="logo"><a href="index.php"><img src="img/logo.gif" alt="logo"  /></a></div>
                
                
    <div id="" class="menu">
    
    <div class="menu-principal2"> 

    </div>
    
        <ul>
        <li><a rel="usuarios" 		onclick="demoApp.seleccionarNew(this);" >Usuarios</a></li>
        <li>
        
    <a rel="inmuebles" 		onclick="demoApp.seleccionarNew(this);" >Inmuebles</a>
    <select name="cbo_inmueble">
      <option value="0" selected="selected">todos</option>
      <option value="1">casa</option>
      <option value="2">terreno</option>
      <option value="3">departamento</option>
      <option value="4">local</option>
    </select>        
        </li>
        <li><a rel="Producto" 		onclick="demoApp.seleccionarNew(this);"	>Producto</a></li>
    </ul>
    
    
    
    
    </div>                
                
<div class="clear"></div>
</div>
