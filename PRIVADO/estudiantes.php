<?php
session_start();

/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
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

/* --------------------------
   Conexión a la base de datos
   -------------------------- */
$servidor = "localhost";
$usuario_db = "root";
$contraseña = "";
$basededatos = "poe";

$enlace = mysqli_connect($servidor, $usuario_db, $contraseña, $basededatos);
if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

/* --------------------------
   Procesamiento del POST
   -------------------------- */
// Persistir selección de persona SOLO si llega un valor válido
if (isset($_POST['persona']) && in_array($_POST['persona'], ['1', '2'], true)) {
    $_SESSION['persona'] = $_POST['persona'];
}
$persona = $_SESSION['persona'] ?? '';

// Filtros comunes y captura de inputs (soportando variaciones de nombre)
$jornada = $_POST['jornada'] ?? '';
$sede    = $_POST['sede'] ?? '';
$curso   = $_POST['curso'] ?? '';
$Ti      = $_POST['doc_dato'] ?? ''; // documento filtro

// año para egresados: aceptamos "aaño", "año" o "anio"
$anio = $_POST['aaño'] ?? $_POST['año'] ?? $_POST['anio'] ?? '';

// Limpiar filtros si se presionó el botón correspondiente
if (isset($_POST['limpiar'])) {
    $jornada = '';
    $sede    = '';
    $curso   = '';
    $Ti      = '';
    $anio    = '';
}

/* --------------------------
   Consultas según persona
   -------------------------- */
$resultado = null;
$error_msg = '';

// Si seleccionaron Estudiantes (persona === '1')
if ($persona === '1') {
    // Consulta base (estudiantes)
    $consulta = "
        SELECT 
            de.id_dato,            
            de.nom_dato,          
            de.doc_dato,
            de.jornada_dato,
            de.sede_dato,          
            oe.gradop_observador
        FROM dato_estudiante de
        INNER JOIN observador_estudiante oe
            ON de.id_dato = oe.id_observador
        WHERE 1=1
    ";

    // Filtrado por grado autorizado de sesión (rango 100->199, 200->299, etc.)
    $grado_autorizado = $_SESSION['grado_autorizado'] ?? '';
    if (is_numeric($grado_autorizado)) {
        $min = (int)$grado_autorizado;
        $max = $min + 100;
        // Usamos >= min y < max para incluir 100 y excluir 200 (puedes ajustar si quieres inclusive)
        $consulta .= " AND oe.gradop_observador >= " . $min . " AND oe.gradop_observador < " . $max;
    }

    // Filtros del formulario (escapados)
    if ($jornada !== '') {
        $consulta .= " AND de.jornada_dato = '" . mysqli_real_escape_string($enlace, $jornada) . "'";
    }
    if ($sede !== '') {
        $consulta .= " AND de.sede_dato = '" . mysqli_real_escape_string($enlace, $sede) . "'";
    }
    if ($curso !== '') {
        // Se asume que curso corresponde a gradop_observador exacto (ej: 1002?) — si no, ajusta la lógica
        $consulta .= " AND oe.gradop_observador = '" . mysqli_real_escape_string($enlace, $curso) . "'";
    }
    if ($Ti !== '') {
        $consulta .= " AND de.doc_dato = '" . mysqli_real_escape_string($enlace, $Ti) . "'";
    }

    // Ejecutar consulta estudiantes
    $resultado = mysqli_query($enlace, $consulta);
    if (!$resultado) {
        $error_msg = "Error en la consulta de estudiantes: " . mysqli_error($enlace);
    }
}

