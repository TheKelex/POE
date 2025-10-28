<?php
session_start();

/* --------------------------
   VALIDACIÓN DE SESIÓN
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}

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
   ENCABEZADOS Y CONFIG UTF-8
   -------------------------- */
header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding("UTF-8");

/* --------------------------
   CONEXIÓN A BASE DE DATOS (utf8mb4)
   -------------------------- */
$enlace = mysqli_connect("localhost", "root", "", "poe");
if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($enlace, "utf8mb4"); // importante: utf8mb4

/* --------------------------
   VERIFICAR ARCHIVO CSV
   -------------------------- */
if (!isset($_FILES['archivoCSV']) || $_FILES['archivoCSV']['error'] !== UPLOAD_ERR_OK) {
    echo "<script>alert('No se cargó ningún archivo válido'); window.location='estudiantes.php';</script>";
    exit();
}

$tmpName = $_FILES['archivoCSV']['tmp_name'];

/* --------------------------
   LEER TODO EL ARCHIVO Y NORMALIZAR ENCODING
   -------------------------- */
$raw = file_get_contents($tmpName);
if ($raw === false) {
    echo "<script>alert('Error leyendo el archivo'); window.location='estudiantes.php';</script>";
    exit();
}

// quitar BOM si existe
$raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);

// detectar codificación y convertir a UTF-8 si es necesario
$enc = mb_detect_encoding($raw, ["UTF-8", "ISO-8859-1", "Windows-1252", "UTF-16"], true);
if ($enc === false) $enc = 'Windows-1252';
if (strtoupper($enc) !== 'UTF-8') {
    $raw = mb_convert_encoding($raw, 'UTF-8', $enc);
}

// dividir en líneas de forma segura
$lines = preg_split("/\r\n|\n|\r/", $raw);
if (!$lines || count($lines) <= 1) {
    echo "<script>alert('Archivo CSV vacío o con formato no válido'); window.location='estudiantes.php';</script>";
    exit();
}

/* --------------------------
   PREPARAR INSERT Y TRANSACCIÓN
   -------------------------- */
// obtener next_id (MAX + 1)
$res_max = mysqli_query($enlace, "SELECT IFNULL(MAX(id_egresados), 0) AS maxid FROM egresados");
$fila_max = mysqli_fetch_assoc($res_max);
$next_id = intval($fila_max['maxid']) + 1;

// preparar insert (incluye id_egresados manual)
$sql_insert = "INSERT INTO egresados (
    id_egresados, año_egresado, nom_egresados, fechanac_egresados, edad_egresados,
    tip_doc_egresados, num_doc_egresados, gruposanguineo_egresados,
    especialidad_egresados, email_egresados, tel_egresados, ocup_egresados,
    estudios_egresados, institucion_egresados, foto_egresados,
    biografia_egresados, aporte_egresados
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt_insert = mysqli_prepare($enlace, $sql_insert);
if (!$stmt_insert) {
    die("Error al preparar sentencia: " . mysqli_error($enlace));
}
$types = "i" . str_repeat("s", 16); // 1 entero + 16 strings

// Iniciar transacción (mejora integridad y performance)
mysqli_begin_transaction($enlace);

/* --------------------------
   RECORRER LÍNEAS (SALTAR ENCABEZADO)
   -------------------------- */
$insertados = 0;
$duplicados = 0;
$line_num = 0;

