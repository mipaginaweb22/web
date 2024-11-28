<?php
// Inicializa la sesión. Es necesario para verificar el estado de inicio de sesión.
session_start();

// Verifica si el usuario ha iniciado sesión, si no, redirige a la página de inicio de sesión.
if (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Recupera las variables de sesión de forma segura
$google_loggedin = $_SESSION['google_loggedin'];
$google_email = $_SESSION['google_email'];
$google_name = $_SESSION['google_name'];
$google_picture = $_SESSION['google_picture'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1">
    <title>Perfil</title>
    <link rel="stylesheet" href="assets/css/style-login.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        .sidebar {
            background-color: #2c3e50;
            color: #fff;
            width: 300px;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-picture img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            border: 3px solid #ecf0f1;
        }

        .profile-details {
            margin-top: 20px;
        }

        .profile-details .name,
        .profile-details .email {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .profile-details .icon {
            background-color: #3498db;
            border-radius: 50%;
            padding: 10px;
            margin-right: 15px;
        }

        .profile-details .wrap {
            color: #ecf0f1;
        }

        .profile-details .wrap strong {
            display: block;
            font-weight: bold;
        }

        .profile-details .wrap span {
            font-size: 14px;
            color: #bdc3c7;
        }

        .logout-btn {
            display: block;
            text-align: center;
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 30px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .logout-btn .icon {
            margin-right: 8px;
        }

        /* Estilos para el cuadro con las imágenes */
        .image-container,
        .image-container2,
        .image-container3 {
            position: fixed;
            top: 20px;
            left: 170px;
            width: 300px;
            height: 250px;
            background-color: #34495e;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            border: 3px solid #ecf0f1;
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
        }

        /* Se define la animación para la transición de las imágenes */
        .image-container img,
        .image-container2 img,
        .image-container3 img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: slideImages 12s infinite;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        /* Animación que controla el deslizamiento y la visibilidad */
        @keyframes slideImages {
            0%, 20% {
                opacity: 1;
                transform: translateX(0);
            }
            33%, 53% {
                opacity: 0;
                transform: translateX(-100%);
            }
            66%, 86% {
                opacity: 0;
                transform: translateX(100%);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Animación para hacer aparecer las imágenes una por una */
        /* Se aplica una diferente animación por cada imagen */
        .image-container img:nth-child(1) {
            animation-delay: 0s;
        }

        .image-container img:nth-child(2) {
            animation-delay: 4s;
        }

        .image-container img:nth-child(3) {
            animation-delay: 8s;
        }

        .image-container2 img:nth-child(1) {
            animation-delay: 0s;
        }

        .image-container2 img:nth-child(2) {
            animation-delay: 4s;
        }

        .image-container2 img:nth-child(3) {
            animation-delay: 8s;
        }

        .image-container3 img:nth-child(1) {
            animation-delay: 0s;
        }

        .image-container3 img:nth-child(2) {
            animation-delay: 4s;
        }

        .image-container3 img:nth-child(3) {
            animation-delay: 8s;
        }

        /* Para que la barra lateral se mantenga visible siempre */
        .content {
            margin-left: 320px; /* Evita que el contenido quede detrás de la barra lateral */
            width: 100%;
        }

        /* Estilos para los enlaces de navegación */
        .menu-link {
            display: block;
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .menu-link:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<!-- Barra lateral -->
<div class="sidebar">
    <div class="profile-picture">
        <img src="<?= htmlspecialchars($google_picture) ?>" alt="<?= htmlspecialchars($google_name) ?>" width="120" height="120">
    </div>
    <div class="profile-details">
        <div class="name">
            <div class="icon">
                <!-- Icono de nombre -->
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                </svg>
            </div>
            <div class="wrap">
                <strong>Nombre</strong>
                <span><?= htmlspecialchars($google_name) ?></span>
            </div>
        </div>
        <div class="email">
            <div class="icon">
                <!-- Icono de correo -->
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/>
                </svg>
            </div>
            <div class="wrap">
                <strong>Correo electrónico</strong>
                <span><?= htmlspecialchars($google_email) ?></span>
            </div>
        </div>
    </div>

    <!-- Enlaces de navegación -->
    <a href="teoria.php" class="menu-link">Teorías</a>
    <a href="servicioFormulas.php" class="menu-link">Fórmulas</a> <!-- Redirige a servicioFormulas.php -->
    <a href="serviciosFiguras.php" class="menu-link">Generar Figuras Geométricas y Calculo.</a>

    <!-- Botón de cerrar sesión -->
    <a href="logout.php" class="logout-btn">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
            </svg>
        </span>
        Cerrar sesión
    </a>
</div>

<!-- Cuadro con las imágenes -->
<div class="image-container">
    <img src="imagenes/circulo1.jpg" alt="Imagen 1">
    <img src="imagenes/circulo2.jpg" alt="Imagen 2">
    <img src="imagenes/circulo3.jpg" alt="Imagen 3">
</div>

<!-- Cuadro 2 con las imágenes -->
<div class="image-container2">
    <img src="imagenes/cuadrado1.jpg" alt="Imagen 1">
    <img src="imagenes/cuadrado2.jpg" alt="Imagen 2">
    <img src="imagenes/cuadrado3.jpg" alt="Imagen 3">
</div>

<!-- Cuadro 3 con las imágenes -->
<div class="image-container3">
    <img src="imagenes/triangulo1.jpg" alt="Imagen 1">
    <img src="imagenes/triangulo2.jpg" alt="Imagen 2">
    <img src="imagenes/triangulo3.jpg" alt="Imagen 3">
</div>

<!-- Botón para redirigir a serviciosFiguras.php -->
<form action="serviciosFiguras.php" method="get">
    <button type="submit" class="menu-link">Generar Figura</button>
</form>

</body>
</html>
