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
    de.nac_dato,
    de.edad_dato,
    de.tipo_doc_dato,           
    de.doc_dato,
    de.rh_dato,
    de.estado_dato,
    de.col_dato, 
    car.dir_caracteristicas, 
    car.barri_caracteristicas,
    car.com_caracteristicas,
    car.est_caracteristicas,
    car.eps_caracteristicas,
    car.cel_caracteristicas,
    car.p_tel_m1_caracteristicas, 
    car.num_m1_caracteristicas,
    car.p_tel_m2_caracteristicas,
    car.num_m2_caracteristicas,
    car.gmail_p_caracteristicas,
    car.acu_caracteristicas,
    car.acu_paren_caracteristicas,
    car.pd_nom_caracteristicas,
    car.pd_esco_caracteristicas,
    car.pd_edad_caracteristicas,
    car.pd_ocu_caracteristicas,
    car.pd_trab_caracteristicas,
    car.md_nom_caracteristicas,
    car.md_esco_caracteristicas,
    car.md_edad_caracteristicas,
    car.md_ocu_caracteristicas,
    car.md_trab_caracteristicas,
    car.economia_caracteristicas,
    ent.casa_entorno,
    ent.hijou_entorno,
    ent.hermano_entorno,
    ent.totalv_entorno,
    sal.diag_salud,
    sal.dis_salud,
    sal.trat_salud,
    sal.trasa_salud,
    sal.med_salud,
    sal.expreso_salud,
    at.comunidad_atributo,
    at.educom_atributo,
    at.dia_educom_atributo,
    at.horario_educom_atributo,
    at.deporte_atributo,
    at.dia_deporte_atributo,
    at.horario_deporte_atributo,
    at.jtrab_atributo,
    at.dia_jtrab_atributo,
    at.horario_jtrab_atributo,
    de.sede_dato,
    de.jornada_dato,       
    oe.gradop_observador,
    de.lugar_nac_dato, 
    at.des_atributo,
    at.ind_atributo,
    sal.def_salud,
    at.cuales_atributo,
    ent.tieli_entorno,
    ent.vive_entorno,
    ent.esp_entorno,
    ent.n_hermanos_entorno,
    ad.jacom_adicional,
    ad.ccuento_adicional,
    ad.transp_adicional
FROM dato_estudiante de
INNER JOIN observador_estudiante oe ON de.id_dato = oe.id_observador
INNER JOIN fichai_estudiante fh ON de.id_dato = fh.id_fichai
INNER JOIN caracteristicas_estudiante car ON de.id_dato = car.id_caracteristicas
INNER JOIN salud_estudiante sal ON de.id_dato = sal.id_salud
INNER JOIN entorno_estudiantes ent ON de.id_dato = ent.id_entorno
INNER JOIN adicional_estudiante ad ON de.id_dato = ad.id_adicional
INNER JOIN atributo_estudiante at ON de.id_dato = at.id_atributo
WHERE de.id_dato = $id_dato;
";

$resultado = mysqli_query($enlace, $consulta);
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($enlace));
}

$datos = mysqli_fetch_assoc($resultado);
$doc_dato = $datos['doc_dato'] ?? 'default';
$_SESSION['doc_dato'] = $doc_dato; // Guardar en sesión

$fecha_nacimiento = $datos['nac_dato']; // formato AAAA-MM-DD
$fecha = $datos['nac_dato']; // por ejemplo: 2001-10-07
$edad = date_diff(date_create($fecha), date_create('today'))->y;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caracterizacion</title>

    <link rel="stylesheet" href="../../../bootstrap-5.3.7-dist/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="./style.css">

</head>
<style>
/* Para navegadores basados en WebKit (Chrome, Safari, Edge) */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Para Firefox y otros navegadores modernos */
input[type=number] {
  -moz-appearance: textfield; /* Prefijo de Firefox para compatibilidad */
  appearance: none; /* Propiedad estándar */
  margin: 0;
}

</style>

