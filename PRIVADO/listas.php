<?php
session_start();

/* --------------------------
   CONFIGURACIÓN / SEGURIDAD
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}

// ⏳ Control de inactividad (5 minutos)
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

/* --------------------------
   CONEXIÓN A LA BASE DE DATOS
   -------------------------- */
$servidor = "localhost";
$usuario_db = "root";
$contraseña = "";
$basededatos = "poe";
$enlace = mysqli_connect($servidor, $usuario_db, $contraseña, $basededatos);
if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

/* --------------------------
   AUTENTICACIÓN DEL USUARIO
   -------------------------- */
$usuario = $_SESSION['usuario'] ?? '';
$contraseña = $_POST['password'] ?? '';

$sql = "SELECT * FROM usuarios WHERE usuario = ? AND contraseña = ?";
$stmt = mysqli_prepare($enlace, $sql);
mysqli_stmt_bind_param($stmt, "ss", $usuario, $contraseña);
mysqli_stmt_execute($stmt);
$resul = mysqli_stmt_get_result($stmt);

if (!$resul || $resul->num_rows == 0) {
    echo "<script>alert('❌ Contraseña incorrectos');</script>";
    header("Location: estudiantes.php?error=1");
    exit();
}

/* --------------------------
   PROCESAMIENTO DEL CSV
   -------------------------- */
if (!isset($_FILES['archivoCSV']) || $_FILES['archivoCSV']['error'] != 0) {
    echo "<script>alert('⚠️ No se cargó ningún archivo válido'); window.location='panel_admin.php';</script>";
    exit();
}

$tmpName = $_FILES['archivoCSV']['tmp_name'];
if (($handle = fopen($tmpName, "r")) !== FALSE) {
    $primeraFila = true;

    while (($datos = fgetcsv($handle, 1000, ";")) !== FALSE) {

        if ($primeraFila) { // Omitir encabezados
            $primeraFila = false;
            continue;
        }

        /* --------------------------
           LECTURA DE DATOS DEL CSV
           -------------------------- */
        $institucion   = trim($datos[4] ?? '');
        $sede          = trim($datos[8] ?? '');
        $jornada       = trim($datos[12] ?? '');
        $grado         = trim($datos[14] ?? '');
        $documento     = trim($datos[23] ?? '');
        $apellido1     = trim($datos[25] ?? '');
        $apellido2     = trim($datos[26] ?? '');
        $nombre1       = trim($datos[27] ?? '');
        $nombre2       = trim($datos[28] ?? '');
        $fecha_nac     = trim($datos[30] ?? '');
        $tipo_sangre   = trim($datos[33] ?? '');

        $nombre_completo = ucfirst(trim("$nombre1 $nombre2 $apellido1 $apellido2"));
        $tipo_sangre = strtoupper(str_replace(' ', '', $tipo_sangre));

        if ($documento == "") continue; // Evitar filas vacías

        /* --------------------------
           VERIFICAR SI YA EXISTE
           -------------------------- */
        $verificar = mysqli_prepare($enlace, "SELECT id_dato FROM dato_estudiante WHERE doc_dato=?");
        mysqli_stmt_bind_param($verificar, "s", $documento);
        mysqli_stmt_execute($verificar);
        $resultado = mysqli_stmt_get_result($verificar);
        if (mysqli_num_rows($resultado) > 0) continue;

        /* --------------------------
           GENERAR NUEVO ID BASE
           -------------------------- */
        $res_id = mysqli_query($enlace, "SELECT IFNULL(MAX(id_dato), 0) + 1 AS nuevo_id FROM dato_estudiante");
        $fila_id = mysqli_fetch_assoc($res_id);
        $nuevo_id = $fila_id['nuevo_id'];

        /* --------------------------
           INSERTAR EN TABLAS RELACIONADAS
           -------------------------- */
        $tablas_relacionadas = [
            "adicional_estudiante" => "id_adicional",
            "atributo_estudiante" => "id_atributo",
            "caracteristicas_estudiante" => "id_caracteristicas",
            "entorno_estudiantes" => "id_entorno",
            "fichai_estudiante" => "id_fichai",
            "folder_estudiante" => "id_folder",
            "salud_estudiante" => "id_salud",
            "observador_estudiante" => "id_observador"
        ];

        foreach ($tablas_relacionadas as $tabla => $campo) {
            $query = "INSERT INTO $tabla ($campo) VALUES ($nuevo_id)";
            if (!mysqli_query($enlace, $query)) {
                echo "<script>console.log('Error en $tabla: " . mysqli_error($enlace) . "');</script>";
            }
        }

        /* --------------------------
           INSERTAR REGISTRO PRINCIPAL
           -------------------------- */
        $insert = mysqli_prepare($enlace, "INSERT INTO dato_estudiante 
            (id_dato, nom_dato, nac_dato, doc_dato, rh_dato, col_dato, sede_dato, jornada_dato,
             id_adicional, id_atributo, id_caracteristicas, id_entorno, id_fichai, id_observador, id_salud, id_folder)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param(
            $insert, "isssssssiiiiiiii",
            $nuevo_id, $nombre_completo, $fecha_nac, $documento, $tipo_sangre,
            $institucion, $sede, $jornada,
            $nuevo_id, $nuevo_id, $nuevo_id, $nuevo_id,
            $nuevo_id, $nuevo_id, $nuevo_id, $nuevo_id
        );
        mysqli_stmt_execute($insert);

        /* --------------------------
           ACTUALIZAR EL GRADO
           -------------------------- */
        $update = mysqli_prepare($enlace, "UPDATE observador_estudiante SET gradop_observador = ? WHERE id_observador = ?");
        mysqli_stmt_bind_param($update, "si", $grado, $nuevo_id);
        mysqli_stmt_execute($update);

        /* --------------------------
           DATOS ADICIONALES BÁSICOS
           -------------------------- */
        mysqli_query($enlace, "UPDATE caracteristicas_estudiante SET barrio_caracteristicas = 'SIN DEFINIR' WHERE id_caracteristicas = $nuevo_id");
        mysqli_query($enlace, "UPDATE salud_estudiante SET eps_salud = 'SIN REGISTRO' WHERE id_salud = $nuevo_id");
        mysqli_query($enlace, "UPDATE atributo_estudiante SET genero_atributo = 'NO ESPECIFICADO' WHERE id_atributo = $nuevo_id");
    }

    fclose($handle);
    echo "<script>alert('✅ Listas cargadas correctamente'); window.location='estudiantes.php';</script>";
} else {
    echo "<script>alert('⚠️ No se pudo abrir el archivo CSV');</script>";
}

?>