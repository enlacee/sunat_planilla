<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Lista de Conceptos Y formulas:</a></li>			

        </ul>
        <div id="tabs-1">         
          <h2>Ingresos</h2>
          <table width="550" border="1">
            <tr>
              <td>Codigo</td>
              <td>Concepto</td>
              <td>&nbsp;</td>
              <td><input type="checkbox" name="checkbox13" id="checkbox13" /></td>
            </tr>
            <tr>
              <td>0107</td>
              <td><p>TRABAJO EN FERIADO O DIA DESCANSO<br />
              Ingreso = (SB + AF)<br />
              = ( (Ingreso / numdiaMES ) * NDAY_F ) * 2</p></td>
              <td><input name="textfield11" type="text" id="textfield16" /></td>
              <td><input type="checkbox" name="checkbox" id="checkbox" />
              <label for="checkbox"></label></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="checkbox" name="checkbox2" id="checkbox2" /></td>
            </tr>
            <tr>
              <td>0121</td>
              <td>Remuneracion o Jornal Basico</td>
              <td><input name="textfield" type="text" id="textfield" value="SB" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox3" id="checkbox3" /></td>
            </tr>
            <tr>
              <td>0121</td>
              <td>Asignacion Familiar</td>
              <td><input name="textfield2" type="text" id="textfield2" value="SB * T_AF" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox4" id="checkbox4" /></td>
            </tr>
            <tr>
              <td>0401</td>
              <td>GRATIFICACION DE FIESTAS PATRIAS, NAV</td>
              <td><input type="text" name="textfield10" id="textfield10"></td>
              <td><input type="checkbox" name="checkbox5" id="checkbox5" /></td>
            </tr>
          </table>
          <h2>Descuentos</h2>
          <table width="550" border="1">
            <tr>
              <td>0701</td>
              <td>ADELANTO</td>
              <td><input name="textfield3" type="text" id="textfield3" value="SUM(15 *2)" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox14" id="checkbox14" /></td>
            </tr>
            <tr>
              <td>0706</td>
              <td>OTROS DESC. NO DEDUC. DE LA BASE IMPONIB</td>
              <td><input name="textfield4" type="text" id="textfield4" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox6" id="checkbox6" /></td>
            </tr>
          </table>
          <h2>Tributos y Aportaciones 'Trabajador'</h2>
          <table width="550" border="1">
            <tr>
              <td>0604</td>
              <td>ESSALUD+VIDA (<strong>SI O NO PARA GENERAR</strong>)</td>
              <td><input name="textfield5" type="text" id="textfield5" value="ESSALUD+" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox8" id="checkbox8" /></td>
            </tr>
            <tr>
              <td>0605</td>
              <td>RENTA DE QUINTA CATEGORIA RETENCIONES</td>
              <td><input name="textfield6" type="text" id="textfield6" value="SB * (X)" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox7" id="checkbox7" /></td>
            </tr>
            <tr>
              <td>0607</td>
              <td>SISTEMA NAC. DE PENSIONES DL 19990</td>
              <td><input name="textfield9" type="text" id="textfield9" value="SB * T_ONP" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox9" id="checkbox9" /></td>
            </tr>
            <tr>
              <td>06012</td>
              <td>SNP-ASEGUTA TU PENSION</td>
              <td><input name="textfield8" type="text" id="textfield8" value="SNP+" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox10" id="checkbox10" /></td>
            </tr>
          </table>
          <h2>Tributos y Aportaciones 'Trabajador'</h2>
          <table width="550" border="1">
            <tr>
              <td>0804</td>
              <td>ESSALUDSSSSSSSSSSSSSSSSSSSSSSSSS</td>
              <td><input name="textfield7" type="text" id="textfield7" value="SB * T_ ESSALUD" readonly="readonly"></td>
              <td><input type="checkbox" name="checkbox11" id="checkbox11" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="checkbox" name="checkbox12" id="checkbox12" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </div>
</div>

</div>