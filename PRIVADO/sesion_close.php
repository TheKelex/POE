<?php 
session_start(); 
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}        // Inicia o recupera la sesión  
session_destroy();       // Destruye toda la sesión (cierra el acceso)
header("Location: ../index.php"); // Redirige al login
exit();
?>