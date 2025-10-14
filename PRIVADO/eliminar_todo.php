<?php
session_start();

if ($_SESSION["tipo_usuario"] !== "administrador") {
    header("Location: INFO_INDIVIDUAL.php");
    exit();
}

if (!isset($_POST["clave_admin"]) || empty($_POST["clave_admin"])) {
    echo "<script>alert('Debe ingresar la contraseña de administrador.'); window.history.back();</script>";
    exit();
}

$claveIngresada = $_POST["clave_admin"];

$enlace = new mysqli("localhost", "root", "", "poe");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$enlace->set_charset("utf8mb4");

//  Validar contraseña del administrador (según tu tabla)
$stmt = $enlace->prepare("SELECT id_usuario FROM usuarios WHERE tipo_usuario='administrador' AND contraseña=? LIMIT 1");
$stmt->bind_param("s", $claveIngresada);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<script>alert(' Contraseña incorrecta.'); window.location.href='INDEX.php';</script>";
    exit();
}

try {
    //  Desactivar validación de llaves foráneas temporalmente
    $enlace->query("SET FOREIGN_KEY_CHECKS = 0");

    //  Eliminar registros de todas las tablas
    $enlace->query("DELETE FROM dato_estudiante");
    $enlace->query("DELETE FROM adicional_estudiante");
    $enlace->query("DELETE FROM atributo_estudiante");
    $enlace->query("DELETE FROM caracteristicas_estudiante");
    $enlace->query("DELETE FROM entorno_estudiantes");
    $enlace->query("DELETE FROM fichai_estudiante");
    $enlace->query("DELETE FROM observador_estudiante");
    $enlace->query("DELETE FROM salud_estudiante");
    $enlace->query("DELETE FROM folder_estudiante");

    //  Reactivar validación de llaves foráneas
    $enlace->query("SET FOREIGN_KEY_CHECKS = 1");

    echo "<script>alert(' Todos los registros fueron eliminados correctamente.'); window.location.href='estudiantes.php';</script>";

} catch (Exception $e) {
    // En caso de error, reactivar restricciones y mostrar mensaje
    $enlace->query("SET FOREIGN_KEY_CHECKS = 1");
    echo "<script>alert('❌ Error al eliminar registros: " . addslashes($e->getMessage()) . "');
    window.location.href='estudiantes.php';
    </script>";
}

$enlace->close();
?>
