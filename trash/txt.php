
<?php
/** Description
   * padclear, clear a pad varchar(9) "*****HOME" -> varchar(4) "HOME"
   * _no use for INT_ : varchar(5) "00100" -> int(3) "100", use $number = (int) "00100";
   *
   * @param string, string original
   * @param char, character to replace
   * @param charreplace, final character
   * @param type, [STR_PAD_RIGHT|STR_PAD_LEFT] see str_pad
   * @return string, transformed string with charreplace
   */
  function padclear($string, $char, $charreplace = null,$type = STR_PAD_RIGHT) {

      $strlen = strlen($string) - 1;
      $i = ($type == STR_PAD_RIGHT) ? 0 : $strlen;
      $stop = ($type == STR_PAD_RIGHT) ? $strlen : 0;
      $difpos = null;
      while(is_null($difpos) && $i != $stop) {
        $difpos = ($string[$i] != $char) ? $i : null;
        if($type == STR_PAD_RIGHT) $i++;
        else $i--;
      }

      if($type == STR_PAD_RIGHT) {
        $ant = str_replace($char,$charreplace,substr($string,0,$difpos));
         $nxt = substr($string,$difpos,$strlen);
      } else {
        $ant = substr($string,0,$difpos);
        $nxt = str_replace($char,$charreplace,substr($string,$difpos,$strlen));
      }

      return $ant . $nxt;
  }

  $string = "HOME*******";
  echo padclear($string,'*','',STR_PAD_LEFT);  // HOME
  //$string = "*****HOME";
  //echo padclear($string,'*','',STR_PAD_RIGHT); // HOME
?>
