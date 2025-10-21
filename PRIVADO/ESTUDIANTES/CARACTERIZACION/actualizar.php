<?php
session_start();
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}

// actualizar_caracterizacion.php
// Recibe campos por POST (los names deben coincidir con los nombres de columna usados abajo)
// Obtiene id_dato de POST (preferible) o de la sesión si no viene por POST.
$id_dato = null;
if (!empty($_POST['id_dato'])) {
    $id_dato = intval($_POST['id_dato']);
} elseif (!empty($_SESSION['id_dato'])) {
    $id_dato = intval($_SESSION['id_dato']);
} else {
    die('Falta id_dato (envíalo por POST o guarda en $_SESSION["id_dato"]).');
}

// Conexión básica — ajusta credenciales si es necesario
$mysqli = new mysqli('localhost','root','','poe');
if ($mysqli->connect_errno) {
    die('Error de conexión: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
$mysqli->autocommit(FALSE);

function do_update($mysqli, $table, $id_column, $id_value, $cols_map) {
    // $cols_map = ['col_db' => 'post_name', ...]
    $sets = [];
    $types = '';
    $values = [];
    foreach ($cols_map as $col => $post_name) {
        if (isset($_POST[$post_name])) {
            $sets[] = "$col = ?";
            $types .= 's';
            $values[] = $_POST[$post_name];
        }
    }
    if (count($sets) === 0) return true; // nada que actualizar
    $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE $id_column = ?";
    $types .= 'i';
    $values[] = $id_value;

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $mysqli->error);
    }
    // bind dinámico
    $bind_names = [];
    $bind_names[] = $types;
    for ($i = 0; $i < count($values); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $values[$i];
        $bind_names[] = &$$bind_name;
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    $stmt->close();
    return true;
}

try {
    // --- dato_estudiante (de)
    $cols = [
        'nom_dato' => 'nom_dato',
        'nac_dato' => 'nac_dato',
        'edad_dato' => 'edad_dato',
        'tipo_doc_dato' => 'tipo_doc_dato',
        'doc_dato' => 'doc_dato',
        'rh_dato' => 'rh_dato',
        'estado_dato' => 'estado_dato',
        'col_dato' => 'col_dato',
        'sede_dato' => 'sede_dato',
        'jornada_dato' => 'jornada_dato',
        'lugar_nac_dato' => 'lugar_nac_dato'
    ];
    do_update($mysqli, 'dato_estudiante', 'id_dato', $id_dato, $cols);

    // --- caracteristicas_estudiante (car)
    $cols = [
        'dir_caracteristicas' => 'dir_caracteristicas',
        'barri_caracteristicas' => 'barri_caracteristicas',
        'com_caracteristicas' => 'com_caracteristicas',
        'est_caracteristicas' => 'est_caracteristicas',
        'eps_caracteristicas' => 'eps_caracteristicas',
        'cel_caracteristicas' => 'cel_caracteristicas',
        'p_tel_m1_caracteristicas' => 'p_tel_m1_caracteristicas',
        'num_m1_caracteristicas' => 'num_m1_caracteristicas',
        'p_tel_m2_caracteristicas' => 'p_tel_m2_caracteristicas',
        'num_m2_caracteristicas' => 'num_m2_caracteristicas',
        'gmail_p_caracteristicas' => 'gmail_p_caracteristicas',
        'acu_caracteristicas' => 'acu_caracteristicas',
        'acu_paren_caracteristicas' => 'acu_paren_caracteristicas',
        'pd_nom_caracteristicas' => 'pd_nom_caracteristicas',
        'pd_esco_caracteristicas' => 'pd_esco_caracteristicas',
        'pd_edad_caracteristicas' => 'pd_edad_caracteristicas',
        'pd_ocu_caracteristicas' => 'pd_ocu_caracteristicas',
        'pd_trab_caracteristicas' => 'pd_trab_caracteristicas',
        'md_nom_caracteristicas' => 'md_nom_caracteristicas',
        'md_esco_caracteristicas' => 'md_esco_caracteristicas',
        'md_edad_caracteristicas' => 'md_edad_caracteristicas',
        'md_ocu_caracteristicas' => 'md_ocu_caracteristicas',
        'md_trab_caracteristicas' => 'md_trab_caracteristicas',
        'economia_caracteristicas' => 'economia_caracteristicas'
    ];
    do_update($mysqli, 'caracteristicas_estudiante', 'id_caracteristicas', $id_dato, $cols);

    // --- salud_estudiante (sal)
    $cols = [
        'diag_salud' => 'diag_salud',
        'dis_salud' => 'dis_salud',
        'trat_salud' => 'trat_salud',
        'trasa_salud' => 'trasa_salud',
        'med_salud' => 'med_salud',
        'expreso_salud' => 'expreso_salud',
        'def_salud' => 'def_salud'
    ];
    // Nota: en tu SELECT aparece med_salud; el name en el form debe ser 'med_salud'.
    // Si tu formulario usa otro name, cámbialo arriba.
    // Corrige posible typo en 'med_sal_salud' si fuera el caso.
    // Aseguramos que el índice correcto sea 'med_salud' en runtime:
    if (!isset($_POST['med_salud']) && isset($_POST['med_sal_salud'])) {
        $_POST['med_salud'] = $_POST['med_sal_salud'];
    }
    do_update($mysqli, 'salud_estudiante', 'id_salud', $id_dato, $cols);

    // --- atributo_estudiante (at)
    $cols = [
        'comunidad_atributo' => 'comunidad_atributo',
        'educom_atributo' => 'educom_atributo',
        'dia_educom_atributo' => 'dia_educom_atributo',
        'horario_educom_atributo' => 'horario_educom_atributo',
        'deporte_atributo' => 'deporte_atributo',
        'dia_deporte_atributo' => 'dia_deporte_atributo',
        'horario_deporte_atributo' => 'horario_deporte_atributo',
        'jtrab_atributo' => 'jtrab_atributo',
        'dia_jtrab_atributo' => 'dia_jtrab_atributo',
        'horario_jtrab_atributo' => 'horario_jtrab_atributo',
        'des_atributo' => 'des_atributo',
        'ind_atributo' => 'ind_atributo',
        'cuales_atributo' => 'cuales_atributo'
    ];
    do_update($mysqli, 'atributo_estudiante', 'id_atributo', $id_dato, $cols);

    // --- entorno_estudiantes (ent)
    $cols = [
        'casa_entorno' => 'casa_entorno',
        'hijou_entorno' => 'hijou_entorno',
        'hermano_entorno' => 'hermano_entorno',
        'totalv_entorno' => 'totalv_entorno',
        'tieli_entorno' => 'tieli_entorno',
        'vive_entorno' => 'vive_entorno',
        'esp_entorno' => 'esp_entorno',
        'n_hermanos_entorno' => 'n_hermanos_entorno'
    ];
    do_update($mysqli, 'entorno_estudiantes', 'id_entorno', $id_dato, $cols);

    // --- adicional_estudiante (ad)
    $cols = [
        'jacom_adicional' => 'jacom_adicional',
        'ccuento_adicional' => 'ccuento_adicional',
        'transp_adicional' => 'transp_adicional'
    ];
    do_update($mysqli, 'adicional_estudiante', 'id_adicional', $id_dato, $cols);

    // --- observador_estudiante (oe)
    $cols = [
        'gradop_observador' => 'gradop_observador'
    ];
    do_update($mysqli, 'observador_estudiante', 'id_observador', $id_dato, $cols);

    $mysqli->commit();
    header('Location: caracterizacion.php');
    exit;
} catch (Throwable $e) {
    $mysqli->rollback();
    http_response_code(500);
    echo 'Error al actualizar: ' . $e->getMessage();
}
?>