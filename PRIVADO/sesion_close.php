<?php 
session_start();         // Inicia o recupera la sesión  
session_destroy();       // Destruye toda la sesión (cierra el acceso)
header("Location: ../index.php"); // Redirige al login
exit();
?>