<?php
session_start();
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../INICIO%20SESION/inicio.php");
    session_destroy();
    exit();
}
if ($_SESSION['tipo_usuario'] !== "psicoorientador" && $_SESSION['tipo_usuario'] !== "administrador") {
    header("Location: ../../INICIO%20SESION/inicio.php");
    session_destroy();
    exit();
}

// Control de inactividad (5 minutos)
$inactividad_maxima = 300;
if (isset($_SESSION['ultimo_movimiento'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_movimiento'];
    if ($tiempo_inactivo > $inactividad_maxima) {
        session_unset();
        session_destroy();
        header("Location: ../PRIVADO/INICIO SESION/inicio.php?expirado=1");
        exit();
    }
}
$_SESSION['ultimo_movimiento'] = time();

$servidor = "localhost";
$usuario_db = "root";
$contraseña = "";
$basededatos = "poe";

$enlace = mysqli_connect($servidor, $usuario_db, $contraseña, $basededatos);
if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

//--------------------- Inicio de subida de informacion ---------------------

$destino = $_POST['destino'] ?? '';
$titulo = $_POST['titulo'] ?? '';
$texto = $_POST['informaciom'] ?? '';

// === Insertar registro vacío para obtener el id_foro ===
$sql_insert = "INSERT INTO foro (destino_foro, titulo_foro, texto_foro, like_foro) 
               VALUES ('$destino', '$titulo', '$texto', 0)";
if (!mysqli_query($enlace, $sql_insert)) {
    mysqli_close($enlace);
    header("Location: editor.php?msg=error_insert");
    exit;
}

$id_foro = mysqli_insert_id($enlace);

// === Crear carpeta para las imágenes ===
// Carpeta raíz, ejemplo: uploads/foro/123/
$carpeta_base = "uploads/foro/$id_foro/";
if (!is_dir($carpeta_base)) {
    mkdir($carpeta_base, 0777, true);
}

// === Procesar las imágenes ===
$rutas = [];
for ($i = 0; $i < 3; $i++) {
    if (isset($_FILES["archivo$i"]) && $_FILES["archivo$i"]["error"] == 0) {
        $nombreArchivo = basename($_FILES["archivo$i"]["name"]);
        $rutaDestino = $carpeta_base . $nombreArchivo;

        if (move_uploaded_file($_FILES["archivo$i"]["tmp_name"], $rutaDestino)) {
            $rutas[$i] = $rutaDestino;
        } else {
            $rutas[$i] = null;
        }
    } else {
        $rutas[$i] = null;
    }
}

// === Actualizar registro con las rutas de las imágenes ===
$sql_update = "UPDATE foro 
               SET archivo1_foro = '" . ($rutas[0] ?? '') . "', 
                   archivo2_foro = '" . ($rutas[1] ?? '') . "', 
                   archivo3_foro = '" . ($rutas[2] ?? '') . "'
               WHERE id_foro = $id_foro";

if (!mysqli_query($enlace, $sql_update)) {
    mysqli_close($enlace);
    header("Location: editor.php?msg=error_update");
    exit;
}

mysqli_close($enlace);

header("Location: editor.php?msg=ok");

exit;

//--------------------- Fin de subida de informacion ---------------------
