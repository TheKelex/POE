<?php
session_start();
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
    fh.espdoc_fichai,
    fh.docente_fichai,
    de.lugar_nac_dato,
    de.nac_dato, 
    de.edad_dato,
    car.dir_caracteristicas,
    car.barri_caracteristicas,
    fh.remitente_fichai,
    fh.f_remic_fichai,
    fh.f_aten_fichai,
    fh.asesor_fichai,
    car.eps_caracteristicas,
    fh.situs_fichai,
    sal.dis_salud,
    fh.seguit_fichai,
    car.pd_nom_caracteristicas,
    car.num_m1_caracteristicas,
    car.pd_doc_caracteristicas,
    car.pd_ocu_caracteristicas,
    car.md_nom_caracteristicas,
    car.num_m2_caracteristicas,
    car.md_doc_caracteristicas,
    car.md_ocu_caracteristicas,
    ent.vive_entorno,
    car.acu_caracteristicas,
    car.acu_paren_caracteristicas,
    car.acu_doc_caracteristicas,    
    car.acu_cel_caracteristicas,
    car.acu_ocup_caracteristicas,
    fh.mconsulta_fichai,
    fh.antecedentes_fichai,
    fh.acc_realizadas_fichai,
    fh.compromiso_fichai,
    fh.continu_fichai,
    fh.oriente_fichai
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
WHERE de.id_dato = $id_dato;
    ";

     $resultado = mysqli_query($enlace, $consulta);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($enlace));
    }

    $datos = mysqli_fetch_assoc($resultado);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ficha_individual</title>

    <link rel="stylesheet" href="../../../bootstrap-5.3.7-dist/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="./style.css">
</head>