// Si seleccionaron Egresados (persona === '2')
if ($persona === '2') {
    // Consulta base (egresados)
    $consulta = "
        SELECT 
            id_egresados, 
            año_egresado,           
            nom_egresados,          
            num_doc_egresados,
            especialidad_egresados
        FROM egresados
        WHERE 1=1
    ";

    // Aplicar filtros (anio y documento)
    if ($anio !== '') {
        // evitar caracteres peligrosos
        $consulta .= " AND año_egresado = '" . mysqli_real_escape_string($enlace, $anio) . "'";
    }
    if ($Ti !== '') {
        $consulta .= " AND num_doc_egresados = '" . mysqli_real_escape_string($enlace, $Ti) . "'";
    }

    // Ejecutar consulta egresados
    $resultado = mysqli_query($enlace, $consulta);
    if (!$resultado) {
        $error_msg = "Error en la consulta de egresados: " . mysqli_error($enlace);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes / Egresados</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../bootstrap-5.3.7-dist/css/bootstrap.css">
</head>
<body>
    <div class="w-100">
        <img src="../Imagenes/Banner.png" alt="" class="img-fluid"
            style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <!-- Navbar (sin cambios) -->
    <nav class="navbar navbar-expand-lg" style="background-color: #017800;">
        <div class="container-fluid">
            <a class="navbar-brand fs-4 ms-4" style="font-weight: bold; color: white; font-size: 25px; cursor: default;" href=""><b>POE</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto rounded-pill gap-2" style="font-weight: bold; background-color: #00ac4a;">
                    <li class="nav-item"><a class="nav-link px-4 py-2 textos_navbar" href="./EDIT/FORM_FORO/editor.php">Editor Foro</a></li>
                    <li class="nav-item"><a class="nav-link px-4 py-2 textos_navbar" href="../EDITOR INDEX/editor index.html">Editor Pag. Principal</a></li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar d-flex align-items-center" href="./sesion_close.php">
                            <span class="material-symbols-outlined mx-2">logout</span>Cerrar Sesion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="caja rounded-5 m-4">
        <!-- Form de filtros -->
        <form action="" method="POST">
            <center>
                <button type="submit" class="boton rounded-pill m-4" name="persona" value="1"
                    <?php if ($persona === '1') echo 'style="background:#028a3d; color:white;"'; ?>>
                    Estudiantes
                </button>
                <button type="submit" class="boton rounded-pill m-4" name="persona" value="2"
                    <?php if ($persona === '2') echo 'style="background:#028a3d; color:white;"'; ?>>
                    Egresados
                </button>
            </center>

            <div class="row">
                <!-- Panel de filtros dinámico -->
                <?php
                if ($persona === '1') {
                    // Filtro para ESTUDIANTES (manteniendo el mismo frontend)
                    echo '
                        <div class="col-2">
                            <div class="cajita_opciones d-flex flex-column rounded-5 d-grid gap-3">
                                <input type="text" name="doc_dato" value="' . htmlspecialchars($Ti) . '"
                                    class="boton_cajita rounded-pill mx-2" id="doc_dato" placeholder="Documento de identidad">

                                <select name="sede" class="boton_cajita rounded-pill mx-2">
                                    <option disabled ' . (empty($sede) ? "selected" : "") . '>Sede</option>
                                    <option value="Central" ' . ($sede === "Central" ? "selected" : "") . '>Sede Central</option>
                                    <option value="Los Martires" ' . ($sede === "Los Martires" ? "selected" : "") . '>Los Martires</option>
                                    <option value="Floresmiro" ' . ($sede === "Floresmiro" ? "selected" : "") . '>Floresmiro</option>
                                    <option value="Elena Lara" ' . ($sede === "Elena Lara" ? "selected" : "") . '>Elena Lara</option>
                                </select>

                                <select name="jornada" class="boton_cajita rounded-pill mx-2">
                                    <option disabled ' . (empty($jornada) ? "selected" : "") . '>Jornada</option>
                                    <option value="Mañana" ' . ($jornada === "Mañana" ? "selected" : "") . '>Mañana</option>
                                    <option value="Tarde" ' . ($jornada === "Tarde" ? "selected" : "") . '>Tarde</option>
                                </select>

                                <input type="text" name="curso" value="' . htmlspecialchars($curso) . '"
                                    class="boton_cajita rounded-pill mx-2" id="curso" placeholder="Curso">

                                <p class="w-100 text-center" style="font-weight: bold;">Ej: 1002</p>

                                <input class="actualizar rounded-pill" type="submit" name="actualizar" value="Enviar">
                                <input class="actualizar rounded-pill" type="submit" name="limpiar"
                                    value="Eliminar filtros" style="color:black; margin: 0 !important">
                            </div>
                        </div>
                    ';
                } elseif ($persona === '2') {
                    // Filtro para EGRESADOS (manteniendo el mismo frontend)
                    echo '
                        <div class="col-2">
                            <div class="cajita_opciones d-flex flex-column rounded-5 d-grid gap-3">
                                <input type="text" name="doc_dato" value="' . htmlspecialchars($Ti) . '"
                                    class="boton_cajita rounded-pill mx-2" id="num_doc_egresados" placeholder="Documento de identidad">

                                <input type="text" name="aaño" value="' . htmlspecialchars($anio) . '"
                                    class="boton_cajita rounded-pill mx-2" id="año_egresados" placeholder="Año">

                                <p class="w-100 text-center" style="font-weight: bold;">Ej: 2025</p>

                                <input class="actualizar rounded-pill" type="submit" name="actualizar" value="Enviar">
                                <input class="actualizar rounded-pill" type="submit" name="limpiar"
                                    value="Eliminar filtros" style="color:black; margin: 0 !important">
                            </div>
                        </div>
                    ';
                } else {
                    // Ninguna persona seleccionada: mensaje (panel vacío a la izquierda para mantener layout)
                    echo '<div class="col-2"><div class="cajita_opciones d-flex flex-column rounded-5 d-grid gap-3"><p class="text-center">Selecciona Estudiantes o Egresados</p></div></div>';
                }
                ?>
        </form>

                <!-- Aquí va la tabla: columna derecha (col-10) -->
                <div class="col-10">
                    <table class="m-4 table table-hover">
                        <?php
                        // Si hay errores en la consulta, muéstralos
                        if ($error_msg !== '') {
                            echo "<tr><td colspan='7' class='text-center text-danger'>" . htmlspecialchars($error_msg) . "</td></tr>";
                        } else {
                            // Mostrar cabeceras y filas según persona
                            if ($persona === '1') {
                                // Cabecera estudiantes
                                echo '
                                    <tr>
                                        <th class="campo">ID</th>
                                        <th class="campo">Nombre</th>
                                        <th class="campo">Documento De Identidad</th>
                                        <th class="campo">Jornada</th>
                                        <th class="campo">Sede</th>
                                        <th class="campo">Curso</th>
                                    </tr>
                                ';

                                if ($resultado && mysqli_num_rows($resultado) > 0) {
                                    if ($jornada!== '' OR $sede !== '' OR $curso !== '') {
                                        while ($colum = mysqli_fetch_assoc($resultado)) {
                                        echo '<tr>';
                                        echo '<td class="campo">' . (int)$colum['id_dato'] . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['nom_dato']) . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['doc_dato']) . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['jornada_dato']) . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['sede_dato']) . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['gradop_observador']) . '</td>';
                                        // Botón acción por fila (manteniendo tu ruta)
                                        echo '<td class="ingreso">
                                                <form action="../PRIVADO/ESTUDIANTES/INFO_INDIVIDUAL.php" method="POST">
                                                    <input type="hidden" name="id_dato" value="' . htmlspecialchars($colum['id_dato']) . '">
                                                    <button type="submit" class="btn" style="border:none; background:none; cursor:pointer; color:black;">
                                                        <span class="material-symbols-outlined fs-2">login</span>
                                                    </button>
                                                </form>
                                              </td>';
                                        echo '</tr>';
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>⚠ Selecciona los filtros necesarios para empezar</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No hay estudiantes registrados</td></tr>";
                                }

                            } elseif ($persona === '2') {
                                // Cabecera egresados
                                echo '
                                    <tr>
                                        <th class="campo">ID</th>
                                        <th class="campo">Nombre</th>
                                        <th class="campo">Documento</th>
                                        <th class="campo">Año</th>
                                        <th class="campo">Especialidad</th>
                                    </tr>
                                ';

                                if ($resultado && mysqli_num_rows($resultado) > 0) {
                                    if ($anio !== '' OR $Ti !== '') {
                                        while ($colum = mysqli_fetch_assoc($resultado)) {
                                        echo '<tr>';
                                        echo '<td class="campo">' . (int)$colum['id_egresados'] . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['nom_egresados']) . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['num_doc_egresados']) . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['año_egresado']) . '</td>';
                                        echo '<td class="campo">' . htmlspecialchars($colum['especialidad_egresados']) . '</td>';
                                        echo '<td class="ingreso">
                                                <form action="../PRIVADO/EGRESADOS/egresados.php" method="POST">
                                                    <input type="hidden" name="id_egresados" value="' . htmlspecialchars($colum['id_egresados']) . '">
                                                    <button type="submit" class="btn" style="border:none; background:none; cursor:pointer; color:black;">
                                                        <span class="material-symbols-outlined fs-2">login</span>
                                                    </button>
                                                </form>
                                              </td>';
                                        echo '</tr>';
                                        echo '</tr>';
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>⚠ Selecciona los filtros necesarios para empezar</td></tr>";
                                    }
                                    
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No hay egresados registrados</td></tr>";
                                }

                            } else {
                                // Ninguna opción seleccionada
                                echo "<tr><td colspan='7' class='text-center'>⚠ Selecciona entre estudiantes o egresados</td></tr>";
                            }
                        }
                        ?>
                    </table>
                </div> <!-- col-10 -->
            </div> <!-- row -->
    </div> <!-- caja -->
    <!-- Botón y modal para cargar listas -->
<div class="col">
    <div class="d-flex flex-column align-items-center w-75 mx-auto gap-3">

        <!-- Botón para abrir modal -->
        <button type="button" class="btn btn-editar d-flex w-50 align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modalListas">
            <span class="material-symbols-outlined">upload</span>Cargar Listas
        </button>

        <!-- Modal -->
        <div class="modal fade" id="modalListas" tabindex="-1" aria-labelledby="modalListasLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalListasLabel">Actualizar listados (CSV)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <form action="listas.php" method="POST" enctype="multipart/form-data" class="p-4">
                        <div class="mb-3">
                            <label for="archivoCSV" class="form-label">Seleccione el archivo CSV</label>
                            <input class="form-control" type="file" name="archivoCSV" id="archivoCSV" accept=".csv" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña de administrador</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese su contraseña" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <input type="submit" class="btn btn-editar" name="actualizar" value="Actualizar listado">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Previene que el Enter dentro de cualquier input envíe el formulario
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                return false;
            }
        });
    });
}); 
</script>
<script src="../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
