<?php
// actualizar.php
session_start();

// activar reportes de error para mysqli (opcional, útil en desarrollo)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// --- Conexión BD ---
$conexion = new mysqli("localhost", "root", "", "poe");
$conexion->set_charset("utf8mb4");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Id del estudiante (POST tiene prioridad; si no, sesión)
$id_dato = $_POST['id_dato'] ?? $_SESSION['id_dato'] ?? null;
if (!$id_dato) {
    http_response_code(400);
    echo "No se recibió id_dato.";
    exit;
}

try {
    $conexion->begin_transaction();

    // -------- TABLA dato_estudiante --------
    $nom_dato        = $_POST['nom_dato']        ?? null;
    $doc_dato        = $_POST['doc_dato']        ?? null;
    $sede_dato       = $_POST['sede_dato']       ?? null;
    $jornada_dato    = $_POST['jornada_dato']    ?? null;
    $nac_dato        = $_POST['nac_dato']        ?? null;
    $lugar_nac_dato  = $_POST['lugar_nac_dato']  ?? null;
    $rh_dato         = $_POST['rh_dato']         ?? null;

    $sql = "UPDATE dato_estudiante
            SET nom_dato=?, doc_dato=?, sede_dato=?, jornada_dato=?, nac_dato=?, lugar_nac_dato=?, rh_dato=?
            WHERE id_dato=?";
    $st = $conexion->prepare($sql);
    $st->bind_param("sssssssi",
        $nom_dato, $doc_dato, $sede_dato, $jornada_dato, $nac_dato, $lugar_nac_dato, $rh_dato, $id_dato
    );
    $st->execute();
    $st->close();

    // -------- TABLA caracteristicas_estudiante --------
    $dir_caracteristicas   = $_POST['dir_caracteristicas']   ?? null;
    $barri_caracteristicas = $_POST['barri_caracteristicas'] ?? null;
    $cel_caracteristicas   = $_POST['cel_caracteristicas']   ?? null;
    $com_caracteristicas   = $_POST['com_caracteristicas']   ?? null;
    $est_caracteristicas   = $_POST['est_caracteristicas']   ?? null;
    $eps_caracteristicas   = $_POST['eps_caracteristicas']   ?? null;
    $pd_nom_caracteristicas= $_POST['pd_nom_caracteristicas']?? null;
    $num_m1_caracteristicas= $_POST['num_m1_caracteristicas']?? null;
    $pd_doc_caracteristicas= $_POST['pd_doc_caracteristicas']?? null;
    $pd_esco_caracteristicas= $_POST['pd_esco_caracteristicas']?? null;
    $pd_ocu_caracteristicas= $_POST['pd_ocu_caracteristicas']?? null;
    $md_nom_caracteristicas= $_POST['md_nom_caracteristicas']?? null;
    $num_m2_caracteristicas= $_POST['num_m2_caracteristicas']?? null;
    $md_doc_caracteristicas= $_POST['md_doc_caracteristicas']?? null;
    $md_esco_caracteristicas= $_POST['md_esco_caracteristicas']?? null;
    $md_ocu_caracteristicas= $_POST['md_ocu_caracteristicas']?? null;
    $acu_caracteristicas   = $_POST['acu_caracteristicas']   ?? null;
    $acu_paren_caracteristicas = $_POST['acu_paren_caracteristicas'] ?? null;
    $acu_doc_caracteristicas   = $_POST['acu_doc_caracteristicas']   ?? null;
    $acu_cel_caracteristicas   = $_POST['acu_cel_caracteristicas']   ?? null;
    $acu_esco_caracteristicas  = $_POST['acu_esco_caracteristicas']  ?? null;
    $acu_ocup_caracteristicas  = $_POST['acu_ocup_caracteristicas']  ?? null;

    $sql = "UPDATE caracteristicas_estudiante SET
            dir_caracteristicas=?, barri_caracteristicas=?, cel_caracteristicas=?, com_caracteristicas=?,
            est_caracteristicas=?, eps_caracteristicas=?,
            pd_nom_caracteristicas=?, num_m1_caracteristicas=?, pd_doc_caracteristicas=?,
            pd_esco_caracteristicas=?, pd_ocu_caracteristicas=?,
            md_nom_caracteristicas=?, num_m2_caracteristicas=?, md_doc_caracteristicas=?,
            md_esco_caracteristicas=?, md_ocu_caracteristicas=?,
            acu_caracteristicas=?, acu_paren_caracteristicas=?, acu_doc_caracteristicas=?,
            acu_cel_caracteristicas=?, acu_esco_caracteristicas=?, acu_ocup_caracteristicas=?
            WHERE id_caracteristicas=?";
    $st = $conexion->prepare($sql);
    $st->bind_param(
        "ssssssssssssssssssssssi",
        $dir_caracteristicas, $barri_caracteristicas, $cel_caracteristicas, $com_caracteristicas,
        $est_caracteristicas, $eps_caracteristicas,
        $pd_nom_caracteristicas, $num_m1_caracteristicas, $pd_doc_caracteristicas,
        $pd_esco_caracteristicas, $pd_ocu_caracteristicas,
        $md_nom_caracteristicas, $num_m2_caracteristicas, $md_doc_caracteristicas,
        $md_esco_caracteristicas, $md_ocu_caracteristicas,
        $acu_caracteristicas, $acu_paren_caracteristicas, $acu_doc_caracteristicas,
        $acu_cel_caracteristicas, $acu_esco_caracteristicas, $acu_ocup_caracteristicas,
        $id_dato
    );
    $st->execute();
    $st->close();

    // -------- TABLA atributo_estudiante --------
    $des_atributo   = $_POST['des_atributo']   ?? null;
    $ind_atributo   = $_POST['ind_atributo']   ?? null;
    $cuales_atributo= $_POST['cuales_atributo']?? null;

    $sql = "UPDATE atributo_estudiante SET des_atributo=?, ind_atributo=?, cuales_atributo=? WHERE id_atributo=?";
    $st = $conexion->prepare($sql);
    $st->bind_param("sssi", $des_atributo, $ind_atributo, $cuales_atributo, $id_dato);
    $st->execute();
    $st->close();

    // -------- TABLA salud_estudiante --------
    $diag_salud = $_POST['diag_salud'] ?? null;
    $tie_atributo = $_POST['tie_atributo'] ?? null;
    $trat_salud = $_POST['trat_salud'] ?? null;
    $dis_salud = $_POST['dis_salud'] ?? null;
    $def_salud = $_POST['def_salud'] ?? null;

    $sql = "UPDATE salud_estudiante SET diag_salud=?, tie_atributo=?, trat_salud=?, dis_salud=?, def_salud=? WHERE id_salud=?";
    $st = $conexion->prepare($sql);
    $st->bind_param("sssssi", $diag_salud, $tie_atributo, $trat_salud, $dis_salud, $def_salud, $id_dato);
    $st->execute();
    $st->close();

    // -------- TABLA entorno_estudiantes --------
    $hermano_entorno = $_POST['hermano_entorno'] ?? null;
    $tieli_entorno   = $_POST['tieli_entorno'] ?? null;
    $vive_entorno    = $_POST['vive_entorno'] ?? null;
    $esp_entorno     = $_POST['esp_entorno'] ?? null;
    $n_hermanos_entorno = $_POST['n_hermanos_entorno'] ?? null;

    $sql = "UPDATE entorno_estudiantes
            SET hermano_entorno=?, tieli_entorno=?, vive_entorno=?, esp_entorno=?, n_hermanos_entorno=?
            WHERE id_entorno=?";
    $st = $conexion->prepare($sql);
    $st->bind_param("sssssi", $hermano_entorno, $tieli_entorno, $vive_entorno, $esp_entorno, $n_hermanos_entorno, $id_dato);
    $st->execute();
    $st->close();

    // -------- TABLA observador_estudiante --------
    $gradop_observador = $_POST['gradop_observador'] ?? null;
    $inf_prp_observador = $_POST['inf_prp_observador'] ?? null;
    $inf_sgp_observador = $_POST['inf_sgp_observador'] ?? null;
    $inf_terp_observador = $_POST['inf_terp_observador'] ?? null;
    $inf_cuarp_observador = $_POST['inf_cuarp_observador'] ?? null;

    // si la columna en la BD tiene ñ en el nombre (`año_observador`) usamos backticks
    $sql = "UPDATE observador_estudiante
            SET gradop_observador=?, inf_prp_observador=?, inf_sgp_observador=?, inf_terp_observador=?, inf_cuarp_observador=?
            WHERE id_observador=?";
    $st = $conexion->prepare($sql);
    $st->bind_param(
    "sssssi",
    $gradop_observador,
    $inf_prp_observador,
    $inf_sgp_observador,
    $inf_terp_observador,
    $inf_cuarp_observador,
    $id_dato
);
    $st->execute();
    $st->close();

    // Confirmar cambios
    $conexion->commit();

    // Redirigir (ajusta la ruta si la tuya es otra)
    header("Location: observador.php?id_estudiante=" . $id_dato);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo "Error actualizando ficha: " . $e->getMessage();
    exit;
}