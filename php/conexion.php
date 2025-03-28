<?php
$DB_HOST = 'dpg-cv87h4dumphs73aggv8g-a.oregon-postgres.render.com';
$DB_NAME = 'buscare';
$DB_USER = 'buscare_user';
$DB_PASS = 'UtjDlgh7AcEv82pZbkeGsc6ERsxCo2fm';

try {
    // Conectar a PostgreSQL con PDO
    $dbConnection = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);

    // Configurar el modo de errores de PDO
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
try {
    $db = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Eliminar autobuses de la tabla de mantenimiento si no tienen problemas
    $sqlDelete = "DELETE FROM Mantenimiento 
                  WHERE ID_Autobus IN (SELECT ID_Autobus FROM Autobuses WHERE Problema IS NULL OR Problema = '')";
    $db->exec($sqlDelete);

    // 2. Cambiar estado a 'Operativo' si el autobús ya no está en mantenimiento
    $sqlUpdate = "UPDATE Autobuses 
                  SET Estado = 'Operativo' 
                  WHERE ID_Autobus NOT IN (SELECT ID_Autobus FROM Mantenimiento)";
    $db->exec($sqlUpdate);
} catch (PDOException $e) {
}
