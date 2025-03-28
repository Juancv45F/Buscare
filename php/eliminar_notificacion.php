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

    try {
        $query = "DELETE FROM Notificaciones_Mantenimiento WHERE ID_Notificacion = :id_notificacion";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_notificacion', $id_notificacion);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Notificación eliminada correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar la notificación: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
