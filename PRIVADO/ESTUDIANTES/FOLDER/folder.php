<?php
// folder.php
session_start();
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../INICIO SESION/inicio.php");
    exit();
}

// Control de inactividad (5 minutos)
$inactividad_maxima = 300;
if (isset($_SESSION['ultimo_movimiento'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_movimiento'];
    if ($tiempo_inactivo > $inactividad_maxima) {
        session_unset();
        session_destroy();
        header("Location: ../../INICIO SESION/inicio.php?expirado=1");
        exit();
    }
}
$_SESSION['ultimo_movimiento'] = time();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion = new mysqli("localhost", "root", "", "poe");
$conexion->set_charset("utf8mb4");


// id_dato por GET o SESSION
$id_dato = $_GET['id_dato'] ?? $_SESSION['id_dato'] ?? null;
if (!$id_dato) {
    exit("<p style='color:darkred;'>No se recibió el ID del estudiante (id_dato).</p>");
}

// Mensaje opcional desde el procesador (GET)
$msg = $_GET['msg'] ?? '';
$alert = '';
if ($msg === 'ok') $alert = 'Archivo subido correctamente.';
if ($msg === 'full') $alert = 'El estudiante ya tiene 5 archivos registrados.';
if ($msg === 'error') $alert = 'Ocurrió un error al subir el archivo.';

// Obtener los archivos guardados (campos varchar con rutas)
$archivos = [];
$sql = "SELECT archivo1_folder, archivo2_folder, archivo3_folder, archivo4_folder, archivo5_folder
        FROM folder_estudiante WHERE id_folder = ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $id_dato);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        for ($i = 1; $i <= 5; $i++) {
            $campo = "archivo{$i}_folder";
            if (!empty($row[$campo])) $archivos[] = $row[$campo];
        }
    }
    $stmt->close();
} else {
    $alert = "Error al preparar consulta: " . htmlspecialchars($conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Folder - Estudiante</title>
<link rel="stylesheet" href="../../../bootstrap-5.3.7-dist/css/bootstrap.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link rel="stylesheet" href="./style.css">
<style>
.descarga {
  background-color:#f5f5f5; border:none; padding:0.6rem 1rem;
  transition:all .15s; display:flex; justify-content:space-between; width:100%;
}
.descarga:hover { background:#e9e9e9; transform:scale(1.01); text-decoration:none; }
.upload-container { cursor:pointer; padding:2rem; border:2px dashed #ddd; border-radius:12px; }
</style>
</head>
<body>

<div class="w-100">
  <img src="../../../Imagenes/Banner.png" class="img-fluid" style="width:100%; max-height:160px; object-fit:cover;">
</div>

<a href="../INFO_INDIVIDUAL.php" class="rounded-pill m-4 volver">
  <span class="material-symbols-outlined mx-2">logout</span>Volver
</a>

<div class="caja rounded-5 m-4">
  <div class="row g-4">

    <!-- Columna izquierda: lista de archivos -->
    <div class="col-3">
      <div class="d-flex flex-column gap-3 align-items-center">
        <h1 style="font-weight:bold;">Archivos</h1>

        <?php if (count($archivos) > 0): ?>
  <?php 
    $contador = 1;
    foreach ($archivos as $ruta): 
  ?>
    <a href="<?php echo htmlspecialchars($ruta); ?>" download class="descarga rounded-pill text-decoration-none">
      <span><?php echo 'Archivo ' . $contador; ?></span>
      <span class="material-symbols-outlined p-2" style="font-size:1.7rem;">download</span>
    </a>
  <?php 
    $contador++;
    endforeach; 
  ?>
<?php else: ?>
  <p class="text-muted">No hay archivos subidos.</p>
<?php endif; ?>


      </div>
    </div>

    <!-- Columna derecha: formulario de subida -->
    <form action="subir_archivo.php" method="post" enctype="multipart/form-data" class="col-9">

      <input type="hidden" name="id_dato" value="<?php echo htmlspecialchars($id_dato); ?>">

      <div class="text-center">
        <div class="upload-container" onclick="document.getElementById('fileInput').click()">
          <div class="icon-wrapper">
            <span class="material-symbols-outlined" style="font-size:20rem;">docs</span>
          </div>
          <div class="file-name" id="fileName">Ningún archivo seleccionado</div>
        </div>
        <input type="file" id="fileInput" name="archivo" onchange="updateFileName()" style="display:none;">
      </div>

      <div class="col-12 d-flex justify-content-end pe-md-4 mt-4">
        <button class="btn-actualizar rounded-pill my-4 btn btn-primary" type="submit">
          <span class="material-symbols-outlined mx-1">edit</span>Actualizar
        </button>
      </div>
    </form>

  </div>
</div>

<script>
function updateFileName(){
  const input = document.getElementById('fileInput');
  const fileName = document.getElementById('fileName');
  if (input.files.length > 0) fileName.textContent = input.files[0].name;
  else fileName.textContent = 'Ningún archivo seleccionado';
}
</script>

<script src="../../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
