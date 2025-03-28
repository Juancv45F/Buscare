<?php
include './php/registro.php'; // Archivo que contiene el código para procesar los formularios

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

if ($_SESSION['rol'] === 'personal_mantenimiento') {
    header("Location: Mantenimiento.php");
    exit(); // Stop script execution after redirect
}



include './php/conexion.php'; // Archivo que contiene la conexión a la base de datos
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
    <link rel="stylesheet" href="CSS/forms.css">
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
            <a href="registrar_notificacion.php"><button class="menu-btn"><i
                        class="fa-solid fa-calendar-plus"></i></button></a>
            <a href="crear_notificaciones.php"><button class="menu-btn"><i
                        class="fa-solid fa-calendar-days"></i></button></a>
            <a href="Autobus.php"><button class="menu-btn"><i class="fa-solid fa-train-subway"></i></button></a>
            <a href="Mantenimiento.php"><button class="menu-btn"><i class="fa-solid fa-wrench"></i></button></a>
            <a href="./php/logout.php"><button class="menu-btn"><i class="fa-solid fa-sign-out-alt"></i></button></a>
        </div>
    </nav>

    <div class="container1">
        <div class="form-selector" id="formSelector">
            <?php
            if (isset($_SESSION['operario_contraseña'])) {
                echo '<div class="message alert-info">
            <div class="message-content">
                <i class="fas fa-key"></i>
                <span>La contraseña generada para el operario es: <strong>' . htmlspecialchars($_SESSION['operario_contraseña']) . '</strong></span>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
          </div>';
                unset($_SESSION['operario_contraseña']);
            }

            if (isset($_SESSION['mantenimiento_contraseña'])) {
                echo '<div class="message alert-info">
            <div class="message-content">
                <i class="fas fa-key"></i>
                <span>La contraseña generada para el personal de mantenimiento es: <strong>' . htmlspecialchars($_SESSION['mantenimiento_contraseña']) . '</strong></span>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
          </div>';
                unset($_SESSION['mantenimiento_contraseña']);
            }

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
            <button onclick="showForm('AutobusForm')">Registro de Autobuses</button>
            <button onclick="showForm('ChoferForm')">Registro de Choferes</button>
            <button onclick="showForm('MantenimientoForm')">Registro de Mantenimiento</button>
            <button onclick="showForm('AsignacionForm')">Asignar Autobuses</button>
        </div>

        <div class="form-container" id="AutobusForm">
            <div class="registro">
                <h2>Registro de Autobuses</h2>
                <form method="post">
                    Marca:
                    <input type="text" name="marca" class="input-field1">
                    <br>
                    Modelo:
                    <input type="text" name="modelo" class="input-field1">
                    <br>
                    Año:
                    <input type="number" name="año" class="input-field1">
                    <br>
                    Placas:
                    <input type="text" name="placas" class="input-field1">
                    <br>
                    Ruta:
                    <select name="ruta" class="input-field1">
                        <option value="" selected disabled hidden>Seleccionar la ruta</option>
                        <option value="Ruta 1">Ruta 1</option>
                        <option value="Ruta 2">Ruta 2</option>
                        <option value="Ruta 3">Ruta 3</option>
                        <option value="Ruta 4">Ruta 4</option>
                        <option value="Ruta 5">Ruta 5</option>
                        <option value="Ruta 6">Ruta 6</option>
                        <option value="Ruta 7">Ruta 7</option>
                        <option value="Ruta 8">Ruta 8</option>
                        <option value="Ruta 9">Ruta 9</option>
                        <option value="Ruta 10">Ruta 10</option>
                        <option value="Ruta 11">Ruta 11</option>
                        <option value="Ruta 12">Ruta 12</option>
                        <option value="Ruta 14">Ruta 14</option>
                        <option value="Ruta 16">Ruta 16</option>
                        <option value="Ruta 18">Ruta 18</option>
                        <option value="Ruta 19">Ruta 19</option>
                        <option value="Ruta 20 N">Ruta 20 N</option>
                        <option value="Ruta 20 S">Ruta 20 S</option>
                        <option value="Ruta 23">Ruta 23</option>
                        <option value="Ruta 24">Ruta 24</option>
                        <option value="Ruta 25">Ruta 25</option>
                        <option value="Ruta 27">Ruta 27</option>
                        <option value="Ruta 28">Ruta 28</option>
                        <option value="Ruta 29">Ruta 29</option>
                        <option value="Ruta 30">Ruta 30</option>
                        <option value="Ruta 33">Ruta 33</option>
                        <option value="Ruta 34">Ruta 34</option>
                        <option value="Ruta 35">Ruta 35</option>
                        <option value="Ruta 36">Ruta 36</option>
                        <option value="Ruta 37">Ruta 37</option>
                        <option value="Ruta 38">Ruta 38</option>
                        <option value="Ruta 39">Ruta 39</option>
                        <option value="Ruta 40 N">Ruta 40 N</option>
                        <option value="Ruta 40 S">Ruta 40 S</option>
                        <option value="Ruta 41 T.Oriente">Ruta 41 T.Oriente</option>
                        <option value="Ruta 41 Alameda">Ruta 41 Alameda</option>
                        <option value="Ruta 42">Ruta 42</option>
                        <option value="Ruta 43">Ruta 43</option>
                        <option value="Ruta 45">Ruta 45</option>
                        <option value="Ruta 46">Ruta 46</option>
                        <option value="Ruta 47">Ruta 47</option>
                        <option value="Ruta 48">Ruta 48</option>
                        <option value="Ruta 50">Ruta 50</option>
                        <option value="Ruta 50 B">Ruta 50 B</option>
                        <option value="Ruta 51">Ruta 51</option>
                        <option value="Ruta UTR">Ruta UTR</option>
                    </select>
                    <br>
                    Terminal:
                    <select name="terminal" class="input-field1" id="">
                        <option value="" selected hidden disabled>Seleccionar la terminal</option>
                        <option value="1">Norte</option>
                        <option value="2">Sur</option>
                        <option value="3">Poniente</option>
                        <option value="4">Oriente</option>
                        <option value="5">Sur oriente</option>
                    </select>
                    <br>

                    <button type="submit" class="btn">Enviar</button>
                </form>
                <button onclick="showForm('')" class="btn">Regresar</button>
            </div>
        </div>

        <div class="form-container" id="ChoferForm">
            <div class="registro">
                <h2>Registro de Choferes</h2>
                <form method="post">

                    Nombre(s):
                    <input type="text" name="nombre" class="input-field1">
                    <br>
                    Apellido(s):
                    <input type="text" name="apellido" class="input-field1">
                    <br>
                    Teléfono:
                    <input type="text" name="telefono" class="input-field1">
                    <br>
                    Turno:
                    <select name="turno" id="turno" class="input-field1">
                        <option value="" selected disabled hidden>Seleccionar</option>
                        <option value="TM">Matutino</option>
                        <option value="TV">Vespertino</option>
                    </select>
                    <br>
                    Ruta:
                    <select name="ruta" class="input-field1">
                        <option value="" selected disabled hidden>Seleccionar la ruta</option>
                        <option value="Ruta 1">Ruta 1</option>
                        <option value="Ruta 2">Ruta 2</option>
                        <option value="Ruta 3">Ruta 3</option>
                        <option value="Ruta 4">Ruta 4</option>
                        <option value="Ruta 5">Ruta 5</option>
                        <option value="Ruta 6">Ruta 6</option>
                        <option value="Ruta 7">Ruta 7</option>
                        <option value="Ruta 8">Ruta 8</option>
                        <option value="Ruta 9">Ruta 9</option>
                        <option value="Ruta 10">Ruta 10</option>
                        <option value="Ruta 11">Ruta 11</option>
                        <option value="Ruta 12">Ruta 12</option>
                        <option value="Ruta 14">Ruta 14</option>
                        <option value="Ruta 16">Ruta 16</option>
                        <option value="Ruta 18">Ruta 18</option>
                        <option value="Ruta 19">Ruta 19</option>
                        <option value="Ruta 20 N">Ruta 20 N</option>
                        <option value="Ruta 20 S">Ruta 20 S</option>
                        <option value="Ruta 23">Ruta 23</option>
                        <option value="Ruta 24">Ruta 24</option>
                        <option value="Ruta 25">Ruta 25</option>
                        <option value="Ruta 27">Ruta 27</option>
                        <option value="Ruta 28">Ruta 28</option>
                        <option value="Ruta 29">Ruta 29</option>
                        <option value="Ruta 30">Ruta 30</option>
                        <option value="Ruta 33">Ruta 33</option>
                        <option value="Ruta 34">Ruta 34</option>
                        <option value="Ruta 35">Ruta 35</option>
                        <option value="Ruta 36">Ruta 36</option>
                        <option value="Ruta 37">Ruta 37</option>
                        <option value="Ruta 38">Ruta 38</option>
                        <option value="Ruta 39">Ruta 39</option>
                        <option value="Ruta 40 N">Ruta 40 N</option>
                        <option value="Ruta 40 S">Ruta 40 S</option>
                        <option value="Ruta 41 T.Oriente">Ruta 41 T.Oriente</option>
                        <option value="Ruta 41 Alameda">Ruta 41 Alameda</option>
                        <option value="Ruta 42">Ruta 42</option>
                        <option value="Ruta 43">Ruta 43</option>
                        <option value="Ruta 45">Ruta 45</option>
                        <option value="Ruta 46">Ruta 46</option>
                        <option value="Ruta 47">Ruta 47</option>
                        <option value="Ruta 48">Ruta 48</option>
                        <option value="Ruta 50">Ruta 50</option>
                        <option value="Ruta 50 B">Ruta 50 B</option>
                        <option value="Ruta 51">Ruta 51</option>
                        <option value="Ruta UTR">Ruta UTR</option>
                    </select>
                    <br>
                    Terminal:
                    <input type="text" name="terminal_chofer" class="input-field1">
                    <br>
                    <button type="submit" class="btn">Enviar</button>
                </form>
                <button onclick="showForm('')" class="btn">Regresar</button>
            </div>
        </div>

        <div class="form-container" id="MantenimientoForm">
            <div class="registro">
                <h2>Registro personal de Mantenimiento</h2>
                <form method="post">
                    Nombre(s):
                    <input type="text" name="nombre_mantenimiento" class="input-field1">
                    <br>
                    Apellido(s):
                    <input type="text" name="apellido_mantenimiento" class="input-field1">
                    <br>
                    Teléfono:
                    <input type="text" name="telefono_mantenimiento" class="input-field1">
                    <br>
                    <button type="submit" class="btn">Enviar</button>
                </form>
                <button onclick="showForm('')" class="btn">Regresar</button>
            </div>
        </div>
        <div class="form-container" id="AsignacionForm">
            <div class="registro">
                <h2>Asignar Autobuses a Operarios</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="operario">Seleccionar Operario:</label>
                        <select name="id_operario" id="operario" class="input-field1" required>
                            <option value="" selected disabled hidden>Seleccione un operario</option>
                            <?php
                            $query = "SELECT id_operario, nombre, apellido FROM operario ORDER BY apellido, nombre";
                            $stmt = $dbConnection->query($query);
                            while ($operario = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$operario['id_operario']}'>{$operario['apellido']}, {$operario['nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Autobuses Disponibles:</label>
                        <div class="autobuses-container">
                            <?php
                            $query = "SELECT a.id_autobus, a.marca, a.modelo, a.placas, t.nombre as terminal 
                                      FROM autobuses a
                                      JOIN terminales t ON a.id_terminal = t.id_terminal
                                      ORDER BY a.placas";
                            $stmt = $dbConnection->query($query);
                            while ($autobus = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<div class='autobus-item'>
                                        <input type='checkbox' name='autobuses[]' id='autobus_{$autobus['id_autobus']}' value='{$autobus['id_autobus']}'>
                                        <label for='autobus_{$autobus['id_autobus']}'>
                                            {$autobus['marca']} {$autobus['modelo']} - Placas: {$autobus['placas']} (Terminal: {$autobus['terminal']})
                                        </label>
                                      </div>";
                            }
                            ?>
                        </div>
                    </div>

                    <button type="submit" class="btn">Asignar Autobuses</button>
                </form>
                <button onclick="showForm('')" class="btn">Regresar</button>
            </div>
        </div>
    </div>

    <script>
        function showForm(formId) {
            const formSelector = document.getElementById('formSelector');
            const formContainers = document.querySelectorAll('.form-container');

            // Oculta todos los formularios
            formContainers.forEach(form => {
                form.classList.remove('active');
            });

            if (formId) {
                // Oculta los botones de selección de formulario
                formSelector.classList.add('hidden');
                // Muestra el formulario seleccionado
                document.getElementById(formId).classList.add('active');
            } else {
                // Muestra los botones de selección de formulario
                formSelector.classList.remove('hidden');
            }
        }
    </script>
    <script src="js/bell.js"></script>
    <script>
        document.querySelectorAll('.message strong').forEach(strong => {
            strong.style.cursor = 'pointer';
            strong.title = 'Click para copiar';
            strong.addEventListener('click', function() {
                const range = document.createRange();
                range.selectNode(this);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand('copy');
                window.getSelection().removeAllRanges();

                const originalText = this.textContent;
                this.textContent = '¡Copiado!';
                setTimeout(() => {
                    this.textContent = originalText;
                }, 2000);
            });
        });
    </script>
</body>

</html>