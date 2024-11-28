<?php
// Inicializa la sesión
session_start();

// Destruye todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige al usuario a la página de inicio de sesión (o cualquier otra página)
header("Location: login.php");
exit;
?>
