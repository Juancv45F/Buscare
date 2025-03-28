<?php
session_start(); // Start the session to access session variables
// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit(); // Stop script execution after redirect
}

if ($_SESSION['rol'] === 'operario') {
    header("Location: Autobus.php");
    exit(); // Stop script execution after redirect
}

include './php/conexion.php'; // Archivo que contiene la conexión a la base de datos

// User is logged in, continue with role checks
$isLoggedIn = true; // We already know the user is logged in at this point
$isOperario = $_SESSION['rol'] === 'operario';
$isPersonalMantenimiento = $_SESSION['rol'] === 'personal_mantenimiento';
$shouldHideButtons = $isOperario || $isPersonalMantenimiento;

try {
    // Fetch data from the Mantenimiento table, including the license plate
    $query = "SELECT mantenimiento.*, autobuses.placas FROM mantenimiento JOIN autobuses ON mantenimiento.id_autobus = autobuses.id_autobus";
    $stmt = $dbConnection->query($query);
    $mantenimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Problemas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="CSS/styles.css"> <!-- Asegúrate de que el CSS esté en este archivo -->
    <link rel="stylesheet" href="CSS/principal.css"> <!-- Asegúrate de que el CSS esté en este archivo -->
    <link rel="stylesheet" href="CSS/mantenimiento.css">
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
                <!-- Only show user-plus button if the user is NOT an operario or a personal_mantenimiento-->
                <a href="Registro.php"><button class="menu-btn"><i class="fa-solid fa-user-plus"></i></button></a>
                <a href="Autobus.php"><button class="menu-btn"><i class="fa-solid fa-train-subway"></i></button></a>
            <?php endif; ?>
            <a href="registrar_notificacion.php"><button class="menu-btn"><i
                        class="fa-solid fa-calendar-plus"></i></button></a>
            <a href="crear_notificaciones.php"><button class="menu-btn"><i
                        class="fa-solid fa-calendar-days"></i></button></a>
            <a href="./php/logout.php"><button class="menu-btn"><i class="fa-solid fa-sign-out-alt"></i></button></a>
        </div>
    </nav>
    <div class="container">
        <h1>Mantenimiento</h1>
        <div class="contenido">
            <div class="tabla-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Placas de camión</th>
                            <th>Problema de camión</th>
                            <th>Reparación realizada</th>
                            <th>Observaciones</th>
                            <th>Costo</th>
                            <th>Fecha de mantenimiento</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mantenimientos as $mantenimiento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($mantenimiento['placas']); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['problema_detectado']); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['reparacion_realizada']); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['observaciones']); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['costo']); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['fecha_mantenimiento']); ?></td>
                                <td>
                                    <button class="edit-btn"
                                        data-autobus="<?php echo htmlspecialchars($mantenimiento['placas']); ?>"
                                        data-problema="<?php echo htmlspecialchars($mantenimiento['problema_detectado']); ?>"
                                        data-reparacion="<?php echo htmlspecialchars($mantenimiento['reparacion_realizada']); ?>"
                                        data-observaciones="<?php echo htmlspecialchars($mantenimiento['observaciones']); ?>"
                                        data-costo="<?php echo htmlspecialchars($mantenimiento['costo']); ?>"
                                        data-fecha="<?php echo htmlspecialchars($mantenimiento['fecha_mantenimiento']); ?>">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para editar -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Mantenimiento</h2>
            <form id="editForm" action="./php/mantenimiento.php" method="POST">
                <input type="hidden" id="edit_id" name="id">

                <div class="form-group">
                    <label for="edit_placas">Placas de camión:
                        <?php echo htmlspecialchars($mantenimiento['placas']) ?></label>
                    <input type="hidden" id="edit_placas" name="placas">
                </div>

                <div class="form-group">
                    <label for="edit_problema_detectado">Problema detectado:</label>
                    <textarea id="edit_problema_detectado" name="problema_detectado" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_reparacion_realizada">Reparación realizada:</label>
                    <textarea id="edit_reparacion_realizada" name="reparacion_realizada" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_observaciones">Observaciones:</label>
                    <textarea id="edit_observaciones" name="observaciones"></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_costo">Costo:</label>
                    <input type="number" step="0.01" id="edit_costo" name="costo" required>
                </div>

                <div class="form-group">
                    <label for="edit_fecha_mantenimiento">Fecha de mantenimiento:</label>
                    <input type="date" id="edit_fecha_mantenimiento" name="fecha_mantenimiento" required>
                </div>

                <div class="form-buttons">
                    <button type="button" class="btn-cancel" id="cancelEdit">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/bell.js"></script>
    <script src="js/mantenimiento.js"></script>
</body>

</html>