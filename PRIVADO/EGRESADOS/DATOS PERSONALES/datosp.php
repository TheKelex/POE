<?php
session_start();
if(!isset($_SESSION['usuario'])){
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

$id_egresados = (int) $_SESSION['id_egresados'];
$sql = "SELECT * FROM egresados WHERE id_egresados = $id_egresados";
$resultado = mysqli_query($enlace, $sql);
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($enlace));
}
$datos = mysqli_fetch_assoc($resultado);



$fecha_nacimiento = $datos['fechanac_egresados']; // formato AAAA-MM-DD
$fecha = $datos['fechanac_egresados']; // por ejemplo: 2001-10-07
$edad = date_diff(date_create($fecha), date_create('today'))->y;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATOS PERSONALES</title>

    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../../../bootstrap-5.3.7-dist/css/bootstrap.css">

</head>

<body>

    <div class="w-100">
        <img src="../../../Imagenes/Banner.png" alt="" class="img-fluid"
            style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <a href="../egresados.php" class="rounded-pill m-4 volver"><span class="material-symbols-outlined mx-2">logout</span>Volver</a>

    <div class="caja m-3"> <!--Caja para el fondo blanco de la info-->

        <form class="m-4 p-2" action="actualizar.php" method="POST"> <!--Inicio del formulario-->



            <div class="row align-items-center m-4"> <!--Primera fila-->


                <!--Nombres Y Apellidos-->
                <div class="col-1">

                    <p class="titulo">EGRESADO</p>

                </div>

                <div class="col-6">

                    <label class="titulo" for="nom_egresados">NOMBRES Y APELLIDOS</label>
                    <input class="campo form-control rounded-pill" type="text" name="nom_egresados" value="<?php echo htmlspecialchars($datos['nom_egresados'] ?? ''); ?>">

                </div>


                <!--Fecha De Nacimiento-->                
                <div class="col-3">

                    <input class="campo form-control rounded-pill" type="date" name="fechanac_egresados" value="<?php echo htmlspecialchars($datos['fechanac_egresados'] ?? ''); ?>">

                </div>


                <!--Edad-->
                <div class="col-2">

                    <label class="titulo d-block">EDAD</label>
                    <input class="campo form-control rounded-pill" type="text" name="edad_egresados" value="<?php echo htmlspecialchars($edad ?? ''); ?>">

                </div>

            </div>



            <div class="row align-items-center m-4"> <!--Segunda fila-->


                <!--Documento-->
                <div class="col-1">

                    <p class="titulo">DOCUMENTO</p>

                </div>


                <!--Inicio de opciones-->
                <div class="col-3">
                    <div class="row">
                        <div class="col">
                            <label class="titulo d-block">R.C</label>
                            <input class="form-check-input opcion" type="radio" name="tip_doc_egresados" value="R.C"
                            <?php if (($datos['tip_doc_egresados'] ?? '') === 'R.C') echo 'checked'; ?>>
                        </div>

                        <div class="col">
                            <label class="titulo d-block">T.I</label>
                            <input class="form-check-input" type="radio" name="tip_doc_egresados" value="T.I"
                            <?php if (($datos['tip_doc_egresados'] ?? '') === 'T.I') echo 'checked'; ?>>
                        </div>

                        <div class="col">
                            <label class="titulo d-block">C.C</label>
                            <input class="form-check-input" type="radio" name="tip_doc_egresados" value="C.C"
                            <?php if (($datos['tip_doc_egresados'] ?? '') === 'C.C') echo 'checked'; ?>>
                        </div>

                        <div class="col">
                            <label class="titulo d-block" id="otro">OTRO</label>
                            <input class="form-check-input" for="otro" type="radio" name="tip_doc_egresados" value="OTRO"
                            <?php if (($datos['tip_doc_egresados'] ?? '') === 'OTRO') echo 'checked'; ?>>
                        </div>
                    </div>
                </div>
                <!--Fin de opciones-->



                <!--Numero de documento-->
                <div class="col-4 d-flex align-items-center gap-2">

                    <p class="titulo mb-0">Nro</p>
                    <input class="campo form-control rounded-pill" type="number" name="num_doc_egresados" value="<?php echo htmlspecialchars($datos['num_doc_egresados'] ?? ''); ?>">

                </div>



                <!--Grupo sanguineo-->

                <div class="col-4 d-flex align-items-center gap-2">

                    <p class="titulo mb-0">GRUPO SANGUINEO</p>
                    <input class="campo form-control rounded-pill" type="text" name="gruposanguineo_egresados" value="<?php echo htmlspecialchars($datos['gruposanguineo_egresados'] ?? ''); ?>">
                    
                </div>

            </div>


            <!--Tercera linea-->
            <div class="row align-items-center m-4">


                <!--Especialidad-->
                <div class="col-1">

                    <p class="titulo mb-0">ESPECIALIDAD</p>
                    
                </div>

                <div class="col-3">

                    <input class="campo form-control rounded-pill" type="text" name="especialidad_egresados" value="<?php echo htmlspecialchars($datos['especialidad_egresados'] ?? ''); ?>">

                </div>

                <!--Correo electronico-->
                <div class="col-1">

                    <p class="titulo mb-0">CORREO ELECTRONICO</p>
                    
                </div>

                <div class="col-7">

                    <input class="campo form-control rounded-pill" type="email" name="email_egresados" value="<?php echo htmlspecialchars($datos['email_egresados'] ?? ''); ?>">

                </div>


            </div>


            <!--Cuarta linea-->
            <div class="row align-items-center m-4">


                <!--Telefono movil-->
                <div class="col-1">

                    <p class="titulo mb-0">TELEFONO MOVIL</p>
                    
                </div>

                <div class="col-3">

                    <input class="campo form-control rounded-pill" type="number" name="tel_egresados" value="<?php echo htmlspecialchars($datos['tel_egresados'] ?? ''); ?>">

                </div>


                <!--Ocupacion actual-->
                <div class="col-1">

                    <p class="titulo mb-0">OCUPACION ACTUAL</p>
                    
                </div>

                <div class="col-7">

                    <input class="campo form-control rounded-pill" type="text" name="ocup_egresados" value="<?php echo htmlspecialchars($datos['ocup_egresados'] ?? ''); ?>">

                </div>


            </div>
        

            <!--Quinta Linea-->
            <div class="row align-items-center m-4">

                <div class="col-1">

                    <p class="titulo">ESTUDIOS REALIZADOS</p>

                </div>

                <div class="col-11" style="padding-left: 0 !important;">

                    <textarea class="campo form-control rounded-pill" name="estudios_egresados"><?php echo htmlspecialchars($datos['estudios_egresados'] ?? ''); ?></textarea>

                </div>

            </div>

            
            <!--Sexta linea-->
            <div class="row align-items-center m-4">

                <div class="col-1">

                    <p class="titulo">INSTITUCION</p>

                </div>

                <div class="col-11">

                    <input class="campo form-control rounded-pill" type="text" name="institucion_egresados" value="<?php echo htmlspecialchars($datos['institucion_egresados'] ?? ''); ?>">

                </div>

            </div>

            <center>

            <button class="volver rounded-pill m-4" type="submit" style="border:0;"> 
                <span class="material-symbols-outlined mx-1">edit</span> 
                Actualizar
            </button>

            </center>

        </form>

    </div>

    <script src="../../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>