<body>

    <div class="w-100">
        <img src="../../../Imagenes/Banner.png" alt="" class="img-fluid"
            style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <a href="../INFO_INDIVIDUAL.php" class="rounded-pill m-4 volver"><span
            class="material-symbols-outlined mx-2">logout</span>Volver</a>

    <form class="m-4" action="actualizar.php" method="POST">

        <table class="table table-hover">

            <!--Primera linea-->
            <tr>

                <td>ESTUDIANTE</td>

                <td><input class="campo form-control" type="text" name="nom_dato" placeholder="NOMBRE Y APELLIDO" value="<?php echo htmlspecialchars($datos['nom_dato']); ?>"></td>

                <td>

                    <div class="row d-flex flex-colum align-items-center">

                        <div class="col d-flex flex-colum align-items-center">

                            <label>FECHA DE NACIMIENTO:</label>

                            <input class="campo form-control" type="date" value="<?php echo htmlspecialchars($datos['nac_dato']); ?>">

                        </div>
                    
                    </div>

                </td>

                <td>

                <div class="row d-flex flex-colum align-items-center">

                        <div class="col d-flex flex-colum align-items-center gap-2">

                            <label>EDAD</label>

                            <input class="campo form-control" type="number" name="edad" placeholder="EDAD" value="<?php echo $edad; ?>">

                        </div>
                    
                    </div>

                </td>

            </tr>

            <!--Segunda linea-->
            <tr>

                <td>DOCUMENTO</td>

                <td>

                    <div class="d-flex justify-content-between gap-3">

                        <div class="w-100">
                            <div class="d-flex align-items-center gap-2 w-100">

                                <input type="radio" class="btn-check" id="btn-check-outlined1" autocomplete="off"
                                    name="tipo_doc_dato" value="R.C" <?php if ($datos['tipo_doc_dato'] == "R.C") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined1">R.C</label><br>

                                <input type="radio" class="btn-check" id="btn-check-outlined2" autocomplete="off"
                                    name="tipo_doc_dato" value="T.I" <?php if ($datos['tipo_doc_dato'] == "T.I") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined2">T.I</label><br>

                                <input type="radio" class="btn-check" id="btn-check-outlined3" autocomplete="off"
                                    name="tipo_doc_dato" value="C.C" <?php if ($datos['tipo_doc_dato'] == "C.C") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined3">C.C</label><br>

                                <input type="radio" class="btn-check" id="btn-check-outlined4" autocomplete="off"
                                    name="tipo_doc_dato" value="OTRO" <?php if ($datos['tipo_doc_dato'] == "OTRO") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined4">OTRO</label><br>

                            </div>
                        </div>

                </td>

                <td>

                    <div class="d-flex align-items-center gap-2">

                        <label for="doc_dato">Nro</label>
                        <input class="campo form-control" type="number" name="doc_dato"
                            placeholder="NUMERO DE DOCUMETO" value="<?php echo htmlspecialchars($datos['doc_dato']); ?>">

                    </div>

                </td>

                <td>

                    <div class="d-flex align-items-center gap-2">

                        <label for="rh_dato">GRUPO SANGUINEO</label>
                        <input class="campo form-control" type="text" name="rh_dato"
                            placeholder="GRUPO SANGUINEO" value="<?php echo htmlspecialchars($datos['rh_dato']); ?>">

                    </div>

                </td>

            </tr>

            <!--Tercera Linea-->
            <tr>

                <td>ESTUDIANTE</td>

                <td colspan="2">

                    <div class="w-100">
                        <div class="d-flex align-items-center gap-2 w-100">

                            <input type="radio" class="btn-check" id="btn-check-outlined5" autocomplete="off"
                                name="estado_dato" value="ANTIGUO"<?php if ($datos['estado_dato'] == "ANTIGUO") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined5">ANTIGUO</label><br>

                            <input type="radio" class="btn-check" id="btn-check-outlined6" autocomplete="off"
                                name="estado_dato" value="ANTIGUO REPITENTE" <?php if ($datos['estado_dato'] == "ANTIGUO REPITENTE") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined6">A. REPITENTE</label><br>

                            <input type="radio" class="btn-check" id="btn-check-outlined7" autocomplete="off"
                                name="estado_dato" value="NUEVO" <?php if ($datos['estado_dato'] == "NUEVO") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined7">NUEVO</label><br>

                            <input type="radio" class="btn-check" id="btn-check-outlined8" autocomplete="off"
                                name="estado_dato" value="NUEVO REPITENTE" <?php if ($datos['estado_dato'] == "NUEVO REPITENTE") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined8">N. REPITENTE</label><br>

                        </div>
                    </div>

                </td>

                <td>

                    <div class="d-flex align-items-center gap-2">

                        <label for="col_dato">COLEGIO DE PROCEDENCIA</label>
                        <input class="campo form-control" type="text" name="col_dato"
                            placeholder="COLEGIO DE PROCEDENCIA" value="<?php echo htmlspecialchars($datos['col_dato']); ?>">

                    </div>

                </td>

            </tr>

            <!--Cuarta linea-->
            <tr>

                <td>DIRECCION</td>

                <td>

                    <div class="d-flex align-items-center gap-2">

                        <label for="dir_caracteristicas">DIRECCION RESIDENCIA</label>
                        <input class="campo form-control" type="text" name="dir_caracteristicas"
                            placeholder="DIRECCION RESIDENCIA" value="<?php echo htmlspecialchars($datos['dir_caracteristicas']); ?>">

                    </div>

                </td>

                <td>

                    <div class="row d-flex flex-colum w-100 align-items-center">

                        <div class="col w-100">
                            <input class="campo form-control" type="text" name="barri_caracteristicas" placeholder="BARRIO" value="<?php echo htmlspecialchars($datos['barri_caracteristicas']); ?>">
                        </div>

                        <div class="col w-100">
                            <input class="campo form-control" type="number" name="com_caracteristicas" placeholder="COMUNA" value="<?php echo htmlspecialchars($datos['com_caracteristicas']); ?>">
                        </div>

                        <div class="col w-100">
                            <input class="campo form-control" type="number" name="est_caracteristicas" placeholder="ESTRATO" value="<?php echo htmlspecialchars($datos['est_caracteristicas']); ?>">
                        </div>

                    </div>

                </td>

                <td>

                    <div class="d-flex align-items-center gap-2">

                        <label for="eps_caracteristicas">SERVICIO DE SALUD</label>
                        <input class="campo form-control" type="text" name="eps_caracteristicas"
                            placeholder="SERVICIO DE SALUD" value="<?php echo htmlspecialchars($datos['eps_caracteristicas']); ?>">

                    </div>

                </td>

            </tr>

            <!--Quinta linea-->
            <tr>

                <td>CONTACTOS</td>

                <td colspan="2">

                    <div class="row d-flex flex-colum align-items-center w-100">

                        <div class="col w-100">

                            <div class="d-flex flex-colum align-items-center gap-1 py-2">

                                <label for="">TELEFONO FIJO</label>

                            </div>

                            <input class="campo form-control" type="number" name="cel_caracteristicas"
                                placeholder="TELEFONO FIJO" value="<?php echo htmlspecialchars($datos['cel_caracteristicas']); ?>">

                        </div>

                        <div class="col w-100">

                            <div class="d-flex flex-colum align-items-center gap-1">

                                <label for="">TELEFONO MOVIL</label>
                                <input type="radio" class="btn-check" id="btn-check-outlined9" autocomplete="off"
                                    name="p_tel_m1_caracteristicas" value="p" <?php if ($datos['p_tel_m1_caracteristicas'] == "P") echo "checked"; ?>>
                                <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                    for="btn-check-outlined9">P</label><br>
                                <input type="radio" class="btn-check" id="btn-check-outlined10" autocomplete="off"
                                    name="p_tel_m1_caracteristicas" value="M" <?php if ($datos['p_tel_m1_caracteristicas'] == "M") echo "checked"; ?>>
                                <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                    for="btn-check-outlined10">M</label><br>

                            </div>

                            <input class="campo form-control" type="number" name="num_m1_caracteristicas"
                                placeholder="TELEFONO MOVIL" value="<?php echo htmlspecialchars($datos['num_m1_caracteristicas']); ?>">

                        </div>

                        <div class="col w-100">

                            <div class="d-flex flex-colum align-items-center gap-1">

                                <label for="">TELEFONO MOVIL</label>
                                <input type="radio" class="btn-check" id="btn-check-outlined11" autocomplete="off"
                                    name="p_tel_m2_caracteristicas" value="P" <?php if ($datos['p_tel_m2_caracteristicas'] == "P") echo "checked"; ?>>
                                <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                    for="btn-check-outlined11">P</label><br>
                                <input type="radio" class="btn-check" id="btn-check-outlined12" autocomplete="off"
                                    name="p_tel_m2_caracteristicas" value="M" <?php if ($datos['p_tel_m2_caracteristicas'] == "M") echo "checked"; ?>>
                                <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                    for="btn-check-outlined12">M</label><br>
                                <input type="radio" class="btn-check" id="btn-check-outlined13" autocomplete="off"
                                    name="p_tel_m2_caracteristicas" value="O" <?php if ($datos['p_tel_m2_caracteristicas'] == "O") echo "checked"; ?>>
                                <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                    for="btn-check-outlined13">O</label><br>

                            </div>

                            <input class="campo form-control" type="number" name="num_m2_caracteristicas"
                                placeholder="TELEFONO MOVIL" value="<?php echo htmlspecialchars($datos['num_m2_caracteristicas']); ?>">

                        </div>

                    </div>

                </td>

                <td>

                    <div class="d-flex flex-colum align-items-center gap-1 py-2">

                        <label>DIR.ELECTRONICA CONTACTO PADRES</label>

                    </div>

                    <input class="campo form-control" type="text" name="gmail_p_caracteristicas"
                        placeholder="DIR.ELECTRONICA CONTACTO PADRES" value="<?php echo htmlspecialchars($datos['gmail_p_caracteristicas']); ?>">

                </td>

            </tr>

            <!--Sexta linea-->
            <tr>

                <td>ACUDIENTE</td>

                <td colspan="2"><input class="campo form-control" type="text" name="acu_caracteristicas" placeholder="Acudiente" value="<?php echo htmlspecialchars($datos['acu_caracteristicas']); ?>">
                </td>

                <td>

                    <div class="d-flex align-items-center gap-2">

                        <label for="acu_paren_caracteristicas">PARENTESCO</label>
                        <input class="campo form-control" type="name" name="acu_paren_caracteristicas" placeholder="Parentesco" value="<?php echo htmlspecialchars($datos['acu_paren_caracteristicas']); ?>">

                    </div>

                </td>

            </tr>

            <!--Séptima linea-->
            <tr>

                <td colspan="2">

                    <label class="my-2"><b>INFORMACION PADRE (PD)</b></label>

                    <!-- Primer input de NOMBRES -->
                    <div class="row mb-2">

                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="pd_nom_caracteristicas" class="mb-0">NOMBRES</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="campo form-control" type="text" name="pd_nom_caracteristicas" placeholder="NOMBRES" value="<?php echo htmlspecialchars($datos['pd_nom_caracteristicas']); ?>">
                        </div>

                    </div>

                    <!-- Segundo input de ESCOLARIDAD y EDAD -->
                    <div class="row mb-2">

                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="pd_esco_caracteristicas" class="mb-0">ESCOLARIDAD</label>
                        </div>
                        <div class="col-sm-5">
                            <input class="campo form-control" type="text" name="pd_esco_caracteristicas"
                                placeholder="ESCOLARIDAD" value="<?php echo htmlspecialchars($datos['pd_esco_caracteristicas']); ?>">
                        </div>
                        <div class="col-sm-5">
                            <input class="campo form-control" type="number" name="pd_edad_caracteristicas" placeholder="EDAD" value="<?php echo htmlspecialchars($datos['pd_edad_caracteristicas']); ?>">
                        </div>

                    </div>

                    <!-- Tercer input de OCUPACION -->
                    <div class="row mb-2">

                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="pd_ocu_caracteristicas" class="mb-0">OCUPACION</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="campo form-control" type="text" name="pd_ocu_caracteristicas"
                                placeholder="OCUPACION" value="<?php echo htmlspecialchars($datos['pd_ocu_caracteristicas']); ?>">
                        </div>

                    </div>

                    <div class="d-flex align-items-center gap-2 w-100 justify-content-end">

                        <div class="d-flex align-items-center gap-2 w-50">

                            <input type="radio" class="btn-check" id="btn-check-outlined14" autocomplete="off"
                                name="pd_trab_caracteristicas" value="INDEPENDIENTE" <?php if ($datos['pd_trab_caracteristicas'] == "INDEPENDIENTE") echo "checked"; ?>>
                            <label class="btn btn-outline-success w-50 align-items-end" style="font-weight: bold;"
                                for="btn-check-outlined14">INDEPENDIENTE</label><br>

                            <input type="radio" class="btn-check" id="btn-check-outlined15" autocomplete="off"
                                name="pd_trab_caracteristicas" value="EMPLEADO" <?php if ($datos['pd_trab_caracteristicas'] == "EMPLEADO") echo "checked"; ?>>
                            <label class="btn btn-outline-success w-50 align-items-end" style="font-weight: bold;"
                                for="btn-check-outlined15">EMPLEADO</label><br>

                        </div>

                    </div>

                </td>


                <td colspan="2">

                    <label class="my-2"><b>INFORMACION MADRE (MD)</b></label>

                    <!-- Primer input de NOMBRES -->
                    <div class="row mb-2">

                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="md_nom_caracteristicas" class="mb-0">NOMBRES</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="campo form-control" type="text" name="md_nom_caracteristicas" placeholder="NOMBRES" value="<?php echo htmlspecialchars($datos['md_nom_caracteristicas']); ?>">
                        </div>

                    </div>

                    <!-- Segundo input de ESCOLARIDAD y EDAD -->
                    <div class="row mb-2">

                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="md_esco_caracteristicas" class="mb-0">ESCOLARIDAD</label>
                        </div>
                        <div class="col-sm-5">
                            <input class="campo form-control" type="text" name="md_esco_caracteristicas"
                                placeholder="ESCOLARIDAD" value="<?php echo htmlspecialchars($datos['md_esco_caracteristicas']); ?>">
                        </div>
                        <div class="col-sm-5">
                            <input class="campo form-control" type="number" name="md_edad_caracteristicas" placeholder="EDAD" value="<?php echo htmlspecialchars($datos['md_edad_caracteristicas']); ?>">
                        </div>

                    </div>

                    <!-- Tercer input de OCUPACION -->
                    <div class="row mb-2">

                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="md_ocu_caracteristicas" class="mb-0">OCUPACION</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="campo form-control" type="text" name="md_ocu_caracteristicas"
                                placeholder="OCUPACION" value="<?php echo htmlspecialchars($datos['md_ocu_caracteristicas']); ?>">
                        </div>

                    </div>

                    <div class="d-flex align-items-center gap-2 w-100 justify-content-end">

                        <div class="d-flex align-items-center w-50 gap-2">

                            <input type="radio" class="btn-check" id="btn-check-outlined16" autocomplete="off"
                                name="md_trab_caracteristicas" value="INDEPENDIENTE" <?php if ($datos['md_trab_caracteristicas'] == "INDEPENDIENTE") echo "checked"; ?>>
                            <label class="btn btn-outline-success w-50 align-items-end" style="font-weight: bold;"
                                for="btn-check-outlined16">INDEPENDIENTE</label><br>

                            <input type="radio" class="btn-check" id="btn-check-outlined17" autocomplete="off"
                                name="md_trab_caracteristicas" value="EMPLEADO" <?php if ($datos['md_trab_caracteristicas'] == "EMPLEADO") echo "checked"; ?>>
                            <label class="btn btn-outline-success w-50 align-items-end" style="font-weight: bold;"
                                for="btn-check-outlined17">EMPLEADO</label><br>

                        </div>

                    </div>

                </td>

            </tr>

            <!--Octava linea-->
            <tr>

                <td>LOS INGRESOS ECONOMICOS DEL HOGAR SON:</td>

                <td colspan="3">

                    <div class="w-100">
                        <div class="d-flex align-items-center gap-2 w-100">

                            <input type="radio" class="btn-check" id="btn-check-outlined18" autocomplete="off"
                                name="economia_caracteristicas" value="MENOS DE UN SALARIO MINIMO" <?php if ($datos['economia_caracteristicas'] == "MENOS DE UN SALARIO MINIMO") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined18">MENOS DE UN SALARIO MINIMO</label><br>

                            <input type="radio" class="btn-check" id="btn-check-outlined19" autocomplete="off"
                                name="economia_caracteristicas" value="ENTRE 1 Y 2 SALARIOS" <?php if ($datos['economia_caracteristicas'] == "ENTRE 1 Y 2 SALARIOS") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined19">ENTRE 1 Y 2 SALARIOS</label><br>

                            <input type="radio" class="btn-check" id="btn-check-outlined20" autocomplete="off"
                                name="economia_caracteristicas" value="MAS DE DOS SALARIOS MINIMOS" <?php if ($datos['economia_caracteristicas'] == "MAS DE DOS SALARIOS MINIMOS") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined20">MAS DE DOS SALARIOS MINIMOS</label><br>

                        </div>
                    </div>

                </td>

            </tr>

            <!--Novena linea-->
            <tr>

                <td colspan="4">

                    <div class="row d-flex align-items-center">
                        
                        <div class="d-flex align-items-center gap-2">

                            <label for="otro"><b>EN CASA VIVO CON:</b></label>
                            <input class="campo form-control" type="text" name="vive_entorno" placeholder="PADRES, MADRE, PADRE, HERMANOS, MADRASTRAS, FAMILIARES, ETC." value="<?php echo htmlspecialchars($datos['vive_entorno']); ?>">

                        </div>
                        <br><br><br><br>
                        <!-- Séptima columna con opciones de casa -->
                        <div class="d-flex flex-column mx-auto gap-2">

                        <label for=""><B>MI CASA ES:</B></label>

                            <input type="radio" class="btn-check" id="btn-check-outlined30" autocomplete="off"
                                name="casa_entorno" value="PROPIA" <?php if ($datos['casa_entorno'] == "PROPIA") echo "checked"; ?>>
                            <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                for="btn-check-outlined30">PROPIA</label>

                            <input type="radio" class="btn-check" id="btn-check-outlined31" autocomplete="off"
                                name="casa_entorno" value="ARRENDADA" <?php if ($datos['casa_entorno'] == "ARRENDADA") echo "checked"; ?>>
                            <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                for="btn-check-outlined31">ARRENDADA</label>

                            <input type="radio" class="btn-check" id="btn-check-outlined32" autocomplete="off"
                                name="casa_entorno" value="DE FAMILIARES" <?php if ($datos['casa_entorno'] == "DE FAMILIARES") echo "checked"; ?>>
                            <label class="btn btn-outline-success" style="font-weight: bold; font-size: 1rem;"
                                for="btn-check-outlined32">DE FAMILIARES</label>

                        </div>
                    </div>
                </td>

            </tr>

            <!--Decimo Primero linea-->
            <tr>

                <td colspan="4">

                    <div class="row d-flex align-items-center">

                        <div class="col align-items-center d-flex mx-auto">

                            <label class="w-100" for=""><b>HIJO UNICO</b></label>

                            <div class="d-flex gap-2 w-100">

                                <input type="radio" class="btn-check" id="btn-check-outlined33" autocomplete="off"
                                    name="hijou_entorno" value="SI" <?php if ($datos['hijou_entorno'] == "SI") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined33">SI</label><br>

                                <input type="radio" class="btn-check" id="btn-check-outlined34" autocomplete="off"
                                    name="hijou_entorno" value="NO" <?php if ($datos['hijou_entorno'] == "NO") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined34">NO</label><br>

                            </div>


                        </div>

                        <div class="col align-items-center d-flex mx-auto">

                            <label class="w-100" for=""><b>TIENE HERMANOS EN EL COLEGIO</b></label>

                            <div class="d-flex gap-2 w-100">

                                <input type="radio" class="btn-check" id="btn-check-outlined35" autocomplete="off"
                                    name="hermano_entorno" value="SI" <?php if ($datos['hermano_entorno'] == "SI") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined35">SI</label><br>

                                <input type="radio" class="btn-check" id="btn-check-outlined36" autocomplete="off"
                                    name="hermano_entorno" value="NO" <?php if ($datos['hermano_entorno'] == "NO") echo "checked"; ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined36">NO</label><br>

                            </div>

                        </div>

                        <div class="col align-items-center d-flex mx-auto">

                            <div class="d-flex align-items-center gap-2 w-100">

                                <label for="totalv_entorno">TOTAL DE PERSONAS QUE VIVEN EN SU HOGAR</label>
                                <input class="campo form-control" type="number" name="totalv_entorno"
                                    placeholder="TOTAL DE PERSONAS QUE VIVIEN EN SU HOGAR" value="<?php echo htmlspecialchars($datos['totalv_entorno']); ?>">

                            </div>

                        </div>

                    </div>

                </td>

            </tr>

            <!--Decimo Segundo linea-->
            <tr>

                <td colspan="4">

                    <!--Inicio de selecciones de discapacidades-->

                    <div class="row align-items-center">

                        <div class="col-2">

                            <label for=""><b>PRESENTO DISCAPACIDAD EN</b></label>

                        </div>

                        <div class="col-10">

                            <textarea class="campo form-control" name="dis_salud" style="resize: none;" rows="3" placeholder="Auditiva castellano oral, sordoceguera, intelectual, psicosocial, multiple, fisica, auditiva lenguaje de señas, visual, trastorno autista TEA, otra"><?php echo htmlspecialchars($datos['dis_salud']); ?></textarea>

                        </div>

                    </div>

                    <!--Fin seleccion de discapacidades-->

                    <br>

                    <!--Inicio seleccion transtorno de aprendizaje-->

                    <div class="row align-items-center">

                        <div class="col-2">

                            <label for=""><b>TRANSTORNO APRENDIZAJE</b></label>

                        </div>

                        <div class="col-10">

                            <input class="campo form-control" name="trasa_salud" placeholder="Lectura, escritura, calculo, ortografia, conducta, de habla - lenguaje" type="text" value="<?php echo htmlspecialchars($datos['trasa_salud']); ?>">

                        </div>

                    </div>

                    <!--Fin seleccion transtono de aprendizaje-->

                </td>

            </tr>

            <!--Decimo Tercera linea-->
            <tr>

                <td colspan="4">

                    <div class="row d-flex align-items-start">

                        <div class="col-6">

                            <label for=""><b>PRESENTO ENFERMEDAD DIAGNOSTICADA</b></label>

                            <div class="d-flex gap-2">

                                <div class="d-flex gap-2 w-50">

                                    <input type="radio" class="btn-check" id="btn-check-outlined53" autocomplete="off"
                                        name="" value="NO" <?php if ($datos['diag_salud'] == '') {
                                            echo "checked";
                                        } ?>>
                                    <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                        for="btn-check-outlined53">NO</label>

                                    <input type="radio" class="btn-check" id="btn-check-outlined54" autocomplete="off"
                                        name="" value="SI" <?php if ($datos['diag_salud'] !== '') {
                                            echo "checked";
                                        } ?>>
                                    <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                        for="btn-check-outlined54">SI</label>

                                </div>

                                <div class="d-flex gap-2 align-items-center w-50">

                                    <label for="diag_salud">CUAL</label>
                                    <input class="campo form-control" type="text" name="diag_salud" placeholder="CUAL" value="<?php echo htmlspecialchars($datos['diag_salud']); ?>">

                                </div>

                            </div>

                        </div>

                        <div class="col-6">

                            <label for=""><b>RECIBO TRATAMIENTO MEDICO</b></label>

                            <div class="d-flex gap-2">

                                <div class="d-flex gap-2 w-50">

                                    <input type="radio" class="btn-check" id="btn-check-outlined55" autocomplete="off"
                                        name="tratamiento_medico" value="No" <?php if ($datos['trat_salud'] == '') {
                                            echo "checked";
                                        } ?>>
                                    <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                        for="btn-check-outlined55">NO</label>

                                    <input type="radio" class="btn-check" id="btn-check-outlined56" autocomplete="off"
                                        name="tratamiento_medico" value="Si" <?php if ($datos['trat_salud'] !== '') {
                                            echo "checked";
                                        } ?>>
                                    <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                        for="btn-check-outlined56">SI</label>

                                </div>

                                <div class="d-flex gap-2 align-items-center w-50">

                                    <label for="trat_salud">CUAL</label>
                                    <input class="campo form-control" type="text" name="trat_salud" placeholder="CUAL" value="<?php echo htmlspecialchars($datos['trat_salud']); ?>">

                                </div>

                            </div>

                        </div>

                    </div>

                    <br>

                    <div class="row d-flex align-items-center">

                        <div class="col-2">

                            <label for=""><b>CONSUMO ALGUN MEDICAMENTO</b></label>

                        </div>

                        <div class="col-2 gap-2 d-flex">

                            <input type="radio" class="btn-check" id="btn-check-outlined57" autocomplete="off"
                                name="algun_medicamento" value="No" <?php if ($datos['med_salud'] == '') {
                                            echo "checked";
                                        } ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined57">NO</label>

                            <input type="radio" class="btn-check" id="btn-check-outlined58" autocomplete="off"
                                name="algun_medicamento" value="Si" <?php if ($datos['med_salud'] !== '') {
                                            echo "checked";
                                        } ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined58">SI</label>

                        </div>

                        <div class="col-8 d-flex align-items-center gap-2">

                            <label for="">DESCRIBA</label>
                            <input class="campo form-control" type="text" name="med_salud" placeholder="DESCRIBA" value="<?php echo htmlspecialchars($datos['med_salud']); ?>">

                        </div>

                    </div>

                </td>

            </tr>

            <!--Decimo Cuarta linea-->
            <tr>

                <td colspan="4">

                    <label style="margin-bottom: 1rem;" for=""><b>EXPRESO EXCEPCIONALIDAD DEMOSTRABLE EN:</b></label>

                    <div class="d-flex align-items-center gap-2">

                        <input class="campo form-control" name="expreso_salud" placeholder="Tecnologia, liderazgo social, ciencias de la naturaleza, artes y letras, actividad fisica, Cienc. sociales y hum" type="text" value="<?php echo htmlspecialchars($datos['expreso_salud']); ?>">

                    </div>

                </td>

            </tr>

            <!--Duodécima Quinta linea-->
            <tr>

                <td colspan="4">

                    <label style="margin-bottom: 1rem;" for=""><b>ESCRIBO LO QUE ME INDENTIFICA O CORRESPONDA:</b></label>

                    <div class="d-flex align-items-center gap-2">

                         <textarea class="campo form-control" name="comunidad_atributo" style="resize: none;" rows="3" placeholder="Com. blanca, Com. mestiza, Com. rural, afrocolombianidad, grupos indigenas, comunidad lgbti, desplazado, victima conflicto armado, victima de conflicto armado, hijo de desmovilizado, asentamiento subnormal, asistido fundacion / ICBF" value="<?php echo htmlspecialchars($datos['comunidad_atributo']); ?>"></textarea>

                    </div>

                </td>

            </tr>

            <!--Decimo Sexta linea-->
            <tr>

                <td colspan="4">

                    <div>

                        <!--Educación Complementaria-->
                        <div class="row">

                            <div class="col d-flex gap-2 h-50 mt-auto">

                                <input type="radio" class="btn-check" id="btn-check-outlined76" autocomplete="off"
                                    name="educacion_complementaria" value="No" <?php if ($datos['educom_atributo'] == '') {
                                            echo "checked";
                                        } ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined76">NO</label>

                                <input type="radio" class="btn-check" id="btn-check-outlined77" autocomplete="off"
                                    name="educacion_complementaria" value="Si" <?php if ($datos['educom_atributo'] !== '') {
                                            echo "checked";
                                        } ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined77">SI</label>

                            </div>

                            <div class="col">

                                <label class="w-100 text-start" style="margin-left: 8rem;" for=""><b>RECIBO EDUCACION
                                        COMPLEMENTARIA</b></label>

                                <div class="d-flex align-items-center gap-2">

                                    <label for="educom_atributo" class="w-25 text-end">DESCRIBA</label>
                                    <input class="campo form-control" type="number" name="educom_atributo"
                                        placeholder="SENA, CURSOS CORTOS, IDRD, OTROS" value="<?php echo htmlspecialchars($datos['educom_atributo']); ?>">

                                </div>

                            </div>

                            <div class="col h-50 mt-auto d-flex gap-2">

                                <div class="d-flex align-items-center gap-2 w-100">

                                    <label for="dia_educom_atributo" class="w-25 text-end">DIAS</label>
                                    <input class="campo form-control" type="text" name="dia_educom_atributo"
                                        placeholder="DIAS" value="<?php echo htmlspecialchars($datos['dia_educom_atributo']); ?>">

                                </div>

                                <div class="d-flex align-items-center gap-2 w-50">

                                    <label for="horario_educom_atributo" class="text-end">HORARIO</label>
                                    <input class="campo form-control" type="number"
                                        name="horario_educom_atributo" placeholder="HORARIO" value="<?php echo htmlspecialchars($datos['horario_educom_atributo']); ?>">

                                </div>

                            </div>

                        </div>

                        <br>

                        <!--Entrenamiento Deportivo-->
                        <div class="row">

                            <div class="col d-flex gap-2 h-50 mt-auto">

                                <input type="radio" class="btn-check" id="btn-check-outlined78" autocomplete="off"
                                    name="entrenamiento_deportivo" value="No" <?php if ($datos['deporte_atributo'] == '') {
                                            echo "checked";
                                        } ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined78">NO</label>

                                <input type="radio" class="btn-check" id="btn-check-outlined79" autocomplete="off"
                                    name="entrenamiento_deportivo" value="Si" <?php if ($datos['deporte_atributo'] !== '') {
                                            echo "checked";
                                        } ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined79">SI</label>

                            </div>

                            <div class="col">

                                <label class="w-100 text-start" style="margin-left: 8rem;" for=""><b>ASISTO A
                                        ENTRENAMIENTO DEPORTIVO</b></label>

                                <div class="d-flex align-items-center gap-2">

                                    <label for="deporte_atributo" class="w-25 text-end">DEPORTE</label>
                                    <input class="campo form-control" type="number" name="deporte_atributo"
                                        placeholder="BASQUETBOLL, NATACION, GIMNACIA, OTROS" value="<?php echo htmlspecialchars($datos['deporte_atributo']); ?>">

                                </div>

                            </div>

                            <div class="col h-50 mt-auto d-flex gap-2">

                                <div class="d-flex align-items-center gap-2 w-100">

                                    <label for="dia_deporte_atributo" class="w-25 text-end">DIAS</label>
                                    <input class="campo form-control" type="text" name="dia_deporte_atributo"
                                        placeholder="DIAS" value="<?php echo htmlspecialchars($datos['dia_deporte_atributo']); ?>">

                                </div>

                                <div class="d-flex align-items-center gap-2 w-50">

                                    <label for="horario_deporte_atributo" class="text-end">HORARIO</label>
                                    <input class="campo form-control" type="number"
                                        name="horario_deporte_atributo" placeholder="HORARIO" value="<?php echo htmlspecialchars($datos['horario_deporte_atributo']); ?>">

                                </div>

                            </div>

                        </div>

                        <br>

                        <!--Joven Trabajador-->
                        <div class="row">

                            <div class="col d-flex gap-2 h-50 mt-auto">

                                <input type="radio" class="btn-check" id="btn-check-outlined80" autocomplete="off"
                                    name="joven_trabajador" value="No" <?php if ($datos['jtrab_atributo'] == '') {
                                            echo "checked";
                                        } ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined80">NO</label>

                                <input type="radio" class="btn-check" id="btn-check-outlined81" autocomplete="off"
                                    name="joven_trabajador" value="Si" <?php if ($datos['jtrab_atributo'] !== '') {
                                            echo "checked";
                                        } ?>>
                                <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                    for="btn-check-outlined81">SI</label>

                            </div>

                            <div class="col">

                                <label class="w-100 text-start" style="margin-left: 8rem;" for=""><b>SOY JOVEN
                                        TRABAJADOR</b></label>

                                <div class="d-flex align-items-center gap-2">

                                    <label for="jtrab_atributo" class="w-25 text-end">OCUPACION</label>
                                    <input class="campo form-control" type="number" name="jtrab_atributo"
                                        placeholder="TRABAJA COMO: MESERO, AUXILIAR, VENDEDOR, OTROS" value="<?php echo htmlspecialchars($datos['jtrab_atributo']); ?>">

                                </div>

                            </div>

                            <div class="col h-50 mt-auto d-flex gap-2">

                                <div class="d-flex align-items-center gap-2 w-100">
                                    <label for="dia_jtrab_atributo" class="w-25 text-end">DIAS</label>
                                    <input class="campo form-control" type="text" name="dia_jtrab_atributo"
                                        placeholder="DIAS" value="<?php echo htmlspecialchars($datos['dia_jtrab_atributo']); ?>">
                                </div>

                                <div class="d-flex align-items-center gap-2 w-50">
                                    <label for="horario_jtrab_atributo" class="text-end">HORARIO</label>
                                    <input class="campo form-control" type="number" name="horario_jtrab_atributo"
                                        placeholder="HORARIO" value="<?php echo htmlspecialchars($datos['horario_jtrab_atributo']); ?>">
                                </div>

                            </div>

                        </div>

                    </div>


                </td>

            </tr>

            <!--Decimo Septima linea-->
            <tr>

                <td colspan="4">
                    <div class="row d-flex align-items-center">
                        <div class="col-2 align-items-center">

                            <label for="jacom_adicional"><b>EN LA JORNADA CONTRARIO ME ACOMPAÑAN</b></label>

                        </div>

                    <div class="col-10">
                        <input class="campo form-control" type="text" name="jacom_adicional"
                            placeholder="MADRE, PADRE, PADRES, HERMANOS..." value="<?php echo htmlspecialchars($datos['jacom_adicional']); ?>">
                    </div>

                    </div>
                    
                    <br><br>
                    <div class="row d-flex align-items-center">

                        <div class="col-2 align-items-center">

                            <label for=""><b>EN CASA CUENTO CON</b></label>

                        </div>

                        <div class="col-10">

                            <input class="campo form-control" name="ccuento_adicional" placeholder="Computador, internet, tv. suscripcion, celular personal, servicios publicos" type="text" value="<?php echo htmlspecialchars($datos['ccuento_adicional']); ?>">

                        </div>

                    </div>

                </td>

            </tr>

            <!--Decimo Octava linea-->
            <tr>

                <td colspan="4">

                    <label style="margin-bottom: 1rem;" for=""><b>ME TRANSPORTO AL COLEGIO EN:</b></label>

                    <div class="row d-flex align-items-center">

                        <div class="col-2 align-items-center">

                            <input type="radio" class="btn-check" id="btn-check-outlined92" autocomplete="off"
                                name="transp_adicional" value="Servicio Publico" <?php if ($datos['transp_adicional'] == "Servicio Publico") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined92">Servicio Publico</label>

                        </div>

                        <div class="col-2 align-items-center">

                            <input type="radio" class="btn-check" id="btn-check-outlined93" autocomplete="off"
                                name="transp_adicional" value="Vehiculo familiar" <?php if ($datos['transp_adicional'] == "Vehiculo familiar") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined93">Vehiculo familiar</label>

                        </div>

                        <div class="col-2 align-items-center">

                            <input type="radio" class="btn-check" id="btn-check-outlined94" autocomplete="off"
                                name="transp_adicional" value="Bicicleta" <?php if ($datos['transp_adicional'] == "Bicicleta") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined94">Bicicleta</label>

                        </div>

                        <div class="col-2 align-items-center">

                            <input type="radio" class="btn-check" id="btn-check-outlined95" autocomplete="off"
                                name="transp_adicional" value="Motocicleta Personal" <?php if ($datos['transp_adicional'] == "Motocicleta Personal") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined95">Motocicleta Personal</label>

                        </div>

                        <div class="col-2 align-items-center">

                            <input type="radio" class="btn-check" id="btn-check-outlined96" autocomplete="off"
                                name="transp_adicional" value="Caminando" <?php if ($datos['transp_adicional'] == "Caminando") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined96">Caminando</label>

                        </div>

                        <div class="col-2 align-items-center">

                            <input type="radio" class="btn-check" id="btn-check-outlined97" autocomplete="off"
                                name="transp_adicional" value="Pago Ruta" <?php if ($datos['transp_adicional'] == "Pago Ruta") echo "checked"; ?>>
                            <label class="btn btn-outline-success mx-auto w-100" style="font-weight: bold;"
                                for="btn-check-outlined97">Pago Ruta</label>

                        </div>

                    </div>

                </td>

            </tr>

            <tr>

                <td colspan="4">

                    <center>
                    <label style="margin-top: 3rem;" for=""><b>PADRE/ MADRE DE FAMILIA O ACUDIENTE, INFORMACION ADICIONAL RELEVANTE SOBRE SU HIJO/A OFRECERLA EN ORIENTACIÓN ESCOLAR</b></label>
                    </center>

                </td>

            </tr>

        </table>


        <div class="col-12 d-flex justify-content-end pe-4">

            <button class="btn-actualizar rounded-pill my-4" type="submit"><span
                    class="material-symbols-outlined mx-1">edit</span>Actualizar</button>

        </div>

    </form>

    <script src="../../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>