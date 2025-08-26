<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    // Si no hay usuario logueado, redirigir al login
    header("Location: ../PRIVADO/INICIO SESION/inicio.html");
    exit();
}
// 2. Definir tiempo máximo de inactividad (en segundos)
$inactividad_maxima = 300; // 300 segundos = 5 minutos

// 3. Verificar si existe la variable de última actividad
if (isset($_SESSION['ultimo_movimiento'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_movimiento'];

    if ($tiempo_inactivo > $inactividad_maxima) {
        // Si el usuario estuvo inactivo más tiempo del permitido, cerrar sesión
        session_unset();    // Limpiar variables de sesión
        session_destroy();  // Destruir la sesión
        header("Location: ../PRIVADO/INICIO SESION/inicio.php?expirado=1"); // Redirigir al login con mensaje
        exit();
    }
}

// 4. Actualizar la hora del último movimiento
$_SESSION['ultimo_movimiento'] = time(); // Guardar la hora actual

$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$basededatos = "poe";

$enlace = mysqli_connect($servidor, $usuario, $contraseña, $basededatos);

// --- CONSULTA JOIN ---
$consulta = "
    SELECT 
        de.id_dato,            
        de.nom_dato,          
        de.doc_dato,          
        oe.gradop_observador  
    FROM dato_estudiante de
    INNER JOIN observador_estudiante oe
        ON de.id_dato = oe.id_observador
";

$resultado = mysqli_query($enlace, $consulta);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>

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

    <nav class="navbar navbar-expand-lg" style="background-color: #04BF55;">
        <div class="container-fluid">
            <a class="navbar-brand fs-4 ms-4" style="font-weight: bold; color: white; font-size: 25px; cursor: default;"
                href=""><b>POE</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto rounded-pill gap-2" style="font-weight: bold; background-color: #00ac4a;">
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="../index.html">POE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar"
                            href="../PUBLICO/ESCUELA DE PADRES/escuela.html">Escuela De Padres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar" href="../PUBLICO/FORO/foro.html">Foro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar"
                            href="../PUBLICO/TALLERES FORMATIVOS/taller.html">Talleres Formativos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 textos_navbar d-flex align-items-center"
                            href="./sesion_close.php"><span class="material-symbols-outlined mx-2">logout</span>Cerrar Sesion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--Fin inicio barra de navegacion-->

    <div class="caja rounded-5 m-4"> <!--Cajita Para El Color-->

        <!--Inicio Formulario Consulta-->


        <form action="">

            <!--Inicio Parte Superior Del Formulario-->
            <center>
                <button class="boton rounded-pill m-4" name="estudiantes" value="estudiantes">Estudiantes</button>
                <button class="boton rounded-pill m-4" name="egresados" value="egresados">Egresados</button>
            </center>
            <!--Fin Parte Superior Del Formulario-->

            <div class="row">
                <div class="col-2">

                    <!--Inicio de la caja de filtros-->
                    <div class="cajita_opciones rounded-5 d-grid gap-3"> <!--Cajita Para Las Opciones-->

                        <button class="boton_cajita rounded-pill mx-2">Sede</button>
                        <button class="boton_cajita rounded-pill mx-2">Jornada</button>
                        <button class="boton_cajita rounded-pill mx-2">Curso</button>

                        <input class="actualizar rounded-pill" type="submit" name="actualizar" value="Enviar"> <!--Boton submit-->

                    </div>
                    <!--Fin de la caja de filtros-->

                </div>

                <!--Inicio de la tabla-->
                <div class="col-10">

                    <table class="m-4 table table-hover">

                        <tr>

                            <th class="campo">Nombre:</th>
                            <th class="campo">Documento De Identidad</th>
                            <th class="campo">Grado</th>

                        </tr>

                        <?php while ($colum = mysqli_fetch_array($resultado)) { ?>
                            <tr>
                                <td class="campo"><?php echo $colum['nom_dato']; ?></td>
                                <td class="campo"><?php echo $colum['doc_dato']; ?></td>
                                <td class="campo"><?php echo $colum['gradop_observador'];?></td>
                                <td class="ingreso">
                                    <form action="./ESTUDIANTES/INFO_INDIVIDUAL.php" method="POST">
                                        <input type="hidden" name="id_dato" value="<?php echo $_SESSION['id_dato'] = $colum['id_dato']; ?>">
                                        <a style="border:none; background:none; color:black;" href="./ESTUDIANTES/INFO_INDIVIDUAL.php">
                                            <span class="material-symbols-outlined fs-2">login</span>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>

                    </table>

                </div>
                <!--Fin de la tabla-->
            </div>

        </form>

        <!--Fin Formulario Consulta-->

    </div>

    <script src="../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>