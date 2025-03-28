<?php
session_start();
include './php/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/principal.css">
</head>

<body>
    <nav>
        <img src="img/Logo.png" alt="Aguascalientes">
    </nav>
    <div class="container">
        <img src="img/1.png" alt="Bus" width="100">
        <h2>Inicio de Sesión</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // Limpiar el mensaje de error después de mostrarlo
        }
        ?>
        <form action="php/login.php" method="POST">
            <input type="text" name="telefono" class="input-field" placeholder="Telefono del usuario" required>
            <input type="password" name="password" class="input-field" placeholder="Contraseña" required>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
    </div>
</body>

</html>