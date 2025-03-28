<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './conexion.php'; // Ensure this file contains the database connection

// Set the timezone to America/Mexico_City
date_default_timezone_set('America/Mexico_City');

// Calculate the date 3 days from now
$date_now = date('Y-m-d');
$date_limit = date('Y-m-d', strtotime('+3 days'));

try {
    // Query to fetch notifications with bus plate, ordered by fecha_programada
    $query = "SELECT nm.*, a.placas 
              FROM notificaciones_mantenimiento nm
              JOIN autobuses a ON nm.id_autobus = a.id_autobus
              WHERE nm.fecha_programada BETWEEN :date_now AND :date_limit
              ORDER BY nm.fecha_programada ASC";
    $stmt = $dbConnection->prepare($query);
    $stmt->bindParam(':date_now', $date_now);
    $stmt->bindParam(':date_limit', $date_limit);
    $stmt->execute();

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the date for each notification
    foreach ($notifications as &$notification) {
        $originalDate = $notification['fecha_programada'];
        $formattedDate = date('d-m-Y', strtotime($originalDate));
        $notification['fecha_programada'] = $formattedDate;
    }

    // Return the notifications as a JSON response
    header('Content-Type: application/json');
    echo json_encode($notifications);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
