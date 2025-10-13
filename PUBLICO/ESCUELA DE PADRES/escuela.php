<?php

// --- Conexión a la BD ---
$servidor = "localhost";
$usuario_db = "root";
$contraseña = "";
$basededatos = "poe";

$conexion = mysqli_connect($servidor, $usuario_db, $contraseña, $basededatos);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// --- Consultar todos los posts ---
$posts = $conexion->query("SELECT * FROM foro WHERE destino_foro = 'escuela' ORDER BY id_foro DESC");

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../../bootstrap-5.3.7-dist/css/bootstrap.css">
</head>

<body>

    <div class="w-100">
        <img src="../../Imagenes/Banner.png" alt="" class="img-fluid"
            style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>



    <!--Inicio barra de navegacion-->

    <nav class="navbar navbar-expand-lg" style="background-color: #017800;">
        <div class="container-fluid">
            <a class="navbar-brand fs-4 ms-4" style="font-weight: bold; color: white; font-size: 25px;"
                href=""><b>POE</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto rounded-pill gap-2" style="font-weight: bold; background-color: #378b4a;">
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="../../index.html">POE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar"
                            href="../TALLERES FORMATIVOS/taller.php">Talleres Formativos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar"
                            href="../../PRIVADO/INICIO SESION/inicio.php">Iniciar Sesion</a>
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

                <h1 class="titulo-principal">ESCUELA
                    DE<br> DE PADRES (EDP)
                </h1>

                <p class="textos">
                    "Un espacio para fortalecer el vínculo con tus hijos a través del diálogo, la escucha y la
                    comprensión mutua."
                </p>

                <a href="#escuela_padres" class="btn rounded-pill px-4 py-2 mt-3 shadow w-30 w-md-auto" id="boton_1">
                    ¿cual es su funcion?
                </a>

                <br>

            </div>

            <!-- Columna de imagen de manos con mariposas -->
            <div class="col-12 col-md-6 text-center" style="width: 50%;">

                <img src="../../Imagenes/Logo_Mariposas.png" alt="" class="img-fluid w-100">

            </div>

        </div>

        <div class="logo-container text-start my-4">
            <img src="../../Imagenes/Logo_Tecnico.png" alt="" class="img-fluid" style="max-width: 200px;">
        </div>

    </div>

    <!--Fin POE-->

    <br><br><br><br><br>

    <img src="../../Imagenes/HR.png" class="img-fluid" width="100%">



    <!--Inicio De la funcion de la escuerla de padres-->

    <h1 class="text-center my-4 titulos rounded-pill" id="escuela_padres">

        ¿La funcion de la escuela de padres?

    </h1>

    <h1 class="text-center my-6 textos">

        La escuela de padres busca fortalecer el lazo de padre he hijo con herramientas practicas y estrategias para
        lograr eso

    </h1>
    <br><br>


    <div class="d-flex flex-column align-items-center">

        <div class="d-flex flex-column align-items-start">
            <div class="d-flex align-items-center mb-3 cuadro">
                <div class="d-flex align-items-center justify-content-center circulos me-3 ">1</div>
                <p class="textos mb-0">Mejorar el laso padre he hijo </p>
            </div>

            <div class="d-flex align-items-center cuadro" style="width: 510px;">
                <div class="d-flex align-items-center justify-content-center circulos me-3">2</div>
                <p class="textos mb-0">Dar talleres para estos </p>
            </div>
        </div>


    </div>


    <center>
        <img src="./Imagenes_escuela/escuela padres.png" class="img-fluid m-4 borde"
            style="width: 600px; height: auto;">
    </center>

    <br><br><br><br>
    <!--Fin De Que Es La Escuela De Padres-->

    <!--Inicio De Los POST-->

    <div class="w-100">

        <?php while ($post = $posts->fetch_assoc()) { ?>

            <img src="../../Imagenes/HR.png" class="img-fluid" width="100%">

            <div class="text-center w-50 mx-auto my-4">

                <h1><?= htmlspecialchars($post['titulo_foro']) ?></h1>

            </div>

            <div class="text-justify w-75 mx-auto fw-bold texto-post">

                <p><?= nl2br(htmlspecialchars($post['texto_foro'])) ?></p>

            </div>

            <?php

            // Rutas relativas a la carpeta de uploads
            $basePath = "../../PRIVADO/EDIT/FORM_FORO/";
            $imagenes = [
                $post['archivo1_foro'],
                $post['archivo2_foro'],
                $post['archivo3_foro']
            ];

            // Filtrar imágenes válidas (que existan físicamente)
            $imagenes_validas = array_filter($imagenes, function ($img) use ($basePath) {
                return !empty($img) && file_exists($basePath . $img);
            });

            // Mostrar el recuadro solo si hay imágenes válidas
            if (!empty($imagenes_validas)) {

            ?>

                <div class="d-flex w-75 mx-auto my-4 justify-content-center gap-4 rounded-5 contenedor-imagenes">

                    <?php foreach ($imagenes_validas as $img) { ?>
                        <div class="imagen-box">

                            <img src="<?= $basePath . $img ?>" class="preview-img rounded-5" alt="Imagen del post">

                        </div>

                    <?php } ?>

                </div>

            <?php } ?>

        <?php } ?>

    </div>

    <!--Fin De Los POST-->

    <!--Inicio Contacto Psicoorientadora-->

    <div class="position-relative text-center">

        <img src="../../Imagenes/Contacto.png" class="img-fluid w-100">

        <div class="position-absolute top-50 start-50 translate-middle">

            <h2 class="titulo_contacto text-center">Contacto Psicoorientadora</h2>
            <p class="text-center m-4" style="color: white;">Contacto Directo Via Email</p>
            <p class="email rounded-pill m-5">DaCode@example.com</p>

        </div>

    </div>

    <!--Fin Contacto Psicoorientadora-->

    <!--Inicio Footer-->

    <footer style="background: url(./Imagenes/footer.png) center center/cover; ">
        <div class="container py-5 text-start">

            <div class="row g-4">

                <div class="col-md-3 d-flex align-items-center text-start">
                    <p class="mb-3" style="color: #04BF55;">Palpitante juventud adelante con el arte tenemos que
                        avanzar,es consigna de buen estudiante con la brega la meta alcanzar
                    </p>
                </div>

                <div class="col-md-2">
                    <h5 class="titulo_footer">Paginas</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Escuela de padres</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Foro</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Talleres formativos</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Iniciar Sesion</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h5 class="titulo_footer">Proyectos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Control de seguimiento del PAE</a>
                        </li>
                        <li><a href="#" class="texto_footer text-decoration-none">Almacen</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">POE</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h5 class="titulo_footer">Institucional</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Minieducacion</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Gov</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">PAE</a></li>
                    </ul>
                </div>

            </div>

            <p class="text-start mb-0" style="color: #04BF55;">© Software Development 2025</p>

        </div>
    </footer>

    <!--Fin Footer-->

    <script src="../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>