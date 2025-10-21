<?php
session_start();
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}
$_SESSION['ultimo_movimiento'] = time();

// --- Conexión BD ---
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$basededatos = "poe";
$enlace = mysqli_connect($servidor, $usuario, $contraseña, $basededatos);
if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}
// Verificar si existe en sesión
if (!isset($_SESSION["id_dato"])) {
    echo "No se recibió el estudiante.";
    exit();
}

$id_dato = $_SESSION["id_dato"];

$consulta = "
    SELECT 
    de.nom_dato,          
    de.doc_dato,  
    de.sede_dato,
    de.jornada_dato,       
    oe.gradop_observador,
    de.nac_dato,
    de.lugar_nac_dato,
    de.rh_dato, 
    car.dir_caracteristicas,
    car.barri_caracteristicas,
    car.cel_caracteristicas,
    car.com_caracteristicas,
    car.est_caracteristicas,
    car.eps_caracteristicas,
    at.des_atributo,
    at.ind_atributo,
    sal.diag_salud,
    sal.tie_atributo,
    sal.trat_salud,
    sal.dis_salud,
    sal.def_salud,
    at.cuales_atributo,
    ent.hermano_entorno,
    ent.tieli_entorno,
    ent.vive_entorno,
    ent.esp_entorno,
    ent.n_hermanos_entorno,
    car.pd_nom_caracteristicas, 
    car.num_m1_caracteristicas,
    car.pd_doc_caracteristicas,
    car.pd_esco_caracteristicas,
    car.pd_ocu_caracteristicas,
    car.md_nom_caracteristicas,
    car.num_m2_caracteristicas,
    car.md_doc_caracteristicas,
    car.md_ocu_caracteristicas,
    car.md_esco_caracteristicas,
    car.acu_caracteristicas,
    car.acu_paren_caracteristicas,
    car.acu_doc_caracteristicas,    
    car.acu_cel_caracteristicas,
    car.acu_esco_caracteristicas,
    car.acu_ocup_caracteristicas,
    de.jornada_dato,
    oe.año_observador,
    oe.inf_prp_observador,
    oe.inf_sgp_observador,
    oe.inf_terp_observador,
    oe.inf_cuarp_observador,
    oe.foto_observador
FROM dato_estudiante de
INNER JOIN observador_estudiante oe 
    ON de.id_dato = oe.id_observador
INNER JOIN fichai_estudiante fh
    ON de.id_dato = fh.id_fichai
INNER JOIN caracteristicas_estudiante car
    ON de.id_dato = car.id_caracteristicas
INNER JOIN salud_estudiante sal
    ON de.id_dato = sal.id_salud
INNER JOIN entorno_estudiantes ent
    ON de.id_dato = ent.id_entorno
INNER JOIN atributo_estudiante at
    ON de.id_dato = at.id_atributo
WHERE de.id_dato = $id_dato;
    ";

$resultado = mysqli_query($enlace, $consulta);
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($enlace));
}

$datos = mysqli_fetch_assoc($resultado);
$doc_dato = $datos['doc_dato'] ?? 'default';
$_SESSION['doc_dato'] = $doc_dato; // Guardar en sesión
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBSERBADOR DEL ALUMNO</title>

    <link rel="stylesheet" href="../../../bootstrap-5.3.7-dist/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="./style.css">

</head>

