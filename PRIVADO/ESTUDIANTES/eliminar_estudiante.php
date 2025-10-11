<?php
session_start();

// ✅ Seguridad: debe venir el id y ser administrador
if (!isset($_POST["id_dato"]) || $_SESSION["tipo_usuario"] !== "administrador") {
    header("Location: INFO_INDIVIDUAL.php");
    exit();
}

$id = (int)$_POST["id_dato"];

// 🔗 Conexión a la base de datos
$enlace = new mysqli("localhost", "root", "", "poe");
if ($enlace->connect_error) {
    die("Error de conexión: " . $enlace->connect_error);
}

$enlace->begin_transaction();

try {
    // 🔍 Obtener IDs relacionados antes de eliminar
    $sql = "SELECT id_adicional, id_atributo, id_caracteristicas, id_entorno, id_fichai, id_observador, id_salud, id_folder 
            FROM dato_estudiante WHERE id_dato = $id";
    $res = $enlace->query($sql);

    if ($res && $res->num_rows > 0) {
        $rel = $res->fetch_assoc();

        // 🔥 Eliminar registros relacionados
        $enlace->query("DELETE FROM adicional_estudiante WHERE id_adicional = {$rel['id_adicional']}");
        $enlace->query("DELETE FROM atributo_estudiante WHERE id_atributo = {$rel['id_atributo']}");
        $enlace->query("DELETE FROM caracteristicas_estudiante WHERE id_caracteristicas = {$rel['id_caracteristicas']}");
        $enlace->query("DELETE FROM entorno_estudiantes WHERE id_entorno = {$rel['id_entorno']}");
        $enlace->query("DELETE FROM fichai_estudiante WHERE id_fichai = {$rel['id_fichai']}");
        $enlace->query("DELETE FROM observador_estudiante WHERE id_observador = {$rel['id_observador']}");
        $enlace->query("DELETE FROM salud_estudiante WHERE id_salud = {$rel['id_salud']}");
        $enlace->query("DELETE FROM folder_estudiante WHERE id_folder = {$rel['id_folder']}");
    }

    // 🧹 Eliminar el estudiante principal
    $enlace->query("DELETE FROM dato_estudiante WHERE id_dato = $id");

    // Confirmar transacción
    $enlace->commit();

    $_SESSION["eliminado"] = true;
    header("Location: INFO_INDIVIDUAL.php");
    exit();
    

} catch (Exception $e) {
    $enlace->rollback();
    echo "Error al eliminar estudiante: " . $e->getMessage();
}

$enlace->close();
?>

