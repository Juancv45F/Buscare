<?php
// Procesar el formulario de registro o edición de problemas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['problema']) && isset($_POST['id_autobus'])) {
    $problema = $_POST['problema'];
    $idAutobus = $_POST['id_autobus'];

    try {
        $updateQuery = "UPDATE autobuses SET problema = :problema WHERE id_autobus = :id_autobus";
        $updateStmt = $dbConnection->prepare($updateQuery);
        $updateStmt->bindParam(':problema', $problema);
        $updateStmt->bindParam(':id_autobus', $idAutobus);
        $updateStmt->execute();

        // Redirigir para evitar reenvío del formulario
        header("Location: Autobus.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
}

// Procesar la eliminación de problemas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_problem' && isset($_POST['id_autobus'])) {
    $idAutobus = $_POST['id_autobus'];

    try {
        $updateQuery = "UPDATE autobuses SET problema = NULL WHERE id_autobus = :id_autobus";
        $updateStmt = $dbConnection->prepare($updateQuery);
        $updateStmt->bindParam(':id_autobus', $idAutobus);
        $updateStmt->execute();

        // Redirigir para evitar reenvío del formulario
        header("Location: Autobus.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al eliminar problema: " . $e->getMessage();
    }
}
try {
    $query = "SELECT * FROM autobuses";
    $stmt = $dbConnection->query($query);
    $autobuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
