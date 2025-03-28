<?php
session_start();
include 'conexion.php'; // Archivo con la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];

    try {
        // Conectar a la base de datos con PDO
        $conn = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Tablas donde se buscará al usuario
        $roles = ['administradores', 'operario', 'personal_mantenimiento'];
        $authenticated = false;

        foreach ($roles as $role) {
            $sql = "SELECT * FROM $role WHERE telefono = :telefono";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['contraseña'])) {
                $_SESSION['usuario'] = $user['nombre'];
                $_SESSION['rol'] = $role;
                header("Location: ../Autobus.php");
                exit();
            }
        }

        // Si no se autenticó correctamente
        $_SESSION['error'] = "Usuario o contraseña incorrectos";
        header("Location: ../Login.php");
        exit();
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
