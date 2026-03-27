<?php 

session_start();

session_destroy();  

header("Location: ../Auth/login.php");
exit(); 

?>