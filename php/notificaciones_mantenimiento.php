<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

try {
    // Consulta para obtener las notificaciones de mantenimiento junto con la información del autobús
    $query = "
        SELECT 
            nm.ID_Notificacion,
            a.Modelo,
            a.Placas,
            nm.Tipo_Mantenimiento,
            nm.Descripcion,
            nm.Fecha_Programada,
            nm.Estado,
            nm.Fecha_Notificacion
        FROM 
            Notificaciones_Mantenimiento nm
        INNER JOIN 
            Autobuses a ON nm.ID_Autobus = a.ID_Autobus
        ORDER BY 
            nm.Fecha_Programada ASC;
    ";

    // Preparar y ejecutar la consulta
    $stmt = $db->prepare($query);
    $stmt->execute();

    // Obtener todas las notificaciones como un array asociativo
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejar errores de la base de datos
    echo "Error al obtener las notificaciones: " . $e->getMessage();
    exit;
}

function editarNotificacion($id, $tipo, $descripcion, $fecha, $estado)
{
    global $db;
    $query = "UPDATE Notificaciones_Mantenimiento 
              SET Tipo_Mantenimiento = :tipo, Descripcion = :descripcion, Fecha_Programada = :fecha, Estado = :estado 
              WHERE ID_Notificacion = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

// Función para eliminar una notificación
function eliminarNotificacion($id)
{
    global $db;
    $query = "DELETE FROM Notificaciones_Mantenimiento WHERE ID_Notificacion = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
