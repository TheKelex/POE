<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "poe");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener datos de la tabla info_index
$sql = "SELECT * FROM info_index WHERE id_index = 1";
$resultado = $conexion->query($sql);
$datos = $resultado->fetch_assoc();

// Función para obtener ruta de imagen
function img_src_for($valorDB) {
    if (empty($valorDB)) return "./Imagenes/Img_Divisiones.png"; // placeholder por si no hay nada
    return "./EDITOR_INDEX/" . ltrim($valorDB, './'); // asegurar que apunte a la carpeta correcta
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POE</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="./bootstrap-5.3.7-dist/css/bootstrap.css">
</head>

<body>

    <div class="w-100">
        <img src="./Imagenes/Banner.png" alt="" class="img-fluid" style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <!--Inicio barra de navegacion-->
    <nav class="navbar navbar-expand-lg" style="background-color: #017800;">
        <div class="container-fluid">
            <a class="navbar-brand fs-4 ms-4" style="font-weight: bold; color: white; font-size: 25px; cursor: default;" href=""><b>POE</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto rounded-pill gap-2" style="font-weight: bold; background-color: #378b4a;">
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="./PUBLICO/ESCUELA DE PADRES/escuela.php">Escuela de Padres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="./PUBLICO/TALLERES FORMATIVOS/taller.php">Talleres Formativos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="./PRIVADO/INICIO SESION/inicio.php">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--Fin inicio barra de navegacion-->



    <!--POE-->
    <div class="container my-5" style="position: relative;">
        <div class="row align-items-center">

            <!-- Columna de texto -->
            <div class="col-12 col-md-6 my-5">
                <h1 class="titulo_principal">
                    <?= htmlspecialchars($datos['titulo_principal'] ?? 'PROYECTO DE ORIENTACIÓN ESCOLAR (POE)') ?>
                </h1>

                <p class="textos">
                    <?= htmlspecialchars($datos['desc_principal'] ?? '“Orientar no es solo guiar caminos...”') ?>
                </p>

                <a href="#que_es_el_poe" class="btn rounded-pill px-4 py-2 mt-3 shadow w-30 w-md-auto" id="boton_1">
                    ¿Qué es el POE?
                </a>
            </div>

            <!-- Columna de imagen -->
            <div class="col-12 col-md-6 text-center" style="width: 50%;">
                <img src="./Imagenes/Logo_Mariposas.png" alt="" class="img-fluid w-100 borde">
            </div>
        </div>

        <div class="logo-container text-start my-4">
            <img src="./Imagenes/Logo_Tecnico.png" alt="" class="img-fluid" style="max-width: 200px;">
        </div>
    </div>
    <!--Fin POE-->

    <br><br>

    <img src="./Imagenes/HR.png" class="img-fluid" width="100%">


    <!--Inicio De Que Es El POE-->
    <h1 class="text-center my-4 titulos rounded-pill" id="que_es_el_poe">
        <?= htmlspecialchars($datos['titulo_sec'] ?? '¿Qué es el POE?') ?>
    </h1>

    <p class="texto_que m-4 text-justify w-75 mx-auto">
        <?= htmlspecialchars($datos['desc_sec'] ?? 'El POE es el Proyecto de Orientación Escolar del Técnico Superior Neiva...') ?>
    </p>

    <center>
        <img src="<?= img_src_for($datos['img_sec'] ?? '') ?>" class="img-fluid m-4 rounded-5" style="width: 600px; height: auto;">
    </center>
    <!--Fin De Que Es El POE-->

    <img src="./Imagenes/HR.png" class="img-fluid" width="100%">


    <!--Inicio De La Informacion-->
    <div class="row shadow">

        <div class="col-4" style="border-right: 2px solid;"> <!--Division 1-->
            <br>
            <p class="titulos rounded-pill" style="width: 90%;">
                <?= htmlspecialchars($datos['titulo_division1'] ?? 'Visión') ?>
            </p>
            <br>
            <p class="textos_divisiones">
                <?= htmlspecialchars($datos['desc_division1'] ?? 'Texto de ejemplo de la primera división.') ?>
            </p>
            <img src="<?= img_src_for($datos['img_division1'] ?? '') ?>" style="width: 90%; height: auto;" class="rounded-5 m-4">
            <br>
        </div>

        <div class="col-4" style="border-right: 2px solid;"> <!--Division 2-->
            <br>
            <p class="titulos rounded-pill" style="width: 90%;">
                <?= htmlspecialchars($datos['titulo_division2'] ?? 'Objetivo') ?>
            </p>
            <br>
            <p class="textos_divisiones">
                <?= htmlspecialchars($datos['desc_division2'] ?? 'Texto de ejemplo de la segunda división.') ?>
            </p>
            <img src="<?= img_src_for($datos['img_division2'] ?? '') ?>" style="width: 90%; height: auto;" class="rounded-5 m-4">
            <br>
        </div>

        <div class="col-4"> <!--Division 3-->
            <br>
            <p class="titulos rounded-pill" style="width: 90%;">
                <?= htmlspecialchars($datos['titulo_division3'] ?? 'Misión') ?>
            </p>
            <br>
            <p class="textos_divisiones">
                <?= htmlspecialchars($datos['desc_division3'] ?? 'Texto de ejemplo de la tercera división.') ?>
            </p>
            <img src="<?= img_src_for($datos['img_division3'] ?? '') ?>" style="width: 90%; height: auto;" class="rounded-5 m-4">
            <br>
        </div>

    </div>
    <!--Fin De La Informacion-->


    <!--Inicio Contacto Psicoorientadora-->
    <div class="position-relative text-center">
        <img src="./Imagenes/Contacto.png" class="img-fluid w-100">
        <div class="position-absolute top-50 start-50 translate-middle">
            <h2 class="titulo_contacto text-center">Contacto Psicoorientadora</h2>
            <p class="text-center m-4" style="color: white;">Contacto Directo Vía Email</p>
            <p class="email rounded-pill m-5">
                <?= htmlspecialchars($datos['contacto_psicoo'] ?? 'Informacion No Disponible') ?>
            </p>
        </div>
    </div>
    <!--Fin Contacto Psicoorientadora-->


    <!--Inicio Footer-->
    <footer style="background: url(./Imagenes/footer.png) center center/cover;">
        <div class="container py-5 text-start">
            <div class="row g-4">
                <div class="col-md-3 d-flex align-items-center text-start">
                    <p class="mb-3" style="color: #04BF55;">“¡Palpitante juventud! Adelante con el arte, tenemos que avanzar.
Es consigna de buen estudiante: con la brega, la meta alcanzar.”</p>
                </div>

                <div class="col-md-2">
                    <h5 class="titulo_footer">Páginas</h5>
                    <ul class="list-unstyled">
                        <li><a href="./PUBLICO/ESCUELA DE PADRES/escuela.php" class="texto_footer text-decoration-none">Escuela de padres</a></li>
                        <li><a href="./PUBLICO/TALLERES FORMATIVOS/taller.php" class="texto_footer text-decoration-none">Talleres formativos</a></li>
                        <li><a href="./PRIVADO/INICIO SESION/inicio.php" class="texto_footer text-decoration-none">Iniciar sesión</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h5 class="titulo_footer">Proyectos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Control del PAE</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Almacén</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">POE</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h5 class="titulo_footer">Institucional</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Mineducación</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Gov</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">PAE</a></li>
                    </ul>
                </div>
            </div>
            <p class="text-start mb-0" style="color: #04BF55;">© Desarrollo de Software 2025</p>
        </div>
    </footer>
    <!--Fin Footer-->

    <script src="./bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>