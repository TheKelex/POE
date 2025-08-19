<?php 
session_start();         // Inicia o recupera la sesión  
session_destroy();       // Destruye toda la sesión (cierra el acceso)
header("Location: ../index.html"); // Redirige al login
exit();
?>