<?php
session_start();
if(!$_SESSION['adminweb']){
header("location: ../index.php");
}	
?>