<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Establecer Periodo</a></li>			

        </ul>
        <div id="tabs-1">
<table width="534" border="1">
  <tr>
    <td width="417">&nbsp;</td>
    <td width="101"><label for="anio"></label>
      <select name="anio" id="anio">
        <option value="2011">2011</option>
        <option value="2012">2012</option>
    </select></td>
  </tr>
</table>
<p>Seleccione Mes:
  <label for="mes"></label>
  <select name="mes" id="mes">
    <option value="1">ENERO</option>
    <option value="2">FEBRERO</option>
    <option value="3">MARZO</option>
    <option value="4">ABRIL</option>
    <option value="5">MAYO</option>
    <option value="6">JUNIO</option>
    <option value="7">JULIO</option>
    <option value="8">AGOSTO</option>
    <option value="9">SEPTIEMBRE</option>
    <option value="10">OCTUBRE</option>
    <option value="11">NOVIEMBRE</option>
    <option value="12">DICIEMBRE</option>
  </select>
  <label for="txt_mes"></label>
  <input name="txt_mes" type="text" id="txt_mes" size="5" />
  <input type="button" name="seleccionar" id="seleccionar" value="Seleccionar">
</p>

        
        </div>
</div>

</div>
