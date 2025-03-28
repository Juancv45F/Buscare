<?php
session_start();
// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}

// Check if the user has the appropriate role
if ($_SESSION['rol'] === 'operario') {
    header("Location: ../Autobus.php");
    exit();
}

include 'conexion.php'; // Include database connection

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id_mantenimiento = $_POST['id']; // This is the hidden field from the form
    $id_autobus = $_POST['id_autobus'];
    $problema_detectado = $_POST['problema_detectado'];
    $reparacion_realizada = $_POST['reparacion_realizada'];
    $observaciones = $_POST['observaciones'];
    $costo = $_POST['costo'];
    $fecha_mantenimiento = $_POST['fecha_mantenimiento'];

    try {
        // Prepare the update query
        $query = "UPDATE Mantenimiento SET 
                  id_autobus = :id_autobus,
                  problema_detectado = :problema_detectado,
                  reparacion_realizada = :reparacion_realizada,
                  observaciones = :observaciones,
                  costo = :costo,
                  fecha_mantenimiento = :fecha_mantenimiento
                  WHERE id_mantenimiento = :id_mantenimiento";

        $stmt = $dbConnection->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id_mantenimiento', $id_mantenimiento);
        $stmt->bindParam(':id_autobus', $id_autobus);
        $stmt->bindParam(':problema_detectado', $problema_detectado);
        $stmt->bindParam(':reparacion_realizada', $reparacion_realizada);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':fecha_mantenimiento', $fecha_mantenimiento);

        // Execute the query
        $stmt->execute();

        // Redirect back to the main page with success message
        header("Location: ../Mantenimiento.php?update=success");
        exit();
    } catch (PDOException $e) {
        // Redirect back with error message
        header("Location: ../Mantenimiento.php?update=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If not a POST request, redirect back to the main page
    header("Location: ../Mantenimiento.php");
    exit();
}
