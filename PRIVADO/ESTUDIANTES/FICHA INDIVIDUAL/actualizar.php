
<?php
session_start();
/* --------------------------
   Configuración / Seguridad
   -------------------------- */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../PRIVADO/INICIO SESION/inicio.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "poe");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_dato = $_SESSION['id_dato'];

// -------- TABLA dato_estudiante --------
$nom_dato     = $_POST['nom_dato'];
$lugar_nac_dato = $_POST['lugar_nac_dato'];
$doc_dato     = $_POST['doc_dato'];
$edad_dato    = $_POST['edad_dato'];
$sede_dato    = $_POST['sede_dato'];
$jornada_dato = $_POST['jornada_dato'];

$sql1 = "UPDATE dato_estudiante 
         SET nom_dato = ?, lugar_nac_dato = ? ,doc_dato = ?, edad_dato = ?, sede_dato = ?, jornada_dato = ?
         WHERE id_dato = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("sssissi", $nom_dato, $lugar_nac_dato ,$doc_dato, $edad_dato, $sede_dato, $jornada_dato, $id_dato);
$stmt1->execute();
$stmt1->close();

// -------- TABLA observador_estudiante --------
$gradop_observador = $_POST['gradop_observador'];

$sql2 = "UPDATE observador_estudiante 
         SET gradop_observador = ?
         WHERE id_observador = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("si", $gradop_observador, $id_dato);
$stmt2->execute();
$stmt2->close();



// --- Conexión BD ---
$conn = new mysqli("localhost", "root", "", "poe");
if ($conn->connect_error) { die("Error de conexión: " . $conn->connect_error); }

// Id del estudiante (POST tiene prioridad; si no, sesión)
$id_dato = $_POST['id_dato'] ?? ($_SESSION['id_dato'] ?? null);

$conn->begin_transaction();

