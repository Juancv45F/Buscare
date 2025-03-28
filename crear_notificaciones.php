<?php
session_start();

// Verificar autenticación y roles
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['rol'] === 'operario') {
    header("Location: Autobus.php");
    exit();
}

include './php/conexion.php';

$isLoggedIn = true;
$isOperario = $_SESSION['rol'] === 'operario';
$isPersonalMantenimiento = $_SESSION['rol'] === 'personal_mantenimiento';
$shouldHideButtons = $isOperario || $isPersonalMantenimiento;

include './php/problemas.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de notificaciones</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/principal.css">
    <link rel="stylesheet" href="CSS/notificacion_modal.css">
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
            <a href="registrar_notificacion.php"><button class="menu-btn"><i
                        class="fa-solid fa-calendar-plus"></i></button></a>
            <a href="Mantenimiento.php"><button class="menu-btn"><i class="fa-solid fa-wrench"></i></button></a>
            <a href="./php/logout.php"><button class="menu-btn"><i class="fa-solid fa-sign-out-alt"></i></button></a>
        </div>
    </nav>
    <div class="container">
        <h1>Notificaciones de autobuses</h1>
        <div class="contenido">
            <?php
            include './php/notificaciones_mantenimiento.php';
            ?>

            <div class="tabla-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Autobús</th>
                            <th>Tipo Mantenimiento</th>
                            <th>Descripción</th>
                            <th>Fecha Programada</th>
                            <th>Estado</th>
                            <th>Fecha Notificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notificaciones as $notificacion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($notificacion['modelo'] . ' - ' . $notificacion['placas']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($notificacion['tipo_mantenimiento']); ?></td>
                                <td><?php echo htmlspecialchars($notificacion['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($notificacion['fecha_programada']))); ?>
                                </td>
                                <td><?php echo htmlspecialchars($notificacion['estado']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($notificacion['fecha_notificacion']))); ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button
                                            onclick="editarNotificacion(<?php echo $notificacion['id_notificacion']; ?>)"
                                            class="edit-btn menu-btn">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($notificacion['estado'] !== 'Completada'): ?>
                                            <button
                                                onclick="eliminarNotificacion(<?php echo $notificacion['id_notificacion']; ?>)"
                                                class="delete-btn menu-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para editar notificación -->
    <div id="editarNotificacionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editarNotificacionModal')">&times;</span>
            <h2>Editar Notificación</h2>
            <form id="editarNotificacionForm">
                <input type="hidden" id="edit_id_notificacion" name="id_notificacion">

                <div class="form-group">
                    <label for="edit_tipo_mantenimiento">Tipo de Mantenimiento:</label>
                    <select id="edit_tipo_mantenimiento" name="tipo_mantenimiento"
                        placeholder=" <?php echo htmlspecialchars($notificacion['tipo_mantenimiento']); ?> " required>
                        <option value="<?php echo htmlspecialchars($notificacion['tipo_mantenimiento']) ?>" selected
                            hidden>
                            <?php echo htmlspecialchars($notificacion['tipo_mantenimiento']); ?>
                        <option value="Semanal">Semanal</option>
                        <option value="Mensual">Mensual</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_descripcion">Descripción:</label>
                    <textarea id="edit_descripcion" name="descripcion"
                        required><?php echo htmlspecialchars($notificacion['descripcion']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_fecha_programada">Fecha Programada:</label>
                    <input type="date" id="edit_fecha_programada" name="fecha_programada" required
                        placeholder="<?php echo htmlspecialchars($notificacion['fecha_programada']); ?>"
                        value="<?php echo htmlspecialchars($notificacion['fecha_programada']); ?>">
                </div>

                <div class="form-group">
                    <label for="edit_estado">Estado:</label>
                    <select id="edit_estado" name="estado" required>
                        <option value="<?php echo htmlspecialchars($notificacion['estado']) ?>" selected hidden>
                            <?php echo htmlspecialchars($notificacion['estado']); ?>
                        </option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn-secondary"
                        onclick="closeModal('editarNotificacionModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div id="eliminarNotificacionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('eliminarNotificacionModal')">&times;</span>
            <h2>Confirmar Eliminación</h2>
            <p>¿Está seguro que desea eliminar esta notificación?</p>
            <input type="hidden" id="delete_id_notificacion">
            <div class="form-buttons">
                <button onclick="confirmarEliminarNotificacion()" class="btn-danger">Eliminar</button>
                <button onclick="closeModal('eliminarNotificacionModal')" class="btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>

    <script src="js/notificaciones.js"></script>
    <script src="js/bell.js"></script>

</body>

</html>