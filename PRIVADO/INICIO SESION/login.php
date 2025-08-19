<?php 
session_start(); // Inicia o continúa una sesión. Obligatorio para usar variables $_SESSION

// Parámetros para conectarse a la base de datos
$host = "localhost";      // Dirección del servidor (en este caso, local)
$user = "root";           // Usuario de MySQL (por defecto es root)
$pass = "";               // Contraseña del usuario (vacía si no le pusiste)
$db   = "poe";            // Nombre de la base de datos a la que te quieres conectar

// Crear la conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verificar si hubo error al conectar
if ($conn->connect_error) {
    die("Conexión con el host fallida: " . $conn->connect_error); // Si falla, se detiene y muestra el error
}

// Recibir los datos del formulario (los name del input deben ser "usuario" y "contraseña")
$usuario    = $_POST['usuario'];     // Guarda lo que el usuario escribió en el input "usuario"
$contraseña = $_POST['contraseña'];  // Guarda lo que el usuario escribió en el input "contraseña"

// Consulta con sentencia preparada (seguro contra inyecciones SQL)
$sql = "SELECT * FROM usuarios WHERE usuario = ? AND contraseña = ?"; // La ? se reemplaza luego
$stmt = $conn->prepare($sql); // Prepara la consulta para evitar inyecciones
$stmt->bind_param("ss", $usuario, $contraseña); // Sustituye las ? por los valores, ambos son string ("ss")
$stmt->execute(); // Ejecuta la consulta
$resultado = $stmt->get_result(); // Obtiene el resultado (como si fuera un SELECT normal)

// Verifica si se encontró un usuario con esa contraseña
if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc(); // Obtiene los datos del usuario en un array asociativo

    // Guardamos los datos en la sesión
    $_SESSION['usuario'] = $fila['usuario']; // Guarda el nombre del usuario
    $_SESSION['grado_autorizado'] = $fila['grado_autorizado']; // Guarda el grado que ese usuario puede ver

    // Redirige al panel principal del sistema
    header("Location: ../estudiantes.html");
    exit; // Siempre se pone para evitar que el script siga corriendo después de redirigir

} else {
    // Si no se encontró el usuario o la contraseña no coincide
    echo "Usuario o contraseña incorrectos. Intenta de nuevo.";
    $error = "Usuario O Contraseña Incorrectos";
    header("Location: inicio.html");
    exit;
}
?>
