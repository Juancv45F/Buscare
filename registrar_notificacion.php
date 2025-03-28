<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit(); // Stop script execution after redirect
}

// Check if user is operario or personal_mantenimiento, if yes redirect to Autobus.php
if ($_SESSION['rol'] === 'operario') {
    header("Location: Autobus.php");
    exit(); // Stop script execution after redirect
}

$isLoggedIn = true;
$isOperario = $_SESSION['rol'] === 'operario';
$isPersonalMantenimiento = $_SESSION['rol'] === 'personal_mantenimiento';
$shouldHideButtons = $isOperario || $isPersonalMantenimiento;



include './php/conexion.php'; // Archivo que contiene la conexión a la base de datos
include './php/registro_notificaciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/principal.css">
    <link rel="stylesheet" href="CSS/notificaciones.css">

</head>

<body>
    <nav>
        <img src="img/Logo.png" alt="Aguascalientes">
        <div class="menu-buttons">
            <button class="menu-btn" id="bell-btn">
                <i class="fa-solid fa-bell"></i>
                <div id="notification-indicator"></div>
            </button>
            <div id="notification-display" style="display: none;">
                <h3>Próximas Notificaciones</h3>
                <ul id="notification-list"></ul>
            </div>
            <?php if (!$shouldHideButtons): ?>
                <a href="Registro.php"><button class="menu-btn"><i class="fa-solid fa-user-plus"></i></button></a>
                <a href="Autobus.php"><button class="menu-btn"><i class="fa-solid fa-train-subway"></i></button></a>
            <?php endif; ?>
            <a href="crear_notificaciones.php"><button class="menu-btn"><i
                        class="fa-solid fa-calendar-days"></i></button></a>
            <a href="Mantenimiento.php"><button class="menu-btn"><i class="fa-solid fa-wrench"></i></button></a>
            <a href="./php/logout.php"><button class="menu-btn"><i class="fa-solid fa-sign-out-alt"></i></button></a>
        </div>
    </nav>
    <div class="container1">
        <div class="form-container" id="notificacionForms">
            <div class="registro">
                <h2>Registro de Notificaciones</h2>

                <?php
                if (isset($_SESSION['mensaje'])) {
                    echo '<div class="message alert-success">
            <div class="message-content">
                <i class="fas fa-check-circle"></i>
                <span>' . htmlspecialchars($_SESSION['mensaje']) . '</span>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
          </div>';
                    unset($_SESSION['mensaje']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<div class="message alert-error">
            <div class="message-content">
                <i class="fas fa-exclamation-circle"></i>
                <span>' . htmlspecialchars($_SESSION['error']) . '</span>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
          </div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form method="post">
                    Placa del Autobus:
                    <input type="text" name="placa_autobus" class="input-field1" required>
                    <br>
                    Tipo de mantenimiento:
                    <select name="tipo_mantenimiento" id="tipo_mantenimiento" class="input-field1" required>
                        <option value="" selected disabled hidden>Semanal o mensual</option>
                        <option value="Semanal">Semanal</option>
                        <option value="Mensual">Mensual</option>
                    </select>
                    <br>
                    Descripcion:
                    <input type="text" name="descripcion" class="input-field1" required>
                    <br>
                    Fecha Programada:
                    <input type="date" name="fecha_programada" class="input-field1" required>
                    <br>

                    <button type="submit" class="btn">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/bell.js"></script>
    <script>
        // Cerrar automáticamente los mensajes después de 5 segundos
        document.querySelectorAll('.message').forEach(msg => {
            setTimeout(() => {
                msg.classList.add('hide');
                setTimeout(() => msg.remove(), 500);
            }, 5000);
        });

        // Mejor manejo del botón cerrar
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.message').classList.add('hide');
                setTimeout(() => this.closest('.message').remove(), 500);
            });
        });
    </script>
</body>

</html>