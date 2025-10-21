<?php
session_start();
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}
// actualizar_index.php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "poe");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Carpeta donde se guardarán las imágenes
$uploadsDir = __DIR__ . '/uploads/';
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Array de errores para registrar problemas con imágenes
$errores = [];

// ---- FUNCIÓN para subir imagen y reemplazar la anterior ----
function subirYReemplazar($nombreCampo, $columna, $conexion, $uploadsDir, &$errores)
{
    if (isset($_FILES[$nombreCampo]) && $_FILES[$nombreCampo]['error'] === UPLOAD_ERR_OK) {

        // Obtener la imagen anterior desde DB
        $consulta = "SELECT $columna FROM info_index WHERE id_index = 1";
        $resultado = $conexion->query($consulta);
        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $anterior = $fila[$columna];
            if (!empty($anterior)) {
                $anterior_limpia = ltrim($anterior, './');
                $rutaFisicaAnter = __DIR__ . '/' . $anterior_limpia;
                if (file_exists($rutaFisicaAnter)) {
                    @unlink($rutaFisicaAnter);
                }
            }
        }

        // Subir nueva imagen
        $nombreArchivo = uniqid() . "_" . basename($_FILES[$nombreCampo]['name']);
        $rutaDestinoFisica = $uploadsDir . $nombreArchivo;

        if (move_uploaded_file($_FILES[$nombreCampo]['tmp_name'], $rutaDestinoFisica)) {
            return 'uploads/' . $nombreArchivo;
        } else {
            // ⚠️ Si no se pudo guardar la imagen
            $errores[] = "No se pudo guardar la imagen '$nombreArchivo' en el servidor.";
        }
    } elseif (isset($_FILES[$nombreCampo]) && $_FILES[$nombreCampo]['error'] !== UPLOAD_ERR_NO_FILE) {
        // ⚠️ Si hubo un fallo al subir
        $errores[] = "Error al subir el archivo del campo '$nombreCampo'.";
    }

    return null;
}

// ---- Capturar los datos del formulario ----
$campos = [
    'titulo_principal',
    'desc_principal',
    'titulo_sec',
    'desc_sec',
    'titulo_division1',
    'desc_division1',
    'titulo_division2',
    'desc_division2',
    'titulo_division3',
    'desc_division3',
    'cont'
];

$datos = [];
foreach ($campos as $campo) {
    $datos[$campo] = $_POST[$campo] ?? '';
}

// ---- Subir imágenes ----
$imagenes = [
    'img_sec'        => subirYReemplazar('img_sec', 'img_sec', $conexion, $uploadsDir, $errores),
    'img_division1'  => subirYReemplazar('img_division1', 'img_division1', $conexion, $uploadsDir, $errores),
    'img_division2'  => subirYReemplazar('img_division2', 'img_division2', $conexion, $uploadsDir, $errores),
    'img_division3'  => subirYReemplazar('img_division3', 'img_division3', $conexion, $uploadsDir, $errores)
];

// ---- Armar la consulta de actualización ----
$sql = "UPDATE info_index SET 
    titulo_principal = ?, 
    desc_principal = ?, 
    titulo_sec = ?, 
    desc_sec = ?, 
    titulo_division1 = ?, 
    desc_division1 = ?, 
    titulo_division2 = ?, 
    desc_division2 = ?, 
    titulo_division3 = ?, 
    desc_division3 = ?, 
    contacto_psicoo = ?";

foreach ($imagenes as $col => $ruta) {
    if ($ruta) {
        $sql .= ", $col = '" . $conexion->real_escape_string($ruta) . "'";
    }
}

$sql .= " WHERE id_index = 1";

// ---- Ejecutar actualización ----
$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "sssssssssss",
    $datos['titulo_principal'],
    $datos['desc_principal'],
    $datos['titulo_sec'],
    $datos['desc_sec'],
    $datos['titulo_division1'],
    $datos['desc_division1'],
    $datos['titulo_division2'],
    $datos['desc_division2'],
    $datos['titulo_division3'],
    $datos['desc_division3'],
    $datos['cont']
);

if ($stmt->execute()) {
    mysqli_close($conexion);

    if (!empty($errores)) {
        // ⚠️ Si hubo errores al guardar imágenes
        $msg = urlencode(implode(' | ', $errores));
        header("Location: editor index.php?msg=error_update&detalles=$msg");
    } else {
        // ✅ Todo salió bien
        header("Location: editor index.php?msg=ok");
    }
    exit;
} else {
    mysqli_close($conexion);
    header("Location: editor index.php?msg=error_insert");
    exit;
}

$stmt->close();
$conexion->close();
?>