try {
    // ------ TABLA fichai_estudiante ------
    $espdoc_fichai      = $_POST['espdoc_fichai']     ?? null;
    $docente_fichai     = $_POST['docente_fichai']    ?? null;
    $remitente_fichai   = $_POST['remitente_fichai']  ?? null;
    $asesor_fichai      = $_POST['asesor_fichai']     ?? null;
    $f_remic_fichai     = $_POST['f_remic_fichai']    ?? null;
    $f_aten_fichai      = $_POST['f_aten_fichai']     ?? null;
    $situs_fichai       = $_POST['situs_fichai']      ?? null;
    $seguit_fichai      = $_POST['seguit_fichai']     ?? null;
    $mconsulta_fichai   = $_POST['mconsulta_fichai']  ?? null;
    $antecedentes_fichai= $_POST['antecedentes_fichai'] ?? null;
    $acc_realizadas_fichai = $_POST['acc_realizadas_fichai'] ?? null;
    $compromiso_fichai  = $_POST['compromiso_fichai'] ?? null;
    $continu_fichai     = $_POST['continu_fichai']    ?? null;
    $oriente_fichai     = $_POST['oriente_fichai']    ?? null;

    $sql_fh = "UPDATE fichai_estudiante SET 
        espdoc_fichai=?, docente_fichai=?, remitente_fichai=?, asesor_fichai=?, 
        f_remic_fichai=?, f_aten_fichai=?, situs_fichai=?, seguit_fichai=?,
        mconsulta_fichai=?, antecedentes_fichai=?, acc_realizadas_fichai=?, 
        compromiso_fichai=?, continu_fichai=?, oriente_fichai=?
        WHERE id_fichai = ?";
    $st_fh = $conn->prepare($sql_fh);
    $st_fh->bind_param(
        "ssssssssssssssi",
        $espdoc_fichai, $docente_fichai, $remitente_fichai, $asesor_fichai,
        $f_remic_fichai, $f_aten_fichai, $situs_fichai, $seguit_fichai,
        $mconsulta_fichai, $antecedentes_fichai, $acc_realizadas_fichai,
        $compromiso_fichai, $continu_fichai, $oriente_fichai, $id_dato
    );
    $st_fh->execute();
    $st_fh->close();

    // ------ TABLA caracteristicas_estudiante ------
    $dir_caracteristicas   = $_POST['dir_caracteristicas']   ?? null;
    $barri_caracteristicas = $_POST['barri_caracteristicas'] ?? null;
    $eps_caracteristicas   = $_POST['eps_caracteristicas']   ?? null;

    $pd_nom_caracteristicas = $_POST['pd_nom_caracteristicas'] ?? null;
    $num_m1_caracteristicas = $_POST['num_m1_caracteristicas'] ?? null; // tratar como texto
    $pd_doc_caracteristicas = $_POST['pd_doc_caracteristicas'] ?? null; // tratar como texto
    $pd_ocu_caracteristicas = $_POST['pd_ocu_caracteristicas'] ?? null;

    $md_nom_caracteristicas = $_POST['md_nom_caracteristicas'] ?? null;
    $num_m2_caracteristicas = $_POST['num_m2_caracteristicas'] ?? null; // texto
    $md_doc_caracteristicas = $_POST['md_doc_caracteristicas'] ?? null; // texto
    $md_ocu_caracteristicas = $_POST['md_ocu_caracteristicas'] ?? null;

    $acu_caracteristicas    = $_POST['acu_caracteristicas']    ?? null;
    $acu_paren_caracteristicas = $_POST['acu_paren_caracteristicas'] ?? null;
    $acu_doc_caracteristicas   = $_POST['acu_doc_caracteristicas']   ?? null; // texto
    $acu_cel_caracteristicas   = $_POST['acu_cel_caracteristicas']   ?? null; // texto
    $acu_ocup_caracteristicas  = $_POST['acu_ocup_caracteristicas']  ?? null;

    $sql_car = "UPDATE caracteristicas_estudiante SET
        dir_caracteristicas=?, barri_caracteristicas=?, eps_caracteristicas=?,
        pd_nom_caracteristicas=?, num_m1_caracteristicas=?, pd_doc_caracteristicas=?, pd_ocu_caracteristicas=?,
        md_nom_caracteristicas=?, num_m2_caracteristicas=?, md_doc_caracteristicas=?, md_ocu_caracteristicas=?,
        acu_caracteristicas=?, acu_paren_caracteristicas=?, acu_doc_caracteristicas=?, acu_cel_caracteristicas=?, acu_ocup_caracteristicas=?
        WHERE id_caracteristicas = ?";
    $st_car = $conn->prepare($sql_car);
    $st_car->bind_param(
        "ssssssssssssssssi",
        $dir_caracteristicas, $barri_caracteristicas, $eps_caracteristicas,
        $pd_nom_caracteristicas, $num_m1_caracteristicas, $pd_doc_caracteristicas, $pd_ocu_caracteristicas,
        $md_nom_caracteristicas, $num_m2_caracteristicas, $md_doc_caracteristicas, $md_ocu_caracteristicas,
        $acu_caracteristicas, $acu_paren_caracteristicas, $acu_doc_caracteristicas, $acu_cel_caracteristicas, $acu_ocup_caracteristicas,
        $id_dato
    );
    $st_car->execute();
    $st_car->close();

    // ------ TABLA salud_estudiante ------
    $dis_salud = $_POST['dis_salud'] ?? null;
    $st_sal = $conn->prepare("UPDATE salud_estudiante SET dis_salud=? WHERE id_salud=?");
    $st_sal->bind_param("si", $dis_salud, $id_dato);
    $st_sal->execute();
    $st_sal->close();

    // ------ TABLA entorno_estudiantes ------
    $vive_entorno = $_POST['vive_entorno'] ?? null;
    $st_ent = $conn->prepare("UPDATE entorno_estudiantes SET vive_entorno=? WHERE id_entorno=?");
    $st_ent->bind_param("si", $vive_entorno, $id_dato);
    $st_ent->execute();
    $st_ent->close();

    $conn->commit();

    // Redirige SIN echo previo
    header("Location: ficha.php");
    exit;

} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo "Error actualizando ficha: " . $e->getMessage();
}