<body>

    <div class="w-100">
        <img src="../../../Imagenes/Banner.png" alt="" class="img-fluid"
            style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <!--Grid para la parte superior de la foto y la info general-->

    

        <a href="../INFO_INDIVIDUAL.php" class="rounded-pill m-4 volver"><span class="material-symbols-outlined mx-2">logout</span>Volver</a>

        <form class="m-4" action="actualizar_foto.php" method="POST" enctype="multipart/form-data">
            
            <div class="row m-4 align-items-center">

            <div class="col g-0">

                <input class="campo_superior form-control" type="number" name="gradop_observador" placeholder="GRADO" value="<?php echo htmlspecialchars($datos['gradop_observador'] ?? ''); ?>">

            </div>
            <!-- HTML que muestra la imagen y el modal -->
            <div class="col">
                <div class="d-flex flex-column align-items-center w-75 mx-auto gap-3">

                    <!-- Imagen actual -->
                    <img src="<?= htmlspecialchars($datos['foto_observador']) ?>" class="w-50" style="width: 8rem;" alt="Imagen Del Estudiante">

                    <!-- Botón para abrir modal -->
                    <button type="button" class="btn btn-editar d-flex w-50 align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <span class="material-symbols-outlined">edit</span>Editar
                    </button>

                    <!-- Modal para subir imagen -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Foto Estudiante</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>

                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                         <label for="formFile" class="form-label">Inserte la foto</label>
                                         <input class="form-control" type="file" name="imagen" id="formFile" accept="image/*" required>
                                    </div>

                                    <!-- Enviar el ID del estudiante -->
                                    <input type="hidden" name="id_dato" value="<?= $id_dato ?>">

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <input type="submit" class="btn btn-editar" name="actualizar" value="Actualizar foto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">

                <input class="campo_superior m-3 form-control" type="text" name="sede_dato" placeholder="SEDE" value="<?php echo htmlspecialchars($datos['sede_dato'] ?? ''); ?>">
                <input class="campo_superior m-3 form-control" type="text" name="jornada_dato" placeholder="JORNADA" value="<?php echo htmlspecialchars($datos['jornada_dato'] ?? ''); ?>">

            </div>

        </div>
        </form> 
        <!--1. Identificacion-->
    <form class="m-4" action="actualizar.php" method="POST">
        <div class="row borde m-4 d-flex align-items-center">

            <div class="col-12 d-flex justify-content-center align-items-center"
                style="border-bottom: 1px solid black;"> <!--Separador para el titulo-->

                <p class="titulo">1. IDENTIFICACION</p>

            </div>

            <!--Informacion-->

            <div class="col-12">

                <!--Primera linea de informacion-->
                <table class="table">

                    <tr>
                        <td colspan="5">

                            <div class="d-flex justify-content-between gap-3">

                                <div class="w-100">

                                    <div class="d-flex align-items-center gap-2">
                                        <label for="nom_dato">NOMBRE COMPLETO</label>
                                        <input class="campo form-control" type="text" name="nom_dato" value="<?php echo htmlspecialchars($datos['nom_dato']); ?>">
                                    </div>

                                </div>

                                <div class="w-100">

                                    <div class="d-flex align-items-center gap-2">
                                        <label for="doc_dato">DOCUMENTO</label>
                                        <input class="campo form-control" type="number" name="doc_dato" value="<?php echo htmlspecialchars($datos['doc_dato']); ?>">
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>


                    <!--Segunda linea de informacion-->
                    <tr>
                        <td colspan="5">

                            <div class="d-flex justify-content-between gap-3">

                                <!-- Fecha de nacimiento -->

                                <div class="d-flex align-items-center gap-2 w-100">

                                    <label for="nac_dato">FECHA DE NACIMIENTO</label>
                                    <input class="campo form-control" type="date" name="nac_dato" value="<?php echo htmlspecialchars($datos['nac_dato']); ?>">

                                </div>


                                <!-- Lugar -->
                                <div class="d-flex align-items-center gap-2 w-100">

                                    <label for="lugar_nac_dato">LUGAR</label>
                                    <input class="campo form-control" type="text" name="lugar_nac_dato" value="<?php echo htmlspecialchars($datos['lugar_nac_dato']); ?>">

                                </div>

                                <!-- RH -->
                                <div class="d-flex align-items-center gap-2 w-100">

                                    <label for="rh_dato">RH</label>
                                    <input class="campo form-control" type="text" name="rh_dato" value="<?php echo htmlspecialchars($datos['rh_dato']); ?>">

                                </div>

                            </div>
                        </td>
                    </tr>


                    <!--Tercera linea de informacion-->
                    <tr>

                        <td colspan="5">

                            <div class="d-flex justify-content-between gap-3">

                                <div class="w-100">

                                    <div class="d-flex align-items-center gap-2">
                                        <label for="dir_caracteristicas">DIRECCION</label>
                                        <input class="campo form-control" type="text" name="dir_caracteristicas" value="<?php echo htmlspecialchars($datos['dir_caracteristicas']); ?>">
                                    </div>

                                </div>

                                <div class="w-100">

                                    <div class="d-flex align-items-center gap-2">

                                        <label for="barri_caracteristicas">BARRIO</label>
                                        <input class="campo form-control" type="text" name="barri_caracteristicas" value="<?php echo htmlspecialchars($datos['barri_caracteristicas']); ?>">

                                    </div>

                                </div>

                                <div class="w-100">


                                    <div class="d-flex align-items-center gap-2">

                                        <label for="cel_caracteristicas">CEL</label>
                                        <input class="campo form-control" type="number" name="cel_caracteristicas" value="<?php echo htmlspecialchars($datos['cel_caracteristicas']); ?>">

                                    </div>

                                </div>

                            </div>

                        </td>

                    </tr>

                    <!--Cuarta linea de informacion-->
                    <tr>

                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <label for="com_caracteristicas">COMUNA</label>
                                <input class="campo form-control" type="number" name="com_caracteristicas" value="<?php echo htmlspecialchars($datos['com_caracteristicas']); ?>">

                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <label for="est_caracteristicas">ESTRATO</label>
                                <input class="campo form-control" type="number" name="est_caracteristicas" value="<?php echo htmlspecialchars($datos['est_caracteristicas']); ?>">

                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <label for="eps_caracteristicas">E.P.S</label>
                                <input class="campo form-control" type="text" name="eps_caracteristicas" value="<?php echo htmlspecialchars($datos['eps_caracteristicas']); ?>">

                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <label for="des_atributo">DESPLAZADO</label>
                                <select class="form-select" name="des_atributo">
                                    <option value="" selected disabled>Seleccione Una Opcion</option>
                                    <option value="Si" <?php if ($datos['des_atributo'] == "Si") echo "selected"; ?>>Si</option>
                                    <option value="No" <?php if ($datos['des_atributo'] == "No") echo "selected"; ?>>No</option>
                                </select>

                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <label for="ind_atributo">INDIGENA</label>
                                <select class="form-select" name="ind_atributo">
                                    <option value="" selected disabled>Seleccione Una Opcion</option>
                                    <option value="Si" <?php if ($datos['ind_atributo'] == "Si") echo "selected"; ?>>Si</option>
                                    <option value="No" <?php if ($datos['ind_atributo'] == "No") echo "selected"; ?>>No</option>

                                </select>

                            </div>
                        </td>

                    </tr>

                    <!--Quinta linea de informacion-->
                    <tr>
                        <td colspan="5">

                            <div class="d-flex justify-content-between gap-3">

                                <div class="w-100">

                                    <div class="d-flex align-items-center gap-2">
                                        <label for="diag_salud">ENFERMEDAD QUE PADECE</label>
                                        <input class="campo form-control" type="text" name="diag_salud" value="<?php echo htmlspecialchars($datos['diag_salud']); ?>">
                                    </div>

                                </div>

                                <div class="w-100">

                                    <div class="d-flex align-items-center gap-2">
                                        <label for="tie_atributo">TIEMPO</label>
                                        <input class="campo form-control" type="text" name="tie_atributo" value="<?php echo htmlspecialchars($datos['tie_atributo']); ?>">
                                    </div>

                                </div>

                                <div class="w-100">

                                    <div class="d-flex align-items-center gap-2">
                                        <label for="trat_salud">TRATAMIENTO</label>
                                        <input class="campo form-control" type="text" name="trat_salud" value="<?php echo htmlspecialchars($datos['trat_salud']); ?>">
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>

                    <!--Sexta linea de informacion-->
                    <tr>

                        <td colspan="5">

                            <div class="d-flex justify-content-between gap-3">

                                <div class="w-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <label for="def_salud">PADECE DEFICIENCIAS EN</label>
                                        <input
                                            class="campo form-control"
                                            type="text"
                                            name="def_salud" ;
                                            placeholder="Visión, Visión, Auditiva, Motriz, Lenguaje, Psíquica..."
                                            value="<?php echo htmlspecialchars($datos['def_salud'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                        </td>

                    </tr>

                    <!--Septima linea de informacion-->
                    <tr>

                        <td colspan="5">

                            <div class="d-flex justify-content-between gap-3">

                                <div class="w-100">
                                    <div class="d-flex align-items-center gap-2">

                                        <label for="n_hermanos_entorno">No.DE HERMANOS</label>
                                        <input class="campo form-control" type="number" name="n_hermanos_entorno" value="<?php echo htmlspecialchars($datos['n_hermanos_entorno'] ?? ''); ?>">

                                    </div>
                                </div>

                                <div class="w-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <label for="tieli_entorno">EL TIEMPO LIBRE LO EMPLEA EN</label>
                                        <input class="campo form-control" type="text" name="tieli_entorno" value="<?php echo htmlspecialchars($datos['tieli_entorno'] ?? ''); ?>">
                                    </div>
                                </div>

                            </div>

                        </td>

                    </tr>

                    <!--Octava linea de informacion-->
                    <tr>

                        <td colspan="5">
                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="vive_entorno">VIVE CON:</label>
                                    <input class="campo form-control" type="text" name="vive_entorno" value="<?php echo htmlspecialchars($datos['vive_entorno'] ?? ''); ?>">
                                </div>
                            </div>
                        </td>

                    </tr>

                </table>

            </div>

        </div>

        <!--2 ASPECTO FAMILIAR-->

        <div class="row borde m-4 d-flex align-items-center">

            <div class="col-12 d-flex justify-content-center align-items-center"
                style="border-bottom: 1px solid black;"> <!--Separador para el titulo-->

                <p class="titulo">2 ASPECTO FAMILIAR</p>

            </div>

            <div class="col-12">

                <table class="table">

                    <!--Informacion padre-->
                    <tr>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="pd_nom_caracteristicas">NOMBRE DEL PADRE</label>
                                    <input class="campo form-control" type="text" name="pd_nom_caracteristicas" value="<?php echo htmlspecialchars($datos['pd_nom_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="num_m1_caracteristicas">CEL</label>
                                    <input class="campo form-control" type="number" name="num_m1_caracteristicas" value="<?php echo htmlspecialchars($datos['num_m1_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="pd_esco_caracteristicas">NIVEL DE ESCOLARIDAD</label>
                                    <input class="campo form-control" type="text" name="pd_esco_caracteristicas" value="<?php echo htmlspecialchars($datos['pd_esco_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="pd_ocu_caracteristicas">OCUPACION</label>
                                    <input class="campo form-control" type="text" name="pd_ocu_caracteristicas" value="<?php echo htmlspecialchars($datos['pd_ocu_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                    </tr>

                    <!--Informacion madre-->
                    <tr>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="md_nom_caracteristicas">NOMBRE DEL MADRE</label>
                                    <input class="campo form-control" type="text" name="md_nom_caracteristicas" value="<?php echo htmlspecialchars($datos['md_nom_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="num_m2_caracteristicas">CEL</label>
                                    <input class="campo form-control" type="number" name="num_m2_caracteristicas" value="<?php echo htmlspecialchars($datos['num_m2_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="md_esco_caracteristicas">NIVEL DE ESCOLARIDAD</label>
                                    <input class="campo form-control" type="text" name="md_esco_caracteristicas" value="<?php echo htmlspecialchars($datos['md_esco_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="md_ocu_caracteristicas">OCUPACION</label>
                                    <input class="campo form-control" type="text" name="md_ocu_caracteristicas" value="<?php echo htmlspecialchars($datos['md_ocu_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                    </tr>

                    <!--Informacion acudiente-->
                    <tr>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="acu_caracteristicasdw">NOMBRE DEL ACUDIENTE</label>
                                    <input class="campo form-control" type="text" name="acu_caracteristicas" value="<?php echo htmlspecialchars($datos['acu_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="acu_cel_caracteristicas">CEL</label>
                                    <input class="campo form-control" type="number" name="acu_cel_caracteristicas" value="<?php echo htmlspecialchars($datos['acu_cel_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="acu_esco_caracteristicas">NIVEL DE ESCOLARIDAD</label>
                                    <input class="campo form-control" type="text" name="acu_esco_caracteristicas" value="<?php echo htmlspecialchars($datos['acu_esco_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="acu_ocup_caracteristicas">OCUPACION</label>
                                    <input class="campo form-control" type="text" name="acu_ocup_caracteristicas" value="<?php echo htmlspecialchars($datos['acu_ocup_caracteristicas'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

        <div class="p-0" style="width: calc(100% + 3rem); margin-left: -1.5rem; margin-right: -1.5rem;">
            <img src="../../../Imagenes/HR.png" class="img-fluid d-block m-0 p-0">
        </div>

        <div class="row borde m-4 d-flex align-items-center g-0">

            <div class="col-12">

                <table class="table">

                    <tr> <!--Encabezado de la parte de la informacion-->

                        <td colspan="4">
                            <center>
                                <p class="titulo">REGISTRO, SEGUIMIENTO ACADEMICO Y DISCIPLINARIO DEL ESTUDIANTE</p>
                            </center>
                        </td>

                    </tr>

                    <!--Inicio de la informacion basica-->

                    <tr>

                        <td colspan="3">

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="nom_dato">NOMBRES COMPLETO</label>
                                    <input class="campo form-control" type="text" name="nom_dato" value="<?php echo htmlspecialchars($datos['nom_dato'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="gradop_observador">GRADO</label>
                                    <input class="campo form-control" type="number" name="gradop_observador" value="<?php echo htmlspecialchars($datos['gradop_observador'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="jornada_dato">JORNADA</label>
                                    <input class="campo form-control" type="text" name="jornada_dato" value="<?php echo htmlspecialchars($datos['jornada_dato'] ?? ''); ?>">
                                </div>
                            </div>

                        </td>

                    </tr>


                    <!--Fin de la informacion basica-->

                    <!--Inicio de los informes por periodo-->

                    <tr>

                        <td colspan="3" style="background-color: #d9d9d9; border: 1px solid black !important; border-left: none !important;">

                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>INFORME DEL PRIMER PERIODO</b></div>
                            </div>

                        </td>
                        <td style="background-color: #d9d9d9; border: 1px solid black !important; border-right: none !important;">
                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>FIRMA</b></div>
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" style="border: 1px solid black !important; border-left: none !important;">

                            <textarea class="campo form-control mx-auto" style="height: 7rem; border: none !important;" name="inf_prp_observador"><?php echo htmlspecialchars($datos['inf_prp_observador']); ?></textarea>

                        </td>

                        <td style="border: 1px solid black !important; border-right: none !important;">

                            <center>
                                <input class="form-check-input my-3" type="checkbox" name="check_informe_1" value="check_informe_1">
                            </center>

                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" style="background-color: #d9d9d9; border: 1px solid black !important; border-left: none !important;">

                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>INFORME DEL SEGUNDO PERIODO</b></div>
                            </div>

                        </td>
                        <td style="background-color: #d9d9d9; border: 1px solid black !important; border-right: none !important;">
                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>FIRMA</b></div>
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" style="border: 1px solid black !important; border-left: none !important;">

                            <textarea class="campo form-control mx-auto" style="height: 7rem; border: none !important;" name="inf_sgp_observador"><?php echo htmlspecialchars($datos['inf_sgp_observador']); ?></textarea>

                        </td>

                        <td style="border: 1px solid black !important; border-right: none !important;">

                            <center>
                                <input class="form-check-input my-3" type="checkbox" name="check_informe_2" value="check_informe_2">
                            </center>

                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" style="background-color: #d9d9d9; border: 1px solid black !important; border-left: none !important;">

                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>INFORME DEL TERCER PERIODO</b></div>
                            </div>

                        </td>
                        <td style="background-color: #d9d9d9; border: 1px solid black !important; border-right: none !important;">
                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>FIRMA</b></div>
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" style="border: 1px solid black !important; border-left: none !important;">

                            <textarea class="campo form-control mx-auto" style="height: 7rem; border: none !important;" name="inf_terp_observador"><?php echo htmlspecialchars($datos['inf_terp_observador']); ?></textarea>

                        </td>

                        <td style="border: 1px solid black !important; border-right: none !important;">

                            <center>
                                <input class="form-check-input my-3" type="checkbox" name="check_informe_3" value="check_informe_3">
                            </center>

                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" style="background-color: #d9d9d9; border: 1px solid black !important; border-left: none !important;">

                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>INFORME DEL CUARTO PERIODO</b></div>
                            </div>

                        </td>
                        <td style="background-color: #d9d9d9; border: 1px solid black !important; border-right: none !important;">
                            <div class="d-flex justify-content-center align-items-center" style="height: 1rem;">
                                <div><b>FIRMA</b></div>
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" style="border: 1px solid black !important; border-left: none !important;">

                            <textarea class="campo form-control mx-auto" style="height: 7rem; border: none !important;" name="inf_cuarp_observador"><?php echo htmlspecialchars($datos['inf_cuarp_observador']); ?></textarea>

                        </td>

                        <td style="border: 1px solid black !important; border-right: none !important;">

                            <center>
                                <input class="form-check-input my-3" type="checkbox" name="check_informe_4" value="check_informe_4">
                            </center>

                        </td>

                    </tr>

                    <!--Fin de los informes por periodo-->

                    <tr>

                        <td colspan="4">

                            <button class="btn-actualizar rounded-pill my-4 m-auto" type="submit" value="actualizar"><span class="material-symbols-outlined mx-1">edit</span>Actualizar</button>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </form>

    <script src="../../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>