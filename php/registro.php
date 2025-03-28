<?php
session_start(); // Iniciar sesión para almacenar la contraseña temporalmente
include './php/conexion.php'; // Archivo que contiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de registro de autobuses
    if (isset($_POST['marca'], $_POST['modelo'], $_POST['año'], $_POST['placas'], $_POST['ruta'], $_POST['terminal'])) {
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $año = $_POST['año'];
        $placas = $_POST['placas'];
        $ruta = $_POST['ruta'];
        $terminal = $_POST['terminal'];

        // Insertar en la tabla Autobuses
        $query = "INSERT INTO Autobuses (Marca, Modelo, Año, Placas, Estado, Problema, Ruta, ID_Terminal) VALUES (?, ?, ?, ?, 'Operativo', '',?, ?)";
        $stmt = $dbConnection->prepare($query);
        $stmt->execute([$marca, $modelo, $año, $placas, $ruta, $terminal]);
    }

    function generarContraseña($longitud = 10)
    {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%&*!';
        return substr(str_shuffle($caracteres), 0, $longitud);
    }

    // Procesar el formulario de registro de choferes
    if (isset($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['turno'], $_POST['ruta_chofer'], $_POST['terminal_chofer'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $turno = $_POST['turno'];
        $ruta_chofer = $_POST['ruta_chofer'];
        $terminal_chofer = $_POST['terminal_chofer'];

        $contraseña = generarContraseña();
        $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);

        // Insertar en la tabla Operario
        $query = "INSERT INTO Operario (Nombre, Apellido, Telefono, Turno, Contraseña, ID_Autobus) VALUES (?, ?, ?, ?, ?, NULL)";
        $stmt = $dbConnection->prepare($query);
        $stmt->execute([$nombre, $apellido, $telefono, $turno, $contraseña_hash]);

        // Guardar la contraseña en la sesión
        $_SESSION['operario_contraseña'] = $contraseña;

        // Redireccionar para mostrar la contraseña en otra página (opcional)
        header("Location: ../Registro.php");
        exit();
    }

    // Procesar el formulario de registro de personal de mantenimiento
    if (isset($_POST['nombre_mantenimiento'], $_POST['apellido_mantenimiento'], $_POST['telefono_mantenimiento'])) {
        $nombre_mantenimiento = $_POST['nombre_mantenimiento'];
        $apellido_mantenimiento = $_POST['apellido_mantenimiento'];
        $telefono_mantenimiento = $_POST['telefono_mantenimiento'];

        $contraseña = generarContraseña();
        $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);

        // Insertar en la tabla Operario
        $query = "INSERT INTO personal_mantenimiento (Nombre, Apellido, Telefono, Contraseña) VALUES (?, ?, ?, ?)";
        $stmt = $dbConnection->prepare($query);
        $stmt->execute([$nombre_mantenimiento, $apellido_mantenimiento, $telefono_mantenimiento, $contraseña_hash]);

        // Guardar la contraseña en la sesión
        $_SESSION['mantenimiento_contraseña'] = $contraseña;

        // Redireccionar para mostrar la contraseña en otra página (opcional)
        header("Location: ../Registro.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_operario'])) {
    $id_operario = $_POST['id_operario'];
    $autobuses = $_POST['autobuses'] ?? [];

    try {
        $dbConnection->beginTransaction();

        // Eliminar asignaciones previas para este operario
        $deleteQuery = "DELETE FROM operario_Autobus WHERE id_operario = ?";
        $deleteStmt = $dbConnection->prepare($deleteQuery);
        $deleteStmt->execute([$id_operario]);

        // Insertar las nuevas asignaciones
        if (!empty($autobuses)) {
            $insertQuery = "INSERT INTO operario_autobus (id_operario, id_autobus) VALUES (?, ?)";
            $insertStmt = $dbConnection->prepare($insertQuery);

            foreach ($autobuses as $id_autobus) {
                $insertStmt->execute([$id_operario, $id_autobus]);
            }
        }

        $dbConnection->commit();
        $_SESSION['mensaje'] = "Autobuses asignados correctamente al operario.";
    } catch (PDOException $e) {
        $dbConnection->rollBack();
        $_SESSION['error'] = "Error al asignar autobuses: " . $e->getMessage();
    }
    // Redireccionar para mostrar la contraseña en otra página (opcional)
    header("Location: ../Registro.php");
    exit();
}
