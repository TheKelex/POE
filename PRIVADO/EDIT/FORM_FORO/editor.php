<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['actualizar']) && !isset($_POST['borrar'])) {
    unset($_GET['editar']);
    $post_editar = null;
}

// -------------------- 🧠 ZONA PHP LÓGICA --------------------

// 1️⃣ Conexión a la base de datos
$servidor = "localhost";
$usuario_db = "root";
$contraseña = "";
$basededatos = "poe";

$conexion = mysqli_connect($servidor, $usuario_db, $contraseña, $basededatos);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// 2️⃣ Borrar post
if (isset($_POST['borrar'])) {
    $id = $_POST['id_foro'];
    $consulta = $conexion->query("SELECT archivo1_foro, archivo2_foro, archivo3_foro FROM foro WHERE id_foro=$id");
    $imagenes = $consulta->fetch_assoc();

    // 🔧 Borrar imágenes si existen
    foreach ($imagenes as $img) {
        if (!empty($img) && file_exists($img)) {
            unlink($img);
        }
    }

    // 🔧 Borrar carpeta solo si está vacía
    $carpeta = "uploads/foro/$id";
    if (is_dir($carpeta)) {
        @rmdir($carpeta);
    }

    // Eliminar registro de la BD
    $conexion->query("DELETE FROM foro WHERE id_foro=$id");

    header("Location: editor.php?msg=deleted");
    exit;
}

// 3️⃣ Actualizar post (con actualización de imágenes)
if (isset($_POST['actualizar'])) {
    $id = $_POST['id_foro'];
    $destino = mysqli_real_escape_string($conexion, $_POST['destino']);
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $texto = mysqli_real_escape_string($conexion, $_POST['informaciom']);


    // Actualizar los campos de texto
    $conexion->query("UPDATE foro 
                      SET destino_foro='$destino', 
                          titulo_foro='$titulo', 
                          texto_foro='$texto' 
                      WHERE id_foro=$id");

    // Crear carpeta si no existe
    $carpeta = "uploads/foro/$id/";
    if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

    // Traer imágenes actuales
    $consulta = $conexion->query("SELECT archivo1_foro, archivo2_foro, archivo3_foro FROM foro WHERE id_foro=$id");
    $imagenes_actuales = $consulta->fetch_assoc();

    // 🔧 Permitir reemplazar imágenes y mantener las viejas si no se sube nada
    for ($i = 0; $i < 3; $i++) {
        $inputName = "archivo$i";
        $columna = "archivo" . ($i + 1) . "_foro";
        $imagenActual = $imagenes_actuales[$columna];

        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
            // Borrar la imagen anterior si existe
            if (!empty($imagenActual) && file_exists($imagenActual)) {
                unlink($imagenActual);
            }

            // Subir la nueva imagen
            $nombreArchivo = basename($_FILES[$inputName]['name']);
            $rutaDestino = $carpeta . $nombreArchivo;

            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $rutaDestino)) {
                // Actualizar la ruta en la BD
                $conexion->query("UPDATE foro SET $columna='$rutaDestino' WHERE id_foro=$id");
            }
        }
        // 🔧 Si no hay nueva imagen, se conserva la anterior (no se toca nada)
    }

    header("Location: editor.php?msg=updated");
    exit;
}

// 4️⃣ Consultar todos los posts
$posts = $conexion->query("SELECT * FROM foro");

// 5️⃣ Cargar uno específico (si viene con ?editar=)
$post_editar = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $resultado = $conexion->query("SELECT * FROM foro WHERE id_foro=$id");
    $post_editar = $resultado->fetch_assoc();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POE</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../../../bootstrap-5.3.7-dist/css/bootstrap.css">
</head>

