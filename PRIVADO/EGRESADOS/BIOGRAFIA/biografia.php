<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../INICIO SESION/inicio.php");
    exit();
}
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

// Verificar si existe en sesión
if (!isset($_SESSION["id_egresados"])) {
    echo "No se recibió el estudiante.";
    exit();
}
$id_egresados = $_SESSION['id_egresados'];

$sql = "SELECT 
id_egresados, 
nom_egresados, 
edad_egresados, 
fechanac_egresados,
especialidad_egresados, 
biografia_egresados,
aporte_egresados,
foto_egresados,
num_doc_egresados
FROM egresados 
WHERE $id_egresados=id_egresados"; // Consulkta SQL
$resultado = mysqli_query($enlace, $sql);
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($enlace));
}
$datos = mysqli_fetch_assoc($resultado);

$_SESSION['doc_egresados'] = $datos['num_doc_egresados']; //Guardamos en sesión el documento del estudiante

$fecha_nacimiento = $datos['fechanac_egresados']; // formato AAAA-MM-DD
$fecha = $datos['fechanac_egresados']; // por ejemplo: 2001-10-07
$edad = date_diff(date_create($fecha), date_create('today'))->y;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIOGRAFIA</title>

    <link rel="stylesheet" href="../../../bootstrap-5.3.7-dist/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="./style.css">

</head>

<body>

    <div class="w-100">
        <img src="../../../Imagenes/Banner.png" alt="" class="img-fluid" style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <a href="../egresados.php" class="rounded-pill m-4 volver"><span class="material-symbols-outlined mx-2">logout</span>Volver</a>



    <!--Grid para la primera linea (Foto e info)-->
    <div class="row align-items-center m-4">

        <!-- Columna izquierda: Foto -->
        <div class="col-3 d-flex justify-content-center">

            <!-- Formulario para cambiar foto -->
            <form action="actualizar_foto.php" method="POST" enctype="multipart/form-data">

                <div class="d-flex flex-column align-items-center w-75 mx-auto gap-3">

                    <img src="<?= htmlspecialchars($datos['foto_egresados']) ?>" class="w-100" style="width: 8rem;" alt="Imagen Del Estudiante">

                    <button type="button" class="btn btn-actualizar  d-flex w-100 align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <span class="material-symbols-outlined">edit</span>Editar
                    </button>

                </div>

            </form>

        </div>

        <!-- Columna derecha: Info -->
        <div class="col-9">

            <form action="actualizar.php" method="POST">

                <center>

                    <h2 class="titulo rounded-pill">EGRESADO</h2>
                    <input class="campo form-control rounded-pill m-4" type="text" placeholder="Nombre" value="<?= htmlspecialchars($datos['nom_egresados'] ?? '') ?>">
                    <input class="campo form-control rounded-pill m-4" type="number" placeholder="Documento" name="num_doc_egresados" value="<?= htmlspecialchars($datos['num_doc_egresados'] ?? '') ?>">
                    <input class="campo form-control rounded-pill m-4" type="date" value="<?= htmlspecialchars($datos['fechanac_egresados'] ?? '') ?>">
                    <input class="campo form-control rounded-pill m-4" type="number" placeholder="Edad" name="edad_egresados" value="<?= htmlspecialchars($edad) ?>">
                    <input class="campo form-control rounded-pill m-4" type="text" placeholder="Especialidad" value="<?= htmlspecialchars($datos['especialidad_egresados'] ?? '') ?>">

                </center>

        </div>

    </div>

    <!-- Segunda fila: Textarea -->
    <div class="row">

        <div class="col-12">

            <textarea class="area form-control mx-auto" placeholder="Agregar texto..." rows="7" name="biografia_egresados"><?= htmlspecialchars($datos['biografia_egresados'] ?? '') ?></textarea>
            <h3 style="text-align: center;">Que me gustaria aportar a la Institución</h3>
            <textarea class="area form-control mx-auto" placeholder="Agregar texto..." rows="7" name="aporte_egresados"><?= htmlspecialchars($datos['aporte_egresados'] ?? '') ?></textarea>


            <div class="d-flex justify-content-end pe-4">

                <button class="btn-actualizar rounded-pill my-4" type="submit">
                    <span class="material-symbols-outlined mx-1">edit</span>Actualizar
                </button>

            </div>

        </div>
        </form>

    </div>


    <script src="../../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>