<body>

    <div class="w-100">
        <img src="../../../Imagenes/Banner.png" alt="" class="img-fluid" style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <a href="../INFO_INDIVIDUAL.php" class="rounded-pill m-4 volver"><span class="material-symbols-outlined mx-2">logout</span>Volver</a>

    <h1 class="text-center" style="font-weight: bold;">FICHA INDIVIDUAL DEL ESTUDIANTE</h1>

    <!--Inicio de la tabla-->


    <form class="m-4" action="actualizar.php" method="POST">

        <table class="table table-hover">

            <!--Primera linea-->
            <tr>
            <td>NOMBRE DEL ESTUDIANTE</td>
            <td colspan="3">
                <input class="campo form-control" 
                       type="text" 
                       name="nom_dato" 
                       placeholder="Nombre Del Estudiante"
                       value="<?php echo htmlspecialchars($datos['nom_dato']); ?>">
            </td>
        </tr>

            <!--Segunda linea-->
            <tr>
                <td>DOCUMENTO DE IDENTIFICACION</td>
                <td>
                    <input class="campo form-control"
                        type="number"
                        name="doc_dato"
                        placeholder="Documento De Identificacion"
                        value="<?php echo htmlspecialchars($datos['doc_dato']); ?>">
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <label for="sede_dato">SEDE:</label>
                        <select class="form-select" name="sede_dato" id="sede" aria-placeholder="seleccione una opcion">
                            <option value="" disabled>Seleccione Una Opcion</option>
                            <option value="Central" <?php if ($datos['sede_dato'] == "central") echo "selected"; ?>>Central</option>
                            <option value="Florez Miro Azuero" <?php if ($datos['sede_dato'] == "florez") echo "selected"; ?>>Florez Miro Azuero</option>
                            <option value="Elena Lara De Cuellar" <?php if ($datos['sede_dato'] == "elena") echo "selected"; ?>>Elena Lara De Cuellar</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <label for="jornada_dato">JORNADA:</label>
                        <select class="form-select" name="jornada_dato" id="jornada">
                            <option value="" disabled>Seleccione Una Opcion</option>
                            <option value="Mañana" <?php if ($datos['jornada_dato'] == "mañana") echo "selected"; ?>>Mañana</option>
                            <option value="Tarde" <?php if ($datos['jornada_dato'] == "tarde") echo "selected"; ?>>Tarde</option>
                        </select>
                    </div>
                </td>
            </tr>

            <!--Tercera Linea-->
            <tr>
                <td>GRADO</td>
                <td>
                    <input class="campo form-control"
                        type="number"
                        name="gradop_observador"
                        placeholder="Grado"
                        value="<?php echo htmlspecialchars($datos['gradop_observador']); ?>">
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <label for="espdoc_fichai">ESPECIALIDAD:</label>
                        <input class="campo form-control"
                            type="text"
                            name="espdoc_fichai"
                            placeholder="Especialidad"
                            value="<?php echo htmlspecialchars($datos['espdoc_fichai']); ?>">
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <label for="docente_fichai">DOCENTE:</label>
                        <input class="campo form-control"
                            type="text"
                            name="docente_fichai"
                            placeholder="Docente"
                            value="<?php echo htmlspecialchars($datos['docente_fichai']); ?>">
                    </div>
                </td>
            </tr>

            <!--Cuarta linea-->
            <tr>
                <td>LUGAR Y FECHA DE NACIMIENTO</td>
                <td colspan="2">
                    <div class="d-flex align-items-center gap-2">
                        <label for="lugar_nac_dato">LUGAR:</label>
                        <input class="campo form-control"
                            type="text"
                            name="lugar_nac_dato"
                            placeholder="Lugar"
                            value="<?php echo htmlspecialchars($datos['lugar_nac_dato']); ?>">

                        <label for="nac_dato">FECHA:</label>
                        <input class="campo form-control"
                            type="date"
                            name="nac_dato"
                            value="<?php echo htmlspecialchars($datos['nac_dato']); ?>">
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <label for="edad_dato">EDAD:</label>
                        <input class="campo form-control"
                            type="number"
                            name="edad_dato"
                            placeholder="Edad"
                            value="<?php echo htmlspecialchars($datos['edad_dato']); ?>">
                    </div>
                </td>
            </tr>

            <!--Quinta linea-->
            <tr>
                <td>DIRECCION</td>
                <td colspan="2">
                    <input class="campo form-control"
                        type="text"
                        name="dir_caracteristicas"
                        placeholder="Direccion"
                        value="<?php echo htmlspecialchars($datos['dir_caracteristicas']); ?>">
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <label for="barri_caracteristicas">BARRIO:</label>
                        <input class="campo form-control"
                            type="text"
                            name="barri_caracteristicas"
                            placeholder="Barrio"
                            value="<?php echo htmlspecialchars($datos['barri_caracteristicas']); ?>">
                    </div>
                </td>
            </tr>

            <!--Sexta linea-->
            <tr>
                <td>REMITENTE</td>
                <td colspan="3">
                    <input class="campo form-control"
                        type="text"
                        name="remitente_fichai"
                        placeholder="Remitente"
                        value="<?php echo htmlspecialchars($datos['remitente_fichai']); ?>">
                </td>
            </tr>

            <!--Séptima linea-->
            <tr>
                <td>FECHA DE REMISION</td>
                <td>
                    <input class="campo form-control"
                        type="date"
                        name="f_remic_fichai"
                        value="<?php echo htmlspecialchars($datos['f_remic_fichai']); ?>">
                </td>
                <td colspan="2">
                    <div class="d-flex align-items-center gap-2">
                        <label for="f_aten_fichai">FECHA DE ATENCION:</label>
                        <input class="campo form-control"
                            type="date"
                            name="f_aten_fichai"
                            value="<?php echo htmlspecialchars($datos['f_aten_fichai']); ?>">
                    </div>
                </td>
            </tr>

            <!--Octava linea-->
            <tr>
                <td>ASESOR (A) DE GRUPO</td>
                <td colspan="3">
                    <input class="campo form-control"
                        type="text"
                        name="asesor_fichai"
                        placeholder="Asesor (A)"
                        value="<?php echo htmlspecialchars($datos['asesor_fichai']); ?>">
                </td>
            </tr>

            <!--Novena linea-->
            <tr>
                <td>E.P.S:</td>
                <td>
                    <input class="campo form-control"
                        type="text"
                        name="eps_caracteristicas"
                        placeholder="E.P.S"
                        value="<?php echo htmlspecialchars($datos['eps_caracteristicas']); ?>">
                </td>
                <td colspan="2">
                    <div class="d-flex align-items-center gap-2">
                        <label for="situs_fichai">SITUACION DE SALUD:</label>
                        <input class="campo form-control"
                            type="text"
                            name="situs_fichai"
                            placeholder="Situacion De Salud"
                            value="<?php echo htmlspecialchars($datos['situs_fichai']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Primera linea-->
            <tr>
                <td>DISCAPACIDAD Y/O TRASTORNO DE APRENDIZAJE</td>
                <td>
                    <input class="campo form-control"
                        type="text"
                        name="dis_salud"
                        placeholder="Discapacidad"
                        value="<?php echo htmlspecialchars($datos['dis_salud']); ?>">
                </td>
                <td colspan="2">
                    <div class="d-flex align-items-center gap-2">
                        <label for="seguit_fichai">SEGUIMIENTO Y TERAPIAS:</label>
                        <input class="campo form-control"
                            type="text"
                            name="seguit_fichai"
                            placeholder="Seguimiento Y Terapias"
                            value="<?php echo htmlspecialchars($datos['seguit_fichai']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Segundo linea-->
            <tr>
                <td>NOMBRE DEL PADRE:</td>
                <td colspan="3">
                    <div class="d-flex align-items-center gap-2">
                        <input class="campo form-control"
                            type="text"
                            name="pd_nom_caracteristicas"
                            placeholder="Nombre Del Padre"
                            value="<?php echo htmlspecialchars($datos['pd_nom_caracteristicas']); ?>">

                        <label for="p_tel_m1_caracteristicas">CELULAR:</label>
                        <input class="campo form-control"
                            type="text"
                            name="num_m1_caracteristicas"
                            placeholder="Celular Del Padre"
                            value="<?php echo htmlspecialchars($datos['num_m1_caracteristicas']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Tercera linea-->
            <tr>
                <td>DOC. DE IDENTIFICACION:</td>
                <td colspan="3">
                    <div class="d-flex align-items-center gap-2">
                        <input class="campo form-control"
                            type="number"
                            name="pd_doc_caracteristicas"
                            placeholder="Doc. De Identificacion"
                            value="<?php echo htmlspecialchars($datos['pd_doc_caracteristicas']); ?>">

                        <label for="pd_ocu_caracteristicas">OCUPACION:</label>
                        <input class="campo form-control"
                            type="text"
                            name="pd_ocu_caracteristicas"
                            placeholder="Ocupacion Del Padre"
                            value="<?php echo htmlspecialchars($datos['pd_ocu_caracteristicas']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Cuarta linea-->
            <tr>
                <td>NOMBRE DE LA MADRE:</td>
                <td colspan="3">
                    <div class="d-flex align-items-center gap-2">
                        <input class="campo form-control"
                            type="text"
                            name="md_nom_caracteristicas"
                            placeholder="Nombre De La Madre"
                            value="<?php echo htmlspecialchars($datos['md_nom_caracteristicas']); ?>">

                        <label for="p_tel_m2_caracteristicas">CELULAR:</label>
                        <input class="campo form-control"
                            type="text"
                            name="num_m2_caracteristicas"
                            placeholder="Celular De La Madre"
                            value="<?php echo htmlspecialchars($datos['num_m2_caracteristicas']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Quinta linea-->
            <tr>
                <td>DOC. DE IDENTIFICACION:</td>
                <td colspan="3">
                    <div class="d-flex align-items-center gap-2">
                        <input class="campo form-control"
                            type="number"
                            name="md_doc_caracteristicas"
                            placeholder="Doc. De Identificacion"
                            value="<?php echo htmlspecialchars($datos['md_doc_caracteristicas']); ?>">

                        <label for="md_ocu_caracteristicas">OCUPACION:</label>
                        <input class="campo form-control"
                            type="text"
                            name="md_ocu_caracteristicas"
                            placeholder="Ocupacion De La Madre"
                            value="<?php echo htmlspecialchars($datos['md_ocu_caracteristicas']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Sexta linea-->
            <tr>
                <td>VIVE CON:</td>
                <td colspan="3">
                    <input class="campo form-control"
                        type="text"
                        name="vive_entorno"
                        placeholder="Vive Con"
                        value="<?php echo htmlspecialchars($datos['vive_entorno']); ?>">
                </td>
            </tr>

            <!--Decimo Septima linea-->
            <tr>
                <td>ACUDIENTE:</td>
                <td colspan="3">
                    <div class="d-flex align-items-center gap-2">
                        <input class="campo form-control"
                            type="text"
                            name="acu_caracteristicas"
                            placeholder="Acudiente"
                            value="<?php echo htmlspecialchars($datos['acu_caracteristicas']); ?>">

                        <label for="acu_paren_caracteristicas">PARENTESCO:</label>
                        <input class="campo form-control"
                            type="text"
                            name="acu_paren_caracteristicas"
                            placeholder="Parentesco"
                            value="<?php echo htmlspecialchars($datos['acu_paren_caracteristicas']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Octava linea-->
            <tr>
                <td>DOC. IDENTIFICACION:</td>
                <td colspan="3">
                    <div class="d-flex align-items-center gap-2">
                        <input class="campo form-control"
                            type="number"
                            name="acu_doc_caracteristicas"
                            placeholder="Doc. Identificacion Acudiente"
                            value="<?php echo htmlspecialchars($datos['acu_doc_caracteristicas']); ?>">

                        <label for="acu_cel_caracteristicas">CELULAR:</label>
                        <input class="campo form-control"
                            type="number"
                            name="acu_cel_caracteristicas"
                            placeholder="Celular Del Acudiente"
                            value="<?php echo htmlspecialchars($datos['acu_cel_caracteristicas']); ?>">

                        <label for="acu_ocup_caracteristicas">OCUPACION:</label>
                        <input class="campo form-control"
                            type="text"
                            name="acu_ocup_caracteristicas"
                            placeholder="Ocupacion Del Acudiente"
                            value="<?php echo htmlspecialchars($datos['acu_ocup_caracteristicas']); ?>">
                    </div>
                </td>
            </tr>

            <!--Decimo Novena linea-->
            <tr>
                <td>DESCRIPCION DEL CASO O MOTIVO DE CONSULTA:</td>
                <td colspan="3">
                    <textarea class="campo form-control mx-auto" style="height: 7rem;" name="mconsulta_fichai"><?php echo htmlspecialchars($datos['mconsulta_fichai']); ?></textarea>
                </td>
            </tr>

            <!--Vigesimo linea-->
            <tr>
                <td>ANTECEDENTES:</td>
                <td colspan="3">
                    <textarea class="campo form-control mx-auto" style="height: 7rem;" name="antecedentes_fichai"><?php echo htmlspecialchars($datos['antecedentes_fichai']); ?></textarea>
                </td>
            </tr>

            <!--Vigesimo primera linea-->
            <tr>
                <td>ACCIONES REALIZADAS:</td>
                <td colspan="3">
                    <textarea class="campo form-control mx-auto" style="height: 7rem;" name="acc_realizadas_fichai"><?php echo htmlspecialchars($datos['acc_realizadas_fichai']); ?></textarea>
                </td>
            </tr>

            <!--Vigesimo segunda linea-->
            <tr>
                <td>TAREAS, ACUERDOS O COMPROMISOS PERSONALES:</td>
                <td colspan="3">
                    <textarea class="campo form-control mx-auto" style="height: 7rem;" name="compromiso_fichai"><?php echo htmlspecialchars($datos['compromiso_fichai']); ?></textarea>
                </td>
            </tr>

            <tr>
                <td>CONTINUACION ANAMNESIS:</td>
                <td colspan="3">
                    <textarea class="campo form-control mx-auto" style="height: 7rem;" name="continu_fichai"><?php echo htmlspecialchars($datos['continu_fichai']); ?></textarea>
                </td>
            </tr>

            <tr>
                <td>NOMBRE PSICORIENTADOR</td>
                <td colspan="3">
                    <input class="campo form-control"
                        type="text"
                        name="oriente_fichai"
                        placeholder="Nombre del Psicorientador que atiende el caso"
                        value="<?php echo htmlspecialchars($datos['oriente_fichai']); ?>">
                </td>
            </tr>

        </table>

        <div class="col-12 d-flex justify-content-end pe-4">
            <button class="btn-actualizar rounded-pill my-4" type="submit">
                <span class="material-symbols-outlined mx-1">edit</span>Actualizar
            </button>
        </div>

    </form>


    <!--Fin de la tabla-->

    <script src="../../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>