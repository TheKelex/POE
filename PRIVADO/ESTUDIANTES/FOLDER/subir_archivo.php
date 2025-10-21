<?php
// subir_archivo.php (versión ajustada para XAMPP / rutas robustas)
session_start();
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion = new mysqli("localhost", "root", "", "poe");
$conexion->set_charset("utf8mb4");

// ID del estudiante
$id_dato = $_POST['id_dato'] ?? $_SESSION['id_dato'] ?? null;
if (!$id_dato) {
    header("Location: folder.php?msg=error");
    exit;
}

// Validar archivo básico
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    header("Location: folder.php?id_dato={$id_dato}&msg=error");
    exit;
}

$maxBytes = 10 * 1024 * 1024; // 10 MB
$allowed = ['pdf','doc','docx','xls','xlsx','jpg','jpeg','png','txt','zip'];

$ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed) || $_FILES['archivo']['size'] > $maxBytes) {
    header("Location: folder.php?id_dato={$id_dato}&msg=error");
    exit;
}

// Obtener doc_dato del estudiante
$sql = "SELECT doc_dato FROM dato_estudiante WHERE id_dato = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    header("Location: folder.php?id_dato={$id_dato}&msg=error");
    exit;
}
$stmt->bind_param("i", $id_dato);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    header("Location: folder.php?id_dato={$id_dato}&msg=error");
    exit;
}
$doc = $res->fetch_assoc()['doc_dato'];
$stmt->close();

// Relative dir pública (la guardaremos como URL, por ejemplo "/POE/FOLDER/ESTUDIANTE/EST_4/123/")
$relative_dir = "PRIVADO/ESTUDIANTES/FOLDER/ESTUDIANTE/{$doc}";

// ---- Construir ruta física robusta ----
// Intento 1: DOCUMENT_ROOT + relative_dir
$docroot = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
$upload_dir = $docroot . '/' . $relative_dir . '/';

// Si no existe la carpeta padre construible desde DOCUMENT_ROOT, intento detectar la raíz del proyecto (ej. '/POE')
if (!is_dir(dirname($upload_dir))) {
    // buscar "/POE" (o "POE") en la ruta del script actual
    $dir = str_replace('\\', '/', __DIR__); // ruta actual del script (con /)
    $projectName = '/POE';
    $pos = stripos($dir, $projectName);
    if ($pos !== false) {
        $project_root = substr($dir, 0, $pos + strlen($projectName));
        $upload_dir = rtrim($project_root, '/') . '/' . $relative_dir . '/';
    } else {
        // fallback: tratar de usar __DIR__ y subir hasta htdocs
        // asumimos estructura: C:/xampp/htdocs/POE/...
        $maybe_root = dirname(__DIR__, 4); // intento subir 4 niveles (ajusta si tu estructura difiere)
        if ($maybe_root && is_dir($maybe_root)) {
            $upload_dir = rtrim($maybe_root, '/\\') . '/' . $relative_dir . '/';
        }
    }
}

// A este punto $upload_dir debería apuntar a la carpeta física
// Intentar crear la carpeta (recursiva) si no existe
if (!is_dir($upload_dir)) {
    if (!@mkdir($upload_dir, 0777, true)) {
        // fallo al crear
        header("Location: folder.php?id_dato={$id_dato}&msg=error");
        exit;
    }
}

// Comprobar permisos
if (!is_writable($upload_dir)) {
    // intentar chmod (si el sistema lo permite)
    @chmod($upload_dir, 0777);
    if (!is_writable($upload_dir)) {
        header("Location: folder.php?id_dato={$id_dato}&msg=error");
        exit;
    }
}

// Nombre seguro del archivo
$original = pathinfo($_FILES['archivo']['name'], PATHINFO_BASENAME);
$safe_name = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $original);
$dest_full = $upload_dir . $safe_name;

// Comprobación anti-duplicado (por si el usuario da doble clic)
if (file_exists($dest_full)) {
    // Genera un nuevo nombre con timestamp adicional
    $nombre_archivo = time() . rand(1000, 9999) . "_" . basename($_FILES['archivo']['name']);
    $dest_full = $carpeta_destino . "/" . $nombre_archivo;
}

// Mover archivo
if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $dest_full)) {
    // intento de diagnóstico: si falla, eliminar tmp y redirigir con error
    header("Location: folder.php?id_dato={$id_dato}&msg=error");
    exit;
}

// Ruta pública que guardaremos en BD
// Si detectamos que el proyecto está en /POE, guardamos con ese prefijo; si no, guardamos sin /POE
$public_prefix = '';
// intentar detectar si $upload_dir contiene "/POE/"
if (stripos($upload_dir, '/POE/') !== false) {
    $public_prefix = '/POE/';
} elseif (stripos($upload_dir, '\\POE\\') !== false) {
    $public_prefix = '/POE/';
}
// normalizar public path
$public_path = '/' . $relative_dir . '/' . $safe_name;
if ($public_prefix) {
    $public_path = $public_prefix . $relative_dir . '/' . $safe_name;
}

// ---- Guardar en la BD en el primer campo libre (archivo1..archivo5) ----
$sql_check = "SELECT * FROM folder_estudiante WHERE id_folder = ?";
$stmt_check = $conexion->prepare($sql_check);
if (!$stmt_check) { header("Location: folder.php?id_dato={$id_dato}&msg=error"); exit; }
$stmt_check->bind_param("i", $id_dato);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check->num_rows === 0) {
    // Insert nuevo registro con archivo1
    $sql_insert = "INSERT INTO folder_estudiante (id_folder, archivo1_folder) VALUES (?, ?)";
    $stmt_i = $conexion->prepare($sql_insert);
    if (!$stmt_i) { header("Location: folder.php?id_dato={$id_dato}&msg=error"); exit; }
    $stmt_i->bind_param("is", $id_dato, $public_path);
    $ok = $stmt_i->execute();
    $stmt_i->close();
    if ($ok) header("Location: folder.php?id_dato={$id_dato}&msg=ok");
    else header("Location: folder.php?id_dato={$id_dato}&msg=error");
    exit;
} else {
    $row = $res_check->fetch_assoc();
    $campo_disponible = null;
    for ($i = 1; $i <= 5; $i++) {
        $campo = "archivo{$i}_folder";
        if (empty($row[$campo])) { $campo_disponible = $campo; break; }
    }

    if ($campo_disponible === null) {
        @unlink($dest_full);
        header("Location: folder.php?id_dato={$id_dato}&msg=full");
        exit;
    } else {
        $sql_upd = "UPDATE folder_estudiante SET {$campo_disponible} = ? WHERE id_folder = ?";
        $stmt_u = $conexion->prepare($sql_upd);
        if (!$stmt_u) { @unlink($dest_full); header("Location: folder.php?id_dato={$id_dato}&msg=error"); exit; }
        $stmt_u->bind_param("si", $public_path, $id_dato);
        $ok = $stmt_u->execute();
        $stmt_u->close();
        if ($ok) header("Location: folder.php?id_dato={$id_dato}&msg=ok");
        else { @unlink($dest_full); header("Location: folder.php?id_dato={$id_dato}&msg=error"); }
        exit;
    }
}
