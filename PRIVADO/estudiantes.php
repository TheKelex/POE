<?php
session_start();

// Limpiar id_dato si existe
if (isset($_SESSION["id_dato"])) {
    unset($_SESSION['id_dato']);
}

// Verificación de sesión activa
if (!isset($_SESSION['usuario'])) {
    // Si no hay usuario logueado, redirigir al login
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}

// --- Control de inactividad ---
$inactividad_maxima = 300; // 5 min
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

// --- Conexión BD ---
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$basededatos = "poe";
$enlace = mysqli_connect($servidor, $usuario, $contraseña, $basededatos);
if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

// --- Persistir selección de persona SOLO si llega un valor válido ---
if (isset($_POST['persona']) && in_array($_POST['persona'], ['1', '2'], true)) {
    $_SESSION['persona'] = $_POST['persona'];
}
$persona = $_SESSION['persona'] ?? '';

// --- Filtros ---
$jornada = $_POST['jornada'] ?? '';
$sede    = $_POST['sede'] ?? '';
$curso   = $_POST['curso'] ?? '';

// Si se presiona "Eliminar filtros", vaciar variables
if (isset($_POST['limpiar'])) {
    $jornada = '';
    $sede    = '';
    $curso   = '';
}

// --- Consulta (solo estudiantes) ---
$resultado = null;
if ($persona === '1') {
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

    if ($jornada !== '') {
        $consulta .= " AND de.jornada_dato = '" . mysqli_real_escape_string($enlace, $jornada) . "'";
    }
    if ($sede !== '') {
        $consulta .= " AND de.sede_dato = '" . mysqli_real_escape_string($enlace, $sede) . "'";
    }
    if ($curso !== '') {
        $consulta .= " AND oe.gradop_observador = '" . mysqli_real_escape_string($enlace, $curso) . "'";
    }

    $resultado = mysqli_query($enlace, $consulta);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($enlace));
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #017800;">
        <div class="container-fluid">
            <a class="navbar-brand fs-4 ms-4" style="font-weight: bold; color: white; font-size: 25px; cursor: default;" href=""><b>POE</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto rounded-pill gap-2" style="font-weight: bold; background-color: #00ac4a;">
                    <li class="nav-item"><a class="nav-link px-4 py-2 textos_navbar" href="../index.html">POE</a></li>
                    <li class="nav-item"><a class="nav-link px-4 py-2 textos_navbar" href="../PUBLICO/ESCUELA DE PADRES/escuela.html">Escuela De Padres</a></li>
                    <li class="nav-item"><a class="nav-link px-4 py-2 textos_navbar" href="../PUBLICO/FORO/foro.html">Foro</a></li>
                    <li class="nav-item"><a class="nav-link px-4 py-2 textos_navbar" href="../PUBLICO/TALLERES FORMATIVOS/taller.html">Talleres Formativos</a></li>
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
                <div class="col-2">
                    <div class="cajita_opciones d-flex flex-column rounded-5 d-grid gap-3">

                        <select name="sede" class="boton_cajita rounded-pill mx-2">
                            <option disabled <?php if (empty($sede)) echo 'selected'; ?>>Sede</option>
                            <option value="Central" <?php if ($sede === 'Central') echo 'selected'; ?>>Sede Central</option>
                            <option value="Los Martires" <?php if ($sede === 'Los Martires') echo 'selected'; ?>>Los Martires</option>
                            <option value="Floresmiro" <?php if ($sede === 'Floresmiro') echo 'selected'; ?>>Floresmiro</option>
                            <option value="Elena Lara" <?php if ($sede === 'Elena Lara') echo 'selected'; ?>>Elena Lara</option>
                        </select>

                        <select name="jornada" class="boton_cajita rounded-pill mx-2">
                            <option disabled <?php if (empty($jornada)) echo 'selected'; ?>>Jornada</option>
                            <option value="Mañana" <?php if ($jornada === 'Mañana') echo 'selected'; ?>>Mañana</option>
                            <option value="Tarde" <?php if ($jornada === 'Tarde')  echo 'selected'; ?>>Tarde</option>
                        </select>

                        <input type="text" name="curso" value="<?php echo htmlspecialchars($curso); ?>"
                            class="boton_cajita rounded-pill mx-2" id="curso" placeholder="Curso">

                        <p class="w-100 text-center" style="font-weight: bold;">Ej: 1002</p>

                        <!-- Botón Enviar -->
                        <input class="actualizar rounded-pill" type="submit" name="actualizar" value="Enviar">

                        <!-- Botón Limpiar -->
                        <input class="actualizar rounded-pill" type="submit" name="limpiar"
                            value="Eliminar filtros" style="color:black; margin: 0 !important">

                    </div>
                </div>

        </form>

        <!-- Tabla de resultados -->
        <div class="col-10">
            <table class="m-4 table table-hover">
                <tr>
                    <th class="campo">ID</th>
                    <th class="campo">Nombre</th>
                    <th class="campo">Documento De Identidad</th>
                    <th class="campo">Jornada</th>
                    <th class="campo">Sede</th>
                    <th class="campo">Curso</th>
                </tr>
                <?php
                if ($persona === '1') {
                    if ($resultado && mysqli_num_rows($resultado) > 0) {
                        while ($colum = mysqli_fetch_assoc($resultado)) { ?>
                            <tr>
                                <td class="campo"><?php echo (int)$colum['id_dato']; ?></td>
                                <td class="campo"><?php echo htmlspecialchars($colum['nom_dato']); ?></td>
                                <td class="campo"><?php echo htmlspecialchars($colum['doc_dato']); ?></td>
                                <td class="campo"><?php echo htmlspecialchars($colum['jornada_dato']); ?></td>
                                <td class="campo"><?php echo htmlspecialchars($colum['sede_dato']); ?></td>
                                <td class="campo"><?php echo htmlspecialchars($colum['gradop_observador']); ?></td>
                                <td class="ingreso">
                                    <!-- Form independiente por fila -->
                                    <form action="../PRIVADO/ESTUDIANTES/INFO_INDIVIDUAL.php" method="POST">
                                        <input type="hidden" name="id_dato"
                                            value="<?php echo htmlspecialchars($colum['id_dato']); ?>">
                                        <button type="submit" class="btn"
                                            style="border:none; background:none; cursor:pointer; color:black;">
                                            <span class="material-symbols-outlined fs-2">login</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                <?php }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No hay estudiantes registrados</td></tr>";
                    }
                } elseif ($persona === '2') {
                    echo "<tr><td colspan='7' class='text-center'>No hay egresados registrados</td></tr>";
                } else {
                    echo "<tr><td colspan='7' class='text-center'>⚠ Escoge entre estudiantes o egresados</td></tr>";
                }
                ?>
            </table>
        </div>
    </div> <!-- AQUÍ cierro el form de filtros antes de la tabla -->

    <script src="../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>