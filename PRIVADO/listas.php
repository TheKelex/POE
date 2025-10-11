<?php
// listas.php

session_start();
include('conexion.php'); // tu conexión a MySQL

if (isset($_POST['actualizar'])) {

    $password = $_POST['password'];

    // 🔒 Validar contraseña
    $sql = "SELECT * FROM usuarios WHERE rol='admin' AND password=MD5(?) LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
        exit;
    }

    // ✅ Validación del archivo
    if (isset($_FILES['archivoCSV']) && $_FILES['archivoCSV']['error'] == 0) {
        $tmpName = $_FILES['archivoCSV']['tmp_name'];

        if (($handle = fopen($tmpName, "r")) !== FALSE) {
            $primeraFila = true;

            while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {

                if ($primeraFila) { // Omitir encabezados
                    $primeraFila = false;
                    continue;
                }

                // Suponiendo que el CSV tiene columnas:
                // Apellido, Nombre, Documento, TipoDoc, TipoSangre, Grado, Especialidad, Institucion, Sede

                $apellido = trim($datos[0]);
                $nombre = trim($datos[1]);
                $documento = trim($datos[2]);
                $tipo_doc = trim($datos[3]);
                $tipo_sangre = trim($datos[4]);
                $grado = trim($datos[5]);
                $especialidad = trim($datos[6]);
                $institucion = trim($datos[7]);
                $sede = trim($datos[8]);

                $nombre_completo = ucfirst($nombre) . " " . ucfirst($apellido);

                // 🔁 Verificar si ya existe
                $verificar = $conn->prepare("SELECT id_estudiante FROM estudiantes WHERE documento=?");
                $verificar->bind_param("s", $documento);
                $verificar->execute();
                $res = $verificar->get_result();

                if ($res->num_rows > 0) {
                    // 🔄 Actualizar
                    $update = $conn->prepare("UPDATE estudiantes SET nombre=?, tipo_doc=?, tipo_sangre=?, grado=?, especialidad=?, institucion=?, sede=? WHERE documento=?");
                    $update->bind_param("ssssssss", $nombre_completo, $tipo_doc, $tipo_sangre, $grado, $especialidad, $institucion, $sede, $documento);
                    $update->execute();
                } else {
                    // ➕ Insertar
                    $insert = $conn->prepare("INSERT INTO estudiantes (nombre, documento, tipo_doc, tipo_sangre, grado, especialidad, institucion, sede) VALUES (?,?,?,?,?,?,?,?)");
                    $insert->bind_param("ssssssss", $nombre_completo, $documento, $tipo_doc, $tipo_sangre, $grado, $especialidad, $institucion, $sede);
                    $insert->execute();
                }
            }

            fclose($handle);
            echo "<script>alert('Lista cargada correctamente.'); window.location='estudiantes.php';</script>";
        } else {
            echo "<script>alert('No se pudo abrir el archivo.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error al subir el archivo.'); window.history.back();</script>";
    }
}
?>