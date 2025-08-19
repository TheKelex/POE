<?php
session_start();
// Creamos la consulta SQL
$sql = "SELECT * FROM dato_estudiantes"; // Trae todos los campos y registros

// Ejecutamos la consulta
$resultado = $conn->query($sql); // $resultado es un objeto con la info de la consulta

?>