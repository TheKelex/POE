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

if (isset($_SESSION["eliminado"])) {
    echo '<div class="alert alert-success text-center m-3 rounded-pill shadow-sm">
            ✅ Estudiante eliminado correctamente.
          </div>';
    unset($_SESSION["eliminado"]);
}

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
                    <a href="./FOLDER/folder.php" class="rounded-pill m-4 cargar">Cargar</a>
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
                }elseif ($_SESSION['tipo_usuario']==='psicoorientador') {
                    echo $caracterizacion;
                    echo $ficha_individual;
                    echo $observador;
                    echo $folder;
                }
            ?>

        </div>
        <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
    <!-- Botón que abre el modal -->
     <center>
     <button type="button" class="btn btn-danger rounded-pill m-4 cargar" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal" style="background-color:  ; width:16%">
            <span class="material-symbols-outlined">delete</span>Eliminar Estudiante
        </button>
     </center>
           
<?php endif; ?>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header bg-danger text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="confirmarEliminarModalLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p class="fs-5 mb-0">¿Estás seguro de que deseas eliminar este estudiante?</p>
        <p class="text-muted">Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">No</button>
        
        <form action="eliminar_estudiante.php" method="POST" style="display:inline;">
            <input type="hidden" name="id_dato" value="<?php echo htmlspecialchars($_SESSION['id_dato']); ?>">
            <button type="submit" class="btn btn-danger rounded-pill px-4">Sí, eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<?php if (isset($_GET['msg']) && $_GET['msg'] === 'eliminado'): ?>
<div class="alert alert-success text-center m-3 rounded-pill shadow-sm">
    Estudiante eliminado correctamente.
</div>
<?php endif; ?>

    </div>



    <script src="../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>