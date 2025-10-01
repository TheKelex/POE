<?php
// actualizar_foto.php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion = new mysqli("localhost", "root", "", "poe");
$conexion->set_charset("utf8mb4");

$id_dato = $_POST['id_dato'] ?? $_SESSION['id_dato'] ?? null;
if (!$id_dato) {
    http_response_code(400);
    exit("No se recibió id_dato.");
}


// --- Validación de imagen ---
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit("Imagen no válida.");
}

// Validar extensión permitida
$ext_permitidas = ['jpg','jpeg','png'];
$extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
if (!in_array($extension, $ext_permitidas)) {
    http_response_code(400);
    exit("Formato de imagen no permitido.");
}

$doc_dato = $_SESSION['doc_dato'] ?? 'default';

// --- Carpeta destino ---
// --- Carpeta del estudiante ---
$carpeta = "../FOLDER/ESTUDIANTE/" . $doc_dato . "/";
if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
}

// --- Obtener extensión de la imagen ---
$extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

// --- Nombre fijo de la foto ---
$nombre_nuevo = "foto." . $extension;
$ruta_destino = $carpeta . $nombre_nuevo;

// --- Mover la imagen, reemplazando si ya existe ---
if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
    http_response_code(500);
    exit("Error al mover la imagen.");
}



// --- Actualizar la ruta de la imagen ---
$sql = "UPDATE observador_estudiante
        SET foto_observador=?
        WHERE id_observador=$id_dato";
$st = $conexion->prepare($sql);
$st->bind_param("s", $ruta_destino); // "s" = string, "i" = integer
$st->execute();
$st->close();


header("Location: observador.php?id_estudiante=" . $id_dato);
exit;
