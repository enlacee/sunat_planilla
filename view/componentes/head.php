<?php
session_start();
//echo "</h2>"; print_r($_SESSION); echo "</h2>";
//$HOST2 	= "http://".$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI']; 
//$HOST3 	= "http://".$_SERVER['HTTP_HOST'].''; 

if(!$_SESSION['adminweb']){
header("location: login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Untitled Document</title>
