<?php
//hacer el cierre de sesión
session_start();
session_destroy();
header('Location: ../Login.php');
