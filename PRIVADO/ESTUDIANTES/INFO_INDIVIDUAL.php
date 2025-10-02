<?php
session_start();
$_SESSION['ultimo_movimiento'] = time();
// Guardar el id en sesión si viene del formulario
if (isset($_POST["id_dato"])) {
    $_SESSION["id_dato"] = $_POST["id_dato"];
}

// Verificar si existe en sesión
if (!isset($_SESSION["id_dato"])) {
    echo "No se recibió el estudiante.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info_individual</title>

    <link rel="stylesheet" href="../../bootstrap-5.3.7-dist/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="./style.css">
</head>

<body>

    <div class="w-100">
        <img src="../../Imagenes/Banner.png" alt="" class="img-fluid" style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <div class="fondo"> <!--Para tener esa margen y el color blanco-->

        <a href="../estudiantes.php" class="rounded-pill m-4 volver"><span class="material-symbols-outlined mx-2">logout</span>Volver</a>

        <div class="row">
            <?php 
                $caracterizacion = '<div class="col rounded-5 mx-5 opciones">

                <center>
                    <h3 class="p-5" style="font-weight: bold;">Caracterizacion</h3>
                </center>

                <center>
                    <a href="./CARACTERIZACION/caracterizacion.php" class="rounded-pill m-4 cargar">Cargar</a>
                </center>

            </div>';

                $ficha_individual = '<div class="col rounded-5 mx-5 opciones">

                <center>
                    <h3 class="p-5" style="font-weight: bold;">Ficha Individual</h3>
                </center>

                <center>
                    <a href="./FICHA INDIVIDUAL/ficha.php" class="rounded-pill m-4 cargar">Cargar</a>
                </center>

            </div>';

                $observador = '<div class="col rounded-5 mx-5 opciones">

                <center>
                    <h3 class="p-5" style="font-weight: bold;">Observador</h3>
                </center>

                <center>
                    <a href="./OBSERVADOR/observador.php" class="rounded-pill m-4 cargar">Cargar</a>
                </center>

            </div>';

                $folder = '<div class="col rounded-5 mx-5 opciones">

                <center>
                    <h3 class="p-5" style="font-weight: bold;">Folder</h3>
                </center>

                <center>
                    <a href="./FOLDER/folder.html" class="rounded-pill m-4 cargar">Cargar</a>
                </center>

            </div>';

                if ($_SESSION['tipo_usuario']==='administrador') {
                    echo $caracterizacion;
                    echo $ficha_individual;
                    echo $observador;
                    echo $folder;
                } elseif ($_SESSION['tipo_usuario']==='docente') {
                    echo $caracterizacion;
                    echo $observador;
                    echo $folder;
                }elseif ($_SESSION['tipo_usuario']==='psicorientacion') {
                    echo $caracterizacion;
                    echo $ficha_individual;
                    echo $observador;
                    echo $folder;
                }
            ?>

        </div>

    </div>

    <script src="../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>