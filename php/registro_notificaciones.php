<?php
include './php/conexion.php'; // Asegúrate de que este archivo configure la variable $dbConnection

date_default_timezone_set('America/Mexico_City'); // Establece la zona horaria para funciones de fecha

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar y sanitizar los datos del formulario
    $placa_autobus = filter_input(INPUT_POST, 'placa_autobus', FILTER_SANITIZE_STRING);
    $tipo_mantenimiento = filter_input(INPUT_POST, 'tipo_mantenimiento', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $fecha_programada = filter_input(INPUT_POST, 'fecha_programada', FILTER_SANITIZE_STRING);
    $estado = 'Pendiente'; // Estado predeterminado para nuevas notificaciones

    // Validar campos requeridos
    if ($placa_autobus && $tipo_mantenimiento && $descripcion && $fecha_programada) {
        // Consulta para obtener el ID del autobús basado en la placa
        $sql_bus = "SELECT ID_Autobus FROM Autobuses WHERE Placas = :placas";
        $stmt_bus = $dbConnection->prepare($sql_bus);
        $stmt_bus->bindParam(':placas', $placa_autobus, PDO::PARAM_STR);
        $stmt_bus->execute();
        $result_bus = $stmt_bus->fetch(PDO::FETCH_ASSOC);

        if ($result_bus) {
            $id_autobus = $result_bus['id_autobus'];

            // Preparar declaración SQL para insertar notificación
            $sql = "INSERT INTO Notificaciones_Mantenimiento (ID_Autobus, Tipo_Mantenimiento, Descripcion, Fecha_Programada, Estado, fecha_notificacion) 
                    VALUES (:id_autobus, :tipo_mantenimiento, :descripcion, :fecha_programada, :estado, NOW() AT TIME ZONE 'America/Mexico_City')";
            $stmt = $dbConnection->prepare($sql);
            $stmt->bindParam(':id_autobus', $id_autobus, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_mantenimiento', $tipo_mantenimiento, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_programada', $fecha_programada, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

            // Ejecutar la declaración
            if ($stmt->execute()) {
                $_SESSION['mensaje'] = "La notificación ha sido registrada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al registrar la notificación: " . implode(", ", $stmt->errorInfo());
            }
        } else {
            $_SESSION['error'] = "No se encontró la placa de ese autobús en la base de datos.";
        }
    } else {
        $_SESSION['error'] = "Por favor rellena todos los campos para poder insertar.";
    }

    // Redirigir de vuelta al formulario
    header("Location: ../registrar_notificacion.php");
    exit();
}
