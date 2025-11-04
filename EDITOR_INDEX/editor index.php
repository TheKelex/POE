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

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "poe");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener datos de la tabla info_index con id = 1
$sql = "SELECT * FROM info_index WHERE id_index = 1";
$resultado = $conexion->query($sql);
$datos = $resultado->fetch_assoc();

// ✅ Función para obtener la ruta visible de una imagen
function img_src_for($valorDB)
{
    if (empty($valorDB)) return null;
    return ltrim($valorDB, './'); // Limpia posibles puntos o barras iniciales
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
    <link rel="stylesheet" href="../bootstrap-5.3.7-dist/css/bootstrap.css">
</head>

<body>

    <div class="w-100">
        <img src="../Imagenes/Banner.png" alt="" class="img-fluid"
            style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <!--Inicio barra de navegacion-->
    <nav class="navbar navbar-expand-lg" style="background-color: #017800;">
        <div class="container-fluid">
            <a class="navbar-brand fs-4 ms-4" style="font-weight: bold; color: white; font-size: 25px; cursor: default;">
                <b>POE</b>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto rounded-pill gap-2" style="font-weight: bold; background-color: #378b4a;">
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="../PRIVADO/estudiantes.php">Volver</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--Fin inicio barra de navegacion-->

    <!--Inicio sistema de notificaciones acerca de la actualizacion del index-->

    <br>

    <?php
    if (isset($_GET['msg'])) {

        switch ($_GET['msg']) {

            case 'ok':
                echo '<div class="alert correcto text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill" role="alert">
                Página principal actualizada correctamente
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                break;

            case 'error_insert':
                echo '<div class="alert alerta text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill" role="alert">
                Error al actualizar la página principal
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                break;

            case 'error_update':
                // Mostrar los detalles si existen
                $detalles = isset($_GET['detalles']) ? urldecode($_GET['detalles']) : 'Error desconocido al guardar las imágenes.';
                echo '<div class="alert alerta text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill" role="alert">
                Algunas imágenes no se guardaron correctamente:<br>
                <small>' . htmlspecialchars($detalles) . '</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                break;
        }
    }
    ?>


    <!--Fin sistema de notificaciones acerca de la actualizacion del index-->

    <form action="actualizar_index.php" method="POST" enctype="multipart/form-data">

        <!--POE-->
        <div class="container my-5" style="position: relative;">
            <div class="row align-items-center">

                <!-- Columna de texto -->
                <div class="col-12 col-md-6 my-5">
                    <textarea class="form form-control titulo_principal p-0" rows="1"
                        name="titulo_principal" style="margin-bottom: 0.5rem;"><?= htmlspecialchars($datos['titulo_principal'] ?? '') ?></textarea>

                    <textarea class="form form-control textos p-0 text-justify" rows="1"
                        name="desc_principal"><?= htmlspecialchars($datos['desc_principal'] ?? '') ?></textarea>

                    <a href="#que_es_el_poe" class="btn rounded-pill px-4 py-2 mt-3 shadow w-30 w-md-auto" id="boton_1">
                        ¿Qué es el POE?
                    </a>
                </div>

                <!-- Columna de imagen -->
                <div class="col-12 col-md-6 text-center">
                    <img src="../Imagenes/Logo_Mariposas.png" alt="" class="img-fluid w-100 borde">
                </div>

            </div>

            <div class="logo-container text-start my-4">
                <img src="../Imagenes/Logo_Tecnico.png" alt="" class="img-fluid" style="max-width: 200px;">
            </div>

        </div>

        <br><br>
        <img src="../Imagenes/HR.png" class="img-fluid" width="100%">

        <!--Inicio Sección ¿Qué es el POE?-->
        <div class="w-100">

            <textarea class="form form-control text-center rounded-pill my-4 titulos" rows="1"
                id="que_es_el_poe" name="titulo_sec"><?= htmlspecialchars($datos['titulo_sec'] ?? '') ?></textarea>

            <center>
                <textarea class="form m-4 form-control text-justify w-75  texto_que" name="desc_sec" rows="1"><?= htmlspecialchars($datos['desc_sec'] ?? '') ?></textarea>
            </center>

            <center>
                <div class="image-upload">
                    <div class="preview-container">
                        <?php $src = img_src_for($datos['img_sec'] ?? ''); ?>
                        <?php if (!empty($src)): ?>
                            <img class="preview-img rounded-5 mb-2" src="<?= $src ?>" alt="Vista previa"
                                style="display:block; max-width:100%; height:auto;">
                            <span class="material-symbols-outlined icon-placeholder"
                                style="display:none; font-size:12rem;">image</span>
                        <?php else: ?>
                            <img class="preview-img rounded-5 mb-2"
                                style="display:none; max-width:100%; height:auto;">
                            <span class="material-symbols-outlined icon-placeholder"
                                style="display:inline-block; font-size:12rem;">image</span>
                        <?php endif; ?>
                    </div>
                    <label class="btn btn-custom px-4 py-2 mt-3">
                        Subir imagen
                        <input type="file" name="img_sec" accept="image/*">
                    </label>
                </div>
            </center>
        </div>

        <br>

        <!--Fin Sección ¿Qué es el POE?-->

        <img src="../Imagenes/HR.png" class="img-fluid" width="100%">

        <!--Inicio De Las Divisiones-->
        <div class="row">

            <?php
            $divisiones = [
                ['titulo_division1', 'desc_division1', 'img_division1'],
                ['titulo_division2', 'desc_division2', 'img_division2'],
                ['titulo_division3', 'desc_division3', 'img_division3']
            ];

            foreach ($divisiones as $index => [$titulo, $desc, $img]) {
                $src = img_src_for($datos[$img] ?? '');
                $borde = $index < 2 ? 'border-right: 2px solid;' : '';
            ?>
                <div class="col-4" style="<?= $borde ?>">
                    <br>
                    <textarea class="form form-control text-center rounded-pill titulos" rows="1"
                        style="width: 90% !important;" name="<?= $titulo ?>"><?= htmlspecialchars($datos[$titulo] ?? '') ?></textarea>
                    <br>
                    <textarea class="form m-4 form-control textos_divisiones p-2" name="<?= $desc ?>" rows="1"><?= htmlspecialchars($datos[$desc] ?? '') ?></textarea>

                    <center>
                        <div class="image-upload" style="width: 90% !important;">
                            <div class="preview-container" style="text-align: center !important; width: auto !important;">
                                <?php if (!empty($src)): ?>
                                    <img class="preview-img rounded-5 mb-2" src="<?= $src ?>" alt="Vista previa"
                                        style="display:block; width: 100%; height:auto;">
                                    <span class="material-symbols-outlined icon-placeholder"
                                        style="display:none; font-size:12rem;">image</span>
                                <?php else: ?>
                                    <img class="preview-img rounded-5 mb-2"
                                        style="display:none; max-width:100%; height:auto;">
                                    <span class="material-symbols-outlined icon-placeholder"
                                        style="display:inline-block; font-size:12rem;">image</span>
                                <?php endif; ?>
                            </div>
                            <label class="btn btn-custom px-4 py-2 mt-3">
                                Subir imagen
                                <input type="file" name="<?= $img ?>" accept="image/*">
                            </label>
                        </div>
                    </center>
                    <br>
                </div>
            <?php } ?>

        </div>

        <!--Fin Divisiones-->

        <!--Contacto-->
        <div class="position-relative text-center">
            <img src="../Imagenes/Contacto.png" class="img-fluid w-100">
            <div class="position-absolute top-50 start-50 translate-middle">
                <h2 class="titulo_contacto text-center">Contacto Psicoorientadora</h2>
                <p class="text-center m-4" style="color: white;">Contacto Directo Via Email</p>
                <center>
                    <textarea class="form m-5 form-control email rounded-pill p-2" name="cont" rows="1"><?= htmlspecialchars($datos['contacto_psicoo'] ?? '') ?></textarea>
                </center>
            </div>
        </div>

        <div class="d-flex justify-content-center pe-4">
            <button class="btn-actualizar rounded-pill my-4" type="submit">
                <span class="material-symbols-outlined mx-1">edit</span>Actualizar
            </button>
        </div>

    </form>

    <!--Footer-->
    <footer style="background: url(../Imagenes/footer.png) center center/cover;">
        <div class="container py-5 text-start">
            <div class="row g-4">
                <div class="col-md-3 d-flex align-items-center text-start">
                    <p class="mb-3" style="color: #04BF55;">Palpitante juventud adelante con el arte tenemos que avanzar, es consigna de buen estudiante con la brega la meta alcanzar</p>
                </div>
                <div class="col-md-2">
                    <h5 class="titulo_footer">Paginas</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Escuela de padres</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Foro</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Talleres formativos</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Iniciar Sesión</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5 class="titulo_footer">Proyectos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Control de seguimiento del PAE</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Almacén</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">POE</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5 class="titulo_footer">Institucional</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="texto_footer text-decoration-none">Minieducación</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">Gov</a></li>
                        <li><a href="#" class="texto_footer text-decoration-none">PAE</a></li>
                    </ul>
                </div>
            </div>
            <p class="text-start mb-0" style="color: #04BF55;">© Software Development 2025</p>
        </div>
    </footer>

    <script src="../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Ajuste automático de altura para textareas
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll("textarea").forEach(el => {
                el.style.overflow = "hidden";
                el.style.resize = "none";
                const ajustarAltura = () => {
                    el.style.height = "auto";
                    el.style.height = el.scrollHeight + "px";
                };
                el.addEventListener("input", ajustarAltura);
                ajustarAltura();
            });
        });

        // Vista previa de imágenes
        document.querySelectorAll('.image-upload').forEach((bloque) => {
            const input = bloque.querySelector('input[type="file"]');
            const img = bloque.querySelector('.preview-img');
            const icono = bloque.querySelector('.icon-placeholder');
            input.addEventListener('change', (e) => {
                const archivo = e.target.files[0];
                if (archivo) {
                    const lector = new FileReader();
                    lector.onload = function(evento) {
                        img.src = evento.target.result;
                        img.style.display = 'block';
                        if (icono) icono.style.display = 'none';
                    };
                    lector.readAsDataURL(archivo);
                } else {
                    img.style.display = 'none';
                    if (icono) icono.style.display = 'inline-block';
                }
            });
        });
    </script>

</body>

</html>