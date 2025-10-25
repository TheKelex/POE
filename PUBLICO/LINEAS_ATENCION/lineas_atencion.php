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
function img_src_for($valorDB)
{
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
    <link rel="stylesheet" href="../../bootstrap-5.3.7-dist/css/bootstrap.css">
</head>

<body>

    <div class="w-100">
        <img src="../../Imagenes/Banner.png" alt="" class="img-fluid" style="width: 100%; max-height: 160px; object-fit: cover;">
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
                        <a class="nav-link px-4 py-2 textos_navbar" href="../../index.php">POE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="../../PUBLICO/ESCUELA DE PADRES/escuela.php">Escuela de Padres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="../../PUBLICO/TALLERES FORMATIVOS/taller.php">Talleres Formativos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="../../PRIVADO/INICIO SESION/inicio.php">Iniciar Sesión</a>
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
                    LINEAS DE ATENCIÓN
                </h1>

                <p class="textos">
                    Aquí encontrarás nuestras rutas de atención y apoyo.
                </p>

                <a href="#que_es_el_poe" class="btn rounded-pill px-4 py-2 mt-3 shadow w-30 w-md-auto" id="boton_1">
                    ¡Conocer más!!!
                </a>
            </div>

            <!-- Columna de imagen -->
            <div class="col-12 col-md-6 text-center" style="width: 50%;">
                <img src="../../Imagenes/Logo_Mariposas.png" alt="" class="img-fluid w-100 borde">
            </div>
        </div>

        <div class="logo-container text-start my-4">
            <img src="../../Imagenes/Logo_Tecnico.png" alt="" class="img-fluid" style="max-width: 200px;">
        </div>
    </div>
    <!--Fin POE-->

    <br><br>

    <img src="../../Imagenes/HR.png" class="img-fluid" width="100%">


    <!--Inicio De Que Es El POE-->
    <h1 class="text-center my-4 titulos rounded-pill">
        ¡NO TE QUEDES CALLADO!!!
    </h1>

    <p class="texto_que m-4 text-justify w-75 mx-auto">
        Esta estrategia, coordinada por la Secretaría de Salud, brinda apoyo a todas las personas que tengan la necesidad de ser escuchadas y orientadas por un profesional. La Línea de Vida es un centro de escucha que opera a través de líneas telefónicas y que ofrece atención integral las 24 horas, a través de psicólogos, cuya atención genera una reacción inmediata a las solicitudes allí expresadas. Incluso, se ofrece una relación con otras redes e instituciones si la persona así lo necesita.
    </p>

    <img src="../../Imagenes/HR.png" class="img-fluid" width="100%">

    <h1 class="text-center my-4 titulos rounded-pill">
        RUTAS DE ATENCIÓN
    </h1>

    <!--Inicio De La Informacion-->
    <div class="row d-flex justify-content-center gap-4 m-4">

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">ATENCION INTEGRAL PARA LA CONVIVENCIA ESCOLAR</h5>
                <p>Busca promover el respeto, la tolerancia y la resolución pacífica de conflictos dentro del entorno educativo. <br>
                    <b>Se activa</b> cuando hay conflictos entre estudiantes o situaciones que alteren la armonía escolar.
                </p>
            </div>
            <a href="./PDF/ATENCION_INTEGRAL_CONVIVENCIA_ESCOLAR.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">AGRESION Y ACOSO ESCOLAR INTEGRLA</h5>
                <p>Atiende casos de maltrato físico, verbal, psicológico o virtual entre estudiantes.<br>
                    <b>Se activa</b> ante evidencias o denuncias de bullying o ciberacoso.
                </p>
            </div>
            <a href="./PDF/AGRESION_ACOSO_ESCOLAR.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">CONDUCTA SUICIDA NO FATAL</h5>
                <p>Brinda atención inmediata a estudiantes con intentos o ideación suicida. <br>
                    <b>Se activa</b> ante cualquier señal de riesgo o manifestación de autolesión.
                </p>
            </div>
            <a href="./PDF/CONDUCTA_SUICIDA_NO_FATAL.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">SUICIDIO CONSUMADO</h5>
                <p>Ofrece acompañamiento psicosocial y orientación a la comunidad educativa tras la pérdida de un estudiante por suicidio. <br>
                    <b>Se activa</b> cuando ocurre el hecho, para contener emocionalmente y prevenir nuevos casos.
                </p>
            </div>
            <a href="./PDF/SUICIDIO_CONSUMADO.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">CONSUMO DE SUSTANCIAS PSICOACTIVAS</h5>
                <p>Promueve la prevención y atención de casos de uso o abuso de drogas.<br>
                    <b>Se activa</b> al identificar consumo o posesión de sustancias dentro o fuera del colegio.
                </p>
            </div>
            <a href="./PDF/CONSUMO_SUSTANCIAS_PSICOACTIVAS.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">EMBARAZO ADOLECENTE TEMPRANO</h5>
                <p>Brinda orientación médica, psicológica y educativa a adolescentes gestantes.<br>
                    <b>Se activa</b> cuando se confirma o se sospecha un embarazo en estudiante menor de edad.
                </p>
            </div>
            <a href="./PDF/EMBARAZO_ADOLECENTE_TEMPRANO.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">VIOLENCIA INTRAFAMILIAR</h5>
                <p>Protege a menores víctimas de maltrato físico, psicológico o negligencia en su hogar.<br>
                    <b>Se activa</b> ante cualquier señal o denuncia de abuso dentro de la familia.
                </p>
            </div>
            <a href="./PDF/VIOLENCIA_INTRAFAMILIAR.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">VIOLENCIA POR DISCRIMINACIÓN</h5>
                <p>Busca garantizar el respeto a la diversidad y prevenir tratos injustos por origen, condición o creencia.<br>
                    <b>Se activa</b> cuando un estudiante es excluido, humillado o agredido por diferencias personales.
                </p>
            </div>
            <a href="./PDF/VIOLENCIA_DISCRIMACION.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">VIOLENCIA SEXUAL</h5>
                <p>Atiende y protege a víctimas de abuso o acoso sexual, garantizando atención médica, psicológica y legal.<br>
                    <b>Se activa</b> ante cualquier sospecha, relato o evidencia de abuso sexual.</p>
            </div>
            <a href="./PDF/VIOLENCIA_SEXUAL.pdf" class="btn actualizar m-3">Visualizar</a>
        </div>

    </div>
    <!--Fin De La Informacion-->


    <!--Inicio Contacto Psicoorientadora-->
    <div class="position-relative text-center">
        <img src="../../Imagenes/Contacto.png" class="img-fluid w-100">
        <div class="position-absolute top-50 start-50 translate-middle">
            <h2 class="titulo_contacto text-center">ACCEDE AL DOCUMENTO OFICIAL</h2>
            <p class="text-center m-4" style="color: white;">Protocolos para la activación de las Rutas de Atención Integral según la Ley Nacional De Convivencia Escolar y la Formación para el ejercicio de los Derechos Humanos, la Educación para la Sexualidad y la Prevención y Mitigación de la Violencia Escolar.</p>
            <a href="https://www.mineducacion.gov.co/1759/articles-327397_archivo_pdf_proyecto_decreto.pdf" class="email d-flex rounded-pill w-75 mx-auto justify-content-center m-5" style="text-decoration: none;">
                LEY 1620 DE 2013
            </a>
        </div>
    </div>
    <!--Fin Contacto Psicoorientadora-->


    <!--Inicio Footer-->
    <footer style="background: url(./Imagenes/footer.png) center center/cover;">
        <div class="container py-5 text-start">
            <div class="row g-4">
                <div class="col-md-3 d-flex align-items-center text-start">
                    <p class="mb-3" style="color: #04BF55;" id="que_es_el_poe">“¡Palpitante juventud! Adelante con el arte, tenemos que avanzar.
                        Es consigna de buen estudiante: con la brega, la meta alcanzar.”</p>
                </div>

                <div class="col-md-2">
                    <h5 class="titulo_footer">Páginas</h5>
                    <ul class="list-unstyled">
                        <li><a href="../../PUBLICO/ESCUELA DE PADRES/escuela.php" class="texto_footer text-decoration-none">Escuela de padres</a></li>
                        <li><a href="../../PUBLICO/TALLERES FORMATIVOS/taller.php" class="texto_footer text-decoration-none">Talleres formativos</a></li>
                        <li><a href="../../PRIVADO/INICIO SESION/inicio.php" class="texto_footer text-decoration-none">Iniciar sesión</a></li>
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
                        <li><a href="https://www.mineducacion.gov.co/portal/" class="texto_footer text-decoration-none">Mineducación</a></li>
                        <li><a href="https://www.gov.co/" class="texto_footer text-decoration-none">Gov</a></li>
                        <li><a href="https://www.mineducacion.gov.co/portal/micrositios-preescolar-basica-y-media/Programa-de-Alimentacion-Escolar-PAE/" class="texto_footer text-decoration-none">PAE</a></li>
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