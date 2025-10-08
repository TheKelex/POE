<?php
session_start();

// --- Seguridad: verificar sesión activa ---
if (!isset($_SESSION['usuario'])) {
    header("Location: ../INICIO SESION/inicio.php");
    exit();
}

// --- Conexión a la base de datos ---
$servidor = "localhost";
$usuario_db = "root";
$contraseña = "";
$basededatos = "poe";

$conexion = mysqli_connect($servidor, $usuario_db, $contraseña, $basededatos);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conexion->set_charset("utf8mb4");

// --- Verificar id del egresado ---
if (!isset($_SESSION["id_egresados"])) {
    echo "No se recibió el estudiante.";
    exit();
}
$id_egresados = $_SESSION["id_egresados"];

// --- Verificar si se envió el formulario ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Obtener los valores enviados
    $biografia_egresados = $_POST["biografia_egresados"] ?? '';
    $edad_egresados = $_POST["edad_egresados"] ?? null;

    try {
        // Preparar la consulta con ambos campos
        $sql = "UPDATE egresados SET biografia_egresados=?, edad_egresados=? WHERE id_egresados=?";
        $st = $conexion->prepare($sql);
        $st->bind_param("sii", $biografia_egresados, $edad_egresados, $id_egresados);
        $st->execute();
        $st->close();

        // Redirigir de nuevo a la página de biografía
        header("Location: biografia.php");
        exit();

    } catch (Throwable $e) {
        http_response_code(500);
        echo "Error al actualizar la biografía: " . $e->getMessage();
        exit();
    }
} else {
    echo "Acceso no permitido.";
    exit();
}
?>