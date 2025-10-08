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

    // Obtener los valores enviados (usa el mismo nombre que tus inputs)
    $nom_egresados = $_POST["nom_egresados"] ?? '';
    $fechanac_egresados = $_POST["fechanac_egresados"] ?? '';
    $tip_doc_egresados = $_POST["tip_doc_egresados"] ?? '';
    $num_doc_egresados = $_POST["num_doc_egresados"] ?? '';
    $gruposanguineo_egresados = $_POST["gruposanguineo_egresados"] ?? '';
    $especialidad_egresados = $_POST["especialidad_egresados"] ?? '';
    $email_egresados = $_POST["email_egresados"] ?? '';
    $tel_egresados = $_POST["tel_egresados"] ?? '';
    $ocup_egresados = $_POST["ocup_egresados"] ?? '';
    $estudios_egresados = $_POST["estudios_egresados"] ?? '';
    $institucion_egresados = $_POST["institucion_egresados"] ?? '';

    try {
        // Consulta SQL corregida y completa
        $sql = "UPDATE egresados 
                SET nom_egresados = ?, 
                    fechanac_egresados = ?, 
                    tip_doc_egresados = ?, 
                    num_doc_egresados = ?, 
                    gruposanguineo_egresados = ?, 
                    especialidad_egresados = ?, 
                    email_egresados = ?, 
                    tel_egresados = ?, 
                    ocup_egresados = ?, 
                    estudios_egresados = ?, 
                    institucion_egresados = ? 
                WHERE id_egresados = ?";

        $st = $conexion->prepare($sql);
        $st->bind_param(
            "sssssssssssi",
            $nom_egresados,
            $fechanac_egresados,
            $tip_doc_egresados,
            $num_doc_egresados,
            $gruposanguineo_egresados,
            $especialidad_egresados,
            $email_egresados,
            $tel_egresados,
            $ocup_egresados,
            $estudios_egresados,
            $institucion_egresados,
            $id_egresados
        );

        $st->execute();
        $st->close();

        // Redirigir de nuevo a la página de datosp.php
        header("Location: datosp.php");
        exit();

    } catch (Throwable $e) {
        http_response_code(500);
        echo "Error al actualizar la información: " . $e->getMessage();
        exit();
    }
} else {
    echo "Acceso no permitido.";
    exit();
}
?>