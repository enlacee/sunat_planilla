<?php
$q = '';
if (isset($_GET['term'])) {
    $q = strtolower($_GET['term']);
}
if (!$q) {
    return;
}




 //INICIO CONEXION
/*$conexion = mysql_connect("localhost", "root", "");
mysql_select_db("bizpay", $conexion);*/
$conexion = mysql_connect("localhost", "root", "");
mysql_select_db("db_planilla", $conexion);

//$queEmp = "SELECT * FROM comercios ORDER BY nombre_comercial ASC";
$query = "
SELECT * FROM personas
    WHERE 
        num_documento 
    LIKE '%$q%'
AND id_empleador = '1'
    
ORDER BY num_documento ASC ";

$resEmp = mysql_query($query, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

//$data = array();

if ($totEmp> 0) {
   while ($rowEmp = mysql_fetch_assoc($resEmp)) {
      
       $data[] = array('codigo'=>$rowEmp['id_persona'],
										 
               'nombres'=>$rowEmp['nombres'],
               'apellido_paterno'=>$rowEmp['apellido_paterno'],
               'apellido_materno'=>$rowEmp['apellido_materno'],
			   
               'num_documento'=>$rowEmp['num_documento']);
      
      // $data[] = $rowEmp['nombre'];      

   }
}

//print_r($data);

$items = $data;

 //   print_r ($items);
	$result = array();
foreach ($items as $key =>$value) {  //STRPOST() =>> BUSCADOR DE AGUJAaaa!!!!!!!
   // print_r ("entro  key = $value ");
    //echo $key;
   		//print_r($value);
		if (/*strpos(strtolower($value['nombre']), $q) !== false*/ true){
			$nombre_comercial = ($value['num_documento']=="") ? "" : $value['num_documento']." - ";
			$CADENA = $nombre_comercial.$value['apellido_paterno']." ".$value['apellido_materno']." ".$value['nombres'];
			$CADENA = strtoupper($CADENA);
		array_push($result, array("id"=>$value['codigo'],"label"=>$CADENA, "value" => strip_tags($CADENA)));


		}
		if (count($result) > 5)
		break;
		
}
echo array_to_json($result);

  
function array_to_json( $array ){

    if( !is_array( $array ) ){
        return false;
    }

    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    if( $associative ){

        $construct = array();
        foreach( $array as $key => $value ){

            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.

            // Format the key:
            if( is_numeric($key) ){
                $key = "key_$key";
            }
            $key = "\"".addslashes($key)."\"";

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "\"".addslashes($value)."\"";
            }

            // Add to staging array:
            $construct[] = "$key: $value";
        }

        // Then we collapse the staging array into the JSON form:
        $result = "{ " . implode( ", ", $construct ) . " }";

    } else { // If the array is a vector (not associative):

        $construct = array();
        foreach( $array as $value ){

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "'".addslashes($value)."'";
            }

            // Add to staging array:
            $construct[] = $value;
        }

        // Then we collapse the staging array into the JSON form:
        $result = "[ " . implode( ", ", $construct ) . " ]";
    }

    return $result;
}

?>