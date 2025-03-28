<?php
session_start();

// Verificar autenticación y roles
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['rol'] === 'personal_mantenimiento') {
    header("Location: Mantenimiento.php");
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
    <title>Registro de Problemas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/principal.css">
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
                <a href="Mantenimiento.php"><button class="menu-btn"><i class="fa-solid fa-wrench"></i></button></a>
                <a href="crear_notificaciones.php"><button class="menu-btn"><i
                            class="fa-solid fa-calendar-days"></i></button></a>
                <a href="registrar_notificacion.php"><button class="menu-btn"><i
                            class="fa-solid fa-calendar-plus"></i></button></a>
            <?php endif; ?>
            <a href="./php/logout.php"><button class="menu-btn"><i class="fa-solid fa-sign-out-alt"></i></button></a>
        </div>
    </nav>
    <div class="container">
        <h1>Datos de Autobuses</h1>
        <div class="contenido">
            <div class="tabla-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th>Placas</th>
                            <th>Estado</th>
                            <th>Ruta</th>
                            <th>Problema</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($autobuses as $autobus): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($autobus['marca']); ?></td>
                                <td><?php echo htmlspecialchars($autobus['modelo']); ?></td>
                                <td><?php echo htmlspecialchars($autobus['año']); ?></td>
                                <td><?php echo htmlspecialchars($autobus['placas']); ?></td>
                                <td><?php echo htmlspecialchars($autobus['estado']); ?></td>
                                <td><?php echo htmlspecialchars($autobus['ruta']); ?></td>
                                <td>
                                    <?php if (!empty($autobus['problema'])): ?>
                                        <?php echo htmlspecialchars($autobus['problema']); ?>
                                    <?php else: ?>
                                        <button class="problema-btn menu-btn"
                                            data-id="<?php echo htmlspecialchars($autobus['id_autobus']); ?>">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($autobus['problema'])): ?>
                                        <div class="action-buttons">
                                            <button class="edit-btn menu-btn"
                                                data-id="<?php echo htmlspecialchars($autobus['id_autobus']); ?>"
                                                data-problema="<?php echo htmlspecialchars($autobus['problema']); ?>">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <button class="delete-btn menu-btn"
                                                data-id="<?php echo htmlspecialchars($autobus['id_autobus']); ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="registro-problemas" style="display: none;">
                <h2>Registro de Problemas</h2>
                <form id="damageForm" method="POST">
                    <input type="hidden" id="id_autobus" name="id_autobus" value="">
                    <input type="text" class="input-field" name="problema" placeholder="Problema" required>
                    <button type="submit" class="btn">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para registrar problema -->
    <div id="problemaModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Registrar Problema</h2>
            <p>Está registrando un problema para el autobús ID: <span id="modalBusId"></span></p>
            <form id="modalForm" method="POST">
                <input type="hidden" id="modal_id_autobus" name="id_autobus" value="">
                <input type="text" class="input-field" name="problema" placeholder="Describa el problema" required>
                <button type="submit" class="btn">Registrar Problema</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar problema -->
    <div id="editProblemaModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Problema</h2>
            <p>Está editando el problema para el autobús ID: <span id="editModalBusId"></span></p>
            <form id="editModalForm" method="POST">
                <input type="hidden" id="edit_modal_id_autobus" name="id_autobus" value="">
                <input type="text" id="edit_problema" class="input-field" name="problema"
                    placeholder="Describa el problema" required>
                <button type="submit" class="btn">Actualizar Problema</button>
            </form>
        </div>
    </div>

    <!-- Modal para confirmar eliminación de problema -->
    <div id="deleteProblemaModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Eliminar Problema</h2>
            <p>¿Está seguro que desea eliminar el problema del autobús ID: <span id="deleteModalBusId"></span>?</p>
            <form id="deleteModalForm" method="POST">
                <input type="hidden" id="delete_modal_id_autobus" name="id_autobus" value="">
                <input type="hidden" name="action" value="delete_problem">
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <button type="button" class="btn" style="background-color: #ccc;"
                        onclick="closeDeleteModal()">Cancelar</button>
                    <button type="submit" class="btn" style="background-color: #f44336;">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script src="js/problemas.js"></script>
<script src="js/bell.js"></script>

</html>