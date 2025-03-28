<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_notificacion = $data['id_notificacion'];
    $tipo_mantenimiento = $data['tipo_mantenimiento'];
    $descripcion = $data['descripcion'];
    $fecha_programada = $data['fecha_programada'];
    $estado = $data['estado'];

    try {
        $query = "UPDATE Notificaciones_Mantenimiento 
                  SET Tipo_Mantenimiento = :tipo_mantenimiento, 
                      Descripcion = :descripcion, 
                      Fecha_Programada = :fecha_programada, 
                      Estado = :estado 
                  WHERE ID_Notificacion = :id_notificacion";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tipo_mantenimiento', $tipo_mantenimiento);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha_programada', $fecha_programada);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id_notificacion', $id_notificacion);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Notificación actualizada correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la notificación: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
