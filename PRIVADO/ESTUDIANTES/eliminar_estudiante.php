<?php
session_start();

// Seguridad: solo administrador y con ID válido
if (!isset($_POST["id_dato"]) || $_SESSION["tipo_usuario"] !== "administrador") {
    header("Location: INFO_INDIVIDUAL.php");
    exit();
}

$id = (int)$_POST["id_dato"];

// Conexión segura
$enlace = new mysqli("localhost", "root", "", "poe");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$enlace->set_charset("utf8mb4");

try {
    // 🚨 Desactivar temporalmente las validaciones de llaves foráneas
    $enlace->query("SET FOREIGN_KEY_CHECKS = 0");

    // Iniciar transacción
    $enlace->begin_transaction();

    // Buscar las llaves relacionadas
    $sql = "SELECT id_adicional, id_atributo, id_caracteristicas, id_entorno, id_fichai, id_observador, id_salud, id_folder 
            FROM dato_estudiante WHERE id_dato = $id";
    $res = $enlace->query($sql);

    if ($res && $res->num_rows > 0) {
        $rel = $res->fetch_assoc();

        // 🧹 Eliminar primero el estudiante principal (según tu requerimiento)
        $enlace->query("DELETE FROM dato_estudiante WHERE id_dato = $id");

        // 🧩 Luego eliminar los registros dependientes
        $enlace->query("DELETE FROM adicional_estudiante WHERE id_adicional = {$rel['id_adicional']}");
        $enlace->query("DELETE FROM atributo_estudiante WHERE id_atributo = {$rel['id_atributo']}");
        $enlace->query("DELETE FROM caracteristicas_estudiante WHERE id_caracteristicas = {$rel['id_caracteristicas']}");
        $enlace->query("DELETE FROM entorno_estudiantes WHERE id_entorno = {$rel['id_entorno']}");
        $enlace->query("DELETE FROM fichai_estudiante WHERE id_fichai = {$rel['id_fichai']}");
        $enlace->query("DELETE FROM observador_estudiante WHERE id_observador = {$rel['id_observador']}");
        $enlace->query("DELETE FROM salud_estudiante WHERE id_salud = {$rel['id_salud']}");
        $enlace->query("DELETE FROM folder_estudiante WHERE id_folder = {$rel['id_folder']}");
    }

    // Confirmar cambios
    $enlace->commit();

    // ✅ Reactivar validación de llaves foráneas
    $enlace->query("SET FOREIGN_KEY_CHECKS = 1");

    $_SESSION["eliminado"] = true;
    echo "<script>
            alert('✅ Estudiante eliminado correctamente.');
            window.location.href='INFO_INDIVIDUAL.php';
          </script>";
    exit();

} catch (Exception $e) {
    // 🔄 Revertir cambios en caso de error
    $enlace->rollback();

    // Reactivar llaves foráneas aunque haya error
    $enlace->query("SET FOREIGN_KEY_CHECKS = 1");

    echo "<script>
            alert('❌ Error al eliminar estudiante: " . addslashes($e->getMessage()) . "');
            window.location.href='INFO_INDIVIDUAL.php';
          </script>";
}

$enlace->close();
?>