try {
    foreach ($lines as $line) {
        $line_num++;
        // saltar línea vacía
        if (trim($line) === '') continue;
        // salto de header (primer línea)
        if ($line_num === 1) continue;

        // obtener columnas con manejo correcto de comillas
        $cols = str_getcsv($line, ",");

        // normalizar cada columna: trim y asegurar UTF-8
        $cols = array_map(function($c) {
            $c = trim((string)$c);
            // quitar BOM en la primera celda si quedó
            $c = preg_replace('/^\xEF\xBB\xBF/', '', $c);
            if (!mb_check_encoding($c, 'UTF-8')) {
                $c = mb_convert_encoding($c, 'UTF-8', 'Windows-1252');
            }
            return $c;
        }, $cols);

        // mapeo según tu CSV
        $nombre_completo   = $cols[1] ?? '';
        $tipo_doc          = $cols[2] ?? '';
        $num_doc           = $cols[3] ?? '';
        $fecha_nac         = $cols[4] ?? '';
        $grupo_sanguineo   = $cols[5] ?? '';
        $especialidad      = $cols[6] ?? '';
        $email             = $cols[7] ?? '';
        $telefono          = $cols[8] ?? '';
        $ocupacion         = $cols[9] ?? '';
        $estudios          = $cols[10] ?? '';
        $institucion       = $cols[11] ?? '';
        $biografia         = $cols[12] ?? '';
        $aporte            = trim(($cols[13] ?? '') . ' ' . ($cols[14] ?? ''));
        $foto              = $cols[15] ?? '';
        $año               = $cols[16] ?? '';

        if ($nombre_completo === '' || $num_doc === '') continue;

        // normalizar fecha YYYY-MM-DD
        if (!empty($fecha_nac)) {
            $partes = preg_split('/[\/\-]/', $fecha_nac);
            if (count($partes) === 3 && strlen($partes[0]) <= 2) {
                $fecha_nac = $partes[2] . "-" . str_pad($partes[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($partes[0], 2, '0', STR_PAD_LEFT);
            }
        }

        // evitar duplicados
        $verificar = mysqli_prepare($enlace, "SELECT id_egresados FROM egresados WHERE num_doc_egresados = ?");
        mysqli_stmt_bind_param($verificar, "s", $num_doc);
        mysqli_stmt_execute($verificar);
        $resv = mysqli_stmt_get_result($verificar);
        if (mysqli_num_rows($resv) > 0) {
            $duplicados++;
            mysqli_stmt_close($verificar);
            continue;
        }
        mysqli_stmt_close($verificar);

        // calcular edad
        $edad = '';
        if (!empty($fecha_nac)) {
            try {
                $nacimiento = new DateTime($fecha_nac);
                $hoy = new DateTime();
                $edad = $hoy->diff($nacimiento)->y;
            } catch (Exception $e) {
                $edad = '';
            }
        }

        // asignar id manual
        $id_actual = $next_id;
        $next_id++;

        // bind dinámico (call_user_func_array requiere referencias)
        $bind_vars = [
            $id_actual, $año, $nombre_completo, $fecha_nac, (string)$edad,
            $tipo_doc, $num_doc, $grupo_sanguineo,
            $especialidad, $email, $telefono, $ocupacion,
            $estudios, $institucion, $foto, $biografia, $aporte
        ];

        $refs = [];
        $refs[] = &$types;
        foreach ($bind_vars as $k => &$v) { $refs[] = &$v; }

        call_user_func_array([$stmt_insert, 'bind_param'], $refs);

        if (mysqli_stmt_execute($stmt_insert)) {
            $insertados++;
        } else {
            // revertir id en caso de fallo para no dejar huecos
            $next_id--;
            error_log("Error insertando fila $line_num (doc={$num_doc}): " . mysqli_stmt_error($stmt_insert));
        }
    }

    // si todo ok, commit
    mysqli_commit($enlace);

} catch (Exception $ex) {
    // rollback en cualquier excepción
    mysqli_rollback($enlace);
    error_log("Excepción durante import CSV: " . $ex->getMessage());
    echo "<script>alert('Ocurrió un error durante la importación. Revisa los logs.'); window.location='estudiantes.php';</script>";
    exit();
}

/* --------------------------
   CERRAR Y REDIRECCIONAR
   -------------------------- */
mysqli_stmt_close($stmt_insert);
echo "<script>
    alert('Carga completada:\\n✔ $insertados egresados insertados\\n⚠ $duplicados duplicados ignorados');
    window.location='estudiantes.php';
</script>";
exit();