<body>

    <div class="w-100">
        <img src="../../../Imagenes/Banner.png" alt="" class="img-fluid"
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
                        <a class="nav-link px-4 py-2 textos_navbar"
                            href="../../estudiantes.php">Volver</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--POE-->

    <div class="container my-5" style="position: relative;">
        <div class="row align-items-center">

            <!-- Columna de texto -->
            <div class="col-12 col-md-6 my-5">

                <h1 class="titulos">EDITOR FORO</h1>

                <p class="textos">
                    Este es un apartado para crear, editar o borrar los post creados para las paginas de talleres formativos y escuela de padres, si tiene cualquier duda consulte el manual de usuario
                </p>

                <a href="#crear" class="btn rounded-pill px-4 py-2 mt-3 shadow w-30 w-md-auto" id="boton_1">
                    Subir un Post
                </a>

                <br>

            </div>

            <!-- Columna de imagen de manos con mariposas -->
            <div class="col-12 col-md-6 text-center" style="width: 50%;">

                <img src="../../../Imagenes/Logo_Mariposas.png" alt="" class="img-fluid w-100">

            </div>

        </div>

        <div class="logo-container text-start my-4">
            <img src="../../../Imagenes/Logo_Tecnico.png" alt="" class="img-fluid" style="max-width: 200px;">
        </div>

    </div>

    <!--Fin POE-->

    <br><br><br><br><br>

    <img src="../../../Imagenes/HR.png" class="img-fluid" width="100%">

    <div class="caja_editor m-4 rounded-3">

        <div class="input-container position-relative w-75 mx-auto">

            <input class="buscar rounded-pill w-100 pe-5 text-center" type="text" name="buscador"
                placeholder="Buscar...">
            <span class="material-symbols-outlined search-icon">search</span>

        </div>

        <br>

        <!--Inicio sistema de notificaciones acerca de la creacion del post-->

        <?php

        if (isset($_GET['msg'])) {

            switch ($_GET['msg']) {

                case 'ok':

                    echo '<div class="alert correcto text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill" role="alert">
                    Post creado correctamente
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';

                    break;

                case 'error_insert':

                    echo '<div class="alert alerta text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill" role="alert">
                    Error al crear el post (No se pudo insertar en la base de datos)
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';

                    break;

                case 'error_update':

                    echo '<div class="alert alerta text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill" role="alert">
                    El post se creó, pero hubo un problema guardando las imágenes
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';

                    break;
            }
        }

        ?>

        <!--Fin sistema de notificaciones acerca de la creacion del post-->

        <!--Inicio del form para subir el contenido-->

        <form action="subida_datos.php" method="POST" enctype="multipart/form-data">

            <br>

            <h2 class="text-center" id="crear" style="font-weight: bold;">Destino Del Post</h2>

            <div class="d-flex w-50 gap-2 mx-auto">

                <input type="radio" class="btn-check" id="btn-check-outlined1" required autocomplete="off" name="destino"
                    value="escuela">
                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                    for="btn-check-outlined1">Escuela De Padres</label><br>

                <input type="radio" class="btn-check" id="btn-check-outlined2" required autocomplete="off" name="destino"
                    value="talleres">
                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                    for="btn-check-outlined2">Talleres Formativos</label><br>

            </div>

            <br>

            <div class="w-50 mx-auto">

                <input class="campo w-100 rounded-pill p-2 text-center" type="text" name="titulo"
                    placeholder="Agregar Titulo">

            </div>

            <br>

            <div class="w-75 mx-auto">

                <textarea class="campo w-100 rounded-3" name="informaciom" rows="10"
                    placeholder="Agregar Encabezado"></textarea>

            </div>

            <div class="row justify-content-center w-75 mx-auto">

                <div class="col-4">

                    <div class="image-upload">

                        <div class="preview-container" style="text-align: center !important; width: auto !important;">

                            <span class="material-symbols-outlined icon-placeholder"
                                style="font-size: 12rem;">image</span>
                            <img class="preview-img rounded-5" alt="Vista previa">

                        </div>

                        <label class="btn btn-custom px-4 py-2 mt-3">

                            Subir imagen
                            <input type="file" name="archivo0" accept="image/*">

                        </label>

                    </div>

                </div>

                <div class="col-4">

                    <div class="image-upload">

                        <div class="preview-container" style="text-align: center !important; width: auto !important;">

                            <span class="material-symbols-outlined icon-placeholder"
                                style="font-size: 12rem;">image</span>
                            <img class="preview-img rounded-5" alt="Vista previa">

                        </div>

                        <label class="btn btn-custom px-4 py-2 mt-3">

                            Subir imagen
                            <input type="file" name="archivo1" accept="image/*">

                        </label>

                    </div>

                </div>

                <div class="col-4">

                    <div class="image-upload">

                        <div class="preview-container" style="text-align: center !important; width: auto !important;">

                            <span class="material-symbols-outlined icon-placeholder"
                                style="font-size: 12rem;">image</span>
                            <img class="preview-img rounded-5" alt="Vista previa">

                        </div>

                        <label class="btn btn-custom px-4 py-2 mt-3">

                            Subir imagen
                            <input type="file" name="archivo2" accept="image/*">

                        </label>

                    </div>

                </div>

            </div>

            <div class="col-12 d-flex justify-content-end pe-4">

                <button class="btn-actualizar rounded-pill my-4" type="submit"><span
                        class="material-symbols-outlined mx-1">edit</span>Actualizar</button>

            </div>

    </div>

    </form>

    <!--Fin del form para subir el contenido-->

    <img src="../../../Imagenes/HR.png" class="img-fluid" width="100%">

    <div class="caja_editor m-4 rounded-3">

        <?php

        if (isset($_GET['msg'])) {

            $mensajes = [
                'updated' => ['Post actualizado correctamente :D', 'success'],
                'deleted' => ['Post eliminado correctamente :<', 'danger']
            ];

            if (isset($mensajes[$_GET['msg']])) {

                [$texto, $tipo] = $mensajes[$_GET['msg']];
                echo "<div class='alert correcto text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill' role='alert'>
                $texto

                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>

              </div>";
            }
        }

        ?>

        <table class="table table-hover text-center align-middle">

            <thead>

                <tr>

                    <th class="campo">ID</th>
                    <th class="campo">Destino</th>
                    <th class="campo">Título</th>
                    <th class="campo">Texto</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($fila = $posts->fetch_assoc()) { ?>

                    <tr>

                        <td class="campo"><?= $fila['id_foro'] ?></td>
                        <td class="campo"><?= $fila['destino_foro'] ?></td>
                        <td class="campo"><?= $fila['titulo_foro'] ?></td>
                        <td class="campo"><?= substr($fila['texto_foro'], 0, 50) ?>...</td>
                        <td class="ingreso">

                            <a href="editor.php?editar=<?= $fila['id_foro'] ?>#editor" class="btn text-dark">
                                <span class="material-symbols-outlined fs-2">edit</span>
                            </a>

                        </td>
                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

    <img src="../../../Imagenes/HR.png" class="img-fluid" width="100%">

    <?php if ($post_editar): ?>
        <form action="editor.php" method="POST" enctype="multipart/form-data">

            <div class="caja_editor m-4 rounded-3" id="editor">

                <input type="hidden" name="id_foro" value="<?= $post_editar['id_foro'] ?? '' ?>">

                <h2 class="text-center fw-bold">Destino del Post</h2>

                <div class="d-flex w-50 gap-2 mx-auto">

                    <input type="radio" class="btn-check" id="dest1" name="destino" value="escuela"
                        <?= ($post_editar['destino_foro'] ?? '') === 'escuela' ? 'checked' : '' ?>>
                    <label class="btn btn-outline-success w-100 fw-bold" for="dest1">Escuela de Padres</label>

                    <input type="radio" class="btn-check" id="dest2" name="destino" value="talleres"
                        <?= ($post_editar['destino_foro'] ?? '') === 'talleres' ? 'checked' : '' ?>>
                    <label class="btn btn-outline-success w-100 fw-bold" for="dest2">Talleres Formativos</label>

                </div>

                <br>

                <div class="w-50 mx-auto">
                    <input class="campo w-100 rounded-pill p-2 text-center" type="text" name="titulo"
                        placeholder="Agregar título"
                        value="<?= htmlspecialchars($post_editar['titulo_foro'] ?? '') ?>">
                </div>

                <br>

                <div class="w-75 mx-auto">
                    <textarea class="campo w-100 rounded-3" name="informaciom" rows="10"
                        placeholder="Agregar encabezado"><?= htmlspecialchars($post_editar['texto_foro'] ?? '') ?></textarea>
                </div>

                <div class="row justify-content-center w-75 mx-auto mt-4">

                    <?php for ($i = 1; $i <= 3; $i++) {
                        $campo = "archivo{$i}_foro";
                        $ruta = $post_editar[$campo] ?? '';
                    ?>
                        <div class="col-4 text-center">
                            <div class="image-upload">
                                <div class="preview-container" style="text-align: center !important; width: auto !important;">

                                    <?php if (!empty($ruta) && file_exists($ruta)) { ?>
                                        <img src="<?= $ruta ?>" alt="Imagen <?= $i ?>"
                                            class="preview-img rounded-5 mb-2"
                                            style="display:block; max-width:100%; height:auto;">
                                        <span class="material-symbols-outlined icon-placeholder"
                                            style="display:none; font-size:12rem;">image</span>
                                    <?php } else { ?>
                                        <img class="preview-img rounded-5 mb-2"
                                            style="display:none; max-width:100%; height:auto;">
                                        <span class="material-symbols-outlined icon-placeholder"
                                            style="display:inline-block; font-size:12rem;">image</span>
                                    <?php } ?>

                                </div>

                                <label class="btn btn-custom px-4 py-2 mt-3">
                                    Subir imagen
                                    <input type="file" name="archivo<?= $i - 1 ?>" accept="image/*">
                                </label>
                            </div>
                        </div>
                    <?php } ?>

                </div>

                <div class="col-12 d-flex justify-content-end pe-4 gap-2 mt-4">
                    <button name="borrar" class="btn-borrar rounded-pill my-4" type="submit">
                        <span class="material-symbols-outlined mx-1">delete</span>Borrar
                    </button>

                    <button name="actualizar" class="btn-actualizar rounded-pill my-4" type="submit">
                        <span class="material-symbols-outlined mx-1">edit</span>Actualizar
                    </button>
                </div>

            </div>

        </form>

    <?php else: ?>

        <div class='alert correcto text-center alert-dismissible fade show fw-bold w-75 mx-auto rounded-pill' style="margin-top: 1rem;">

            Selecciona un post de la tabla para editarlo

        </div>

    <?php endif; ?>



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

    <script>
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
                        icono.style.display = 'none';
                    };
                    lector.readAsDataURL(archivo);
                } else {
                    img.style.display = 'none';
                    icono.style.display = 'inline-block';
                }
            });
        });
    </script>



    <script src="../../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>